<?php
date_default_timezone_set('Asia/Taipei');
$current["year"] = strftime("%G");
$current["month"] = strftime("%m");
$current["day"] = strftime("%d");
$current["hour"] = strftime("%H");
$current["minute"] = strftime("%M");
$debug = false;

if ($debug) {
	echo $current["year"] . "/" . $current["month"] . "/" . $current["day"] . " " . $current["hour"] . ":" . $current["minute"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Part-Time Salary Calculator</title>
<script src="include/jquery-1.9.0.min.js"></script>
<script>
$(document).ready(function(){
	var count = 1;
	var defaultValue = "";
	$("#main1 .remove").hide();
	$("input").focus(function() {
		defaultValue = $(this).val();
		$(this).val("");
	});
	$("input").blur(function() {
		if ($(this).val() == "") {
			$(this).val(defaultValue);
		}
	});
	$(":button.add").click(function(){
		count++;
		var newID = "main" + count;
		$("#main1").clone(true).attr("id", newID).appendTo("#content");
		$("#" + newID + " .remove").show();
	});
	$(":button.remove").click(function(){
		$(this).closest("div").remove();
		count--
	});
});
</script>
<style>
* {
	margin: 0px;
	padding: 0px;
}
body {
	font-family: sans-serif;
}
input, select, td {
	font-size: 9pt;
	text-align: center;
}
input[type="text"], select {
	color: #505050 ;
}
input.task {
	width: 70px;
}
input.base_salary {
	width: 45px;
}
input.button {
	padding: 2px;
}
input.add, input.remove {
	width: 20px;
	display: block;
	margin: 5px;	
}
td.header {
	text-align: right;
}
#container {
	border: 1px solid black;
	margin: 0px auto;
	padding: 5px;
	width: 315px;
	background-color: #FFF;
}
#title {
	/* border: 1px solid black; */
	font-size: 12pt;
	text-align: center;
	text-shadow: -1px -1px 0 #FFF, 1px 1px 0 #C0C0C0;
}
#content {
}
.main {
	border: 1px solid black;
	border-radius: 15px;
	box-shadow: 2px 2px 0 #C0C0C0;
	margin-bottom: 5px;
	padding: 5px;
	padding-left: 15px;
}
#buttons {
	margin-top: 10px;
	text-align: right;
}
.time {
	width: 20px;
}
</style>
</head>
<body>
<div id="container">
	<div id="title">
	Part-Time Salary Calculator
	</div>
	<form>
	<div id="content">
		<div id="main1" class="main">
		<table>
		<tr>
			<td class="header">Task Title:</td>
			<td><input type="text" class="task" name="task[]" value="Task Name"></td>
			<td class="header">Base Salary:</td>
			<td><input type="text" class="base_salary" name="base_salary[]" value=""></td>
			<td rowspan="3">
			<input type="button" class="add" name="add" value="+">
			<input type="button" class="remove" name="remove" value="-">
			</td>
		</tr>
		<tr>
			<td class="header">Start Time:</td>
			<td colspan="3">
			<?php 
				echo "<select name=\"year[]\">";
				for ($i = $current["year"]; $i > ($current["year"] - 10); $i--) {
					echo "<option value=\"$i\">$i</option>";
				}
				echo "</select>";
				echo " / ";
				echo "<select name=\"month[]\">";
				for ($i = 1; $i <= 12; $i++) {
					if ($i != $current["month"]) {
						echo "<option value=\"$i\">$i</option>";
					} else {
						echo "<option value=\"$i\" selected>$i</option>";
					}
				}
				echo "</select>";
				echo " / ";
				echo "<select name=\"day[]\">";
				for ($i = 1; $i <= 31; $i++) {
					if ($i != $current["day"]) {
						echo "<option value=\"$i\">$i</option>";
					} else {
						echo "<option value=\"$i\" selected>$i</option>";
					}
				}
				echo "</select>";
				echo " ";
			?>
			<input type="text" class="time" name="hour[]" value="09" maxlength="2">
			: 
			<input type="text" class="time" name="minute[]" value="00" maxlength="2">
			</td>
		</tr>
		<tr>
			<td class="header">End Time:</td>
			<td colspan="3">
			<?php 
				echo "<select name=\"year[]\">";
				for ($i = $current["year"]; $i > ($current["year"] - 10); $i--) {
					echo "<option value=\"$i\">$i</option>";
				}
				echo "</select>";
				echo " / ";
				echo "<select name=\"month[]\">";
				for ($i = 1; $i <= 12; $i++) {
					if ($i != $current["month"]) {
						echo "<option value=\"$i\">$i</option>";
					} else {
						echo "<option value=\"$i\" selected>$i</option>";
					}
				}
				echo "</select>";
				echo " / ";
				echo "<select name=\"day[]\">";
				for ($i = 1; $i <= 31; $i++) {
					if ($i != $current["day"]) {
						echo "<option value=\"$i\">$i</option>";
					} else {
						echo "<option value=\"$i\" selected>$i</option>";
					}
				}
				echo "</select>";
				echo " ";
			?>
			<input type="text" class="time" name="hour[]" value="10" maxlength="2">
			:
			<input type="text" class="time" name="minute[]" value="00" maxlength="2">
			</td>
		</tr>
		</table>
		</div>
	</div>
	<div id="buttons">
		<input type="submit" class="button" name="submit" value="Calculate">
	</div>
	</form>
</div>
</body>
</html>
