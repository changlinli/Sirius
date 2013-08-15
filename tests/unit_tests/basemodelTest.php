<?php
namespace tests\unit_tests\basemodelTest;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;
use r3l_platform\tests\unit_tests\setupLib;
require_once('setupLib.php');

class basemodel_test extends \PHPUnit_Framework_TestCase {
	public $registry;

	public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
	}

	protected function setUp() {
        setupLib\setupEachTest($this);
		$this->registry->primarydb->query("TRUNCATE TABLE `testing`");
	}

	protected function tearDown() {
		$this->registry->primarydb->query("TRUNCATE TABLE `testing`");
		unset($this->registry);
	}

	public function test_retrieve_can_actually_retrieve() {
		$basemodel = new \basemodel($this->registry, true);
		$this->registry->primarydb->query("INSERT INTO `testing` (`test_int`, `test_varchar`) VALUES (1, 'a')");
		$result = $basemodel->retrieve('testing', array('test_int', 'test_varchar'), array(), $this->registry->primarydb);
		$this->assertEquals(count($result), 1);
		$this->assertEquals(array('test_int'=>1, 'test_varchar'=>'a'), $result[0]);
	}

	/**
	 * @depends test_retrieve_can_actually_retrieve
	 */
	public function test_retrieve_on_random_values() {
		$basemodel = new \basemodel($this->registry, true);
		$rand_int = rand();
		$rand_str = md5($rand_int);
		$this->registry->primarydb->query("INSERT INTO `testing` (`test_int`, `test_varchar`) VALUES ($rand_int, '$rand_str')");
		$result = $basemodel->retrieve('testing', array('test_int', 'test_varchar'), array(), $this->registry->primarydb);
		$this->assertEquals(count($result), 1);
		$this->assertEquals(array('test_int'=>$rand_int, 'test_varchar'=>$rand_str), $result[0]);
	}

	/**
	 * @depends test_retrieve_on_random_values
	 */
	public function test_insert_can_actually_insert() {
		$basemodel = new \basemodel($this->registry, true);
		$basemodel->insert('testing', array('test_int'=>2, 'test_varchar'=>'asdf'), $this->registry->primarydb);
		$result = $basemodel->retrieve('testing', array('test_int', 'test_varchar'), array(), $this->registry->primarydb);
		$this->assertEquals(count($result), 1);
		$this->assertEquals(array('test_int' => 2, 'test_varchar' => 'asdf'), $result[0]);
	}

	/**
	 * Right now we require an explicit array of values to update. May make 
	 * more sense in the future to automatically update any fields used in 
	 * insert
	 * @depends test_retrieve_on_random_values
	 */
	public function test_insert_can_update_on_duplicate() {
		$basemodel = new \basemodel($this->registry, true);
		$basemodel->insert('testing', array('id' => 1, 'test_int' => 2, 'test_varchar' => 'asdf'), $this->registry->primarydb);
		$basemodel->insert('testing', array('id' => 1, 'test_int' => 3), $this->registry->primarydb, array('test_int' => 3));
		$result = $basemodel->retrieve('testing', array('test_int', 'test_varchar'), array(), $this->registry->primarydb);
		$this->assertEquals(count($result), 1);
		$this->assertEquals(array('test_int' => 3, 'test_varchar' => 'asdf'), $result[0]);
	}
}
