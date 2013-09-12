<?php 
	class validate_settings extends basevalidator {
        function index_action() {
        	//Basic Action Validation
        	$is_valid = true;
			if($is_valid == false) {
				return array('result'=>'false', 'data'=>'some data 1');
			} else {
				return array('result'=>'true', 'data'=>'some data 2');
			}
		}
		
		function profile_update_action() {
			$messages = array();

			if(!isset($_POST['email']) || (isset($_POST['email']) && $this->validate_email($_POST['email'])===false))
				$messages['error']['email'] = 'Please enter a valid email!';
			
			if(isset($_POST['password']) && isset($_POST['password'])) {
			
			if($_POST['password'] != $_POST['confirm_password'])
				$messages['error']['password'] = 'Please enter a matching password confirmation!';
				
			}
			
			if(isset($messages['error'])) {
				return array('result'=>'false','reason'=>'error','messages'=>$messages['error']);
			} else {
				return array(
					'result'	=>'true',
					'reason'	=>'success',
					'redirect'	=>'/settings/profile',
					'messages'	=>array(''=>'You have successfully updated your profile.'));
			}
		}
	}
?>