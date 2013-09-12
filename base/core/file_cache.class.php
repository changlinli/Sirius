<?php 
class file_cache {
	var $registry;
	var $cache_path;
	var $cache_prefix;
	function __construct($registry) {
		$this->registry = $registry;
		$this->cache_path = '/tmp/cache';
		$this->cache_prefix = 'cache_';
		if(!is_dir($this->cache_path)) {
			mkdir($this->cache_path);
			chmod($this->cache_path,755);
			$this->cache_path .='/';
		} else {
			$this->cache_path .='/';
		}
	}
	
	function get($key) {
		$now				= time();
		$file 				= $this->cache_path.$this->cache_prefix.$key;
		if(file_exists($file)) {
			$expiration_time	= filemtime($file);
			if($expiration_time <= $now) {
				unlink($file);
			} else {
				$f		= fopen($file,"r");
				flock($f,LOCK_SH);
				$contents = file_get_contents($file);
				fclose($f);
				return unserialize($contents);
			}
		} else {
			return false;
		}
		return false;
	}
	
	function replace($key, $value, $expiration) {
		$this->delete($key);
		$this->set($key,$value,$expiration);
		return true;
	}
	
	function set($key, $value, $expiration) {
		$expiration = strtotime("now")+$expiration;
		$file = $this->cache_path.$this->cache_prefix.$key;
		$f = fopen($file,"w");
		$microtime_start = microtime(true);
		b:
		if(!flock($f, LOCK_SH | LOCK_NB)) {
			usleep(10000);
			$microtime_end = microtime(true);
			if($microtime_end >= $microtime_start+10000) {
				goto b;
			}
		} else {
			file_put_contents($file, serialize($value));
			touch($file, $expiration);
		}
		return true;
	}
	
	function delete($key) {
		if(file_exists($this->cache_prefix.$key)) {
			unlink($key);
			return true;
		} else {
			return false;
		}
	}
}
?>