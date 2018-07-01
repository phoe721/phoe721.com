<?php
$con = mysql_connect('localhost', 'root', 'revive');
if (!$con) {
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db("web_admin", $con);
if (!$db_selected) {
    die ("Can't use web_admin: " . mysql_error());
}
?>
