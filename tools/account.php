<html>
<head>
<title>Account Manager</title>
<script type="text/javascript" src="include/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="include/jquery.form.js"></script>
<script>
$(document).ready(function(){
	$.post("accountHelper.php",{auth:"yes"},function(data){
		if (data == "Not authenticated") {
			$(location).attr("href","login.php");
		}
	});
	
	$.post("accountHelper.php",{display:"yes"},function(data){
		$(data).insertBefore("#insert");
	});
	
	$("#insertForm").ajaxForm({
		complete: function(xhr) {
			$("#status").html(xhr.responseText);
		}
	});
});

/* $(document).on("click", 
}); */

function refresh(){
	$.post("accountHelper.php",{display:"yes"},function(data){
		$(data).insertBefore("#insert");
	});
}
</script>
<style>
input[type="text"] {
	margin: 0px;
	padding: 0px;
	border: 0px;
	width: 100px;
	text-align: center;
}
table {
	border-collapse:collapse;
}
td {
	text-align: center;
}
a {
	text-decoration: none;
}
.link {
	width: 150px;
}
.link input {
	width: 150px;
}
</style>
</head>
<body>
<a href="logout.php">Logout</a>
<table id="acctList" border="1">
<tr>
	<td>Account</td>
	<td>User</td>
	<td>Password</td>
	<td>Link</td>
	<td>Comment</td>
	<td></td>
</tr>
<tr id="insert">
	<form method="POST" id="insertForm" action="accountHelper.php">
	<td><input type="text" name="account" id="account"></td>
	<td><input type="text" name="uid" id="uid"></td>
	<td><input type="text" name="pwd" id="pwd"></td>
	<td><input type="text" name="link" id="link"></td>
	<td><input type="text" name="comment" id="comment"></td>
	<td><input type="submit" name="insert" value="Add"></td>
	</form>
</tr>
</table>
<div id="status"></div>
</body>
</html>