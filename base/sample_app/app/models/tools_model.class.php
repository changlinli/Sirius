<?php
	class tools_model extends basemodel {
		function init() {
			$this->log_path = $this->registry->config['error_log_path'];
			$this->logs		= glob($this->log_path."*-error_log");
		}
		
		function log_files($current_log){
			$logs = $this->logs;
			$logs_arr = array();
			if(count($logs) > 0 && is_array($logs)) {
				foreach ($logs as $key=>$log) {
					if(file_exists($log)) {
						$logs_arr[$key]['log_size'] 	= $this->format_bytes(filesize($log)); // Identifying file size to format later.
						$logs_arr[$key]['log'] 		= basename($log); // Trim to file name only.
						$logs_arr[$key]['log_name'] 	= basename($log, '-error_log');
						$logs_arr[$key]['is_active']	= $this->active_log_nav($logs_arr[$key]['log'],$current_log); // Trim suffix to make it look pretty. 
					}
				}
			}
			return $logs_arr;
		}
		
		function active_log_nav($log,$current_log) {
			$log_name = (isset($current_log)) ? $current_log : ''; // Log name selected in.
			if ($log == $log_name) {
				return "active";
			} else {
				return "";
			}
		}
		
		function show_log_contents($log, $lines = 10) {
			$logdir = $this->log_path;
			if(file_exists($logdir.$log)) {
				if ($log == '') {
					return 1;
				} else {
					$handle = fopen($logdir.$log, "r"); // Opens log as 'read only'.
				    $linecounter = $lines; // Counting lines to show.
				    $pos = -2; 
				    $beginning = false;
				    $text = array(); // Return all contents as an array.
				    while ($linecounter > 0) {
				        $t = " ";
				        while ($t != "\n") {
				            if(fseek($handle, $pos, SEEK_END) == -1) {
				                $beginning = true; 
				                break; 
				            }
				            $t = fgetc($handle);
				            $pos --;
				        }
				        $linecounter --;
				        if ($beginning) {
				            rewind($handle);
				        }
				        $text[$lines-$linecounter-1] = fgets($handle);
				        if ($beginning) break;
				    }
				    fclose ($handle);
				    
				    // Organize array to display line by line.
				    return $text;
				}
			} else {
				return 0;
			}
		}	
		
		function format_bytes($a_bytes){
		    if ($a_bytes < 1024) {
		        return $a_bytes .' B';
		    } elseif ($a_bytes < 1048576) {
		        return round($a_bytes / 1024, 2) .' KB';
		    } elseif ($a_bytes < 1073741824) {
		        return round($a_bytes / 1048576, 2) . ' MB';
		    } elseif ($a_bytes < 1099511627776) {
		        return round($a_bytes / 1073741824, 2) . ' GB';
		    } elseif ($a_bytes < 1125899906842624) {
		        return round($a_bytes / 1099511627776, 2) .' TB';
		    } elseif ($a_bytes < 1152921504606846976) {
		        return round($a_bytes / 1125899906842624, 2) .' PB';
		    } elseif ($a_bytes < 1180591620717411303424) {
		        return round($a_bytes / 1152921504606846976, 2) .' EB';
		    } elseif ($a_bytes < 1208925819614629174706176) {
		        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
		    } else {
		        return round($a_bytes / 1208925819614629174706176, 2) .' YB';
		    }
		}
	}
