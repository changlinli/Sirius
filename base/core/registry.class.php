<?php

class registry {

	private $vars;

	public $config;

	function __construct($request_uri, $cli=false, $config=null, $dbs='in_table') {
		$this->vars = array();
		/** get route path **/
        $url_parts = parse_url($request_uri);
        $this->route_path = ltrim($url_parts['path'], '/');
        /** parse query string **/
        $query = array();
        if(isset($url_parts['query'])) parse_str($url_parts['query'], $query);
        $this->query = $query;
		/** get apikey and accesstoken if available **/
		$this->apikey = isset($_POST['X-ApiKey']) ? $_POST['X-ApiKey'] : false;
		$this->accesstoken = isset($_POST['X-AccessToken']) ? $_POST['X-AccessToken'] : false;
		/** initialize platform db **/
        if(DB_NAME !== '') {
            $this->platformdb = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $this->platformdb->set_charset("utf8");
        } else {
            $this->platformdb = null;
        }
		/** load configuration from database **/
		if($config === null) {
			$this->config = $this->load_config();
		}
		else {
			$this->config = $config;
		}
		/** load application databases **/
		if($dbs === 'in_table') {
			$this->load_databases();
		}
		/** load post from input **/
		$this->load_post();
		/** 
		 * load different kinds of roles and what pages they should 
		 * redirect to from configuration file 
		 */
		global $ROLES_ARRAY;
		$this->roles_redirects = $ROLES_ARRAY;
	}

	function load_config() {
		$config = $GLOBALS['TABLE_CONFIG'];
		return $config;
	}

	private function is_json($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	function load_post() {
		$body = file_get_contents("php://input");
		if(isset($this->apikey) && $this->apikey != false) {
			$_POST = (array) json_decode($body);
		} else {
			if($this->is_json($body)) {
				if(isset($_FILES) && count($_FILES) > 0) {
				} else {
					$_POST = (array) json_decode($body);
				}

			} else {
				parse_str($body,$output);
				$_POST = $output;
			}
		}
	}

	function load_databases() {
        $this->db = $this->platformdb;
		$this->dbs = array($this->db);
	}

	function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	function __get($index) {
		return isset($this->vars[$index]) ? $this->vars[$index] : false;
	}
}
