<?php
namespace tests\unit_tests\linkedinTest;
use tests\unit_tests\setup\registry;
use tests\unit_tests\setup\classloader;
use tests\unit_tests\setup\config;
use r3l_platform\tests\unit_tests\setupLib;
require_once('setupLib.php');

class linkedin_test extends \PHPUnit_Framework_TestCase {
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


	/**
	 * Test whether dp_linkedin::request_token correctly mirrors the output 
	 * of OAuth::getRequestToken
	 */
	public function test_request_token_method_of_dp_linkedin() {
		$oauth_stub = $this
			->getMockBuilder('\\OAuth')
			->disableOriginalConstructor()
			->getMock();
		$oauth_stub
			->expects($this->any())
			->method('getRequestToken')
			->will($this->returnValue(array(
				'oauth_token'=>'11111111-1111-1111-1111-111111111111', 
				'oauth_token_secret'=>'00000000-0000-0000-0000-000000000000',
				'oauth_token_callback_confirmed'=>'true',
				'xoauth_request_auth_url'=>'https://stubbed_url.stub',
				'oauth_expires_in'=>'599'
			)));
		require_once(config\DP_ROOT . 'models/dp_linkedin.class.php');
		$this->dp_linkedin_instance = new \dp_linkedin($this->registry, $oauth_stub);
		$request_token_result = $this->dp_linkedin_instance->request_token();
		$this->assertEquals($request_token_result['oauth_token'], '11111111-1111-1111-1111-111111111111');
	}

	/**
	 * Test whether we can convert LinkedIn results correctly using 
	 * grab_linkedin_profile given some sample LinkedIn API input
	 */
	public function test_grab_linkedin_profile_returns_expected_result_given_controller_input() {
		$oauth_stub = $this
			->getMockBuilder('\\OAuth')
			->disableOriginalConstructor()
			->getMock();
		$dummy_linkedin_api_response = file_get_contents('assets/sample_linkedin_json.json');
		$oauth_stub
			->expects($this->any())
			->method('fetch')
			->will($this->returnValue(null));
		$oauth_stub
			->expects($this->any())
			->method('getLastResponse')
			->will($this->returnValue($dummy_linkedin_api_response));
		require_once(config\DP_ROOT . 'models/dp_linkedin.class.php');
		$this->dp_linkedin = new \dp_linkedin($this->registry, $oauth_stub);
		$linkedin_profile = $this->dp_linkedin->grab_linkedin_profile($oauth_stub);
		$this->assertEquals(unserialize(file_get_contents('assets/sample_linkedin_profile_standardized')), $linkedin_profile);
	}

}
