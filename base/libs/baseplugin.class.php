<?php
class baseplugin extends basemodel {
	//want to initialize the app
	//check if it's active
	var $config;
	var $registry;
	
	function __construct($name, $registry) {
		$this->registry = $registry;
		parent::__construct($registry);
		$opts 	= $this->load($name);
		parse_str($opts['values'],$config);
		$classname = $name.'_plugin';
		$this->$name = new $classname($registry);
		if(method_exists($this->$name, 'init')) {
			$this->$name->init($config);
		}
	}
	
	function load($name) {
		$sources = new sources_model($this->registry, true);
		$results = $sources->get(array("`id`","`shortname`", "`name`", "`values`", "`status`", "`created_at`"),array("status"=>"1", "shortname"=>$name),false,false,"",array());
		return $results[0];
	}
	
}