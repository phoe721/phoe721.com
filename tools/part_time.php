<?php
	/* ##### Initalize Local Variables ##### */
	define("BASE_SALARY", 120);
    define("INITIAL_PART_TIME_PAY", 500);
	define("MONTHS_PER_YEAR", 12);
	define("DAYS_PER_MONTH", 31);
	define("HOURS_PER_DAY", 24);
	define("MINUTES_PER_HOUR", 60);
	define("QUARTER_HOUR", 15);
	define("HALF_HOUR", 30);
	define("THREE_QUARTERS_HOUR", 45);
	define("OVERTIME", 20);
	define("OVERTIME_BONUS", 1.5);
	$total_regular_hour = 0;
	$total_overtime_hour = 0;
	$final_total_hour = 0;
	$final_total_amount = 0;
	$output = array();
	$debug = false;
	date_default_timezone_set("Asia/Taipei");
	/* ##################################### */
	
	if (isset($_POST) && !empty($_POST)) {
		$count = $_POST["count"];
		
		for ($i = 0; $i < $count; $i++) {
			/* ##### Get User Inputs ##### */
			$task = $_POST["task"][$i];
			$base_salary = $_POST["base_salary"][$i];
			$start_year = $_POST["start_year"][$i];
			$start_month = $_POST["start_month"][$i];
			$start_day = $_POST["start_day"][$i];
			$start_hour = $_POST["start_hour"][$i];
			$start_minute = $_POST["start_minute"][$i];
			$end_year = $_POST["end_year"][$i];
			$end_month = $_POST["end_month"][$i];
			$end_day = $_POST["end_day"][$i];
			$end_hour = $_POST["end_hour"][$i];
			$end_minute = $_POST["end_minute"][$i];
			/* ########################### */
			
			/* ##### Reset Initial Values ##### */
			$regular_hour = 0;
			$overtime_hour = 0;
			$total_day = 0;
			$total_hour = 0;
			$remain_minute = 0;
			$total_amount = 0;
			$start_adjustment = 0;
			$end_adjustment = 0;
			$output[$i] = "";
			/* ################################ */
			
			/* ##### Total Hour Calculation ##### */
			$start_time = date_create($start_year . "-" . $start_month . "-" . $start_day . " " . $start_hour . ":" . $start_minute);
			$end_time = date_create($end_year . "-" . $end_month . "-" . $end_day . " " . $end_hour . ":" . $end_minute);
			$interval = date_diff($start_time, $end_time);
			$total_day = $interval->days;
			$total_hour = ($total_day * HOURS_PER_DAY) + $interval->h;
			$remain_minute = $interval->i;
			/* ###################### */
			
			/* ##### Regular & Overtime Calculation ##### */
			if ($start_hour < OVERTIME && $end_hour >= OVERTIME) {
				$regular_hour = OVERTIME - $start_hour;
				$overtime_hour= $total_hour - $regular_hour;
			} else if ($start_hour >= OVERTIME) {
				$overtime_hour = $total_hour;
			} else if ($start_hour < OVERTIME && $end_hour < OVERTIME) {
				$regular_hour = $total_hour;
			}
			/* ########################################## */
			
			/* ##### Minute Adjustment ##### */
			if ($start_minute >= QUARTER_HOUR && $start_minute < HALF_HOUR) {
				$start_adjustment = QUARTER_HOUR / MINUTES_PER_HOUR;
			} else if ($start_minute >= HALF_HOUR && $start_minute < THREE_QUARTERS_HOUR) {
				$start_adjustment = HALF_HOUR / MINUTES_PER_HOUR;
			} else if ($start_minute >= THREE_QUARTERS_HOUR && $start_minute < MINUTES_PER_HOUR) {
				$start_adjustment = THREE_QUARTERS_HOUR / MINUTES_PER_HOUR;
			}

			if ($end_minute >= QUARTER_HOUR && $end_minute < HALF_HOUR) {
				$end_adjustment = QUARTER_HOUR / MINUTES_PER_HOUR;
			} else if ($end_minute >= HALF_HOUR && $end_minute < THREE_QUARTERS_HOUR) {
				$end_adjustment = HALF_HOUR / MINUTES_PER_HOUR;
			} else if ($end_minute >= THREE_QUARTERS_HOUR && $end_minute < MINUTES_PER_HOUR) {
				$end_adjustment = THREE_QUARTERS_HOUR / MINUTES_PER_HOUR;
			}

			if ($start_hour >= OVERTIME) {
				$overtime_hour += $start_adjustment;
			} else if ($start_hour < OVERTIME) {
				$regular_hour += $start_adjustment;
			}

			if ($end_hour >= OVERTIME) {
				$overtime_hour += $end_adjustment;
			} else if ($end_hour < OVERTIME) {
				$regular_hour += $end_adjustment;
			}
			
			$total_hour += $start_adjustment + $end_adjustment;
            /* ############################# */
			
			/* ##### Total Amount Calculation ##### */
			$total_amount = ($regular_hour * $base_salary) + ($overtime_hour * ($base_salary * OVERTIME_BONUS));
			if ($total_amount < INITIAL_PART_TIME_PAY) { $total_amount = INITIAL_PART_TIME_PAY; };
			$total_regular_hour += $regular_hour;
			$total_overtime_hour += $overtime_hour;
			$final_total_hour += $total_hour;
			$final_total_amount += $total_amount;
			/* ########################################### */
				
			/* ##### Debug ##### */
			if ($debug == true) {
				echo "########## DEBUG ##########<br />";
				echo "[DEBUG] Task: $task<br />";
				echo "[DEBUG] Count: $count<br />";
				echo "[DEBUG] Start Date: $start_year/$start_month/$start_day $start_hour:$start_minute<br />";
				echo "[DEBUG] End Date: $end_year/$end_month/$end_day $end_hour:$end_minute<br />";
				echo "[DEBUG] Day difference: $total_day<br />";
				echo "[DEBUG] Hour difference: $total_hour<br />";
				echo "[DEBUG] Minute remaining: $remain_minute<br />";
				echo "[DEBUG] Regular hour: $regular_hour<br />";
				echo "[DEBUG] Overtime hour: $overtime_hour<br />";
				echo "[DEBUG] Subtotal amount: $total_amount<br />";
				echo "############################<br /><br />";
			}
			/* ################# */
			
			/* ##### Entry Output ##### */
			$output[$i] .= "<span class=\"output\">Task Title: $task</span>";
			$output[$i] .= "<span class=\"output\">Base Salary: $base_salary</span>";
			$output[$i] .= "<span class=\"output\">Start: $start_year/$start_month/$start_day $start_hour:$start_minute</span>";
			$output[$i] .= "<span class=\"output\">End: $end_year/$end_month/$end_day $end_hour:$end_minute</span>";
			if ($overtime_hour > 0) { 
				$output[$i] .= "<span class=\"output\">Regular Hour: $regular_hour</span>";
				$output[$i] .= "<span class=\"output\">Overtime Hour: $overtime_hour</span>";
				$output[$i] .= "<span class=\"output\">Overtime Salary: " . ($base_salary * OVERTIME_BONUS) . "</span>"; 
			}
			$output[$i] .= "<span class=\"output\">Total Hour: $total_hour</span>";
			$output[$i] .= "<span class=\"output\">Total Amount: $total_amount</span>";
            /* ################## */
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Part-Time Salary Calculator</title>
<meta name="viewport" content="width=device-width">
<script type="text/javascript">
var count = 1;
var validate = true;
function myblur(element) {
	element.style.backgroundColor="#FFFFCC";
	if (element.value == "") { element.value = element.defaultValue;  }
}
function myfocus(element) {
	element.style.backgroundColor="#FFFFFF";
	if (element.value == element.defaultValue) { element.value = ""; }
}
function mychange(element) {
    // alert(element.name);
    element.style.color = "#505050";
    if (IsNumeric(element.value) == false) {
        alert("Value must be number only!");
        element.style.color = "red";
        // element.value = element.defaultValue;
    } else if (element.value < 0) {
        alert("Value must be greater than zero!");
        element.style.color = "red";
        // element.value = element.defaultValue;
    } else {
        if (element.name == "base_salary[]") {
			var check_id = element.parentNode.parentNode.id;
			var found = false;
			var change = document.getElementsByName("base_salary[]");
            for (var i = 0; i < change.length; i++) {
				var parent_id = change[i].parentNode.parentNode.id;
				if (found == true) { change[i].value = element.value; }
				if (check_id == parent_id) { found = true; }
            }
        } else if (element.name == "start_hour[]" || 		element.name == "end_hour[]") {
            if (element.value >= 24) {
                alert("Hour value can not be greater or equal to 24!");
                element.style.color = "red";
                // element.value = element.defaultValue;
            }
        } else if (element.name == "start_minute[]" || element.name == "end_minute[]") {
            if (element.value >= 60) {
                alert("Minute value can not be greater or equal to 60!");
                element.style.color = "red";
                // element.value = element.defaultValue;
            }
        } else if (element.name == "start_year[]") {
            var change = document.getElementsByName("end_year[]");
			change[count-1].selectedIndex = element.selectedIndex;
        } else if (element.name == "start_month[]") {
            var change = document.getElementsByName("end_month[]");
			change[count-1].selectedIndex = element.selectedIndex;
        } else if (element.name == "start_day[]") {
            var change = document.getElementsByName("end_day[]");
			change[count-1].selectedIndex = element.selectedIndex;
        }
    }
	
	if (element.style.color == "red") {
		validate = false;
	} else {
		validate = true;
	}
}
function add_input() {
    var input_field_template = document.getElementById("input_field_template");
    var new_input = document.createElement("div");
    new_input.innerHTML = input_field_template.innerHTML;
    var input_box = document.getElementById("input_box");
    input_box.appendChild(new_input);
    var count_field = document.getElementById("count");
    count_field.value = ++count;
    new_input.id = "input_field" + count;
	new_input.style.backgroundColor = "#447BD4";
    new_input.style.borderTop = "1px solid #104BA9";
    new_input.style.padding = "5px";
	new_input.style.borderRadius = "10px";
}
function checkForm() {
	var bs = document.forms["inputForm"]["base_salary[]"];
    var sy = document.forms["inputForm"]["start_year[]"];
    var ey = document.forms["inputForm"]["end_year[]"];
    var sm = document.forms["inputForm"]["start_month[]"];
    var em = document.forms["inputForm"]["end_month[]"];
    var sd = document.forms["inputForm"]["start_day[]"];
    var ed = document.forms["inputForm"]["end_day[]"];
    var sh = document.forms["inputForm"]["start_hour[]"];
    var eh = document.forms["inputForm"]["end_hour[]"];

    if (count == 1) {
		if (validate == false) {
			alert("There are values still needs to be corrected!");
			return false;
		} else if (bs.value < 0) {
			alert("Base salary must be greater than zero!");
			bs.style.color = "red";
			return false;
		} else if (sy.selectedIndex < ey.selectedIndex) {
            alert("End year must be greater or equal to start year!");
            ey.style.color = "red";
            return false;
        } else if (sy.selectedIndex == ey.selectedIndex) {
            if (sm.selectedIndex > em.selectedIndex) {
                alert("End month must be greater or equal to start month!");
                em.style.color = "red";
                return false;
            } else if (sm.selectedIndex == em.selectedIndex) {
                if (sd.selectedIndex > ed.selectedIndex) {
                    alert("End day must be greater or equal to start day!");
                    ed.style.color = "red";
                    return false;
                } else if (sd.selectedIndex == ed.selectedIndex) {
                    if (parseInt(sh.value) >= parseInt(eh.value)) {
                        alert("End hour must be greater than start hour!");
                        eh.style.color = "red";
                        return false;
                    }
                }
            }
        }
    } else {
        for (var i = 0; i < count; i++) {
			if (bs[i].value < 0) {
				alert("Base salary must be greater than zero!");
				bs[i].style.color = "red";
				return false;
            } else if (sy[i].selectedIndex < ey[i].selectedIndex) {
                 alert("End year must be greater or equal to start year!");
                 ey[i].style.color = "red";
                 return false;
	        } else if (sy[i].selectedIndex == ey[i].selectedIndex) {
	            if (sm[i].selectedIndex > em[i].selectedIndex) {
	                alert("End month must be greater or equal to start month!");
                    em[i].style.color = "red";
	                return false;
	            } else if (sm[i].selectedIndex == em[i].selectedIndex) {
	                if (sd[i].selectedIndex > ed[i].selectedIndex) {
	                    alert("End day must be greater or equal to start day!");
                        ed[i].style.color = "red";
	                    return false;
	                } else if (sd[i].selectedIndex == ed[i].selectedIndex) {
                        if (parseInt(sh[i].value) >= parseInt(eh[i].value)) {
                            alert("End hour must be greater than start hour!");
                            eh[i].style.color = "red";
                            return false;
                        }
                    }
                }
	        }
        }
    }
}
function redirect() {  
    location.href = "<?=$_SERVER['PHP_SELF']?>";  
}  
function IsNumeric(input){
    return !isNaN(input);
}
</script>
<style type="text/css">
* {
	margin: 0px;
	padding: 0px;
}
body {
	font-family: sans-serif;
	font-size: 9pt;
}
input, select {
	font-size: 9pt;
	color: #505050;
	background-color: #FFFFCC;
	margin: 2px 0 2px 0;
	text-align: center;
    border: 1px solid black;
}
#entry_field {
    padding: 10px;
    width: 320px;
	color: #FFF;
	background-color: #104BA9;
	margin: 0 auto;
	/* border-radius: 10px; */
}
#input_field_template {
    visibility:hidden;
}
#input_box {
	margin: 2px 0 2px 0;
	/* border: 1px solid black; */
}
#input_field {
	background-color: #447BD4;
    padding: 5px;
	border-radius: 10px;
	/* border: 1px solid black; */
}
#button_field {
	padding: 2px;
	text-align: right;
	/* border: 1px solid black; */
}
#output_box {
	background-color: #447BD4;
	padding: 5px;
	border-radius: 10px;
}
#result_box {
	/* border: 1px solid black; */
	margin: 2px 0 2px 0;
}
#summary_box {
	background-color: #447BD4;
	padding: 5px;
	border-radius: 5px;
}
.title {
	text-align: center;
}
.line {
    width: 300px;
	margin: 0 auto;
	/* border: 1px solid black; */
}
.heading {
    display: inline-block;
    width: 70px;
    text-align: right;
	/* border: 1px solid black; */
}
.task_value { 
	width: 70px;
}
.salary_value {
    width: 45px;
}
.time {
    width: 20px;
}
.button {
	border: 0px;
	padding: 3px;
	color: #000000;
	background-color: #D8D8D8;
	border-radius: 5px;
}
.button:hover {
	color: #000000;
	background-color: #C0C0C0;
}
.output {
	color: #505050;
	background-color: #FFFFCC;
	display: inline-block;
	width: 148px;
	margin: 2px;
	padding-left: 3px;
}
</style>
</head>
<body>
<div id="entry_field">
<?php if ($final_total_amount == 0) { ?>
	<div class="title">Part-Time Hour Calculator</div>
    <form name="inputForm" id="inputForm" method="post" action="part_time.php">
    <div id="input_box">
		<div id="input_field">
			<div class="line">
				<span class="heading">Task Title:</span>
				<input class="task_value" type="text" name="task[]" value="Task Name" onblur="myblur(this)" onfocus="myfocus(this)">
				<span class="heading">Base Salary:</span>
				<input class="salary_value" type="text" name="base_salary[]" value="<?=BASE_SALARY?>" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">	
			</div>
			<div class="line">
				<span class="heading">Start Time:</span>
				<select name="start_year[]" onchange="mychange(this)">
				<?php
					$current_year = date(Y);
					for ($i = $current_year; $i > ($current_year - 10); $i--) {
						echo "<option value=\"$i\">$i</option>";
					}
				?>
				</select>
				/
				<select name="start_month[]" onchange="mychange(this)">
				<?php
					$current_month = date(m);
					for ($i = 1; $i <= MONTHS_PER_YEAR; $i++) {
						if ($i == $current_month) {
							echo "<option value=\"$i\" selected>$i</option>";
						} else {
							echo "<option value=\"$i\">$i</option>";
						}
					}
				?>
				</select>
				/
				<select name="start_day[]" onchange="mychange(this)">
				<?php
					$current_day = date(j);
					for ($i = 1; $i <= DAYS_PER_MONTH; $i++) {
						if ($i == $current_day) {
							echo "<option value=\"$i\" selected>$i</option>";
						} else {
							echo "<option value=\"$i\">$i</option>";
						}
					}
				?>
				</select>
				<input class="time" type="text" name="start_hour[]" maxlength="2" value="9" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
				:
				<input class="time" type="text" name="start_minute[]" maxlength="2" value="00" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
			</div>
			<div class="line">
				<span class="heading">End Time:</span>
				<select name="end_year[]" onchange="mychange(this)">
				<?php
					$current_year = date(Y);
					for ($i = $current_year; $i > ($current_year - 10); $i--) {
						echo "<option value=\"$i\">$i</option>";
					}
				?>
				</select>
				/
				<select name="end_month[]" onchange="mychange(this)">
				<?php
					$current_month = date(m);
					for ($i = 1; $i <= MONTHS_PER_YEAR; $i++) {
						if ($i == $current_month) {
							echo "<option value=\"$i\" selected>$i</option>";
						} else {
							echo "<option value=\"$i\">$i</option>";
						}
					}
				?>
				</select>
				/
				<select name="end_day[]" onchange="mychange(this)">
				<?php
					$current_day = date(j);
					for ($i = 1; $i <= DAYS_PER_MONTH; $i++) {
						if ($i == $current_day) {
							echo "<option value=\"$i\" selected>$i</option>";
						} else {
							echo "<option value=\"$i\">$i</option>";
						}
					}
				?>
				</select>
				<input class="time" type="text" name="end_hour[]" maxlength="2" value="10" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
				:
				<input class="time" type="text" name="end_minute[]" maxlength="2" value="00" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
			</div>
		</div> <!-- Input Field Div //-->
    </div> <!-- Input Box Div //-->
	<div id="button_field">
		<input type="button" class="button" value="Add" onclick="add_input()">
		<input type="hidden" id="count" name="count" value="1">
		<input type="submit" class="button" name="submit" onclick="return checkForm()" value="Calculate">
	</div>
    </form>
<?php } else {
		for ($j = 0; $j < $count; $j++) {
			echo "<center>Job Entry ". ($j+1) . "</center>";
			echo "<div id=\"output_box\">";
			echo "$output[$j]";
			echo "</div><br />";
		}
		echo "<div id=\"result_box\">";
		echo "<center>Summary</center>";
		echo "<div id=\"summary_box\">";
		if ($overtime_hour > 0) { 
			echo "<span class=\"output\">Total Regular Hour: $total_regular_hour</span>";
			echo "<span class=\"output\">Total Overtime Hour: $total_overtime_hour</span>"; 
		}
		echo "<span class=\"output\">Total Hour: $final_total_hour</span>";
		echo "<span class=\"output\">Total Amount: $final_total_amount</span>";
		echo "</div>";
		echo "</div>";
		echo "<div id=\"button_field\">";
		echo "<input type=\"button\" class=\"button\" onclick=\"redirect();\" value=\"Go Back\">";
		echo "</div>";
      }
