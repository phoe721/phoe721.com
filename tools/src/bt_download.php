<?php
##### Variable Initialization #####
$torrent_user = "aaron";
$torrent_dir = "/home/aaron/bt/watch/start";
$upload_dir = "upload";
$field = "";
$status = "";
$output = "";
$error = "";
##### Variable Initialization #####

##### Panel Control Check #####
if (isset($_POST) && !empty($_POST)) 
{
	if (isset($_POST["torrent_hash"])) 
	{
		$torrent_hash = $_POST["torrent_hash"];
	}
    if (isset($_POST["start_rtorrent"])) 
	{
		start_rtorrent();
    } 
	else if (isset($_POST["stop_rtorrent"])) 
	{
		stop_rtorrent();
    } 
	else if (isset($_POST["list_screen"])) 
	{
		list_screen();
    } 
	else if (isset($_POST["list_torrent"])) 
	{
		header("refresh:5;");
	} 
	else if (isset($_POST["start_torrent"])) 
	{
		start_torrent($torrent_hash);
	} 
	else if (isset($_POST["stop_torrent"])) 
	{
		stop_torrent($torrent_hash);
	} 
	else if (isset($_POST["delete_torrent"])) 
	{
		delete_torrent($torrent_hash);
	}
	else if (isset($_POST["purge_torrent"])) 
	{
		purge_torrent($torrent_hash);
	}
}
##### Panel Control Check #####

##### Upload File Validation #####
if (isset($_POST["submit_file"])) 
{
	if ($_FILES["file"]["error"] > 0) 
	{
		if ($_FILES["file"]["error"] == 4)
		{
			error_msg("Error: Please select a torrent file.");
		} 
		else 
		{
			error_msg("Error: " . $_FILES["file"]["error"]);
		}
	} 
	else 
	{ 
	  $file_name = $_FILES["file"]["name"];
	  $tmp_path = $_FILES["file"]["tmp_name"];
	  $file_path = $upload_dir . "/" . $_FILES["file"]["name"];
	  $file_type = $_FILES["file"]["type"];
	  
	  if (check_ext($file_name) || check_file_type($file_type)) 
	  {	
		  if (file_exists($file_path)) 
		  {
			error_msg("Error: $file_name already exists!");
		  } 
		  else
		  {
			move_uploaded_file($tmp_path, $file_path);
			if (!valid_torrent($file_path)) 
			{
				remove_torrent($file_path);
				error_msg("Error: Invalid torrent file!");
			} else {
				output_msg("Uploaded: $file_name");
			}
		  }
	  } 
	  else 
	  {
		  error_msg("Error: Invalid file type!");
	  } 
	}
}
##### Upload File Validation #####

##### Start-up Check #####
if (check_rtorrent()) 
{
	if (check_torrent()) 
	{
		display_active_torrent();
		display_inactive_torrent();
	} 
	else 
	{
		output_msg("No torrents found!");
	}
} 
else 
{
	if (empty($status))
	{
		status_msg("Rtorrent is not started.");
	}
	if (empty($output) && empty($error)) {
		output_msg("There are " . count_torrent() . " torrent file(s).");
	}
}
##### Startup Check #####

