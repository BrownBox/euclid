<?php
/**
 * Log a string of text to the log file or PHP error_log
 *
 * @param mixed $variable
 * @param string $variable_name
 * @param bool|true $to_file
 * @param string $log_filename
 * @author Anton Zaroutski <anton@zaroutski.com>
 */
function bb_log($variable, $variable_name = '', $to_file = true, $log_filename = 'bb.log') {
	if (is_bool($variable)) {
		ob_start();
		var_dump($variable);
		$variable_value = ob_get_clean();
	} else {
		$variable_value = print_r($variable, true);
	}

	// Log to file on filesystem or PHP error_log?
	if ($to_file) {
	    $log_dir = get_template_directory().'/logs/';
	    if (!file_exists($log_dir)) {
	        mkdir($log_dir);
	    }
		$logfile = $log_dir.$log_filename;
		file_put_contents($logfile, date("Y-m-d H:i:s")." | ".$variable_name."\n\n".$variable_value."\n", FILE_APPEND);
		file_put_contents($logfile, "-------------------------------------------------------------------------------------------\n", FILE_APPEND);
	} else {
		error_log($variable_name.': '.$variable_value);
	}
}
