<?php
namespace sirius\tests\unit_tests\outputTest;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;
use sirius\tests\unit_tests\setupLib;
require_once('setupLib.php');

class OutputTest extends \PHPUnit_Framework_TestCase {
    public $registry;

    public function setUp() {
        setupLib\setupEachTest($this);
    }

    public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
    }

    public function testOutMethodViewOnlyForPHPTemplating() {
        $this->registry->router->request_type = 'web';
        $output = new \output($this->registry, array('asdf' => 1), false, 'assets/testPHPTemplate.php', '');
        ob_start();
        $output->out('php');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>1</div>', rtrim($result));
    }

    public function testOutMethodViewOnlyForTwigTemplating() {
        $this->registry->router->request_type = 'web';
        $output = new \output($this->registry, array('qwerty' => 1), false, null, null, 'assets/testTwigTemplate.html');
        ob_start();
        $output->out('twig');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>1</div>', rtrim($result));
    }

    public function testOutMethodViewOnlyForTwigInheritance() {
        $this->registry->router->request_type = 'web';
        $output = new \output($this->registry, array(), false, null, null, 'assets/testTwigTemplateChild.html');
        ob_start();
        $output->out('twig');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>asdf</div>', rtrim($result));
    }
}
