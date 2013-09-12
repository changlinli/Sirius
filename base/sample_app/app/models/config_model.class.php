<?php
	class config_model extends basemodel {
		var $table;

		function init() {
			$this->table 	= "configuration";
		}
		
		function set($opts) {
			$id = $this->insert($this->table, $opts, $this->db);
			return $id;
		}
		
		function set_with_options($data, $options) {
			return $this->update($this->table, $data, $options, $this->db);
		}
		
		function get_config_item($keys = array()) {
			$fields = array("*");
			$result = $this->get($fields, array(), false, false, "", array());
			$opts	= array();
			
			if(count($result) > 0) {
				foreach($result as $item) {
					if(in_array($item['name'],$keys)) {
						$opts[$item['name']] = $item['value'];
					}
				}
			}
			
			return $opts;
		}
		
		function get($fields, $opts = array(), $limit = false, $page = false, $append = "", $join = array()) {
			$results = $this->retrieve($this->table, $fields, $opts, $this->db, $limit, $page, $append, $join);
			return $results;
		}
		
		function get_by_id($id) {
			$options = array('id' => $id);
			$results = $this->retrieve($this->table, array('*'), array('id' => $id), $this->db);
			if (!empty($results[0]))
				return $results[0];
			return false;
		}
		
		function delete_by_id($id) {
			return $this->remove($this->table, array('id'=>$id), $this->db);
		}
	}
