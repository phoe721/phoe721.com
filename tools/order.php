<?php
/* ##### Connect to Server Database ##### */
$con = mysql_connect("localhost","root","revive");
if (!$con) { die('Could not connect: ' . mysql_error()); }
if (!mysql_select_db("server", $con)) { die('Error: ' . mysql_error()); }
mysql_query("SET NAMES UTF8");
/* ###################################### */

/* ##### Set Initial Values ##### */
$oid = get_oid();
$data = array();
/* ############################## */

/* ##### Get User Input ##### */
if (isset($_POST) && !empty($_POST)) {
	$data = store_data();
	
	if (isset($_POST["select_cid"]) && $_POST["select_cid"] == "新客戶") {	
		/* ##### Add Customer Data ##### */
		if (isset($_POST["add"]) && $_POST["add"] == "新增") {
			/* ##### Check if customer exists. If not, insert a new record ##### */
			$found = check_customer($data["tax_id"]);
			if (!$found) {
				/* ##### Add customer ##### */
				$cid = add_customer($data);
				if ($cid != false) { 
					$output = "客戶資料建立完成~";	
				}
				/* ######################## */
			} else {
				$cid = $found;
				$output = "客戶資料已存在~";
			}
			/* ################################################################## */
		} else if (isset($_POST["send_order"]) && $_POST["send_order"] == "報價傳送") {		
			/* ##### Check if customer exists. If not, insert a new record ##### */
			$found = check_customer($data["tax_id"]);
			if (!$found) {
				$cid = add_customer($data);
			} else {
				$cid = $found;
			}
			/* ################################################################## */

			/* ##### Insert Order Record ##### */
			$status = add_order($oid, $cid, $data);
			if (!$status) {
				$output = "建立報價單發生問題";
			} else {
				$output = "報價單已儲存";
			}
			/* ############################### */
			
			/* ##### Sending Sales Order ##### */
			$sender = "phoe721@yahoo.com";
			$receipient = $data["email"];			
			$status = send_email($sender, $receipient, $data); 
			if ($status) {
				$output .= "發送報價單給客戶中";
			} else {
				$output .= "報價單傳送發生錯誤";
			}
			/* ############################### */
		} else {
			$data = array(); // Reset data
		}
	} else {		
		/* ##### Change Customer Data ##### */
		if (isset($_POST["change"]) && $_POST["change"] == "修改") {
			$cid = $data["select_cid"];
			if (update_customer($data)) {
				$output = "客戶資料更新完成~";
			} else {
				$output = "客戶資料更新發生問題";
			}
		} else if (isset($_POST["delete"]) && $_POST["delete"] == "刪除") {
			$cid = $data["select_cid"];
			if (delete_customer($cid)) {
				$output = "資料刪除完成~";
				unset($cid);
				$data = array();
				$_POST["display_form"] = "no";
			} else {
				$output = "客戶資料刪除發生問題";
			}
		} else if (isset($_POST["send_order"]) && $_POST["send_order"] == "報價傳送") {		
			/* ##### Check if customer exists. If not, insert a new record ##### */
			$found = check_customer($data["tax_id"]);
			if (!$found) {
				$cid = add_customer($data);
			} else {
				$cid = $found;
			}
			/* ################################################################## */

			/* ##### Insert Order Record ##### */
			$status = add_order($oid, $cid, $data);
			if (!$status) {
				$output = "建立報價單發生問題<br />";
			} else {
				$output = "報價單資料已儲存<br />";
			}
			/* ############################### */
			
			/* ##### Sending Sales Order ##### */
			$sender = "phoe721@yahoo.com";
			$receipient = $data["email"];			
			$status = send_email($sender, $receipient, $data); 
			if ($status) {
				$output .= "報價單已發送給客戶";
			} else {
				$output .= "報價單傳送發生錯誤";
			}
			/* ############################### */
		} else {
			$cid = $data["select_cid"];
			$data = get_customer_info($cid);
		}
		/* ################################ */
	}
}
/* ######################### */

