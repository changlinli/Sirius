<?php
namespace sirius\routing;

class Apploader {

    private $vars;

    function __construct($registry) {
        $this->vars = array();
        $this->registry = $registry;
        /** load session **/
        $this->load_session();
    }

    function __set($index, $value) {
        $this->vars[$index] = $value;
    }

    function __get($index) {
        return isset($this->vars[$index]) ? $this->vars[$index] : false;
    }

    function load_session() {
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

    function execute() {
        $validator 	= $this->load_validator();
        $controller = $this->load_controller();
        //execute controller, then check if redirect is set, if it is, then foo

        //check if validation class exists

        if($validator) {
            $validated = $this->execute_validator($validator);
            if($validated[1]['result'] == 'false') {
                return array($validated[0],array_merge(array(),array('validator'=>$validated[1])));
            } else {
                //$controller->init();
                $validated 	= (!empty($validated)) ? $validated[1] : array('result'=>'true');
                $result 	= $this->execute_action($controller);
                return array($result[0],array_merge($result[1],array('validator'=>$validated)));
            }
        } else {
            $controller->init();
            $result = $this->execute_action($controller);
            return $result;
        }

        //check if the validation function for that action exists
        //if exists, run through validation
        //if true, continue to the execute action
        //if false, return the validation error
    }

    function load_validator() {
        $config 			= $this->registry->config;
        $validator_name 	= $config['validators_prefix'].$this->registry->router->controller_name;
        $class_filename 	= $validator_name.$config['class_suffix'];
        if(file_exists($config['validators_controllers'].$class_filename)) {
            $class_path 	= $config['validators_controllers'].$class_filename;
        } else {
            return false;
        }
        include_once($class_path);
        $validator = new $validator_name($this->registry);
        return $validator;
    }

    function load_controller() {
        $config 			= $this->registry->config;
        $controller_name 	= $this->registry->router->controller_name;
        $class_filename 	= $controller_name.$config['class_suffix'];
        if(file_exists($config['app_controllers'].$class_filename)) {
            $class_path 		= $config['app_controllers'].$class_filename;
        } elseif(file_exists($config['dp_controllers'].$class_filename)) {
            $class_path 		= $config['dp_controllers'].$class_filename;
        } else {
            die(header('Location: '.$this->registry->config['404_page']));
        }
        include_once($class_path);
        $controller = new $controller_name($this->registry);
        return $controller;
    }

    function execute_action(&$controller) {
        $config = $this->registry->config;
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
        return array($controller->config, $action_result);
    }

    function execute_validator(&$validator) {
        $config = $this->registry->config;
        $action_name = $this->registry->router->action_name;
        if(!method_exists($validator, $action_name.$config['action_suffix'])) {
            if(isset($_SESSION['auth']) && $_SESSION['auth'] && method_exists($validator, $action_name.$config['private_action_suffix'])) {
                $action_name = $action_name.$config['private_action_suffix'];
            } else {
                return false;
            }
        } else { 
            $action_name = $action_name.$config['action_suffix'];
        }
        $validator_result = call_user_func_array(array($validator, $action_name), $this->registry->router->route_parameters);
        return array($validator->config, $validator_result);
    }

    function validate($response) {
        if($this->registry->router->request_type == 'web' && $this->registry->router->output_format == '') {
            if(!isset($_SESSION['previous'])) {
                $_SESSION['previous']['controller'] = $this->registry->router->controller_name;
                $_SESSION['previous']['action'] 	= $this->registry->router->action_name;
            }
            if(isset($response['validator'])) {
                $validation = $response['validator'];
                if(isset($validation['reason']) && isset($validation['messages'])) {
                    $_SESSION['messages'][$validation['reason']] = $validation['messages'];
                }
                if(isset($validation['redirect'])) {
                    die(header('Location: '.$validation['redirect']));
                } else {
                    if(!empty($_POST) && isset($validation['reason']) && $validation['reason'] == 'error') {
                        die(header('Location: /'.$_SESSION['previous']['controller'].'/'.$_SESSION['previous']['action']));
                    }
                }
            }
            $_SESSION['previous']['controller'] = $this->registry->router->controller_name;
            $_SESSION['previous']['action'] 	= $this->registry->router->action_name;
        }
    }

}
