<?php
/**
 * Copyright 2013 Changlin Li
 *
 * This file is part of Sirius.
 *
 * Sirius is free software: you can redistribute it and/or modify it under the 
 * terms of the Lesser GNU General Public License as published by the Free 
 * Software Foundation, either version 3 of the License, or (at your option) any 
 * later version.
 *
 * Sirius is distributed in the hope that it will be useful, but WITHOUT ANY 
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
 * A PARTICULAR PURPOSE.  See the Lesser GNU General Public License for more 
 * details.
 *
 * You should have received a copy of the Lesser GNU General Public License 
 * along with Sirius.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
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
        $controller = $this->load_controller();
        //execute controller, then check if redirect is set, if it is, then foo

        $result = $this->execute_action($controller);
        return $result;

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
        return $action_result;
    }
}
