<?php
require_once("debugger.php");
require_once("database.php");
$db = new database;
$con = $db->connect("localhost", "root", "revive", "mysql");
mysqli_set_charset($con, "utf8");
$result = $db->query("SELECT * FROM user");
if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		echo $row['Host'] . " - " . $row['User'] . "\n";
	} 
} else {
	echo $db->error();
}
$con2 = $db->getConnection();
print_r($con2);
?>
