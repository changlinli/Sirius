<?php

    class admin extends basecontroller {

        function init() {
			$this->strict = $this->init_api(true);
			$this->config['app_layout']='admin.php';
			$permissions = new dp_permissions($this->registry, true);
			if(!$this->strict) {
				$permissions->is_auth();
				$permissions->has_permissions(array(1,2));
			} else {
				throw new Exception('You are not authorized to access this.');
			}
			ini_set('auto_detect_line_endings',true);
        }

        function index_action() {

        }
		
        function users_action($page = 1) {
        	$users_model = new dp_users_model($this->registry, true);
			$this->registry->layout->add_js('/assets/js/views/admin/users.js');
			$user_result = $users_model->get(array('*'), array(), 10, $page, "", array());
			$roles_model = new dp_roles($this->registry, true);
			return array('result'=>'true', 'roles'=>$this->registry->roles_redirects,'items'=>$user_result['data'],'pagination'=>$user_result['pagination']);
        }
		
		function add_user_action() {
			$users_model 		= new dp_users_model($this->registry, true);
			$post 				= $_POST;
			$post['password'] 	= md5($_POST['password']);
			$post['created_at']	= 'UNIX_TIMESTAMP()';
			$post['active']		= '1';
			$post['role_id']	= implode(',',$_POST['role_id']);
			$result 			= $users_model->set($post);
			return array('result'=>$result);
		}
		
		function update_user_action() {
			$users_model 		= new dp_users_model($this->registry, true);
			$data 				= $_POST;
			if(isset($_POST['password']) && !empty($_POST['password'])) {
				$data['password'] 	= md5($_POST['password']);
			} else {
				unset($data['password']);
			}
			$data['role_id']	= implode(',',$data['role_id']);
			$data['modified_at']= 'UNIX_TIMESTAMP()';
			$options 			= array('id'=>$data['id']);
			unset($data['id']);
			$result 			= $users_model->set_with_options($data, $options);
			return array('result'=>'true');
		}
		
		function delete_user_confirm_action($id = false) {
			$users_model = new dp_users_model($this->registry, true);
			return array('result'=>$users_model->delete_by_id($id));
		}
		
		function delete_user_action() {
			return array('result'=>'true');
		}
		
		function roles_action($page = 1) {
			$roles_model = new dp_roles($this->registry, true);
			$this->registry->layout->add_js('/assets/js/views/admin/roles.js');
			$role_result = $roles_model->get(array('*'), array(), 10, $page, "", array());
			return array('result'=>'true', 'items'=>$role_result['data'],'pagination'=>$role_result['pagination']);
		}
		
		function add_role_action() {
			$roles_model 		= new dp_roles($this->registry, true);
			$post 				= $_POST;
			$post['created_at']	= 'UNIX_TIMESTAMP()';
			$result 			= $roles_model->set($post);
			return array('result'=>$result);
		}
		
		function update_role_action() {
			$roles_model 		= new dp_roles($this->registry, true);
			$data 				= $_POST;
			$options 			= array('id'=>$data['id']);
			unset($data['id']);
			$result 			= $roles_model->set_with_options($data, $options);
			return array('result'=>'true');
		}
		
		function delete_role_confirm_action($id = false) {
			$roles_model 		= new dp_roles($this->registry, true);
			return array('result'=>$roles_model->delete_by_id($id));
		}
		
		function delete_role_action() {
			return array('result'=>'true');
		}
    }
