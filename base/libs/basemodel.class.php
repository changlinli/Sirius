<?php

class basemodel {

	var $registry;

	var $db;

	function __construct($registry, $platform=false) {
		$this->registry = $registry;
		if($platform) {
			$this->db = $registry->platformdb;
		} else {
			$this->db = $registry->db;
		}

		if(method_exists($this, 'init')) {
			$this->init();
		}
		$this->allowed_operators = array('=','<','<=','>','>=','like','in','<>');
		// TODO: Use parametrized SQL queries!
		// Prepare a basemodel::insert statement
		$insert_statement = $this->db->prepare("");
		// Prepare a basemodel::retrieve statement
		//$retrieve_statement = null;
		//$retrieve_count_statement = $this->db->prepare("SELECT count(*) AS count FROM ? ? ? ? $table_name $joins_string $options_string $append")
	}

	private function create_numbers($current_page, $max_pages) {
		if($current_page < 5) {
			$start = 1;
		} else {
			$start = $current_page - 4;
		}
		if($current_page > ($max_pages-5)) {
			$end = $max_pages;
			$start = $end - 5;
			$start -= (8 - ($end-$start));
		} else {
			$end = $current_page + 5;
			$end += (8 - ($end-$start));
		}
		if($start < 1) $start = 1;
		if($end > $max_pages+1) $end = $max_pages+1;
		$pages['before'] = array();
		$pages['after'] = array();
		$pages['selected'][] = $current_page;
		for($i=$start;$i<$current_page; $i++) {
			$pages['before'][] = $i;
		}
		for($i=($current_page+1);$i<=$end; $i++) {
			$pages['after'][] = $i;
		}
		sort($pages['before']);
		sort($pages['after']);
		return $pages;
	}

	public function paginate($current_page, $num_per_page, $total_records) {
		$page               = (!isset($current_page)) ? 1 : $current_page;
		$num_pages          = ceil($total_records / $num_per_page);
		$page_arr           = array();

		$pagination_data    = array(
			'num_pages'     => $num_pages,
			'num_per_page'  => $num_per_page,
			'limit_floor'   => $page * $num_per_page,
			'limit_ceil'    => ($page < $num_pages) ? (($page+1)*$num_per_page) : ($total_records),
			'total_records' => $total_records,
			'page_array'    => $this->create_numbers($current_page, $num_pages)
		);

		return $pagination_data;
	}

	public function insert($table_name, $data, $db_obj, $update_data=null){
		$fields_array = array();
		$values_array = array();

		foreach($data as $key=>$val){
			$fields_array[] = htmlentities($key,ENT_QUOTES);
			if($key == 'created_at' || $key == 'modified_at'){
				$values_array[] = "UNIX_TIMESTAMP()";
			} else {
				$values_array[] = "'".addslashes($val)."'";
			}
		}	
		$fields_string = implode(",", $fields_array);
		$values_string = implode(",", $values_array);

		if ($update_data === null)
			$sql = "INSERT INTO $table_name ($fields_string) VALUES ($values_string)";
		else {
			$update_array = array();
			foreach($update_data as $key=>$value)
				$update_array[] = "$key='$value'";
			$update_string = implode(',', $update_array);
			$sql = "INSERT INTO $table_name ($fields_string) VALUES ($values_string) ON DUPLICATE KEY UPDATE $update_string";
		}
		$log = new log($this->registry, 'SQL_INSERT');
		$log->set($sql."\n");
		if($table_name == 'tags') {
			$log = new log($this->registry, 'SQL_INSERT');
			$log->set($sql."\n");
		}
		$result = $db_obj->query($sql);
		$log->set($result."\n");
		if($result) {
			return $db_obj->insert_id;
		} else {
			$log->set($db_obj->error."\n");	
			return false;
		}
	}

	public function alter($table_name, $field, $type, $after, $db_obj)
	{
		$sql = "ALTER TABLE $table_name ADD $field $type AFTER $after";
		$result = $db_obj->query($sql);

		return $result;
	}

	public function update($table_name, $data, $options, $db_obj, $cache_key = false){
		$update_array = array();
		foreach ($data as $field => $value) {
			if($field == 'created_at' || $field == 'modified_at'){
				$update_array[] = addslashes($field)."="."UNIX_TIMESTAMP()";
			} else {
				$update_array[] = addslashes($field)."='".addslashes($value)."'";
			}
		}
		$update_string = implode(",", $update_array);

		$options_set = array();
		if(count($options) > 0) {
			foreach($options as $key=>$val) {
				$option_set[] = $key."='".addslashes($val)."'";
			}
			$options_string = " WHERE ";
			$options_string .= implode(" AND ", $option_set);
		}

		$sql = "UPDATE $table_name SET $update_string $options_string";
		$result = $db_obj->query($sql);

		return $result;
	}

