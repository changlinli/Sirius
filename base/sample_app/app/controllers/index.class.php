<?php

    class index {
		
        //sample function used within this controller
    	function index_action() {
			$this -> config['app_layout'] = 'layout.php';
            return array(
                'metadata' => null, 
                'data' => array()
            );
		}
    }
