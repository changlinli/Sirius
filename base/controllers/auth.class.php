<?php

class auth extends basecontroller {

	function init() {

	}

	function index_action() {
		$result = $this->init_user();
		if($result == true) {
			return array('result'=>'true', 'data'=>$this->registry->user);
		} else {
			return array('result'=>'false');
		}
	}

	function register_action() {
		$users 				= new dp_users_model($this->registry,true);
		//do some validation
		$message = array();
		if(empty($_POST)) {
			$message = array(''=>'Please fill out the form.');
		}

		if(empty($_POST['first_name'])) {
			$message += array('first_name'=>'');
		}

		if(empty($_POST['last_name'])) {
			$message += array('last_name'=>'');
		}

		if(empty($_POST['email'])) {
			$message += array('email'=>'');
		}

		if($users->get_by_email($_POST['email'])) {
			$message += array('email'=>'This email already exists!');
		}

		if(empty($_POST['password']) || ctype_alnum($_POST['password']) == false || strlen($_POST['password']) < 6) {
			$message += array('password'=>'Please enter an alphanumeric 6 digit valid password.');
		}

		if(count($message) > 0) {
			$message += array(''=>'Please review your form submission.');
			return array('validator'=>
				array('result'=>'false',
					'reason'=>'error',
					'messages'=>$message));
		}

		$user			= $users->create_user(0,$_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'],1,1);
		$_SESSION['auth'] 	= 1;
		$_SESSION['user'] 	= $user;
		$this->roles 		= new dp_roles($this->registry, true);
		$app_roles 					= $this->registry->roles_redirects;
		$_SESSION['user']['roles'] 	= $this->roles->get_user_roles($user['role_id'],$app_roles);
		return array('validator'=>
			array('result'	=>'true',
				'reason'	=>'success',
				'redirect'=>$_SESSION['user']['roles'][0]['auth_redirect'],
				'messages'=>array('You have successfully registered!')),
			'result'	=>'true'
		);
	}

	function auth_action() {
		$strict = $this->init_api(true);
		// Eh... eventually do we want to care about the body of a POST?
		if(isset($_POST['params'])) {
			$params = json_decode(stripslashes($_POST['params']), true);
			$_POST['X-AuthToken'] 	= $params['X-AuthToken'];
			$_POST['email'] 		= $params['email'];
			$_POST['password'] 		= $params['password'];
		}
		// Otherwise, let's check the body of the POST message
		else {
			$post_body = file_get_contents("php://input");
			$params = json_decode($post_body);
		}


		if(!isset($_POST['X-AuthToken'])) if($strict) {
			throw new Exception('Missing AuthToken.');
		}

		$messages = array();

		if(!isset($_POST['email']) || (!isset($_POST['password']) && !$strict)) {
			$messages['error'] = 'Missing email or password.';
			if($strict) throw new Exception('Missing email or password.');
		}

		$users = new dp_users_model($this->registry, true);
		$user = $users->get_by_email($_POST['email']);

		if($strict) {
			if(!$user) {
				$messages['error'] = 'Wrong email or password.';
				return array('validator'=>
					array('result'=>'false',
						'reason'=>'error',
						'messages'=>array(''=>$messages['error'],
						'email'=>'',
						'password'=>'')),
					'result'=>'false');
			} else {
				$email 		= $user['email'];
				$password	= $user['password'];

				/** get key information **/
				$keychain = new dp_apikeys($this->registry, true);
				$key = $keychain->get_key($_POST['X-ApiKey']);

				/** get access token id **/
				$tokens = new dp_accesstokens($this->registry, true);
				$accesstoken = $tokens->get_token($key['id'], $_POST['X-AccessToken']);
				if (!$accesstoken)
					return array('result' => 'false', 'message' => 'AccessToken not found.');

				/** generate auth token **/
				$authtokens = new dp_authtokens($this->registry, true);
				$auth_response = $authtokens->check_token($key['id'], $accesstoken['id'], $params['X-AuthToken']);

				/** check if auth response is true **/
				if ($auth_response == false)	
					return array('result' => 'false', 'message' => 'AuthToken not accepted.');

				/** pass all the initial exception handling, and finally validate if the username and password are correct **/
				if($_POST['email'] == $email && md5($_POST['password']) == $password) {
					$_SESSION['auth'] = 1;
					$_SESSION['user'] = $user;
					if(!empty($user['role_id'])) {
						$this->roles 				= new dp_roles($this->registry, true);
						$this->companies 			= new companies_model($this->registry, false);
						$app_roles 					= $this->registry->roles_redirects;
						$_SESSION['user']['roles'] 	= $this->roles->get_user_roles($user['role_id'],$app_roles);
						$_SESSION['company'] 		= $this->companies->get_by_id(array('*'),$user['company_id']); 

						unset($user['password']);

						$usertokens = new dp_usertokens($this->registry, true);
						$token = $usertokens->set_token($key['id'],$user['id'], false);
						$user += array('user_token'=>$token);

						return array('validator'=>
							array('result'=>'true',
								'reason'=>'success',
								'redirect'=>$_SESSION['user']['roles'][0]['auth_redirect'],
								'messages'=>array('You have successfully logged in!')),
							'result'=>'true','data'=>$user
						);
						//die(header('Location: /'.$this->registry->config['default_controller'].'/'.$this->registry->config['default_action']));
					} else {
						//redirect back to login, and end session, this user doesn't have a role.
						$_SESSION = array();
						session_destroy();
						return array('validator'=>
							array('result'=>'false',
								'reason'=>'error',
								'messages'=>array(''=>'You don\'t have the right permissions.')),
							'result'=>'false');
					}
				} else {
					$messages['error'] = 'Wrong email or password.';
					if($strict) throw new Exception('Invalid Auth.');
					$_SESSION['messages'] = $messages;
					return array('validator'=>
						array('result'=>'false',
							'reason'=>'error',
							'messages'=>array(''=>'Wrong username or password',
							'email'=>'',
							'password'=>'')),
						'result'=>'false');
				}
			}
		} else {

			/** **/

			if(!$user || $user['password'] != md5($_POST['password'])) {
				$messages['error'] = 'Wrong email or password.';
				if($strict) throw new Exception('Invalid Auth.');
				$_SESSION['messages'] = $messages;
				return array('validator'=>
					array('result'=>'false',
						'reason'=>'error',
						'messages'=>array(''=>'Wrong username or password',
						'email'=>'',
						'password'=>'')),
					'result'=>'false');
			} else {
				$_SESSION['auth'] = 1;
				$_SESSION['user'] = $user;
				if(!empty($user['role_id'])) {
					$this->roles 	= new dp_roles($this->registry, true);
					$app_roles 					= $this->registry->roles_redirects;
					$_SESSION['user']['roles'] 	= $this->roles->get_user_roles($user['role_id'],$app_roles);
					$this->companies 			= new companies_model($this->registry, false);
					$_SESSION['company'] 		= $this->companies->get_by_id(array('*'),$user['company_id']); 
				} else {
					//redirect back to login, and end session, this user doesn't have a role.
					$_SESSION = array();
					session_destroy();
					return array('validator'=>
						array('result'=>'false',
							'reason'=>'error',
							'messages'=>array(''=>'You don\'t have the right permissions.')),
						'result'=>'false');
				}
			}

			unset($user['password']);
			unset($user['result']);

			$user['result']='true';
			return array('validator'=>
				array('result'=>'true',
					'reason'=>'success',
					'redirect'=>$_SESSION['user']['roles'][0]['auth_redirect'],
					'messages'=>array('You have successfully logged in!')),
				'result'=>'true','data'=>$user
			);

			/** **/
		}
	}

	function logout_action() {


		$strict = $this->init_api(true);
		session_unset();
		$_SESSION[] = array();
		session_destroy();
		if($strict) {
			//destroy user token
			return array('result'=>'true');
		} else {
			die(header('Location: /index/login'));
		}
	}

	function get_token_action() {
		if(!isset($_POST['X-ApiKey']) || empty($_POST['X-ApiKey'])) throw new Exception('Missing ApiKey.');
		/** get key data **/
		if(!$this->registry->apikey) throw new Exception('Missing ApiKey.');
		$keychain = new dp_apikeys($this->registry, true);
		$key = $keychain->get_key($_POST['X-ApiKey']);
		if(!$key) throw new Exception('ApiKey not found.');
		/** get access token data **/
		if(!$this->registry->accesstoken) throw new Exception('Missing AccessToken.');
		$tokens = new dp_accesstokens($this->registry, true);
		$accesstoken = $tokens->get_token($key['id'], $this->registry->accesstoken);
		if(!$accesstoken) throw new Exception('AccessToken not found');
		/** generate auth token **/
		$authtokens = new dp_authtokens($this->registry, true);
		$new_auth_token = $authtokens->get_token($key['id'], $accesstoken['id']);
		/** return auth token **/
		if(isset($new_auth_token['token'])) {
			$data = $new_auth_token;
			$data += array('access_token'=>$this->registry->accesstoken);
			return array('result'=>'true', 'data'=>$data);
		} else {
			return array('result'=>'false');
		}
	}

	function check_session_action() {
		$_SESSION['user']['password']='';
		return $_SESSION;
	}

}
