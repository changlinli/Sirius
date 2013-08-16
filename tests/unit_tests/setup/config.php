<?php
/**
 * @file config.php A custom configuration file containing settings that 
 * override the default config.php when unit testing. This is useful for setting 
 * up test databases and other structures which are different from production.
 */
namespace tests\unit_tests\setup\config;

const UNIT_TEST_DB_HOST = '127.0.0.1';
const UNIT_TEST_DB_NAME = 'rokk3r_crowdbounty_unit_test';
const UNIT_TEST_DB_USER = 'changlin';
const UNIT_TEST_DB_PASS = 'blah1234';
const DP_ROOT = '../../base/';
const APP_ROOT = '../../app/';
const TEST_ROOT = '.';
const GLOBAL_ROOT = '../../';

$ASSETS_FOLDER = dirname(__FILE__) . '/../';

$TWIG_TEMPLATE_LOCATION = dirname(__FILE__) . '/../';
$TWIG_TEMPLATE_SUFFIX = '.html';

function load_config() {
    $config = array(
        "dp_libs" => DP_ROOT . "libs/",
        "dp_models" => DP_ROOT . "models/",
        "dp_plugins" => DP_ROOT . "plugins/",
        "app_controllers" => APP_ROOT . "controllers/",
        "app_models" => APP_ROOT . "models/",
        "app_views" => APP_ROOT . "views/",
        "default_controller" => "index",
        "default_action" => "login",
        "class_suffix" => ".class.php",
        "app_layout_dirname" => "layouts",
        "app_layout" => "layout.php",
        "action_suffix" => "_action",
        "private_action_suffix" => "_privaction",
        "authtoken_ttl" => "60",
        "accesstoken_ttl" => "31536000",
        "validators_prefix" => "validate_",
        "validators_controllers" => APP_ROOT . "controllers/validators/",
        "message_noauth" => "You're not logged into the system!",
        "message_nopermissions" => "You don't have permissions to view this page!",
        "site_email" => "admin@siriusplatform.com",
        "project_name" => "Crowdbounty",
        "no_mobile_view" => "0",
        "dp_core" => DP_ROOT . "core/",
        "dp_controllers" => DP_ROOT . "controllers/",
        "error_log_path" => "/etc/httpd/logs/", "app_uploads" => "uploads", "bounty_percent" => "0.2",
        "linkedin_api_key" => "yswbak9ojlth",
        "linkedin_secret_key" => "TAxBi9IjV9DdIJyk",
        "linkedin_oauth_callback" => "http://dev.crowdbounty.com/index/linkedin_login",
        "linkedin_oauth_user_token" => "03bceca8-f871-4deb-9ffe-b85c4ad55fe4",
        "linkedin_oauth_user_secret" => "90792266-36de-4d0d-9589-f4d001272920",
        "limit_recommendations" => "2",
    );
    return $config;
}

function setup_db() {
    $mysqli = new \mysqli(
        UNIT_TEST_DB_HOST, 
        UNIT_TEST_DB_USER,
        UNIT_TEST_DB_PASS,
        UNIT_TEST_DB_NAME 
    );
    return $mysqli;
}
