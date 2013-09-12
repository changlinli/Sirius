<?php
/**
 * @file setup.php
 * This file contains instructions for generating a sample application using the 
 * R3L platform. This file is meant to be the first thing that is run after 
 * downloading the R3L platform; this should be the first step in an application 
 * lifecycle. This file will copy the contents of base/sample_app two 
 * directories up and will also set up the MySQL table as needed.
 */
namespace sirius\base\setup;
use sirius\base\setupLib;
require_once("setupLib.php");

define('SAMPLE_DIR', 'sample_app');
define('SQL_TABLE_FILE', 'sql_schema.sql');
define('SQL_UNIT_TEST_TABLE_FILE', 'sql_unit_test_schema.sql');

/**
 * interactive_make_app_files: Generates the requisite files for a new sample 
 * application. This uses an interactive prompt to step the user through the 
 * process of writing all the apps. Generation consists essentially of just 
 * copying all the files found in the sample_app folder, except for the SQL 
 * schema files.
 */
function interactive_make_app_files() {
	// Interactive dialog to generate sample application
	$warning_string = 
	"Be warned, this will overwrite everything two directories up from this file in " . 
	"folders named app and assets. If you want to continue, please type \"y\"\n";
	echo $warning_string;

	$stdin_stream = fopen("php://stdin", "r");
	$user_input = fgets($stdin_stream);
	if($user_input === "y\n") {
		fclose($stdin_stream);
		echo "Proceeding...\n";
	}
	else {
		echo "User aborted creation of application files.\n";
		fclose($stdin_stream);
		return false;
	}
    $excludeFromCopy = array(SQL_TABLE_FILE, SQL_UNIT_TEST_TABLE_FILE, 'README.md');
	setupLib\recursive_copy('base/sample_app', '../', $excludeFromCopy);
	echo "Done!\n";
	return true;
}

/**
 * interactive_make_db: Interactively generate the database required for a new 
 * sample application. The main interaction at this point consists of letting 
 * the user know that the script will overwrite any existing tables.
 */
function interactive_make_db() {
	$warning_string = "Be warned, this will overwrite existing tables; to continue, please type \"y\"\n";
	echo $warning_string;
	$stdin_stream = fopen("php://stdin", "r");
	$user_input = substr(fgets($stdin_stream), 0, -1);
	if($user_input === "y") {
		echo "Proceeding...\n";
	}
	else {
		echo "User aborted operation. Exiting...\n";
		return false;
	}

	$ask_for_db_string = "If you would like to make a new database under another name, please enter it here. Otherwise, leave it blank.\n";
	echo $ask_for_db_string;

	$stdin_stream = fopen("php://stdin", "r");
	$user_input_db_name = substr(fgets($stdin_stream), 0, -1);
	fclose($stdin_stream);

	$ask_for_host_string = "Please enter the SQL hostname you would like to use.\n";
	echo $ask_for_host_string;

	$stdin_stream = fopen("php://stdin", "r");
	$user_input_host_name = substr(fgets($stdin_stream), 0, -1);
	fclose($stdin_stream);

	$ask_for_username_string = "Please enter the SQL username you would like to use.\n";
	echo $ask_for_username_string;

	$stdin_stream = fopen("php://stdin", "r");
	$user_input_username = substr(fgets($stdin_stream), 0, -1);
	fclose($stdin_stream);

	$ask_for_password_string = "Please enter the SQL password you would like to use.\n";
	echo $ask_for_password_string;

	$stdin_stream = fopen("php://stdin", "r");
	$user_input_password = substr(fgets($stdin_stream), 0, -1);
	fclose($stdin_stream);
	if($user_input_db_name === "") {
		$user_input_db_name = 'app_name';
	}
	$warning_string = "One more time, be warned, this will overwrite existing tables; to continue, please type \"y\"\n";
	echo $warning_string;
	$stdin_stream = fopen("php://stdin", "r");
	$user_input = substr(fgets($stdin_stream), 0, -1);
	if($user_input === "y") {
		echo "Proceeding...\n";
	}
	else {
		echo "User aborted database construction.\n";
		return false;
	}
	return setupLib\generate_db($user_input_db_name, $user_input_host_name, $user_input_username, $user_input_password, 'sample_app/sql_schema.sql');
}

/**
 * make_new: Make a new application. This is used to generate a sample 
 * application for users to begin development. In addition to providing the 
 * requisite application files (which are drawn from the sample_app) folder, 
 * make_new will also generate the requisite database structure for application 
 * deployment.
 *
 * Because this is running as a shell script, make_new will return 0 if 
 * successful and non-zero otherwise. This may cause problems if you try to 
 * verify make_new with PHP a la <?if(make_new() == false) { handle error } ?> 
 * or even <? if(!make_new()) ?>. Remember, triple equals is your friend.
 *
 * @return 0 if no errors occured, 1 if an error occurred when making the 
 * application files, and 2 if an error occured when making the database.
 */
function make_new() {
	echo "Beginning construction of new application...\n";
	$app_result = interactive_make_app_files();
	if($app_result) {
		return 0;
	}
	else {
		return 1;
	}
}

return make_new();
