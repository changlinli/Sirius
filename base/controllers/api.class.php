<?php
class api extends basecontroller {
    var $params;
    var $device;

    function init() {
        $this->init_api();
        $this->params = array();
        if(isset($_POST['params']) && !empty($_POST['params'])) $this->params = json_decode($_POST['params'],true);
        $this->config['app_layout'] = 'blank.php';
    }
    
    function index_action() {
        return array('result' => 'true');
    }

    function check_and_add_device() {
        $devices = new dp_devices($this->registry, true);
        if (!isset($_POST['X-DeviceID']) || empty($_POST['X-DeviceID'])) {
            throw new Exception('Missing DeviceID.');
        }
        $device = $devices->get_by_device_id($_POST['X-DeviceID']);
        if (!$device) {
            $devices->add_device($_POST['X-DeviceID']);
            $device = $devices->get_by_device_id($_POST['X-DeviceID']);
        }
       	if(count($device) > 0 && $device != false) {
        	$this->device = $device[0];
       	} else {
       		throw new Exception('Device ID not found.');
       	}
    }

    function auth_action() {
    	$this->check_and_add_device();
        if (!isset($this->params['X-AuthToken'])) {
            throw new Exception('Missing AuthToken.');
        }

        if (!$this->registry->apikey)
            throw new Exception('Missing ApiKey.');
        $keychain = new dp_apikeys($this->registry, true);
        $key = $keychain->get_key($_POST['X-ApiKey']);
        if (!$key)
            return array('result' => 'false', 'message' => 'ApiKey not found.');
        /** get access token data **/

        if (!$this->registry->accesstoken)
            return array('result' => 'false', 'message' => 'Missing AccessToken.');
        $tokens = new dp_accesstokens($this->registry, true);

        $accesstoken = $tokens->get_token($key['id'], $_POST['X-AccessToken']);
        if (!$accesstoken)
            return array('result' => 'false', 'message' => 'AccessToken not found.');
        /** generate auth token **/
        $authtokens = new dp_authtokens($this->registry, true);
        $auth_response = $authtokens->check_token($key['id'], $accesstoken['id'], $this->params['X-AuthToken']);
        
        if ($auth_response == false) {
            return array('result' => 'false', 'message' => 'AuthToken not accepted.');
        } else {
            $devices = new dp_devices($this->registry, true);
            $dtoken = isset($this->params['X-DeviceToken']) ? $this->params['X-DeviceToken'] : '';

            if ($dtoken != '') {
            	$user = $devices->get_by_device_token($dtoken);
            	if(count($user) > 0) {
            		if($user[0]['user_id'] != '') {
            			$devices->link_to_user($user[0]['user_id'], $this->device['device_id'], $dtoken);
            		} else {
            			$user_id = $devices->link_device_to_user(false, 0, 0);
            			$devices->link_to_user($user_id, $this->device['device_id'], $dtoken);
            		}
            	} else {
            		$user_id = $devices->link_device_to_user(false, 0, 0);
            		$devices->link_to_user($user_id, $this->device['device_id'], $dtoken);
            	}
                return array('result' => 'true');
            } else {
                return array('result' => 'false', 'message' => 'No Device Token set.');
            }
        }
    }
}