##### Functions #####
function check_rtorrent() {
	$rtorrent_status = exec("ps U " . $GLOBALS['torrent_user'] . " | grep -w rtorrent | wc -l");
	if ($rtorrent_status >= 2) {
		return true;
	} 
	return false;
}
function start_rtorrent() {
	if (check_rtorrent()) {
		status_msg("Rtorrent is already running.");
	} else {
		run("touch ~/.scgi_local && screen -S bt_download -d -m rtorrent");
		status_msg("Rtorrent started.");
	}
}
function stop_rtorrent() {
	if (check_rtorrent()) {
		run("screen -S bt_download -X quit && rm -f ~/.scgi_local");
		status_msg("Rtorrent stopped."); 
	} else {
		status_msg("There is no rtorrent to stop.");
	}
}
function list_screen() {
	if (check_rtorrent()) {
		output_msg(run("screen -ls"));
	} else {
		output_msg("No session is found.");
	}
}
function check_torrent() {
	$torrent_status = exec("ls " . $GLOBALS['upload_dir'] . " | wc -l");
	if ($torrent_status > 0) {
		return true;
	}
	return false;
}
function count_torrent() {
	return exec("ls " . $GLOBALS['upload_dir'] . " | wc -l");
}
function valid_torrent($path) {
	$check = exec("lstor $path | grep HASH");
	if (!empty($check)) {
		return true;
	}
	return false;
}
function remove_torrent($path) {
	$status = exec("rm -f $path");
}
function start_torrent($torrent_hash) {
	output_msg(run("rtcontrol -q --start hash=$torrent_hash"));
}
function stop_torrent($torrent_hash) {
	output_msg(run("rtcontrol -q --stop hash=$torrent_hash"));
}
function delete_torrent($torrent_hash) {
	output_msg(run("rtcontrol -q --delete --yes hash=$torrent_hash"));
}
function purge_torrent($torrent_hash) {
	output_msg(run("rtcontrol -q --cull --yes hash=$torrent_hash"));
}
function display_active_torrent() {
	$command = "rtcontrol is_active=yes -qo hash,name,up.sz,down.sz,size.sz,done,ratio,is_active";
	display($command);
	
}
function display_inactive_torrent() {
	$command = "rtcontrol is_active=no -qo hash,name,up.sz,down.sz,size.sz,done,ratio,is_active";
	display($command);
}
function check_ext($file_name) {
	if (substr(strrchr($file_name,'.'),1) == "torrent") {
		return true;
	} 
	return false;
}
function check_file_type($file_type) {
	if ($file_type == "application/x-bittorrent") {
		return true;
	} 
	return false;
}
function parse_name($name) {
	// Alphanumeric only, also allowed period and space
	$name = preg_replace('/[^\w-. ]+/', '', trim($name));
	return $name;
}
function output_msg($message) {
	$GLOBALS['output'] .= $message;
}
function status_msg($message) {
	$GLOBALS['status'] .= $message;
}
function error_msg($message) {
	$GLOBALS['error'] .= $message;
}
function display($command) {
	$result = run($command);
	if (!empty($result)) {
		$count = 0;
		$torrent_hash = "";
		$info = explode("\n", $result);
		foreach ($info as $line) {
			$chunk = explode("\t", $line);
			foreach ($chunk as $piece) {
				if (!empty($piece)) {
					if (($count % 8) == 0) {
						// Torrent hash
						$torrent_hash = trim($piece);
					} else if (($count % 8) == 1) {
						// Torrent name
						$GLOBALS['field'] .= "<span class=\"name\">" . substr(trim($piece), 0, 35);
						if (strlen($piece) > 35) { $GLOBALS['field'] .= "..."; }
						$GLOBALS['field'] .= "</span>";
						$GLOBALS['field'] .= "<span class=\"control\">";
						$GLOBALS['field'] .= "<form action='bt_download.php' method='post'>";
						$GLOBALS['field'] .= "<input type=\"hidden\" name=\"torrent_hash\" value=\"$torrent_hash\">";
						$GLOBALS['field'] .= "<input type=\"submit\" name=\"start_torrent\" value=\">\">";
						$GLOBALS['field'] .= "<input type=\"submit\" name=\"stop_torrent\" value=\"||\">";
						$GLOBALS['field'] .= "<input type=\"submit\" name=\"delete_torrent\" value=\"V\">";
						$GLOBALS['field'] .= "<input type=\"submit\" name=\"purge_torrent\" value=\"X\">";
						$GLOBALS['field'] .= "</form>";
						$GLOBALS['field'] .= "</span>";	
					} else if (($count % 8) == 2) {
						$GLOBALS['field'] .= "<span class=\"info\">Up: " . trim($piece) . "</span>";
					} else if (($count % 8) == 3) {
						$GLOBALS['field'] .= "<span class=\"info\">Down: " . trim($piece) . "</span>";
					} else if (($count % 8) == 4) {
						$GLOBALS['field'] .= "<span class=\"info\">Size: " . trim($piece) . "</span>";
					} else if (($count % 8) == 5) {
						// Percentage
						$GLOBALS['field'] .= "<span class=\"info\">Percent: " . round(trim($piece), 2) . " %</span>";
					} else if (($count % 8) == 6) {
						// Ratio
						$GLOBALS['field'] .= "<span class=\"info\">Ratio: " . round(trim($piece), 2) . " %</span>";
					} else if (($count % 8) == 7) {
						// Status
						$GLOBALS['field'] .= "<span class=\"info\">Status: " . trim($piece) . "</span>";
					}
					$count++;
				}
			}
		}
	} 
}
function run($command) {
    // Connect to host and run command
    $connection = ssh2_connect('localhost', 22);
    ssh2_auth_password($connection, 'aaron', 'revive');
    $stream = ssh2_exec($connection, $command);
    $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

    // Enable stream blocking
    stream_set_blocking($errorStream, true);
    stream_set_blocking($stream, true);

    // Get output
    $output = stream_get_contents($stream);
    $error = stream_get_contents($errorStream);

    // Close the streams
    fclose($errorStream);
    fclose($stream);

    // Return value
    return trim($output);
}
##### Functions #####
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Phoenix BT Client</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="refresh" content="60">
<script type="javascript" src="include/jquery-1.9.0.min.js"></script>
<style>
* { 
	font-family: arial;
	font-size: 12px;
	margin: 0px; 
	padding 0px; 
}
#wrapper {
	width: 320px;
