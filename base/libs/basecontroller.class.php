<?php

abstract class basecontroller {

	var $registry;

	function __construct($registry) {
		$this->registry = $registry;
	}

	function uri_redirect() {
		if(isset($_SESSION)) {
			if(isset($_SESSION['redirect_uri']) && $_SESSION['auth'] == 1) {
				$redirect_uri = $_SESSION['redirect_uri'];
				unset($_SESSION['redirect_uri']);
				unset($_SESSION['is_redirect']);
				return $redirect_uri;
			} else {
				return $_SESSION['user']['roles'][0]['auth_redirect'];
			}
		}
	}

	function init_api($loose = false) {
		/** get key data **/
		if(!isset($_POST['X-ApiKey']) || empty($_POST['X-ApiKey'])) {
			if(!$loose) throw new Exception('Missing ApiKey.');
		} else {
			$keychain = new dp_apikeys($this->registry, true);
			if(isset($_POST['X-ApiKey'])) $key = $keychain->get_key($_POST['X-ApiKey']);
			else $key = 'web';
			if(!$key) throw new Exception('Invalid ApiKey.');
			$this->registry->apikey = $key;
		}
		/**get token data**/
		if(!isset($_POST['X-AccessToken']) || empty($_POST['X-AccessToken'])) {
			if(!$loose) throw new Exception('Missing AccessToken.');
		} else {
			$tokens = new dp_accesstokens($this->registry, true);
			if(!isset($key) && !isset($_POST['X-AccessToken'])) $accesstoken = 'web';
			else $accesstoken = $tokens->get_token($key['id'], $_POST['X-AccessToken']);
			if(!$accesstoken) throw new Exception('Invalid AccessToken.');
			$this->registry->accesstoken = $accesstoken;
		}
		if(isset($accesstoken) && isset($key) && $key != 'web') $loose = false;
		return !$loose;
	}

	function init_user() {
		$usertokens = new dp_usertokens($this->registry, true);
		if (!isset($_POST['X-UserToken']) || empty($_POST['X-UserToken'])) {
			return false;
		}

		$user = $usertokens->get_token($_POST['X-UserToken'], false);
		if ($user == false) {
			//this occurs whether the token doesn't exist or the token has expired
			return false;
		}

		if(count($user) > 0 && $user != false) {
			$this->registry->user = $user;
			return true;
		}
	}

	function api($object = false, $method = false, $params = false) {
		if($object != false) {
			if($method != false) {
				$app_object_name = $object.'_model';
				$app_object = new $app_object_name($this->registry, true);
				$app_object->params = $params;
				if(method_exists($app_object, $method)) {
					$response = $app_object->$method($params);
					return $response;
				} else {
					return array('result'=>'false','message'=>'The method requested does not exist.');
				}
			} else {
				return array('result'=>'false','message'=>'Please request a method.');
			}
		} else {
			return array('result'=>'false','message'=>'Please request an object.');
		}
	}

	/**
	 * go_404: Returns 404 page. Right now this function is extremely 
	 * simplistic. Eventually it should rely on the 404 address stored in 
	 * the SQL configuration table and which is stored in registry.
	 */
	public function go_404($message = '') {
		header('HTTP/1.1 404 Not Found');
		echo $message;
		die();
	}

	abstract public function init();
	abstract public function index_action();

}