/* ##### Function ##### */
function get_oid() {
	/* ##### Get Order ID ##### */
	$query = "SELECT max(oid) FROM `server`.`order`";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		if (!empty($row['max(oid)'])) {
			$oid = $row['max(oid)'] + 1;
		} else {
			$oid = 1;
		}
	}
	//echo "Order ID: $oid<br />";
	return sprintf('%010d', $oid);
	/* ######################## */
}
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
function check_customer($tax_id) {
	$query = "SELECT cid FROM `server`.`customer` WHERE tax_id = '$tax_id'";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		$row = mysql_fetch_array($result);
		return $row["cid"];
	} else {
		return false;
	}
}
function add_customer($data) {
	/* ##### Insert customer info to database ##### */
	$query = "INSERT INTO `server`.`customer` VALUES (null, '$data[company]', '$data[tax_id]', '$data[address]', '$data[contact]', '$data[phone]', '$data[email]', '$data[comment]')";
	if (!mysql_query($query)) { 
		die('Error: ' . mysql_error());
		return false;
	} else {
		return mysql_insert_id();
	}
	/* ############################################ */
}
function update_customer($data) {
	$query = "UPDATE `server`.`customer` SET company = '$data[company]', tax_id = '$data[tax_id]', address = '$data[address]', contact = '$data[contact]', phone = '$data[phone]', email = '$data[email]', comment = '$data[comment]' WHERE cid = '$data[select_cid]'";
	if (!mysql_query($query)) {
		die('Error: ' . mysql_error());
		return false;
	} else {
		return true;
	}
}
function delete_customer($cid) {
	$query = "DELETE FROM `server`.`customer` WHERE cid = '$cid'";
	if (!mysql_query($query)) {
		die('Error: ' . mysql_error());
		return false;
	} else {
		return true;
	}
}
function add_order($oid, $cid, $data) {
	/* ##### Insert Order Record ##### */
	$order_date = $data["year"][0] . '-' . $data["month"][0] . '-' . $data["day"][0];
	$query = "INSERT INTO `server`.`order` VALUES ('$oid', '$cid', '$order_date', '$data[subtotal]')";
	if (!mysql_query($query)) { 
		die('Error: ' . mysql_error());
		return false;
	} 
	/* ############################### */
	
	/* ##### Insert Suborder Record ##### */
	for ($i = 0; $i < count($data["order"]); $i++) {
		if (!empty($data["order"][$i])) {
			$suborder_date = $data["year"][$i] . '-' . $data["month"][$i] . '-' . $data["day"][$i];
			$suborder = $data["order"][$i];
			$price = $data["price"][$i];
			$query = "INSERT INTO `server`.`suborder` VALUES (NULL, '$oid', '$suborder_date', '$suborder', '$price')";
			if (!mysql_query($query)) { 
				die('Error: ' . mysql_error()); 
				return false;
			} 
		}
	}
	/* ############################### */
	
	return true;
}
function get_customer_id() {
	$query = "SELECT cid FROM `server`.`customer` ORDER BY cid";
	$result = mysql_query($query);
	$index = 0;
	while ($row = mysql_fetch_array($result)) {
		$cid = $row['cid']; 
		$data[$index] = $cid;
		$index++;
	}
	return $data;
}
function get_customer_info($cid) {
	$query = "SELECT * FROM `server`.`customer` WHERE cid = '$cid'";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		foreach ($row as $key=>$value) {
			$data[$key] = $value;
		}
	}
	return $data;
}
function send_email($sender, $receipient, $data) {
	$to = $receipient;
	$subject = "報價傳送";
	$headers = "From: $sender" . "\r\n";
	$headers .= "Reply-To: $sender" . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$message =	$data["company"] . " " . $data["contact"] . "先生/小姐您好，以下是這次的報價<br /><br />";
	$suborder_date = $data["year"][0] . '-' . $data["month"][0] . '-' . $data["day"][0];
	$message .= "<table border=\"1\" style=\"border-collapse:collapse;\" width=\"300\">";
	$message .= "<tr><td colspan=\"3\" align=\"center\">報價單 日期: " . $suborder_date . "</td></tr>";
	$message .= "<tr><td></td><td width=\"55%\" align=\"center\">品項</td><td width=\"35%\" align=\"center\">價格</td></tr>";
	for ($i = 0; $i < count($data["order"]); $i++) {
		if (!empty($data["order"][$i])) {
			$suborder = $data["order"][$i];
			$price = $data["price"][$i];
			$order = $i + 1;
			$message .= "<tr><td align=\"center\">" . $order . "</td><td>" . $suborder . "</td><td align=\"right\">" . $price ." 元 </td></tr>";
		}
	}
	$message .= "</table>";
	if ($data["tax"] == "yes") {
		$message .= "<br />含稅合計： " . $data["subtotal"] . " 元<br /><br />";
	} else {
		$message .= "<br />不含稅合計： " . $data["subtotal"] . " 元<br /><br />";
	}
	$message .= "如有任何問題，請盡快與我聯絡！<br />祝　康祺<br />";
	
	if (mail($to, $subject, $message, $headers)) {
		return true;
	} else {
		return false;
	}
}
/* #################### */
?>
<!DOCTYPE html>
<html>
<head>
<title>Sales Order</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-cache">
<script type="text/javascript">
var subtotal = 0.0;
function myblur(element) {
	if (element.value == "") { element.value = element.defaultValue;  }
}
function myfocus(element) {
	if (element.value == element.defaultValue) { element.value = ""; }
}
function mychange(element) {
	element.style.border = "0px";
	element.style.borderBottom = "1px solid black";
}
function show_form() {
	var cust_form = document.getElementById("customer_form");
	if (cust_form.style.display == "none") {
		cust_form.style.display = "inherit";
	} else if (cust_form.style.display == "inherit") {
		cust_form.style.display = "none";
	}
}
function total() {
	var subtotal_field = document.getElementsByName("subtotal");
	var price_field = document.getElementsByName("price[]");
	var tax_field = document.getElementsByName("tax");
	subtotal = 0.0;
	for (var i = 0; i < price_field.length; i++) {
		subtotal += parseFloat(price_field[i].value);
	}
	for (var i = 0; i < tax_field.length; i++) {
		if (tax_field[i].checked) {
			if (tax_field[i].value == "yes") {
				subtotal_field[0].value = parseInt(subtotal * 1.05);
			} else {
				subtotal_field[0].value = subtotal;
			}
		}
	}
}
function mytax(element) {
	var subtotal_field = document.getElementsByName("subtotal");
	if (element.value == "yes") {
		subtotal_field[0].value = parseInt(subtotal * 1.05);
	} else {
		subtotal_field[0].value = subtotal;
	}
}
function add_input() { 
	var order_template = document.getElementById("order_field_template");
	var order_box = document.getElementById("order_box");
	var new_input = document.createElement("div");
	new_input.innerHTML = order_field_template.innerHTML;
	order_box.appendChild(new_input);
}
function checkCustomerForm() {
	var phone_regex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
	var email_regex = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var company = document.forms["customerForm"]["company"];
	var tax_id = document.forms["customerForm"]["tax_id"];
	var address = document.forms["customerForm"]["address"];
	var contact = document.forms["customerForm"]["contact"];
	var phone = document.forms["customerForm"]["phone"];
	var email = document.forms["customerForm"]["email"];
	var order = document.forms["customerForm"]["order[]"];
	var price = document.forms["customerForm"]["price[]"];
	
	if (company.value == "") {
		company.style.borderBottom = "1px solid red";
		alert("公司名稱欄位是空白，請補上!");
		return false;
	} else if (tax_id.value == "") {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位是空白，請補上!");
		return false;
	} else if (IsNumeric(tax_id.value) == false) {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位必須是數字，請修改!");
		return false;
	} else if (tax_id.value.length < 8) {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位必須是八碼，請修改!");
		return false;
	} else if (address.value == "") {
		address.style.borderBottom = "1px solid red";
		alert("公司地址欄位是空白，請補上!");
		return false;
	} else if (contact.value == "") {
		contact.style.borderBottom = "1px solid red";
		alert("聯絡人欄位是空白，請補上!");
		return false;
	} else if (phone.value == "") {
		phone.style.borderBottom = "1px solid red";
		alert("電話欄位是空白，請補上!");
		return false;
	} else if (!phone_regex.test(phone.value)) {
		phone.style.borderBottom = "1px solid red";
		alert("電話格式有問題，請修改!");
		return false;
	} else if (email.value == "") {
		email.style.borderBottom = "1px solid red";
		alert("信箱欄位是空白，請補上!");
		return false;
	} else if (!email_regex.test(email.value)) {
		email.style.borderBottom = "1px solid red";
		alert("信箱格式有問題，請修改!");
		return false;
	}
}
function checkForm() {
	var phone_regex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
	var email_regex = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var company = document.forms["order_form"]["company"];
	var tax_id = document.forms["order_form"]["tax_id"];
	var address = document.forms["order_form"]["address"];
	var contact = document.forms["order_form"]["contact"];
	var phone = document.forms["order_form"]["phone"];
	var email = document.forms["order_form"]["email"];
	var order = document.forms["order_form"]["order[]"];
	var price = document.forms["order_form"]["price[]"];
	
	if (company.value == "") {
		company.style.borderBottom = "1px solid red";
		alert("公司名稱欄位是空白，請補上!");
		return false;
	} else if (tax_id.value == "") {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位是空白，請補上!");
		return false;
	} else if (IsNumeric(tax_id.value) == false) {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位必須是數字，請修改!");
		return false;
	} else if (tax_id.value.length < 8) {
		tax_id.style.borderBottom = "1px solid red";
		alert("公司統一編號欄位必須是八碼，請修改!");
		return false;
	} else if (address.value == "") {
		address.style.borderBottom = "1px solid red";
		alert("公司地址欄位是空白，請補上!");
		return false;
	} else if (contact.value == "") {
		contact.style.borderBottom = "1px solid red";
		alert("聯絡人欄位是空白，請補上!");
		return false;
	} else if (phone.value == "") {
		phone.style.borderBottom = "1px solid red";
		alert("電話欄位是空白，請補上!");
		return false;
	} else if (!phone_regex.test(phone.value)) {
		phone.style.borderBottom = "1px solid red";
		alert("電話格式有問題，請修改!");
		return false;
	} else if (email.value == "") {
		email.style.borderBottom = "1px solid red";
		alert("信箱欄位是空白，請補上!");
		return false;
	} else if (!email_regex.test(email.value)) {
		email.style.borderBottom = "1px solid red";
		alert("信箱格式有問題，請修改!");
		return false;
	}
	if (order.value != undefined) {
		if (order.value == "") {
			order.style.borderBottom = "1px solid red";
			alert("品項欄位是空白，請補上!");
			return false;
		} else if (price.value == "0") {
			price.style.borderBottom = "1px solid red";
			alert("價格欄位是零，請修改!");
			return false;
		} else if (IsNumeric(price.value) == false) {
			price.style.borderBottom = "1px solid red";
			alert("價格必須是數字，請修改!");
			return false;
		}
	} else {
		for (var i = 0; i < order.length; i++) {
			if (order[i].value == "" && price[i].value != "0") {
				order[i].style.borderBottom = "1px solid red";
				alert("品項欄位是空白，請補上!");
				return false;
			} else if (order[i].value != "" && price[i].value == "0") {
				price[i].style.borderBottom = "1px solid red";
				alert("價格欄位是零，請修改!");
				return false;
			} else if (IsNumeric(price[i].value) == false) {
				price[i].style.borderBottom = "1px solid red";
				alert("價格必須是數字，請修改!");
				return false;
			}
		}
	}
	
	var reply = confirm("確定發送報價單？");
	if (!reply) {
		return false;
	}
}
function IsNumeric(input){
    return !isNaN(input);
}
</script>
<style type="text/css">
* {
	margin: 0px;
}
input:focus {
	background-color: #FFFFCC;
}
input:blur {
	background-color: #FFFFFF;
}
#company_detail {
	margin: 5px;
}
#company_detail input{
	border: 0px;
	border-bottom: 1px solid black;
	padding-left: 2px;
}
#order_title {
	margin: 0px;
	width: 670px;
