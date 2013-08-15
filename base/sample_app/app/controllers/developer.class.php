<?php class developer extends basecontroller {
	function init() {
		$this->strict = $this->init_api(true);
		$this->config['app_layout']='developer.php';
		$permissions = new dp_permissions($this->registry, true);
		if(!$this->strict) {
			$permissions->is_auth();
			$permissions->has_permissions(array(1));
		} else {
			throw new Exception('You are not authorized to access this.');
		}
		ini_set('auto_detect_line_endings',true);
	}
	
	function index_action() {
		return array('result'=>'true');
	}
	
	function sources_action($page = 1) {
		$this->registry->layout->add_js('/assets/js/views/developer/sources.js');
		$model 		= new sources_model($this->registry, true);
		$result 	= $model->get(array('*'), array(), 10, $page, "", array());
		return array('result'=>'true', 'data'=>$result['data'],'pagination'=>$result['pagination']);
	}
	
	function databases_action() {
		return array();
	}
	
	function add_source_action() {
		$model 		= new sources_model($this->registry, true);
		$opts 		= $_POST;
		$opts['created_at'] = 'UNIX_TIMESTAMP()';
		$result 	= $model->set($opts);
		return array('result'=>'true','data'=>$result);
	}
	
	function edit_source_action() {
		$model 		= new sources_model($this->registry, true);
		$options	= array('id'=>$_POST['id']);
		$data 		= $_POST;
		unset($data['id']);
		$result 	= $model->set_with_options($data, $options);
		return array('result'=>$result);
	}

	function delete_source_action() {
		return array('result'=>'true');
	}
	
	function delete_source_confirm_action($id = false) {
		$model 	= new sources_model($this->registry, true);
		return array('result'=>$model->delete_by_id($id));
	}
	
	function logs_action($current_log = false) {
		$tools_model 	= new tools_model($this->registry, true);
		return array('result'=>'true','log_file_name'=>$current_log, 'log_dir'=>$tools_model->log_path, 'log_files'=>$tools_model->log_files($current_log), 'log_data'=>$tools_model->show_log_contents($current_log));
	}
	
	function add_config_action() {
		$config_model 	= new config_model($this->registry, true);
		$name 			= strtolower($_POST['name']);
		$value 			= $_POST['value'];
		$result 		= $config_model->set(array('name'=>$name,'value'=>$value));
		return array('result'=>$result);
	}
	
	function delete_config_action() {
		return array('result'=>'true');
	}
	
	function delete_config_confirm_action($id = false) {
		$config_model 	= new config_model($this->registry, true);
		return array('result'=>$config_model->delete_by_id($id));
	}


	function keys_action($page = 1) {
		$this->registry->layout->add_js('/assets/js/views/developer/keys.js');
		$key_model 		= new dp_model($this->registry, 'apikeys');
		$key_result 	= $key_model->get(array('*'), array(), 10, $page, "", array());
		return array('result'=>'true', 'data'=>$key_result['data'],'pagination'=>$key_result['pagination']);
	}
	
	function add_key_action() {
		$key_model 			= new dp_model($this->registry, 'apikeys');
		$application_name 	= $_POST['application_name'];
		$result 			= $key_model->set(array('`key`'=>md5($application_name.$_SESSION['user']['id'].microtime()), 'application_name'=>$application_name,'active'=>'1','output_format'=>'json','created_at'=>'UNIX_TIMESTAMP()'));
		return array('result'=>$result);
	}
	
	function delete_key_action() {
		return array('result'=>'true');
	}
	
	function delete_key_confirm_action($id = false) {
		$key_model = new dp_model($this->registry, 'apikeys');
		return array('result'=>$key_model->delete_by_id($id));
	}
	
	function update_key_action() {
		$key_model 	= new dp_model($this->registry, 'apikeys');
		$options	= array('id'=>$_POST['id']);
		$data 		= $_POST;
		unset($data['id']);
		$result 	= $key_model->set_with_options($data, $options);
		return array('result'=>$result);
	}
	
	function add_cache_action() {
		$cache_model 	= new cache_model($this->registry, true);
		$ip 			= $_POST['ip'];
		$port 			= $_POST['port'];
		$weight			= $_POST['weight'];
		$result 		= $cache_model->set(array('ip'=>$ip,'port'=>$port,'weight'=>$weight, 'created_at'=>'UNIX_TIMESTAMP()'));
		return array('result'=>$result);
	}
	
	function delete_cache_action() {
		return array('result'=>'true');
	}
	
	function delete_cache_confirm_action($id = false) {
		$cache_model = new cache_model($this->registry, true);
		return array('result'=>$cache_model->delete_by_id($id));
	}
	
	function update_cache_action() {
		$cache_model 	= new cache_model($this->registry, true);
		$options		= array('id'=>$_POST['id']);
		$data 			= $_POST;
		unset($data['id']);
		$result 		= $cache_model->set_with_options($data, $options);
		return array('result'=>$result);
	}
	
	function update_config_action() {
		$config_model 	= new config_model($this->registry, true);
		$options 		= array('name'=>$_POST['name']);
		$data 			= array('value'=>$_POST['value']);		
		$result 		= $config_model->set_with_options($data, $options);
		return array('result'=>$result);
	}
	
	function config_action($page = 1) {
		$this->registry->layout->add_js('/assets/js/views/developer/config.js');
		$config_model = new config_model($this->registry, true);
		$keys = array('project_name', 'default_controller', 'default_action', 'site_email', 'google_analytics');
		
		foreach($keys as $key) {
			$opts[] = '`name` != "'.$key.'"';
		}

		$opts_str = "WHERE ".implode(' AND ', $opts);
		$config_result = $config_model->get(array('*'), array(), 10, $page, $opts_str, array());
		return array('result'=>'true','primary_items'=>$config_model->get_config_item($keys), 'items'=>$config_result['data'],'pagination'=>$config_result['pagination']);		
	}

	function cache_action($page = 1) {
		$cache_model 	= new cache_model($this->registry, true);
		$this->registry->layout->add_js('/assets/js/views/developer/cache.js');
		$cache_result = $cache_model->get(array('*'), array(), 10, $page, "", array());
		return array('result'=>'true', 'items'=>$cache_result['data'],'pagination'=>$cache_result['pagination']);
	}

	function createobject_submit_action() {
		//loads object
		$admin_model = new admin_model($this->registry);
		$object = $_POST['value'];
		$template = $_POST['template'];
		if($_POST) {
			$admin_model->create_controller($object,$template);
			return array('result'=>'true');
		} else {
			return array('result'=>'false');
		}
		return array('result'=>'false');
	}
	
	function default_action() {
	}
	
	function create_action() {
	}
	
	function views_action($page = 1) {
		$actions = new admin_model($this->registry, true);
		$actions_listing = $actions->retrieve('actions', array('id','value','template','status'), array(), $this->registry->platformdb, 10, $page);
		return array('result'=>'true', 'actions'=>$actions_listing);
	}
	
	function objects_action($page = 1) {
		$objects = new admin_model($this->registry, true);
		$object_listing = $objects->retrieve('objects', array('id','value as name','template','status'), array(), $this->registry->platformdb, 10, $page);
		return array('result'=>'true','objects'=>$object_listing);
	}
	
	function createaction_action() {
		$objects = new admin_model($this->registry, true);
		$object_listing = $objects->retrieve('objects', array('id','name'), array(), $this->registry->platformdb);
		return array('result'=>'true','objects'=>$object_listing);
	}
	
	function createobject_action() {
		return array('result'=>'true');
	}
	
	function createaction_submit_action() {
		//loads action and template
		$action 	= $_POST['value'];
		$object_id 	= $_POST['object_id'];
		$template	= $_POST['template'];
		if($_POST) {
			$admin_model = new admin_model($this->registry);
			$admin_model->create_action($object_id,$action,$template);
			return array('result'=>'true');
		}
		return array('result'=>'false');
	}
} ?>
