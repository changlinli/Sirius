<?php
namespace tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;

class classloader {

	var $registry;

	public function __construct($registry) {
		$this->registry = $registry;
		spl_autoload_register(array($this, 'load'));
	}

	private function load($class_name) {
		if(!class_exists($class_name)) {
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
                        } elseif(file_exists(config\TEST_ROOT.'setup/'.$class_filename)) {
                                $class_path = config\TEST_ROOT.'setup/'.$class_filename;
                        } else {
                                echo config\TEST_ROOT.'setup/'.$class_filename;
				// Try to see if class contains PHPUnit at its 
				// beginning
				$pattern = "/^PHPUnit/";
				if(preg_match($pattern, $class_name)) {
					// This is empty because of the fact 
					// that spl_autoload_register uses a 
					// stack. This load function is thus 
					// popped off the stack before PHPUnit's 
					// own autoload function is and so we 
					// try to find PHPUnit's class when in 
					// fact this is not possible using our 
					// autoload function.
					return null;
				}
				else
					throw new \Exception('Core Object not found ('.$class_name.')');
			}
			include_once($class_path);
		}
	}

}
