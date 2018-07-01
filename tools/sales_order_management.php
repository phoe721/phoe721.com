<?php
/* ##### Connect to Server Database ##### */
$con = mysql_connect("localhost","root","revive");
if (!$con) { die('Could not connect: ' . mysql_error()); }
if (!mysql_select_db("server", $con)) { die('Error: ' . mysql_error()); }
mysql_query("SET NAMES UTF8");
/* ###################################### */

/* ##### Get User Input ##### */
if (isset($_POST) && !empty($_POST)) {
	$data = store_data();
	if ($data["select_oid"] != "查詢報價單") {	
		$oid = $data["select_oid"];
		$data = get_order_info($oid);
	}
}
/* ######################### */

/* ##### Function ##### */
function store_data() {
	/* ##### Store Customer Data ##### */
	$data = array();
	foreach ($_POST as $key=>$value) {
		// echo "$key: $value<br />";
		$data[$key] = $value;
	}
	return $data;
	/* ############################### */
}
function get_order_id() {
	$query = "SELECT oid FROM `server`.`order` ORDER BY oid";
	$result = mysql_query($query);
	$index = 0;
	while ($row = mysql_fetch_array($result)) {
		$oid = $row['oid']; 
		$data[$index] = sprintf('%010d', $oid);
		$index++;
	}
	return $data;
}
function get_order_info($oid) {
	$query = "SELECT O.subtotal, C.company, S.date, S.order, S.price FROM `server`.`order` O, `server`.`customer` C, `server`.`suborder` S WHERE O.oid = '$oid' AND O.cid = C.cid AND O.oid = S.oid";
	$result = mysql_query($query);
	$index = 0;
	while ($row = mysql_fetch_array($result)) {
		foreach ($row as $key=>$value) {
			$data[$key][$index] = $value;
		}
		$index++;
	}
	return $data;
}
/* #################### */
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Order Management</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-cache">
<style type="text/css">
#order_display input{
	border: 0px;
	border-bottom: 1px solid black;
	padding-left: 2px;
}
#subtotal_field {
	width: 600px;
	margin: 0px;
}
.company {
	font-weight: bold;
	text-align: center;
}
.title {
	font-weight: bold;
	text-align: center;
}
.date {
	text-align: center;
	width: 100px;
}
.order {
	text-align: left;
	width: 305px;
}
.price {
	text-align: right;
	width: 185px;
}
.subtotal {
	width: 175px;
	font-weight: bold;
}
</style>
</head>
<body>
<form name="order_display" action="sales_order_management.php" method="post">
<table id="order_title">
<tr>
	<td colspan="3">
	<label for="oid">報價單號:</label>
	<select name="select_oid" class="select_oid" onchange="this.form.submit()">
	<?php
		if (isset($oid)) {
			echo "<option>查詢報價單</option>";
		} else {
			echo "<option selected>查詢報價單</option>";
		}
		
		$oid_array = get_order_id();
		for ($i = 0; $i < count($oid_array); $i++) {
			if ($oid_array[$i] == $oid) {
				echo "<option selected>$oid_array[$i]</option>";
			} else {
				echo "<option>$oid_array[$i]</option>";
			}
		}
	?>
	</select>
	</td>
</tr>
</table>
<?php if (isset($data) && !empty($data)) { ?>
<table id="order_display" border="1" style="border-collapse:collapse;">
<tr>
	<td colspan="3" class="company">
		<?=$data["company"][0]?>
	</td>
</tr>
<tr>
	<td class="title">日期</td>
	<td class="title">品項</td>
	<td class="title">價格</td>
</tr>
<?php for ($i = 0; $i < count($data["order"]); $i++) { ?>
<tr>
	<td align="center" class="date">
		<?=$data["date"][$i]?>
	</td>
	<td align="center" class="order">
		<?=$data["order"][$i]?>
	</td>
	<td align="right" class="price">
		<?=$data["price"][$i]?> 元
	</td>
</tr>
<?php } ?>
<tr>
	<td align="right" class="subtotal" colspan="3">
		<label for="subtotal">合計:&nbsp;&nbsp;</label>
		<?=$data["subtotal"][0]?> 元
	</td>
</tr>
</table>
<?php } ?>
</form>
</body>
</html>