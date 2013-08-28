<?php
/**
 * @file baseTest.php:
 * baseTest.php is meant to test whether or not the testing framework has been 
 * set up correctly. This includes testing the mock objects created, i.e. given 
 * a very particular set of inputs, the "true" objects should have the same 
 * behavior as their mock equivalents. This allows for making sure that our 
 * testing suite stays current with the rest of the Sirius platform.
 *
 * This file also contains code to test that the test database can be connected 
 * to and in fact has the same schema as the normal database used.
 */
namespace tests\unit_tests\test_base;
use tests\unit_tests\setup\registry as registry;
use tests\unit_tests\setup\classloader as classloader;
use tests\unit_tests\setup\config as config;

/**
 * database_test: Test database connection to test database
 */
class database_test extends \PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		require_once('setup/setup_boot.php');
	}

	public function test_database_connection() {
		try {
			$mysqli = new \mysqli(
				config\UNIT_TEST_DB_HOST, 
				config\UNIT_TEST_DB_USER, 
				config\UNIT_TEST_DB_PASS, 
				config\UNIT_TEST_DB_NAME
			);
			$this->assertTrue($mysqli->ping());
		}
		catch (Exception $e) {
			$this->fail('Unit test database connection failed!');
		}
	}
}

/**
 * registry_test: Test whether registry yields correct route_path
 */
class registry_test extends \PHPUnit_Framework_TestCase {
	public static function setUpBeforeClass() {
		require_once('setup/setup_boot.php');
		require_once('../../base/core/registry.class.php');
	}

	public function test_route_path_is_correct() {
		$registry = new \registry('/test_controller/test_action', false, 'turn_off_config', 'not_in_table');
		$this->assertEquals('test_controller/test_action', $registry->route_path);
	}
}
