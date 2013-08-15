<?php
namespace tests\unit_tests\tests;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;
use r3l_platform\tests\unit_tests\setupLib;
require_once('setupLib.php');

class api_test extends \PHPUnit_Framework_TestCase {
	public $registry;

	public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
	}

	protected function setUp() {
        setupLib\setupEachTest($this);
	}

	protected function tearDown() {
		unset($this->registry);
	}

	public function test_primarydb_can_make_insert_sql_query() {
		$query = $this->registry->primary_db->real_query("INSERT INTO bounties SET name='testing'");
		// if statement used because if we forge ahead despite a false 
		// query we will get a lot of PHP exceptions
		if(!$query) {
			$this->assertTrue($query);
		}
		else {
			$select_query = $this->registry->primary_db->real_query("SELECT FROM bounties WHERE name='testing'");
			if (!$select_query) {
				// Make sure that a false $select_query fails
				$this->assertTrue($query);
			}
			else {
				$select_query_result = $select_query->fetch_array(\MYSQLI_ASSOC);
				$this->assertEquals('testing', $select_query_result['name']);
			}
		}
	}
}
