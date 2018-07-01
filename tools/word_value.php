<?php
/* ##### INITIALIZATION ##### */
ini_set('error_reporting', E_ALL);
$list = array();
$alphabet = range("a", "z");
$output = "";
$upload_dir = "upload";
$debug_output = "";

if (!empty($_POST["debug"]) && $_POST["debug"] == "Yes") {
	$debug = true;
} else {
	$debug = false;
}

for ($i = 1; $i <= 26; $i++) {
	$element = array_shift($alphabet);
	$list[$element] = $i;
}

if ($debug) { debug_alphabet($list); }

/* ##### File Upload Function ##### */
if (isset($_POST["submit_file"])) {
	if ($_FILES["file"]["error"] > 0) {
		if ($_FILES["file"]["error"] == 4) {
			$output .= "Please submit a word list file.<br />";
		}
		if ($debug) {
			$debug_output .= "Error: " . $_FILES["file"]["error"] . "<br />";
		}
	} else {
		if ($debug) { debug_upload($_FILES); }
		move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . "/" . $_FILES["file"]["name"]);
		
		$file = fopen($upload_dir . "/" . $_FILES["file"]["name"], "r") or exit("Unable to open file!");
		while(!feof($file)) {
			$word = fgets($file);
            if (!empty($word)) {
			    $score = sum($word);
    			$output .= "$word: $score<br />";
            }
		}
		fclose($file);
	}
}

/* ##### Word Function ##### */
if (isset($_POST["submit_word"])) {
		$word = $_POST["word"];
		$score = sum($word);
		$output = "Word: $word<br />Score: $score<br />";
}

/* ##### Check Last File Function ##### */
if (isset($_POST["check"])) {
	$filename = exec("ls -l $upload_dir | tr -s ' ' | cut -d' ' -f9 | grep -v '^$' | head -n1");
	$file = fopen($upload_dir . "/" . $filename, "r") or exit("Unable to open file!");
	while(!feof($file)) {
		$word = fgets($file);
        if (!empty($word)) {
		    $score = sum($word);
    		$output .= "$word: $score<br />";
        }
	}
	fclose($file);
}

/* ##### Helper Functions ##### */
function sum($word) {
	global $list, $debug, $debug_output;
	$total = 0;
	$value = 0;
	$word = strtolower($word);
	$word = str_replace(array("\r\n", "\n", "\r", " ", "'"), '', $word);

    if ($debug) { $debug_output .= "Word: $word<br /><br />"; }
    
	for ($i = 0; $i < strlen($word); $i++) {
		$ch = substr($word, $i, 1);	
		$value = $list[$ch];
		$total += $value;

        if ($debug) {
	        $debug_output .= "Character: $ch,  Character Value: $value, Subtotal: $total <br />";
        }
	}

    if ($debug) { $debug_output .= "Total: $total<br /><br />"; }
	return $total;
}

function debug_alphabet($list) {
    global $debug_output;
	$debug_output .= "- Alphabet Array -";
	$debug_output .= "<pre>" . print_r($list, true) . "</pre>";
}

function debug_upload($_FILES) {
    global $debug_output;
	$debug_output .= "##### Upload Status #####<br />";
	$debug_output .= "Upload: " . $_FILES["file"]["name"] . "<br />";
	$debug_output .= "Type: " . $_FILES["file"]["type"] . "<br />";
	$debug_output .= "Size: " . ($_FILES["file"]["size"]) . " Bytes<br />";
	$debug_output .= "#########################<br />";
}
?>
<html>
<head>
<style>
#output {
    font:12px courier new;
}
</style>
</head>
<body> 
<form action="word_value.php" method="POST" enctype="multipart/form-data">
	<label for="file">Text File:</label>
	<input type="file" name="file" id="file" />
	<input type="submit" name="submit_file" value="Upload">
	<br />
	Enter Word Here: <input type="text" name="word">
	<input type="submit" name="submit_word" value="Submit">
	<br />
	Check Last File: <input type="submit" name="check" value="Check">
	<br />
	Debug: 
	<input type="radio" name="debug" value="Yes">Yes
	<input type="radio" name="debug" value="No" checked>No
</form>
<div id="output">
<?php
if (!empty($output)) {
	echo "############ Result ############<br />";
	echo $output;
	echo "################################<br /><br />";
}

if (!empty($debug_output)) {
	echo "############ Debug ############<br />";
	echo $debug_output;
	echo "###############################<br />";
}
?>
</div>
</body>
</html>
