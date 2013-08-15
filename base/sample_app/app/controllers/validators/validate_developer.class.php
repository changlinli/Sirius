<?php 
class validate_developer extends basevalidator {
    function index_action() {
    }

	function add_config_action() {
		$messages = array();
		if (!isset($_POST['name']) || $_POST['name'] == "")
            $messages['error']['name'] = 'Please enter a valid name!';
			
		if (!isset($_POST['value']) || $_POST['value'] == "")
            $messages['error']['value'] = 'Please enter a valid value!';
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
                         'redirect'  => '/developer/config',
                         'messages'  => array('You have successfully updated your configuration!')
                        );
		}
	}
	
	function add_key_action() {
		$messages = array();
		if (!isset($_POST['application_name']) || $_POST['application_name'] == "")
            $messages['error']['application_name'] = 'Please enter a valid application name!';
			
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
                         'redirect'  => '/developer/keys',
                         'messages'  => array('You have successfully updated your API Key!')
                        );
		}
	}
	
	function add_source_action() {
		$messages = array();
		if (!isset($_POST['name']) || $_POST['name'] == "")
            $messages['error']['name'] = 'Please enter a valid source name!';
			
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
                         'redirect'  => '/developer/sources',
                         'messages'  => array('You have successfully created your source!')
                        );
		}
	}
	
	
	function edit_source_action() {
		$messages = array();
		if (!isset($_POST['name']) || $_POST['name'] == "")
            $messages['error']['name'] = 'Please enter a valid source name!';
			
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
                         'redirect'  => '/developer/sources',
                         'messages'  => array('You have successfully updated your source!')
                        );
		}
	}
	
	function delete_source_action() {
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
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this source? <a href="/developer/delete_source_confirm/'.$_POST['id'].'">Yes</a> / <a href="/developer/sources">No</a>')
                        );
		}
	}
	
	function delete_source_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/sources',
                         'messages'  => array('You have successfully deleted this source!')
                        );
	}
	
	function delete_config_action() {
		if (!isset($_POST['configuration_id']) || $_POST['configuration_id'] == "")
            $messages['error']['configuration_id'] = 'There is nothing to delete.';
			
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
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this configuration item? <a href="/developer/delete_config_confirm/'.$_POST['configuration_id'].'">Yes</a> / <a href="/developer/config">No</a>')
                        );
		}
	}
	
	function delete_config_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/config',
                         'messages'  => array('You have successfully deleted a configuration item!')
                        );
	}

	function delete_cache_action() {
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
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this cache server? <a href="/developer/delete_cache_confirm/'.$_POST['id'].'">Yes</a> / <a href="/developer/config">No</a>')
                        );
		}
	}
	
	function delete_cache_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/cache',
                         'messages'  => array('You have successfully delete a cache server!')
                        );
	}
	
	function update_cache_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/cache',
                         'messages'  => array('You have successfully updated your cache server!')
                        );
	}



	/** BEGIN KEY **/
	
	function delete_key_action() {
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
                         'messages'  => array('<h4>Warning!</h4>Are you sure you would like to delete this key? <a href="/developer/delete_key_confirm/'.$_POST['id'].'">Yes</a> / <a href="/developer/keys">No</a>')
                        );
		}
	}
	
	function delete_key_confirm_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/keys',
                         'messages'  => array('You have successfully delete a key!')
                        );
	}
	
	function update_key_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/keys',
                         'messages'  => array('You have successfully updated your key!')
                        );
	}
	
	/** END KEY **/


	function update_config_action() {
		return array(
                         'result'    => 'true',
                         'reason'    => 'success',
                         'redirect'  => '/developer/config',
                         'messages'  => array('You have successfully updated your configuration!')
                        );
	}
	
    function createobject_submit_action(){
        $messages = array();
        $app_root = DP_ROOT.'../application';    
        if (!isset($_POST['value']) || $_POST['value'] == "")
            $messages['error']['value'] = 'Please enter a valid object name!';
        
		if (!isset($_POST['template']) || $_POST['template'] == "")
            $messages['error']['template'] = 'Please select a valid template!';
		
		if (!is_writable($app_root)) {
			$messages['error'][] = 'This application isn\'t configured correctly, please make the application directory writable!';
		}
		
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
                         'redirect'  => '/developer/objects',
                         'messages'  => array('You have successfully created an object!')
                        );
        }
    }

	function createaction_submit_action(){
        $messages = array();
        $app_root = DP_ROOT.'../application';    
		if (!isset($_POST['object_id']) || $_POST['object_id'] == "")
            $messages['error']['object_id'] = 'Please select a valid object!';
		
        if (!isset($_POST['value']) || $_POST['value'] == "")
            $messages['error']['value'] = 'Please enter a valid view name!';
        
		if (!isset($_POST['template']) || $_POST['template'] == "")
            $messages['error']['template'] = 'Please select a valid template!';
		
		if (!is_writable($app_root)) {
			$messages['error'][] = 'This application isn\'t configured correctly, please make the application directory writable!';
		}
		
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
                         'redirect'  => '/developer/actions',
                         'messages'  => array('You have successfully created a view!')
                        );
        }
    }
}