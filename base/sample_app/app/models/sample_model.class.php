<?php
	class sample_model extends basemodel {
		function init() {
			$this->model = new dp_model($this->registry, "sample", $this->db);
		}
		
		function set($opts) {
			$id = $this->model->insert($opts);
			return $id;
		}
	
		function get($fields, $opts, $limit = 10, $page = 1, $append = "", $join = array()) {
			$results = $this->model->get($fields, $opts, $limit, $page, $append, $join);
			return $results;
		}
	
		function get_by_id($id) {
			$results = $this->model->get_by_id($id);
			return $results;
		}
	}
