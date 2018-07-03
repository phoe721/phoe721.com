<html>
<head>
<script type="text/javascript" src="include/jquery-2.1.0.min.js"></script>
<script>
$(document).ready(function(){
	$("#question_field").hide();
	
	$("#name").focus(function() {
		$("#nameStatus").html("");
	});
	
	$("#name").on("change keyup blur", function(){
		name=$("#name").val();
		$.post("loginHelper.php",{nameCheck:"yes", name:name},function(data){
			if (data != "User does not exist") {
				$("#question_field").show();
				$("#question").html(data);
				$("#login").removeAttr("disabled");
			} else {
				$("#question_field").hide();
				$("#login").attr("disabled", "disabled");
			}
		});
	});
	
	$("#password").focus(function(){
		name=$("#name").val();
		if (name != "") {
			$.post("loginHelper.php",{nameCheck:"yes", name:name},function(data){
				$("#nameStatus").html(data);
			});
		}
	});
	
	$("#answer").focus(function(){
		$("#answerCheck").html("");
	});
	
	$("#answer").blur(function(){
		name=$("#name").val();
		answer=$("#answer").val();
		if (answer != "") {
			$.post("loginHelper.php",{answer:answer,name:name},function(data){
				$("#answerCheck").html(data);
				if (data == "Correct") {
					setTimeout(function(){
						$("#answerCheck").html("");
					},500);
				}
			});
		} 
	});
	
	$("#login").click(function(){
		name=$("#name").val();
		password=$("#password").val();
		answer=$("#answer").val();
		if (name == "") {
			$("#status").html("Username is missing");
		} else if (password == "") {
			$("#status").html("Password is missing");
		} else if (answer == "") {
			$("#status").html("Secuirty question answer is missing");
		} else {
			$.post("loginHelper.php",{login:"yes",name:name,password:password,answer:answer},function(data) {
				if (data == "Access Granted") {
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
	<input type="text" name="answer" id="answer"> <span id="answerCheck"></span>
</div>
<br />
<button type="button" id="login">Login</button>
<a href="register.php">Go to register</a>
</form>
<div id="status"></div>
</body>
</html>