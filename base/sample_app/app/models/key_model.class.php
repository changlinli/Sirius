<?php
	class key_model extends basemodel {
		var $table;

		function init() {
			$this->table 	= "apikeys";
		}
		
		function set($opts) {
			$id = $this->insert($this->table, $opts, $this->db);
			return $id;
		}
		
		function set_with_options($data, $options) {
			return $this->update($this->table, $data, $options, $this->db);
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