?>
</div> <!-- Entry Field Div //-->
<div id="input_field_template">
	<div class="line">
		<span class="heading">Task Title:</span>
		<input class="task_value" type="text" name="task[]" value="Task Name" onblur="myblur(this)" onfocus="myfocus(this)">
		<span class="heading">Base Salary:</span>
		<input class="salary_value" type="text" name="base_salary[]" value="<?=BASE_SALARY?>" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">	
	</div>
	<div class="line">
		<span class="heading">Start Time:</span>
		<select name="start_year[]" onchange="mychange(this)">
		<?php
			$current_year = date(Y);
			for ($i = $current_year; $i > ($current_year - 10); $i--) {
				echo "<option value=\"$i\">$i</option>";
			}
		?>
		</select>
		/
		<select name="start_month[]" onchange="mychange(this)">
		<?php
			$current_month = date(m);
			for ($i = 1; $i <= MONTHS_PER_YEAR; $i++) {
				if ($i == $current_month) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
		/
		<select name="start_day[]" onchange="mychange(this)">
		<?php
			$current_day = date(j);
			for ($i = 1; $i <= DAYS_PER_MONTH; $i++) {
				if ($i == $current_day) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
		<input class="time" type="text" name="start_hour[]" maxlength="2" value="9" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
		:
		<input class="time" type="text" name="start_minute[]" maxlength="2" value="00" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
	</div>
	<div class="line">
		<span class="heading">End Time:</span>
		<select name="end_year[]" onchange="mychange(this)">
		<?php
			$current_year = date(Y);
			for ($i = $current_year; $i > ($current_year - 10); $i--) {
				echo "<option value=\"$i\">$i</option>";
			}
		?>
		</select>
		/
		<select name="end_month[]" onchange="mychange(this)">
		<?php
			$current_month = date(m);
			for ($i = 1; $i <= MONTHS_PER_YEAR; $i++) {
				if ($i == $current_month) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
		/
		<select name="end_day[]" onchange="mychange(this)">
		<?php
			$current_day = date(j);
			for ($i = 1; $i <= DAYS_PER_MONTH; $i++) {
				if ($i == $current_day) {
					echo "<option value=\"$i\" selected>$i</option>";
				} else {
					echo "<option value=\"$i\">$i</option>";
				}
			}
		?>
		</select>
		<input class="time" type="text" name="end_hour[]" maxlength="2" value="10" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
		:
		<input class="time" type="text" name="end_minute[]" maxlength="2" value="00" onblur="myblur(this)" onfocus="myfocus(this)" onchange="mychange(this)">
</div>
</body>
</html>