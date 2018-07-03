<?php
$debug = false;
$daily_employee = array();
$employee = array (
    "1" => "Jill",
    "2" => "Teemo",
    "3" => "Jimmy",
    "4" => "Jacky",
    "5" => "Mori",
    "6" => "Dudu",
);

$weekday_array = array (
    "Mon" => "(一)",
    "Tue" => "(二)",
    "Wed" => "(三)",
    "Thu" => "(四)",
    "Fri" => "(五)",
    "Sat" => "(六)",
    "Sun" => "(日)",
);

if ($debug) {
    print_all_employees();
    echo get_employee_name_by_id(1). "<br />";
    set_daily_employee(3, 4, 2014, 1);
    print_daily_employee_by_date(3, 4, 2014);
}

$year = 2014;
$month = 3;
$numOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$empCount = count($employee);
$cur = 1;
$pre = $empCount;
for ($day = 1; $day <= $numOfDays; $day++) {
    $day_string = "$month/$day/$year"; 
    date_default_timezone_set("Asia/Taipei");
    $weekday = date("D", strtotime($day_string));   
    if ($weekday == "Sat" || $weekday == "Sun") {
        set_daily_employee($month, $day, $year, "--"); 
    } else {
        set_daily_employee($month, $day, $year, $cur); 
        $pre = $cur;
        $cur++; 
        if ($cur > $empCount) {
            $cur = 1;
        }
    } 
}
print_daily_employee_by_month($month, $year);

function print_all_employees() {
    global $employee;
    foreach($employee as $id => $name) {
        echo "Employee ID: $id, Employee Name: $name<br />";
    }
}

function get_employee_name_by_id($eid) {
    global $employee;
    foreach($employee as $id => $name) {
        if ($id == $eid) {
            return $name;
        }
    }
}

function set_daily_employee($month, $day, $year, $id) {
    global $daily_employee;
    $date_string = "$month/$day/$year";
    $daily_employee[$date_string] = $id;
}

function print_daily_employee_by_date($month, $day, $year) {
    global $daily_employee, $weekday_array;
    date_default_timezone_set("Asia/Taipei");
    $date_string = "$month/$day/$year";
    $weekday = date("D", strtotime($date_string));   
    $chinese_weekday = $weekday_array[$weekday];
    $id = $daily_employee[$date_string];
    if ($id == "--") {
        $name = $id;
    } else {
        $name = get_employee_name_by_id($id);
    } 
    echo "$month/$day$chinese_weekday:$name<br />"; 
}

function print_daily_employee_by_month($month, $year) {
    global $daily_employee;
    $numOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($day = 1; $day <= $numOfDays; $day++) {
        print_daily_employee_by_date($month, $day, $year);
    }
} 
?>