/*  border: 1px solid black; */
	padding: 5px;
	margin: 0px auto;
}
#content {
	text-align: center;
/* 	border: 1px solid black; */
	margin: 0px auto;
}
#field {
	margin: 5px 0px 5px 0px;
	padding: 0px 5px 5px 5px;
	border: 1px solid #AAA;
	border-radius: 10px;
	/* background-color: #F2F2F2 */
}
#status {
	margin: 5px 0px 5px 0px;
	padding: 2px;
	color: blue;
    text-align: center;
	background-color: #CCCCCC;
	border-radius: 5px;
}
#output {
	margin: 5px 0px 5px 0px;
	padding: 2px;
    text-align: center;
	background-color: #CCCCCC;
	border-radius: 5px;
}
#error {
	margin: 5px 0px 5px 0px;
	padding: 2px;
	color: red;
    text-align: center;
	background-color: #CCCCCC;
	border-radius: 5px;
}
.name {
	margin-top: 10px;
	padding: 2px 0px 2px 4px;
	display: inline-block;
    width: 210px;
    text-align: left;
}
.info {
    display: inline-block;
    width: 100px;
    text-align: center;
	border: 1px solid #AAA;
}
.control {
    display: inline-block;
    width: 94px;
	height: 21px;
	text-align: center;
	vertical-align: bottom;
}
</style>
</head>
<body>
<div id="wrapper">
	<div id="content">
		<!--##### Control Panel #####-->
		<form action="bt_download.php" method="post">
			<input type="submit" name="start_rtorrent" value="Start">
			<input type="submit" name="stop_rtorrent" value="Stop">
			<input type="submit" name="list_screen" value="List Session">
			<input type="submit" name="list_torrent" value="Refresh">
		</form>
		<?php 
			if (!empty($status)) {
				echo '<div id="status">' . $status . '</div>';
			} else if (!empty($field)) {	
				echo '<div id="field">' . $field . '</div>';
			}
		?>
		<!--##### Upload Field #####-->
		<form action="bt_download.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file" id="file" />
			<input type="submit" name="submit_file" value="Upload" />
		</form>
		<?php 
			if (!empty($output)) {
				echo '<div id="output">' . $output . '</div>';
			} else if (!empty($error)) {	
				echo '<div id="error">' . $error . '</div>';
			}
		?>
	</div>
</div>
</body>
</html>
