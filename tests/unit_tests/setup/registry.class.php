<?php
namespace tests\unit_tests\setup\registry;
use tests\unit_tests\setup\config as config;

class registry {

	private $vars;

	public static $config;

	function __construct($request_uri, $cli=false) {
		$this->vars = array();
		/** get route path **/
		if($cli) {
			$this->route_path = $request_uri;
			$this->cli = true;
		} else {
			$url_parts = parse_url($request_uri);
			$this->route_path = ltrim($url_parts['path'], '/');
			/** parse GET query string **/
			$query = array();
			if(isset($url_parts['query'])) parse_str($url_parts['query'], $query);
			$this->query = $query;
		}
		/** get apikey and accesstoken if available **/
		$this->apikey = isset($_POST['X-ApiKey']) ? $_POST['X-ApiKey'] : false;
		$this->accesstoken = isset($_POST['X-AccessToken']) ? $_POST['X-AccessToken'] : false;
		/** initialize platform db **/
		$this->platformdb = mysqli_connect(config\UNIT_TEST_DB_HOST, config\UNIT_TEST_DB_USER, config\UNIT_TEST_DB_PASS, config\UNIT_TEST_DB_NAME);
		$this->platformdb->set_charset("utf8");
		/** load configuration **/
		$this->config = config\load_config();
		/** load application databases **/
		$this->load_databases();
		/** load post from input **/
		$this->load_post();
		/** 
		 * load different kinds of roles and what pages they should 
		 * redirect to from configuration file 
		 */
		global $ROLES_ARRAY;
		$this->roles_redirects = $ROLES_ARRAY;
	}

	function is_json($string) {
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
		//$primary_db = false;
		//$dbs = array();
		//$query = "SELECT * FROM `databases`";
		//$result = $this->platformdb->query($query); 
		//if($result->num_rows > 0)
			//while($row = $result->fetch_assoc()) {
				//if($row['primary']=='1') {
					//$primary_db = mysqli_connect($row['host'], $row['username'], $row['password'], $row['name']);
					//$primary_db->set_charset("utf8");
				//} else {
					//$dbs[$row['name']] = mysqli_connect($row['host'], $row['username'], $row['password'], $row['name']) or die();
					//$dbs[$row['name']]->set_charset("utf8");
				//}
			//}
		//if($primary_db == false && count($dbs)>0) {
			//$primary_db = array_pop(array_reverse($dbs));
			//$this->db = $primary_db;
			//$this->db->set_charset("utf8");
		//}
		//var_dump($dbs);
		//die();
		//$this->dbs = $dbs;
		$this->db = new \mysqli(config\UNIT_TEST_DB_HOST, config\UNIT_TEST_DB_USER, config\UNIT_TEST_DB_PASS, config\UNIT_TEST_DB_NAME);
		$this->primary_db = $this->db;
		$this->dbs = array($this->db);
	}

	function __set($index, $value) {
		$this->vars[$index] = $value;
	}

	function __get($index) {
		return isset($this->vars[$index]) ? $this->vars[$index] : false;
	}
}
