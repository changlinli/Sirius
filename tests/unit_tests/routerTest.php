<?php
namespace sirius\tests\unit_tests;
use sirius\tests\unit_tests\setupLib;
use sirius\routing;
require_once('setupLib.php');
require_once('../../vendor/autoload.php');

class RouterTest extends \PHPUnit_Framework_TestCase {
    public function setUp() {
        setupLib\setupEachTest($this);
    }

    public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
    }

    public function testParseDummyURLCorrectlyIntoControllerAndAction() {
        $dummyURL = 'dummyController/dummyAction';
        $router = new routing\Router($dummyURL);
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
        $router = new routing\Router($dummyURL);
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
        $router = new routing\Router($dummyURL);
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
        $router = new routing\Router($dummyURL);
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
        $router = new routing\Router($dummyURL);
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
        $router = new routing\Router($dummyURL);
        $this->assertContains('Content-type: text/css', xdebug_get_headers());
    }

    public function testRouterShouldRouteToControllerAndActionEvenIfRealFileExists() {
        $realFileLocation = 'dummyFiles/dummyRealFile';
        $router = new routing\Router($realFileLocation);
        $this->assertEquals('dummyFiles', $router->controller_name);
        $this->assertEquals('dummyRealFile', $router->action_name);
    }
}
