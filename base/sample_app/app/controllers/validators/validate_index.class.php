<?php 
	class validate_index extends basevalidator {
        function index_action() {
        	//Basic Action Validation
        	$is_valid = true;
			if($is_valid == false) {
				return array('result'=>'false', 'data'=>'some data 1');
			} else {
				return array('result'=>'true', 'data'=>'some data 2');
			}
		}
		
		function forgot_pass_submit_action() {
			$messages = array();

			if(!isset($_POST['email']) || (isset($_POST['email']) && $this->validate_email($_POST['email'])===false))
				$messages['error']['email'] = 'Please enter a valid email!';
			
			if(isset($messages['error'])) {
				return array('result'=>'false','reason'=>'error','messages'=>$messages['error']);
			} else {
				return array(
					'result'	=>'true',
					'reason'	=>'success',
					'redirect'	=>'/index/login',
					'messages'	=>array(''=>'We have sent an email for you to reset your password!'));
			}
		}
		
		function reset_pass_action($token){
			$users = new dp_users_model($this->registry,true);
			$result = $users->check_reset_token($token);
			if($result === false)
				return array('result'=>'false',
							'reason'=>'error',
							'messages'=>array(''=>'You don\'t have a valid token!'));
		}
		
		function reset_pass_submit_action(){
			if(!isset($_POST['token']) || !isset($_POST['password']) || !$this->validate_alphanumeric($_POST['password'])){
				return array('result'=>'false',
							'reason'=>'error',
							'messages'=>array('password'=>'Please enter a valid password!'));
			}
			
			$users = new dp_users_model($this->registry,true);
			$result = $users->check_reset_token($_POST['token']);
			if($result === false)
				$messages['error'][] = 'You cannot reset this password at this time!';
			
			if(isset($messages['error'])) {
				return array('result'=>'false','reason'=>'error','messages'=>$messages['error']);
			} else {
				return array(
					'result'	=>'true',
					'reason'	=>'success',
					'redirect'	=>'/index/login',
					'messages'	=>array(''=>'You have successfully reset your password!'));
			}
		}
		
		function sample_action($a = false) {
			//Basic Action Validation
			if($a == 1) {
				return array('result'=>'false', 'data'=>'a is 1');
			}
			if($a == 2) {
				return array('result'=>'false', 'data'=>'a is 2');
			}
		}
	}
?>