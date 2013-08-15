<?php

class apploader {

    private $vars;

    public function __construct($registry) {
        $this->vars = array();
        $this->registry = $registry;
        /** load session **/
        $this->load_session();
    }

    public function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    public function __get($index) {
        return isset($this->vars[$index]) ? $this->vars[$index] : false;
    }

    public function load_session() {
        if($this->registry->router->request_type == 'api') {
            $keychain = new dp_apikeys($this->registry, true);
            $key = $keychain->get_key($_POST['X-ApiKey']);
            if(!$key) throw new Exception('Api Key not found.');
            if(isset($_POST['X-AccessToken']) && !empty($_POST['X-AccessToken'])) {
                $tokens = new dp_accesstokens($this->registry, true);
                $token_data = $tokens->get_token($key['id'], $_POST['X-AccessToken']);
                if(!$token_data) throw new Exception('Access Token not found.');
                $session_id = $token_data['session_id'];
                session_id($session_id);
                $this->log_action($key['id']);
            }
        } elseif($this->registry->router->request_type != 'cli') {
            session_start();
        }
    }

    public function execute() {
        $controller = $this->load_controller();
        //execute controller, then check if redirect is set, if it is, then foo

        if(method_exists($controller, 'init')) {
            $controller->init();
        }
        $result = $this->execute_action($controller);
        return $result;
    }

    private function load_controller() {
        $config 			= $GLOBALS['TABLE_CONFIG'];
        $controller_name 	= $this->registry->router->controller_name;
        $class_filename 	= $controller_name.$config['class_suffix'];
        if(file_exists($config['app_controllers'].$class_filename)) {
            $class_path 		= $config['app_controllers'].$class_filename;
        } elseif(file_exists($config['dp_controllers'].$class_filename)) {
            $class_path 		= $config['dp_controllers'].$class_filename;
        } else {
            die(header('Location: ' . $config['404_page']));
        }
        include_once($class_path);
        $controller = new $controller_name($this->registry);
        return $controller;
    }

    function execute_action($controller) {
        $config = $GLOBALS['TABLE_CONFIG'];
        $action_name = $this->registry->router->action_name;
        if(!method_exists($controller, $action_name.$config['action_suffix'])) {
            if(isset($_SESSION['auth']) && $_SESSION['auth'] && method_exists($controller, $action_name.$config['private_action_suffix'])) {
                $action_name = $action_name.$config['private_action_suffix'];
            } else {
                die(header('Location: '.$this->registry->config['404_page']));
            }
        } else { 
            $action_name = $action_name.$config['action_suffix'];
        }
        $action_result = call_user_func_array(array($controller, $action_name), $this->registry->router->route_parameters);
        // 'metadata' might contain data such as a custom layout or additional 
        // metadata relevant to the view
        if(isset($action_result['metadata'])) {
            return $action_result;
        } else {
            return array('metadata' => null, 'data' => $action_result);
        }
    }
}