/* 	border: 1px solid black; */
}
#order_title td{
	border: 1px solid black;
}
#order_box {
	margin: 0px;
}
#subtotal_field {
	width: 670px;
	margin: 0px;
}
#button_field {
	margin-top: 15px;
	padding-left: 460px
}
#button_field2 {
	margin-top: 15px;
	padding-left: 525px
}
#order_field_template {
    visibility:hidden;
}
#output {
	color: blue;
	font-weight: bold;
	font-size: 14px;
	padding-left: 310px
}
.order_no {
	text-align: center;
}
.select_cid {
	width: 80px;
}
.order_field {
	margin: 0px;
	width: 705px;
/* 	border: 1px solid black; */
}
.order_field td{
	border: 1px solid black;
}
.date {
	font-weight: bold;
	text-align: center;
	width: 190px;
}
.order {
	font-weight: bold;
	text-align: center;
	width: 305px;
}
.order input {
	border: 0px;
	border-bottom: 1px solid black;
}
.price {
	font-weight: bold;
	text-align: center;
	width: 155px;
}
.price input {
	text-align: right;
	border: 0px;
	border-bottom: 1px solid black;
}
.order_field .add_button {
	border: 0px;
}
.subtotal_title {
	font-weight: bold;
	text-align: right;
}
.tax_radio {
	text-align: center;
	font-weight: normal;
	padding-right: 20px;
}
.subtotal {
	width: 175px;
}
.subtotal input {
	border: 0px;
	border-bottom: 1px solid black;
	text-align: right;
}
</style>
</head>
<body>
<form name="order_form" action="order.php" method="post">
<table id="company_detail">
<tr>
	<td colspan="2">
	報價單號:<input type="text" name="order_no" class="order_no" size="10" maxlength="10" value="<?=$oid?>">
	</td>
