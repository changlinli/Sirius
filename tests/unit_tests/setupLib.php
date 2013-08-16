<?php
/**
 * @file setupLib.php Library of functions used to set up tests for the Sirius 
 * platform. Eventually if enough functions appear these should get wrapped into 
 * a subclass of the PHPUnit test case class and we should use that subclass.
 */
namespace sirius\tests\unit_tests\setupLib;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;

function setupEachTest($class) {
	$class->registry = new registry\registry('', true);
	$classloader = new classloader\classloader($class->registry);
	$class->registry->router = new \router($class->registry->route_path);
	$class->registry->apploader = new \apploader($class->registry);
	$class->registry->primarydb = config\setup_db();
}

function setUpBeforeClassFunc() {
    require_once('setup/setup_boot.php');
    require_once('setup/registry.class.php');
}
