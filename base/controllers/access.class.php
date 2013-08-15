<?php

    class access extends basecontroller {

        function init() {

        }

        function index_action() {

        }

        function get_token_action() {
            /**get apikey data**/
            $keychain = new dp_apikeys($this->registry, true);
            $key = $keychain->get_key($_POST['X-ApiKey']);
            /**instantiate and generate access token **/
            $accesstokens = new dp_accesstokens($this->registry, true);
            $token = $accesstokens->get_token($key['id']);
            $token['result'] = 'success';
            return $token;
        }  
		
		function device_id_action(){
			if(!isset($_POST['X-ApiKey']) || empty($_POST['X-ApiKey'])) throw new Exception('Missing ApiKey.');
			
			/** get key data **/
			if(!$this->registry->apikey) throw new Exception('Missing ApiKey.');
			$keychain = new dp_apikeys($this->registry, true);
			$key = $keychain->get_key($_POST['X-ApiKey']);
			if(!$key) throw new Exception('ApiKey not found.');
			
			$devices_model = new dp_devices($this->registry, true);
			$device_id = md5(time().rand(0,10000));
			
			$device_insert_id = $devices_model->add_device($device_id);
			if ($device_insert_id) {
			  return array('result'=>'true', 'device_id'=>$device_id);
			} else {
			  return array('result'=>'false', 'message'=>"Unable to add device.");
			}
		}       

    }

?>