</tr>
<tr>
	<td colspan="2">
	<label for="cid">客戶編號:</label>
	<select name="select_cid" class="select_cid" onchange="this.form.submit()">
	<?php
		if (isset($cid)) {
			echo "<option>新客戶</option>";
		} else {
			echo "<option selected>新客戶</option>";
		}
		
		$cid_array = get_customer_id();
		for ($i = 0; $i < count($cid_array); $i++) {
			if ($cid_array[$i] == $cid) {
				echo "<option selected>$cid_array[$i]</option>";
			} else {
				echo "<option>$cid_array[$i]</option>";
			}
		}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>
	<?php
		if (!isset($data["company"])) { $data["company"] = ""; }
		if (!isset($data["tax_id"])) { $data["tax_id"] = ""; }
		if (!isset($data["address"])) { $data["address"] = ""; }
		if (!isset($data["comment"])) { $data["comment"] = ""; }
		if (!isset($data["contact"])) { $data["contact"] = ""; }
		if (!isset($data["phone"])) { $data["phone"] = ""; }
		if (!isset($data["email"])) { $data["email"] = ""; }
		if (!isset($output)) { $output = ""; }
	?>
	公司名稱:<input type="text" name="company" class="company" size="30" maxlength="30" value="<?=$data["company"]?>" onchange="mychange(this)">
	統一編號:<input type="text" name="tax_id" class="tax_id" size="8" maxlength="8" value="<?=$data["tax_id"]?>" onchange="mychange(this)"><br />
	公司地址:<input type="text" name="address" class="address" size="58" maxlength="58" value="<?=$data["address"]?>" onchange="mychange(this)"><br />
	其他備註:<input type="text" name="comment" class="comment" size="58" maxlength="58" value="<?=$data["comment"]?>">
	</td>
	<td>
	聯絡人:<input type="text" name="contact" class="contact" size="30" maxlength="10" value="<?=$data["contact"]?>" onchange="mychange(this)"><br />&nbsp;&nbsp;&nbsp;
	電話:<input type="text" name="phone" class="phone" size="30" maxlength="15" value="<?=$data["phone"]?>" onchange="mychange(this)"><br />&nbsp;&nbsp;&nbsp;
	信箱:<input type="text" name="email" class="email" size="30" maxlength="30" value="<?=$data["email"]?>" onchange="mychange(this)"><br />
	</td>
