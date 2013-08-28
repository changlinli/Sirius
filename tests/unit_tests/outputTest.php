<?php
namespace sirius\tests\unit_tests\outputTest;
use tests\unit_tests\setup\config;
use sirius\tests\unit_tests\setupLib;
use sirius\output;
require_once('setupLib.php');
require_once('../../vendor/autoload.php');

class OutputTest extends \PHPUnit_Framework_TestCase {
    public function setUp() {
        setupLib\setupEachTest($this);
    }

    public static function setUpBeforeClass() {
        setupLib\setUpBeforeClassFunc();
    }

    public function testOutMethodViewOnlyForPHPTemplating() {
        $output = new output\Output(array('asdf' => 1), false, 'assets/testPHPTemplate.php', '');
        ob_start();
        $output->out('php');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>1</div>', rtrim($result));
    }

    public function testOutMethodViewOnlyForTwigTemplating() {
        $output = new output\Output(array('qwerty' => 1), false, null, null, 'assets/testTwigTemplate.html');
        ob_start();
        $output->out('twig');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>1</div>', rtrim($result));
    }

    public function testOutMethodViewOnlyForTwigInheritance() {
        $output = new output\Output(array(), false, null, null, 'assets/testTwigTemplateChild.html');
        ob_start();
        $output->out('twig');
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('<div>asdf</div>', rtrim($result));
    }
}
