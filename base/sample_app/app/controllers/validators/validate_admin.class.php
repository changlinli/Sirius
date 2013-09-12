<?php 
class validate_admin extends basevalidator {
	function delete_user_action() {
		if (!isset($_POST['id']) || $_POST['id'] == "")
            $messages['error']['id'] = 'There is nothing to delete.';
			
		if(isset($messages['error'])) {
			return array(
                         'result'   => 'false',
                         'reason'   => 'error',
                         'messages' => $messages['error'],
                         );
		} else {
			return array(
                         'result'    => 'true',
                         'reason'    => 'warning',
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this user? <a href="/admin/delete_user_confirm/'.$_POST['id'].'">Yes</a> / <a href="/admin/users">No</a>')
                        );
		}
	}
	
	function add_user_action() {
		$messages = array();
		if (!isset($_POST['email']) || $_POST['email'] == "")
            $messages['error']['email'] = 'Please enter a valid email!';
		
		if (!isset($_POST['password']) || $_POST['password'] == "")
            $messages['error']['password'] = 'Please enter a valid password!';
		
		$post = array();
		foreach($_POST as $key=>$val) {
			if(preg_match('/role_id/',$key)) {
				if($val != '') {
					$post[] = $val;
				}
				unset($_POST[$key]);
			}
		}
		$_POST['role_id'] = $post;
		
		if (!isset($_POST['role_id']) || count($_POST['role_id']) == 0)	
			$messages['error']['role_id'] = 'Please select a role!';
			
		if(isset($messages['error'])) {
			return array(
                         'result'   => 'false',
                         'reason'   => 'error',
                         'messages' => $messages['error'],
                         );
		} else {
			return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/users',
                         'messages'  => array('You have successfully created a user!')
                        );
		}
	}
	
	function add_role_action() {
		$messages = array();
		if (!isset($_POST['name']) || $_POST['name'] == "")
            $messages['error']['name'] = 'Please enter a valid role name!';
			
		if (!isset($_POST['auth_redirect']) || $_POST['auth_redirect'] == "")
            $messages['error']['auth_redirect'] = 'Please enter a valid Redirect URL!';
			
		if(isset($messages['error'])) {
			return array(
                         'result'   => 'false',
                         'reason'   => 'error',
                         'messages' => $messages['error'],
                         );
		} else {
			return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/roles',
                         'messages'  => array('You have successfully created a role!')
                        );
		}
	}
	
	function delete_user_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/users',
                         'messages'  => array('You have successfully deleted a user!')
                        );
	}
	
	function update_user_action() {
		$post = array();
		foreach($_POST as $key=>$val) {
			if(preg_match('/role_id/',$key)) {
				if($val != '') {
					$post[] = $val;
				}
				unset($_POST[$key]);
			}
		}
		$_POST['role_id'] = $post;
		
		if (!isset($_POST['role_id']) || count($_POST['role_id']) == 0)	
			$messages['error']['role_id'] = 'Please select a role!';
		
		if(isset($messages['error'])) {
			return array(
                         'result'   => 'false',
                         'reason'   => 'error',
                         'messages' => $messages['error'],
                         );
		} else {
			return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/users',
                         'messages'  => array('You have successfully updated a user!')
                        );
		}
	}
	
	
	
	
	function delete_role_action() {
		if (!isset($_POST['id']) || $_POST['id'] == "")
            $messages['error']['id'] = 'There is nothing to delete.';
			
		if(isset($messages['error'])) {
			return array(
                         'result'   => 'false',
                         'reason'   => 'error',
                         'messages' => $messages['error'],
                         );
		} else {
			return array(
                         'result'    => 'true',
                         'reason'    => 'warning',
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this role? <a href="/admin/delete_role_confirm/'.$_POST['id'].'">Yes</a> / <a href="/admin/roles">No</a>')
                        );
		}
	}
	
	function delete_role_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/roles',
                         'messages'  => array('You have successfully deleted a role!')
                        );
	}
	
	function update_role_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/admin/roles',
                         'messages'  => array('You have successfully updated a role!')
                        );
	}
}