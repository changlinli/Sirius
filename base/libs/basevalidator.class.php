<?php

    abstract class basevalidator {
        var $registry;
        public $config;

        function __construct($registry) {
            define('BASEVALIDATOR_MAXVAL',1,false);
			define('BASEVALIDATOR_MINVAL',2,false);
			
			define('BASEVALIDATOR_MAXLENGTH',3,false);
			define('BASEVALIDATOR_MINLENGTH',4,false);
			
            $this->registry = $registry;
            $this->config = $registry->config;
        }
		
		function validate_email($string) {
			if(filter_var($string,FILTER_VALIDATE_EMAIL) === false) {
				return false;
			}
			return true;
		}
		
		function validate_alphanumeric($string,$flag=0,$option=NULL) {
			if(!ctype_alnum($string)){
				return false;
			}
			
			if($flag != 0 && filter_var($option,FILTER_VALIDATE_INT)){
				if(($flag == BASEVALIDATOR_MAXLENGTH && intval($option) < strlen($string)) ||
				$flag == BASEVALIDATOR_MINLENGTH && intval($option) > strlen($string)){
					return false;
				}
					
			}
			
			return true;
		}
		
		function validate_url($string) {
			if(filter_var($string,FILTER_VALIDATE_URL) === false){
				return false;
			}
			
			return true;
		}
		
		function validate_date($string, $flag=0,$option= NULL) {
			$date = strtotime($string);
			if($date===false){
				return false;
			}
			
			if($flag != 0 && strtotime($option) !== FALSE) {
				if(($flag == BASEVALIDATOR_MAXVAL && strtotime($option) < $date) || 
				($flag == BASEVALIDATOR_MINVAL && strtotime($option) > $date)){	
					return false;
				}
			}
			
			return true;
		}
		
				
		function validate_alpha($string,$flag=0,$option=NULL) {
			if(ctype_alpha($string) === false){
				return false;
			};
			
			if($flag != 0 && filter_var($option,FILTER_VALIDATE_INT)){
				if(($flag == BASEVALIDATOR_MAXLENGTH && intval($option) < strlen($string)) ||
				$flag == BASEVALIDATOR_MINLENGTH && intval($option) > strlen($string)){
					return false;
				}
					
			}
			
			return true;
		}
		
		function validate_numeric($string,$flag=0,$option=NULL) {
			if(!is_numeric($string)){
				return false;
			}	
			
			if($flag != 0 && is_numeric($option)){
				if(($flag == BASEVALIDATOR_MAXVAL && floatval($option) < floatval($string)) ||
				($flag == BASEVALIDATOR_MINVAL && floatval($option) > floatval($string))){
					return false;
				}
			}
			
			return true;
		}

		
		function validate_int($string,$flag=0,$option=NULL) {
			if(filter_var($string,FILTER_VALIDATE_INT) === FALSE){
				return false;
			}
			
			if($flag != 0 && filter_var($option,FILTER_VALIDATE_INT)){
				if(($flag == BASEVALIDATOR_MAXVAL && intval($option) < intval($string)) ||
				($flag == BASEVALIDATOR_MINVAL && intval($option) > intval($string))){
					return false;
				}
				
			}
			
			return true;
		}
    }

?>