	// FIXME: Oh god the bloated horror
	public function retrieve($table_name, $fields, $options, $db_obj,$limit=false,$page=false,$append="", $joins=array()){
		$log = new log($this->registry, 'SQL_SELECT');
		$fields_string = implode(",", $fields);
		$options_string = "";
		// We rely on the fact that PHP short-circuits conditions here
		if(is_array($options) && count($options) > 0){
			foreach($options as $key=>$val) {
				$key_array = explode(' ',$key);
				if(count($key_array) === 2 && in_array($key_array[1], $this->allowed_operators) && $key_array[1] == 'in')
					$option_set[] = $key." ".$val;
				else if (count($key_array) === 2 && in_array($key_array[1], $this->allowed_operators) && $key_array[1] != 'in')
					$option_set[] = $key."'".htmlentities($val,ENT_QUOTES)."'";
				else
					$option_set[] = $key."='".$db_obj->real_escape_string($val)."'";
			}
			$options_string .= " WHERE ";
			$options_string .= implode(" AND ", $option_set);
		} 
		else if (!empty($options)) {
			$options_string .= " WHERE ".$options;
		}


		$joins_string = "";
		if(count($joins) > 0) {
			foreach($joins as $key=>$val) {
				$join_set[] = $key."=".htmlentities($val,ENT_QUOTES);
			}
			$joins_string = implode(" ", $join_set);
		}

		$paginated = false;
		if($limit != false && $page != false){
			$sql = "SELECT count(*) AS count FROM $table_name $joins_string $options_string $append";
			$result = $db_obj->query($sql);
			if(!$result){
				$log->set($sql."\n".$db_obj->error."\n");
			}
			else {
				$row = $result->fetch_assoc();
				$count = $row['count'];

				$num_pages = ceil($count / $limit);
				if($num_pages < 1){
					$num_pages = 1;
				}
				if($page > $num_pages){
					$page = $num_pages;
				}
				$offset = ($page - 1) * $limit;

				$pagination_string = " LIMIT $limit OFFSET $offset";
				$pagination_data    = array(
					'num_pages'     => $num_pages,
					'num_per_page'  => $limit,
					'current_page'	=> $page + 0,
					'total_count' 	=> $count,
					'page_array'    => $this->create_numbers($page, $num_pages)
				);
				$paginated = true;
			}
		}
		// TODO: Move this elsewhere
		if(extension_loaded('memcached'))
		{
			$memcached = new Memcached();
			$memcached->addServer('localhost', 11211);
			$memcached_key = md5($fields_string . $table_name . $joins_string . $options_string . $append);
			$memcached_result = $memcached->get($memcached_key);
			if($memcached->getResultCode() === Memcached::RES_NOTFOUND)
			{
				$sql = "SELECT $fields_string FROM $table_name $joins_string $options_string $append";
				$log->set($sql."\n");
				if($paginated){
					$sql .= $pagination_string;
				}
				$result = $db_obj->query($sql);
				if($result) {
					$rows = array();
					while($row = $result->fetch_assoc()) {
						$rows[] = $row;
					} 

					if($paginated){
						return array('data'=>$rows,'pagination'=>$pagination_data);
					}
					$set_attempt = $memcached->set($memcached_key, $rows);
					//if($set_attempt)
						//throw new Exception('true');
					//else
						//throw new Exception('false');
					return $rows;
				} else {
					$log->set($db_obj->error."\n");	
					return false;
				}
			}
			else
				return $memcached_result;
		}
		else
		{
			$sql = "SELECT $fields_string FROM $table_name $joins_string $options_string $append";
			$log->set($sql."\n");
			if($paginated){
				$sql .= $pagination_string;
			}
			$result = $db_obj->query($sql);
			if($result) {
				$rows = array();
				while($row = $result->fetch_assoc()) {
					$rows[] = $row;
				} 

				if($paginated){
					return array('data'=>$rows,'pagination'=>$pagination_data);
				}
				return $rows;
			} else {
				$log->set($db_obj->error."\n");	
				return false;
			}
		}
	}

	public function remove($table_name, $options, $db_obj){
		$options_string = "";

		if(count($options) > 0) {
			foreach($options as $key=>$val) {
				$option_set[] = $key."='".htmlentities($val,ENT_QUOTES)."'";
			}
			$options_string .= implode(" AND ", $option_set);
		} else {
			return false;
		}

		$sql = "DELETE FROM $table_name WHERE $options_string";
		$result = $db_obj->query($sql);
		// We have to check the number of affected rows because 
		// otherwise even a DELETE request which does not delete 
		// anything will return true.
		$num_of_affected_rows = mysqli_affected_rows($db_obj); 
		if($num_of_affected_rows > 0 && $result === true) {
			return true;
		} else {
			return false;
		}
	}

}

?>
