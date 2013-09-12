<?php
namespace tests\unit_tests\setup\setup_boot;
use tests\unit_tests\setup\config;
use tests\unit_tests\setup\registry;
$GLOBALS['UNIT_TEST_CONFIG'] = dirname(__FILE__) . '/config.php';
require_once(dirname(__FILE__) . '/../../../base/config.php');
include(config\GLOBAL_ROOT . 'vendor/autoload.php');
require_once('registry.class.php');
require_once('classloader.class.php');

