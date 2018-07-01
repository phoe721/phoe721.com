<?php
	/* ##### Connect to DB ##### */
	// include("include/connection.php");
	/* ##### Connect to DB ##### */

	/* ##### Local Constants ##### */
	$debug = 1; // 0 = false, 1 = true
	/* ##### Local Constants ##### */
	
	/* ##### Get Server Info ##### */
	/* ##### Parse Command Output ##### */
	$output = explode("\n", shell_exec("lsb_release -a"));
	$distro_id = explode(":", $output[1]);
	$distro_release = explode(":", $output[3]);
	if (preg_match("/Distributor ID/", $distro_id[0]) === 1) {
		$distro_id = trim($distro_id[1]);
	} 
	if (preg_match("/Release/", $distro_release[0]) === 1) {
		$distro_release = trim($distro_release[1]);
	} 
	$hostname = trim(shell_exec("uname -n"));
	$processor = trim(shell_exec("uname -p"));
	list($blank, $os_type) = explode('/', trim(shell_exec("uname -o")));
	$kernel_release = trim(shell_exec("uname -r"));
	$time = trim(shell_exec("date"));
	$processor_model = trim(shell_exec("cat /proc/cpuinfo | grep -m 1 'model name' | cut -d':' -f2"));
	$processor_count = trim(shell_exec("cat /proc/cpuinfo | grep -c 'model name'"));
	$up_check = preg_match("/days/", shell_exec("uptime | awk '{print $4}'"));
	if ($up_check === 1) {
		$up_day = shell_exec("uptime | awk '{print $3}'");
		list($up_hr, $up_min) = explode(":", shell_exec("uptime | awk '{print $5}' | tr -d ','"));
	} else {
		$uptime = trim(shell_exec("uptime | awk '{print $3}' | tr -d ','"));
		$up_check2 = preg_match("/:/", $uptime);
		if ($up_check2 === 1) {
			list($up_hr, $up_min) = explode(":", $uptime);
		} else {
			$up_min = $uptime;
		}
	}
	list($load_avg_1m, $load_avg_5m, $load_avg_15m, $blank) = explode(" ", shell_exec("cat /proc/loadavg"));
	list($blank, $running_ps, $cpu_usage) = explode("\n", shell_exec("top -b -n 1 | head -3"));
	preg_match("/[0-9].*/", $running_ps, $match);
	list($total_ps, $running_ps, $sleeping_ps, $blank) = explode(",", $match[0]);
	list($total_ps, $blank) = explode(" ", $total_ps);
	list($running_ps, $blank) = explode(" ", $running_ps);
	list($sleeping_ps, $blank) = explode(" ", $sleeping_ps);
	preg_match("/[0-9].*/", $cpu_usage, $match);
	list($cpu_usr, $cpu_sys, $blank, $cpu_idle, $blank) = explode(",", $match[0]); 
	$cpu_usr = str_replace("%us", "", $cpu_usr);
	$cpu_sys = str_replace("%sy", "", $cpu_sys);
	$cpu_idle = str_replace("%id", "", $cpu_idle);
	$mem_info = shell_exec("free -b | grep Mem | tr -s ' '");
	list($blank, $total_mem, $used_mem, $free_mem, $blank) = explode(" ", $mem_info);
	$total_mem = formatBytes($total_mem);
	$used_mem = formatBytes($used_mem);
	$swap_info= shell_exec("free -b | grep Swap | tr -s ' '");
	list($blank, $swap_total, $swap_used, $swap_free) = explode(" ", $swap_info);
	$swap_total = formatBytes($swap_total);
	$swap_used = formatBytes($swap_used);
	$disk_info = shell_exec("df -h --total | grep total | tr -d 'G' | tr -s ' '");
	list($blank, $disk_total, $blank, $disk_available, $blank) = explode(" ", $disk_info); 
	$disk_used = $disk_total - $disk_available;
    $server_ip = $_SERVER['REMOTE_ADDR'];
	/* ##### Parse Command Output ##### */
	
	/* ##### Update to DB ##### */
    /*
	$select_query = "SELECT hostname FROM server";
	$result = mysql_query($select_query);	
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		while ($row = mysql_fetch_array($result)) {
			if (strcmp($hostname, $row['hostname']) == 0) {
				$update_query = "UPDATE server SET " .
				"os_type='$os_type', distro_type='$distro_id', " .
				"distro_version='$distro_release', kernel_version='$kernel_release', " .
				"cpu_info='$processor_model', cpu_load_1m='$load_avg_1m'," .
				"cpu_load_5m='$load_avg_5m', cpu_load_15m='$load_avg_15m'," .
				"cpu_usr='$cpu_usr', cpu_sys='$cpu_sys', cpu_idle='$cpu_idle'," .
				"running_ps='$total_ps', memory_total='$total_mem'," .
				"memory_used='$used_mem', swap_total='$swap_total'," .
				"swap_used='$swap_used', disk_total='$disk_total', " .
				"disk_used='$disk_used' WHERE hostname='$hostname'";
				$result = mysql_query($update_query);
				if (!$result) { die("Invalid query: " . mysql_error()); }			
				break;
			}
		}
	} else {
		$insert_query = "INSERT INTO server VALUES ('', '$hostname', '$os_type', " .
		"'$distro_id', '$distro_release', '$kernel_release', '$processor_model', " .
		"'$load_avg_1m','$load_avg_5m', '$load_avg_15m', '$cpu_usr', '$cpu_sys', " .
		"'$cpu_idle', '$total_ps', '$total_mem', '$used_mem', '$swap_total', " .
		"'$swap_used', '$disk_total', '$disk_used')";
		$result = mysql_query($insert_query);
		if (!$result) { die("Invalid query: " . mysql_error()); }
	}
    */
	/* ##### Update to DB ##### */

	/* ##### Get Server Info ##### */

	/* ##### Output Information ##### */
	if ($debug == 1) {
		echo "System Hostname: " . $hostname . "<br />";	
		echo "Linux Distribution: " . $distro_id . " " . $distro_release . "<br />";
		echo "Processor: " . $processor . "<br />";
		echo "Operating System: " . $os_type . "<br />";	
		echo "Kernel Version: " . $kernel_release . "<br />";	
		echo "System Time: " . $time . "<br />";
		echo "Processor Information: " . $processor_model . ", " . $processor_count . " cores<br />";	
		if ($up_check === 1) {
			echo "Uptime: " . $up_day . " days, " . $up_hr . " hours, " . $up_min . " minutes<br />";
		} else {
			if ($up_check2 === 1) {
				echo "Uptime: " . $up_hr . " hours, " . $up_min . " minutes<br />";
			} else {
				echo "Uptime: " . $up_min . " minutes<br />";
			}
		}
		echo "CPU Load Averages: " . $load_avg_1m . " (1 min) " . $load_avg_5m . " (5 min) " . $load_avg_15m . " (15 min)<br />"; 
		echo "CPU Usage: " . $cpu_usr . ", " . $cpu_sys . ", " . $cpu_idle . "<br />";
		echo "Running processes: " . $total_ps . "<br />";
		echo "Real Memory: " . $total_mem . " total, " . $used_mem . " used<br />";
		echo "Virtual Memory: " . $swap_total . " total, " . $swap_used . " used<br />";
		echo "Disk Space: " . $disk_total . " GB total, " . $disk_used . " GB used<br />";
        echo "System IP: " . $server_ip . "<br />";
	}
	/* ##### Output Information ##### */

	/* ##### Function ##### */
	function formatBytes($size, $precision = 2) {
		if ($size > 0) {
			$base = log($size) / log(1024);
			$suffixes = array('', 'KB', 'MB', 'GB', 'TB');   
			return round(pow(1024, $base - floor($base)), $precision) . " " . $suffixes[floor($base)];
		} else {
			return "0 bytes";
		}
	}
	
	function debug($msg, $variable) {
		echo $msg . ": " . $variable . "<br />";
	}
	/* ##### Function ##### */
?>
