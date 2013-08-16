<?php
/**
 * @file setupLib.php A library of functions necessary for the initial setup 
 * phase of installing the R3L platform to occur. This can be thought of as the 
 * "toolbox" from which setup.php draws upon to do everything.
 */
namespace sirius\base\setupLib;
/**
 * recursive_copy: A recursive copy function implemented in PHP. Note that the 
 * behavior of this function will clearly be very dependent on the directory 
 * from which it is run.
 *
 * @param string $source The source directory or file from which we use to copy.
 *
 * @param string $dest The directory or file to which we wish to copy.
 *
 * @param string $exclude Files that should be excluded from copying
 *
 * @return false if an exception is raised, true otherwise
 */
function recursive_copy($source, $dest, $exclude=array()) {
	try {
		if(is_dir($source)) {
			if(!file_exists($dest)) {
				mkdir($dest);
			}
		}
		$dir_contents = scandir($source);
		foreach($dir_contents as $system_entity) {
			if($system_entity === '.' || $system_entity === '..' || in_array($system_entity,$exclude)) {
			}
			else if(is_dir($source . '/' . $system_entity)) {
				recursive_copy($source . '/' . $system_entity, $dest . '/' . $system_entity);
			}
			else {
				copy($source . '/' . $system_entity, $dest . '/' . $system_entity);
			}
		}
	}
	catch(Exception $e) {
		echo 'Caught exception: ' . $e->getMessage() . "\n";
		echo 'A common cause of exceptions is incorrect file permissions. Make sure your permissions are correct.';
		return false;
	}
	return true;
}

/**
 * generate_db: Generates a MySQL database based on the parameters passed to the 
 * function. In particular, it takes a SQL dump file and makes a SQL table based 
 * on the dump file. Any exceptions are caught and displayed to the user with 
 * the reminder to ensure that MySQL and MySQLi are installed. No checks are 
 * made to see whether we are messing with any pre-existing databases or tables. 
 * Note that we DO check for whether they exist, and our CREATE SQL statements 
 * run accordingly. However, we make no guarantees as to what happens to the 
 * data stored on the database or table.
 * 
 * Moreover, we are using file_get_contents as a matter of convenience. This 
 * means that if the SQL schema gets very large and exhausts the memory 
 * available to PHP, then PHP will break.
 *
 * @param string $db_name The name of the database we wish to create.
 *
 * @param string $host The name of the host of the MySQL server (usually 
 * localhost).
 *
 * @param string $user The name of the MySQL user through which we will be 
 * making our SQL requests. 
 *
 * @param string $password The password associted with the MySQL user we are 
 * using.
 *
 * @param filename $sql_schema_file The SQL file that we are reading from.
 *
 * @return true if the query executed successfully, false otherwise.
 */
function generate_db($db_name, $host, $user, $password, $sql_schema_file) {
	try {
		$mysqli = new \mysqli($host, $user, $password);
        $mysqli->query("CREATE DATABASE $db_name DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sql_file_buffer = file_get_contents($sql_schema_file);
		$mysqli->multi_query($sql_file_buffer);
	}
	catch(\Exception $e) {
		echo 'Caught exception: '. $e->getMessage(). "\n";
		echo "For some reason an exception was raised when trying to connect. Make sure that you have MySQL and MySQLi installed.\n";
		return false;
	}
}
