<?php class admin_model extends basemodel {
		
	public $object;
	public $write;
	
	function init() {
		$this->object_str = '[object]';
		$this->action_str = '[action]';
		$this->resource_path = DP_ROOT.'../application/resources/';
	}
	
	function format_str($val) {
		$str = strtolower(preg_replace('/[^A-Za-z0-9-]/','',$val));
		return $str;
	}
	
	function load_controller($object, $template_name) {
		$template = $this->resource_path.'controllers/controller.tpl';
		$controller_template = file_get_contents($template);
		$patterns 			= array($this->object_str,'[template]');
		$replacements 		= array($object,$template_name);
		$controller_template = str_replace($patterns,$replacements,$controller_template);
		return $controller_template;
	}
	
	function create_controller($object, $template) {
		$name = htmlentities($object);
		$object = $this->format_str($object);
		$this->init();
		$payload = $this->load_controller($object, $template);
		if($payload) {
			$this->deploy_controller($object, $payload);
			$fields = array(
				'name'		=> $name,
				'value' 	=> $object, 
				'status' 	=> '1',
				'template'	=> $template
				);
			$object_id = $this->insert('objects',$fields, $this->registry->platformdb);
		}
	}
	
	function recursive_replace($src, $object) {
		 foreach  (scandir($src) as $file) {
		   if (!is_readable($src.'/'.$file)) continue;
		   if (is_dir($file) && ($file!='.') && ($file!='..') && ($file!='.svn')) {
		   } else {
		   	   if(($file!='.') && ($file!='..') && ($file!='.svn')) {
		       	$contents = file_get_contents($src.'/'.$file);
				$patterns 		= array($this->object_str,'[_object]');
				$replacements 	= array(ucfirst($object),$object);
				$contents = str_replace($patterns,$replacements,$contents);
				file_put_contents($src.'/'.$file,$contents);
			   }
		   }
		 }
	}
	
	function xcopy($src,$dest) {
	 foreach  (scandir($src) as $file) {
	   if (!is_readable($src.'/'.$file)) continue;
	   if (is_dir($file) && ($file!='.') && ($file!='..') && ($file!='.svn')) {
	       $this->xcopy($src.'/'.$file, $dest.'/'.$file);
	   } else {
	   	   if(($file!='.') && ($file!='..') && ($file!='.svn')) {
	   	   	$file_pieces = explode('.',$file);
	       	copy($src.'/'.$file, $dest.'/'.$file_pieces[0].'.php');
			chmod($dest.'/'.$file_pieces[0].'.php',0777);
		   }
	   }
	 }
	 return true;
	}
	
	function deploy_controller($object,$payload) {
		$file = DP_ROOT.'../application/controllers/'.$object.'.class.php';
		$model_file = DP_ROOT.'../application/models/'.$object.'_model.class.php';
		$f = fopen($file,"w");
		file_put_contents($file, $payload);
		fclose($f);
		chmod($file,0777);
		mkdir(DP_ROOT.'../application/views/web/'.$object, 0755);
		if($this->xcopy(DP_ROOT.'../application/resources/views',DP_ROOT.'../application/views/web/'.$object)) {
			$this->recursive_replace(DP_ROOT.'../application/views/web/'.$object, $object);
		}
		if(copy(DP_ROOT.'../application/resources/models/model.tpl',$model_file)){
			chmod($model_file,0777);
			//write object name to views
			$model_data = file_get_contents($model_file);
			$model 		= str_replace($this->object_str,$object,$model_data);
			file_put_contents($model_file,$model);
		}
	}
	
	function choose_template($chosen_template) {
		$file = DP_ROOT.'../application/resources/snippets/template.tpl';
		$template = str_replace('[template_name]',$chosen_template,file_get_contents($file));
		return $template;
	}
	
	function modify_controller($object,$append) {
		$file = DP_ROOT.'../application/controllers/'.$object.'.class.php';
		$controller_template = file($file);
		$length 		= count($controller_template);
		$tmp_start		= array_slice($controller_template,0,$length-1);
		$tmp_end 		= array_slice($controller_template,$length-1);
		
		array_push($tmp_start,$append);
		$file_modified = array_merge($tmp_start,$tmp_end);

		$f = fopen($file, "w");
		file_put_contents($file,implode("",$file_modified));
		fclose($f);		
	}
	
	function load_action($action, $template) {
		$action_file 		= $this->resource_path.'snippets/action.tpl';
		$action_content 	= file_get_contents($action_file);
		$layout 			= $this->choose_template($template);
		$patterns 			= array($this->action_str,'[template]');
		$replacements 		= array($action,$layout);
		$action_template 	= str_replace($patterns,$replacements,$action_content);
		return "\n".$action_template."\n";
	}
	
	function create_action($object_id, $action, $template) {
		$object = $this->retrieve('objects', array('id','value'), array('id'=>$object_id), $this->registry->platformdb);
		$action = $this->format_str($action);
		$this->init();
		$payload = $this->load_action($action, $template);
		if($payload) {
			$this->modify_controller($object[0]['value'], $payload);
			$fields = array(
				'value' 	=> $action, 
				'status' 	=> '1',
				'objects_id'=>$object_id,
				'template'	=>$template
				);
			$this->insert('actions',$fields, $this->registry->platformdb);
			$action_path = DP_ROOT.'../application/views/web/'.$object[0]['value'].'/'.$action.'.php';
			copy(DP_ROOT.'../application/resources/views/blank.tpl',$action_path);
			
			$view_data 		= file_get_contents($action_path);
			$patterns 		= array($this->object_str,'[_object]');
			$replacements 	= array(ucfirst($object[0]['value']),$object[0]['value']);
			$view_data 		= str_replace($patterns,$replacements,$view_data);
			
			file_put_contents($action_path,$view_data);
			chmod($action_path,0777);
		}
	}
} ?>
