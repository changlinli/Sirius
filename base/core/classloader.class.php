<?php

    class classloader {

        var $registry;

        public function __construct($registry) {
            $this->registry = $registry;
            spl_autoload_register(array($this, 'load'));
        }

        private function load($class_name) {
            $config = $this->registry->config;
            $class_filename = $class_name.$config['class_suffix'];
            if(file_exists($config['dp_core'].$class_filename)) {
                $class_path = $config['dp_core'].$class_filename;
            } elseif(file_exists($config['dp_libs'].$class_filename)) {
                $class_path = $config['dp_libs'].$class_filename;
            } elseif(file_exists($config['dp_plugins'].$class_filename)) {
                $class_path = $config['dp_plugins'].$class_filename;
            } elseif(file_exists($config['dp_controllers'].$class_filename)) {
                $class_path = $config['dp_controllers'].$class_filename;
            } elseif(file_exists($config['dp_models'].$class_filename)) {
                $class_path = $config['dp_models'].$class_filename;
            } elseif(file_exists($config['app_models'].$class_filename)) {
                $class_path = $config['app_models'].$class_filename;
            } else {
                throw new Exception('Core Object not found ('.$class_name.')');
            }
            include_once($class_path);
        }

    }

?>
