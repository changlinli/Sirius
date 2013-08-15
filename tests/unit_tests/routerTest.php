<?php
namespace r3l_platform\tests\unit_tests\routerTest;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;
use r3l_platform\tests\unit_tests\setupLib;
require_once('setupLib.php');

class OutputTest extends \PHPUnit_Framework_TestCase {
    public $registry;

    public function setUp() {
        setupLib\setupEachTest($this);
        require_once('../../base/core/router.class.php');
    }

    public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
    }

    public function testParseDummyURLCorrectlyIntoControllerAndAction() {
        $dummyURL = 'dummyController/dummyAction';
        $router = new \router($dummyURL);
        $this->assertEquals('dummyController', $router->controller_name);
        $this->assertEquals('dummyAction', $router->action_name);
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function testURLContainingTXTResourceShouldYieldCorrectHeaderMimeType() {
        $dummyURL = ASSETS_URL . '/randomResource.txt';
        if(!file_exists($dummyURL)) {
            throw new Exception('A file required for testing purposes was not found!');
        }
        $router = new \router($dummyURL);
        $this->assertContains('Content-type: text/plain', xdebug_get_headers());
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function testURLContainingCSSResourceShouldYieldCorrectHeaderMimeType() {
        $dummyURL = ASSETS_URL . '/dummyCSS.css';
        if(!file_exists($dummyURL)) {
            throw new Exception('A file required for testing purposes was not found!');
        }
        $router = new \router($dummyURL);
        $this->assertContains('Content-type: text/css', xdebug_get_headers());
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function testURLContainingJSResourceShouldYieldCorrectHeaderMimeType() {
        $dummyURL = ASSETS_URL . '/dummyJS.js';
        if(!file_exists($dummyURL)) {
            throw new Exception('A file required for testing purposes was not found!');
        }
        $router = new \router($dummyURL);
        $this->assertContains('Content-type: application/javascript', xdebug_get_headers());
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function testURLShouldHandleMixedCaseCorrectlyWhenDeliveringMimeType() {
        $dummyURL = ASSETS_URL . '/dummyCSS.cSs';
        if(!file_exists($dummyURL)) {
            throw new Exception('A file required for testing purposes was not found!');
        }
        $router = new \router($dummyURL);
        $this->assertContains('Content-type: text/css', xdebug_get_headers());
    }

    /**
     * @preserveGlobalState disabled
     * @runInSeparateProcess
     */
    public function testURLShouldHandleALLCAPSCorrectlyWhenDeliveringMimeType() {
        $dummyURL = ASSETS_URL . '/dummyCSS.CSS';
        if(!file_exists($dummyURL)) {
            throw new Exception('A file required for testing purposes was not found!');
        }
        $router = new \router($dummyURL);
        $this->assertContains('Content-type: text/css', xdebug_get_headers());
    }

    public function testRouterShouldRouteToControllerAndActionEvenIfRealFileExists() {
        $realFileLocation = 'dummyFiles/dummyRealFile';
        $router = new \router($realFileLocation);
        $this->assertEquals('dummyFiles', $router->controller_name);
        $this->assertEquals('dummyRealFile', $router->action_name);
    }
}
