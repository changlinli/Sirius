<?php
/**
 * @file custom_config.php
 * This is the custom configuration file for use with the Sirius platform. Any 
 * app-specific to platform settings should go here. Otherwise platform updates 
 * may overwrite previous configuration changes.
 */

/**
 * ____  _____ _       ____  _       _    __                      
 *|  _ \|___ /| |     |  _ \| | __ _| |_ / _| ___  _ __ _ __ ___  
 *| |_) | |_ \| |     | |_) | |/ _` | __| |_ / _ \| '__| '_ ` _ \ 
 *|  _ < ___) | |___  |  __/| | (_| | |_|  _| (_) | |  | | | | | |
 *|_| \_\____/|_____| |_|   |_|\__,_|\__|_|  \___/|_|  |_| |_| |_|
 */

/*******************************************************************************
 * Debugging settings
 ******************************************************************************/

/** debugging mode **/
$DEBUG = true;

/**
 * This flag sets whether or not we assume that our development environment has 
 * no internet access. If this is the case, certain functions such as LinkedIn 
 * will be substituted by mock local equivalents
 */
$NO_INTERNET_DEV = 0;

/*******************************************************************************
 * Database settings
 ******************************************************************************/

/**
 * Database host (usually localhost or 127.0.0.1)
 */
$DB_HOST = '127.0.0.1';
/**
 * Database name. Would highly recommend that a separate database be created for 
 * each application to minimize the possibility of overwriting data.
 */
$DB_NAME = '';
/**
 * Database username.
 */
$DB_USER = '';
/**
 * Database password associated with username.
 */
$DB_PASS = '';

/*******************************************************************************
 * What follows are the various roles (i.e. privileges) that can be assigned to 
 * users of the application.
 ******************************************************************************/

/**
 * DEVELOPER_ROLE should be given to, as the name suggests, those who are 
 * developing the site for debugging purposes
 */
$DEVELOPER_ROLE = 1;
/**
 * ADMIN_ROLE should encompass every ability that a user has, but also the 
 * ability to view anything any user can view in addition to other 
 * administrative privileges.
 */
$ADMIN_ROLE = 2;
/**
 * USER_ROLE is the most mundane role, given to ordinary users of your 
 * application.
 */
$USER_ROLE = 3;

$DEVELOPER_ROLE_AUTH_REDIRECT = '/developer/objects';
$ADMIN_ROLE_AUTH_REDIRECT = '/admin/users';
$USER_ROLE_AUTH_REDIRECT = '/index/bounties';

$ROLES_ARRAY = array(
	array('id' => $DEVELOPER_ROLE, 'name' => 'Developer', 'auth_redirect'  => $DEVELOPER_ROLE_AUTH_REDIRECT),
	array('id' => $ADMIN_ROLE, 'name' => 'Admin', 'auth_redirect' => $ADMIN_ROLE_AUTH_REDIRECT),
	array('id' => $USER_ROLE, 'name'  => 'User', 'auth_redirect' => $USER_ROLE_AUTH_REDIRECT),
);


/*******************************************************************************
 * Directory settings concerning where files are located
 ******************************************************************************/
/**
 * DP_ROOT is the location of all Sirius platform files
 */
//$DP_ROOT = $DP_ROOT;
/**
 * APP_ROOT is the base directory containing all application-related files.
 */
$APP_ROOT = $DP_ROOT . '../../app/';
/**
 * Virtual root is a placeholder here. This is the domain name associated with 
 * your application. It will not be available when run from the commandline, so 
 * if you want to, say, construct a link with your application's domain in it 
 * and $_SERVER['SERVER_NAME'] is undefined, put VIR_ROOT here to take its 
 * place.
 */
$VIR_ROOT = '';

$APP_CONTROLLERS = $APP_ROOT . 'controllers/';
$APP_MODELS = $APP_ROOT . 'models/';
$APP_VIEWS = $APP_ROOT . 'views/';

/**
 * ASSETS_FOLDER
 */

/**
 * ASSETS_URL is the URL directory whereby all static assets (such as CSS files, 
 * images, Javascript files, etc) are accessed. Any request to a location within 
 * this URL directory will result in the file being served without any 
 * interference by the Sirius platform.
 */
$ASSETS_URL = 'assets';

/**
 * TEMPLATE_ENGINE refers to the engine used to process templates. Currently the 
 * platform supports both plain, native PHP templates (i.e. normal PHP files 
 * with HTML mixed in) via setting this to 'php' as well as Twig templates 
 * (accessed by the 'twig' option).
 */
$TEMPLATE_ENGINE = 'php';
$TWIG_TEMPLATE_LOCATION = $APP_ROOT . '/views';
$TWIG_TEMPLATE_SUFFIX = '.html';

$project_name = 'default_name';
$site_email = 'admin@siriusplatform.com';

$DEFAULT_CONTROLLER = 'index';
$DEFAULT_ACTION = 'index';

$TABLE_CONFIG = array(
    'dp_libs' => $DP_ROOT . 'libs/', 
    'dp_models' => $DP_ROOT . 'models/', 
    'dp_plugins' => $DP_ROOT . 'plugins/', 
    'app_controllers' => $APP_ROOT . 'controllers/', 
    'app_models' => $APP_ROOT . 'models/', 
    'app_views' => $APP_ROOT . 'views/', 
    'default_controller' => $DEFAULT_CONTROLLER,
    'default_action' => $DEFAULT_ACTION,
    'class_suffix' => '.class.php', 
    'action_suffix' => '_action', 
    'private_action_suffix' => '_privaction', 
    'authtoken_ttl' => '60', 
    'accesstoken_ttl' => '31536000', 
    'validators_prefix' => 'validate_', 
    'validators_controllers' => $APP_ROOT . 'controllers/validators/', 
    'message_noauth' => 'You\'re not logged into the system!', 
    'message_nopermissions' => 'You don\'t have permissions to view this page!', 
    'site_email' => 'admin@siriusplatform.com', 
    'project_name' => 'Sirius Platform', 
    'no_mobile_view' => '0', 
    'dp_core' => $DP_ROOT . 'core/', 
    'dp_controllers' => $DP_ROOT . 'controllers/', 
    'error_log_path' => '/etc/httpd/logs/', 
    'app_uploads' => 'uploads', 
    'app_layout_dirname' => $APP_ROOT . 'views/web/layouts', 
    'app_layout' => 'layout.php', 
);