</tr>
</table>
<table id="order_title">
<tr>
	<td class="date">日期</td>
	<td class="order">品項</td>
	<td class="price">價格</td>
</tr>
</table>
<div id="order_box">
	<table class="order_field">
	<tr>
		<td align="center" class="date">
			<select name="year[]">
			<?php
				$current_year = date('Y');
				for ($i = $current_year; $i < ($current_year + 10); $i++) {
					echo "<option value=\"$i\">$i</option>";
				}
			?>
			</select>
			/
			<select name="month[]">
			<?php
				$current_month = date('m');
				for ($i = 1; $i <= 12; $i++) {
					if ($i == $current_month) {
						echo "<option value=\"$i\" selected>$i</option>";
					} else {
						echo "<option value=\"$i\">$i</option>";
					}
				}
			?>
			</select>
			/
			<select name="day[]">
			<?php
				$current_day = date('j');
				for ($i = 1; $i <= 31; $i++) {
					if ($i == $current_day) {
						echo "<option value=\"$i\" selected>$i</option>";
					} else {
						echo "<option value=\"$i\">$i</option>";
					}
				}
			?>
			</select>
		</td>
		<td align="center" class="order">
			<input type="text" name="order[]" size="50" maxlength="50" onchange="mychange(this)">
		</td>
		<td align="center" class="price">
			<input type="text" name="price[]" size="23" maxlength="23" value="0" onchange="mychange(this);total()" onblur="myblur(this)" onfocus="myfocus(this)">
		</td>
		<td align="center" class="add_button">
			<input type="button" onclick="add_input()" name="add" value="+">
		</td>
	</tr>
	</table>
