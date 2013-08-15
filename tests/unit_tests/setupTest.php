<?php
/**
 * setupTest: Test whether basic functionality required for the platform is 
 * present.
 */
namespace tests\unit_tests\setupTest;
use tests\unit_tests\setup\config;
use r3l_platform\base\setupLib;

class setupTest extends \PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        require_once('setup/config.php');
        require_once(config\DP_ROOT . "setupLib.php");
        define('SETUP_TEST_DB_NAME', 'asdf');
    }

    public function testCanCreateTableCorrectly() {
        setupLib\generate_db(SETUP_TEST_DB_NAME, 'localhost', 'changlin', 'blah1234', config\DP_ROOT . 'sample_app/sql_schema.sql');
        $mysqli = new \mysqli('localhost', 'changlin', 'blah1234', SETUP_TEST_DB_NAME);
        $this->assertEquals(0, $mysqli->connect_errno);
        $mysqli->query("DROP DATABASE `" . SETUP_TEST_DB_NAME . '`');
    }
}
