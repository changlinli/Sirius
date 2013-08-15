<?php
/**
 * @file gen_git_hooks.php File for generating git hooks relevant for 
 * development work on the R3L platform. Note that this file is only necessary 
 * if one wishes to develop the platform itself, rather than an application on 
 * top of this platform.
 */

/**
 * recursive_copy: A recursive copy function implemented in PHP. Note that the 
 * behavior of this function will clearly be very dependent on the directory 
 * from which it is run.
 *
 * @param string $source The source directory or file from which we use to copy.
 *
 * @param string $dest The directory or file to which we wish to copy.
 *
 * @return false if an exception is raised, true otherwise
 */
function recursive_copy($source, $dest) {
	try {
		if(is_dir($source)) {
			if(!file_exists($dest)) {
				mkdir($dest);
			}
		}
		$dir_contents = scandir($source);
		foreach($dir_contents as $system_entity) {
			if($system_entity === '.' || $system_entity === '..') {
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

echo "Copying git hooks...\n";
recursive_copy('git_hooks/', '.git/hooks/');

$dir_contents = scandir('.git/hooks/');
foreach($dir_contents as $system_entity) {
	if(is_file('.git/hooks/' . $system_entity)) {
		chmod('.git/hooks/' . $system_entity, 0755);
	}
}
echo "Done!\n";