</div>
<table id="subtotal_field">
<tr>
	<td class="subtotal_title" colspan="2">
		<span class="tax_radio">
		<label for="tax">含稅:</label>
		是&nbsp;<input type="radio" name="tax" value="yes" checked onclick="mytax(this)">
		否&nbsp;<input type="radio" name="tax" value="no" onclick="mytax(this)">
		</span>
		合計:
	</td>
	<td align="center" class="subtotal">
		<input type="text" name="subtotal" size="25" maxlength="25" value="0">
	</td>
</tr>
</table>
<div id="button_field">
	<input type="hidden" name="display_form" value="no">
	<input type="button" name="manage_customer" value="管理客戶資料" onclick="show_form()">
	<input type="submit" name="send_order" value="報價傳送" onclick="return checkForm()">
	<input type="button" name="preview" value="預覽" onclick="window.open('print.php','print','width=1024,height=768')">
</div>
</table>
</form>
<br />
<?php if (!isset($_POST["display_form"]) || $_POST["display_form"] == "no") { ?>
<div id="customer_form" style="display:none">
<?php } else { ?>
<div id="customer_form" style="display:inherit">
<?php } ?>
	<form name="customerForm" action="order.php" method="post">
	<table id="company_detail">
	<tr>
		<td colspan="2">
		<label for="cid">客戶編號:</label>
		<select name="select_cid" class="select_cid" onchange="this.form.submit()">
		<?php
			if (isset($cid)) {
				echo "<option>新客戶</option>";
			} else {
				echo "<option selected>新客戶</option>";
			}
			
			$cid_array = get_customer_id();
			for ($i = 0; $i < count($cid_array); $i++) {
				if ($cid_array[$i] == $cid) {
					echo "<option selected>$cid_array[$i]</option>";
				} else {
					echo "<option>$cid_array[$i]</option>";
				}
			}
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td>
		公司名稱:<input type="text" name="company" class="company" size="30" maxlength="30" value="<?=$data["company"]?>" onchange="mychange(this)">
		統一編號:<input type="text" name="tax_id" class="tax_id" size="8" maxlength="8" value="<?=$data["tax_id"]?>" onchange="mychange(this)"><br />
		公司地址:<input type="text" name="address" class="address" size="58" maxlength="58" value="<?=$data["address"]?>" onchange="mychange(this)"><br />
		其他備註:<input type="text" name="comment" class="comment" size="58" maxlength="58" value="<?=$data["comment"]?>" onchange="mychange(this)">
		</td>
		<td>
		聯絡人:<input type="text" name="contact" class="contact" size="30" maxlength="10" value="<?=$data["contact"]?>" onchange="mychange(this)"><br />&nbsp;&nbsp;&nbsp;
		電話:<input type="text" name="phone" class="phone" size="30" maxlength="15" value="<?=$data["phone"]?>" onchange="mychange(this)"><br />&nbsp;&nbsp;&nbsp;
		信箱:<input type="text" name="email" class="email" size="30" maxlength="30" value="<?=$data["email"]?>" onchange="mychange(this)"><br />
		</td>
	</tr>
	</table>
	<div id="button_field2">
		<input type="hidden" name="display_form" value="yes">
		<?php if (isset($cid)) { ?>
		<input type="submit" name="add" value="新增" disabled>
		<input type="submit" name="change" value="修改">
		<input type="submit" name="delete" value="刪除">
		<?php } else { ?>
		<input type="submit" name="add" value="新增" onclick="return checkCustomerForm()">
		<input type="submit" name="change" value="修改" disabled> 
		<input type="submit" name="delete" value="刪除" disabled>
		<?php } ?>
	</div>
	</form>
</div>
<br />
<div id="output"><?=$output?></div>
<div id="order_field_template">
<table class="order_field">
<tr>
	<td align="center" class="date">
		<select name="year[]">
		<?php
			$current_year = date('Y');
			for ($i = $current_year; $i < ($current_year + 10); $i++) {
				echo "<option value=\"$i\">$i</option>";
			}
		?>
		</select>
		/
		<select name="month[]">
		<?php
			$current_month = date('m');
			for ($i = 1; $i <= 12; $i++) {
				if ($i == $current_month) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
		/
		<select name="day[]">
		<?php
			$current_day = date('j');
			for ($i = 1; $i <= 31; $i++) {
				if ($i == $current_day) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
	</td>
	<td align="center" class="order">
		<input type="text" name="order[]" size="50" maxlength="50" onchange="mychange(this)">
	</td>
	<td align="center" class="price">
		<input type="text" name="price[]" size="23" maxlength="23" value="0" onchange="mychange(this);total()" onblur="myblur(this)" onfocus="myfocus(this)">
	</td>
	<td align="center" class="add_button">
		<input type="button" onclick="add_input()" name="add" value="+">
	</td>
</tr>
</table>
</div>
</body>
</html>