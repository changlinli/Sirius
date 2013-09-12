<?php 
class cache {
	var $registry;
	var $is_cache;
	var $cache_obj;
	var $expires;
	function __construct($registry) {
		$this->registry = $registry;
		$this->expires 	= (isset($registry->router->cache_parameters['expires'])) ? $registry->router->cache_parameters['expires'] : 24*3600;
		$this->is_cache = (isset($registry->router->cache_parameters['is_cache'])) ? filter_var($registry->router->cache_parameters['is_cache'], FILTER_VALIDATE_BOOLEAN) : true;
		$this->renew	= (isset($registry->router->cache_parameters['renew'])) ? filter_var($registry->router->cache_parameters['renew'], FILTER_VALIDATE_BOOLEAN) : false;
		$this->servers	= array();
		
		if(extension_loaded('memcached')) {
			$memcached = new Memcached();
			//$this->servers();
			//if(count($this->servers) > 0) {
				//foreach($this->servers as $server) {
					//$status = $memcached->getServerStatus($server['server'],$server['port']);
					//if($status == 0) {
						//$memcached->addServer($server['server'],$server['port'],$server['weight']);
					//}
				//}
			//}
			//$this->cache_obj = $memcached;
		} else {
			$this->cache_obj = new file_cache($registry);
		}
	}
	
	function servers() {
		$result = $this->db->query("SELECT * FROM cache");
		$rows = array();
		$i = 0;
		while($row = $result->fetch_assoc()) {
			$rows[$i]['server'] = $row['ip'];
			$rows[$i]['port']	= $row['port'];
			$rows[$i]['weight'] = $row['weight'];
			$i++;
		}
		$this->servers = $rows;
	}
	
	function deep_ksort(&$arr) {
	    ksort($arr);
	    foreach ($arr as &$a) {
	        if (is_array($a) && !empty($a)) {
	            $this->deep_ksort($a);
	        }
	    }
	}
	
	function _key($query) {
		return crc32($query);
	}
	
	function create_key($post,$get,$controller,$action) {
		if(preg_match('/.json/',$get['rt'])) {
			$rt_pieces = explode('.',$get['rt']);
			$get['rt'] = $rt_pieces[0];
		} else if(preg_match('/|/',$get['rt']) && !preg_match('/.json/',$get['rt'])) {
			$rt_pieces = explode('|',$get['rt']);
			$get['rt'] = $rt_pieces[0];
		} else {
		}
		
	    $this->deep_ksort($post);
	    $this->deep_ksort($get);
	    $post    = serialize($post);
	    $get     = serialize($get);
	    $key     = $post.'.'.$get.'.'.$controller.'.'.$action;
		return $controller.'_'.$action.'_'.md5($key);
	}
	
	function get($key) {
		return $this->cache_obj->get($key);
	}
	
	function set($key, $data, $expiration = false) {
		if($expiration == false) {
			$expiration = $this->expires;
		}
		return $this->cache_obj->set($key, $data, $expiration);
	}
	
	function replace($key,$data,$expiration = false) {
		if($expiration == false) {
			$expiration = $this->expires;
		}
		return $this->cache_obj->replace($key, $data, $expiration);
	}

	function delete($key) {
		return $this->cache_obj->delete($key);
	}
}

?>
