<html>
<head>
<script type="text/javascript" src="include/jquery-2.1.0.min.js"></script>
<script>
$(document).ready(function(){
	$.post("loginHelper.php",{question:"yes"},function(data){
		$("#question").html(data);
	});
	
	$("#name").change(function(){
		name=$("#name").val();
		$.post("loginHelper.php",{registerCheck:"yes", name:name},function(data){
			if (data != "OK") {
				$("#nameStatus").html(data);
				$("#register").attr("disabled", "disabled");
			} else {
				$("#nameStatus").html("");
				$("#register").removeAttr("disabled");
			}
		});
	});
	
	$("#name").keyup(function(){
		name=$("#name").val();
		$.post("loginHelper.php",{registerCheck:"yes", name:name},function(data){
			if (data != "OK") {
				$("#nameStatus").html(data);
				$("#register").attr("disabled", "disabled");
			} else {
				$("#nameStatus").html("");
				$("#register").removeAttr("disabled");
			}
		});
	});
	
	$("#register").click(function(){
		name=$("#name").val();
		password=$("#password").val();
		question=$("#question").val();
		answer=$("#answer").val();
		if (name == "") {
			$("#status").html("Userame is missing.");
		} else if (password == "") {
			$("#status").html("Password is missing.");
		} else if (answer == "") {
			$("#status").html("Answer is missing for secuirty question.");
		} else {
			$.post("loginHelper.php",{register:"yes",name:name,password:password,question:question,answer:answer},function(data) {
				if (data == "Registered.") {
					$(location).attr("href","account.php");
				} else {
					$("#status").html(data);
				}
			});
		}
	});
});
</script>
<style>
</style>
</head>
<body>
<form method="POST" action="loginHelper.php">
Username:<br />
<input type="text" name="name" id="name"> <span id="nameStatus"></span><br />
Password:<br />
<input type="password" name="password" id="password"><br />
<div id="question_field">
	Security Question: <br />
	<select name="question" id="question">
	</select>
	<br />
	Answer:<br />
	<input type="text" name="answer" id="answer"></span>
</div>
<br />
<button type="button" id="register">Register</button> 
<a href="login.php">Go back to login</a>
</form>
<div id="status"></div>
</body>
</html>