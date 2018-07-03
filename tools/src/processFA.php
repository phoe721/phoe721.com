<?
	/* Display Errors */
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/* Reqiure Database & Define Constants */
	require_once("database.php");
	define("INPUT", "../upload/fa_input.csv");
	define("OUTPUT", "../upload/output.txt");

	/* Connect to DB */
   	$db	= new database;
	$con = $db->connect("localhost", "root", "revive", "amazon");
	mysqli_set_charset($con, "utf8");

	file_put_contents(OUTPUT, "");
	$file = fopen(INPUT, "r") or die("Unable to open file!");
	$file2 = fopen(OUTPUT, "a+") or die("Unable to open file!");
	if ($file) {
		while (($line = fgets($file)) != false) {
			$line = trim($line);
			$parts = explode(",", $line);
			$parts[0] = preg_replace("/\-A[0-9]*$/", "", $parts[0]);
			$parts[0] = preg_replace("/\-a$/", "", $parts[0]);
			$sku = preg_replace("/^FA-/", "", $parts[0]);

			if (preg_match("/\-/", $sku)) {
				// Ex. 00123-24-25
					$stock = getStock($sku);
					if (is_null($stock)) {
						$skus = explode("-", $sku);
						$stock = getStock($skus[0]);
						echo $skus[0] . " " . $stock . "\n";
					} else {
						/*
						if ($stock > 0) {
							fwrite($file2, $line . "," . $stock . "\n"); 
						} else {
							fwrite($file2, $line . ",0\n"); 
							continue;
						}
						 */
					}

					/*

					$found = false;
					$foundZero = false;
					for ($i = 1; $i < sizeof($skus); $i++) {
						$skuLen = strlen($skus[$i]);
						if ($compareStrLen > $skuLen) {
							$diff = $compareStrLen - $skuLen;
							$padStr = substr($compareStr, 0, $diff);
							$skus[$i] = $padStr . $skus[$i];
						}

						$stock = getStock($skus[$i]);
						if (is_null($stock)) {
							fwrite($file2, $line . ",null\n"); 
							$found = true;
							break;
						} else {
							if ($stock > 0) {
								$sum += $stock;
							} else {
								$foundZero = true;
								break;
							}
						}
					}

					if ($found) continue;
					if ($foundZero) {
						fwrite($file2, $line . ",0\n");
						continue;
					}

					$avgStock = intval($sum / sizeof($skus));
					fwrite($file2, $line . "," . $avgStock . "\n"); 
					 */
			} else {
				/*
				// Ex. 00123
				// Get xN part of the sku
				$multiple = 0;
				if (preg_match("/x[0-9]/", $sku)) {
					$pieces = explode("x", $sku);
					$multiple = $pieces[1];
					$sku = preg_replace("/x[0-9]/", "", $sku);
				}

				// Get stock
				$stock = getStock($sku);
				if (!is_null($stock)) {
					if ($stock > 0) {
						if ($multiple > 0) {
							fwrite($file2, $line . "," . intval($stock / $multiple) . "\n"); 
						} else {
							fwrite($file2, $line . "," . $stock . "\n"); 
						}
					} else {
						fwrite($file2, $line . ",0\n"); 
					}
				} else {
					fwrite($file2, $line . ",null\n"); 
				}
				 */
			}		
		}
	}
	fclose($file2);
	fclose($file);

function getStock($item) {
	global $db;
	$result = $db->query("SELECT mpn, quantity FROM inventory WHERE mpn LIKE '%" . $item . "%'");
	if (mysqli_num_rows($result) == 0) {
		return null;
	} else {
		$row = mysqli_fetch_array($result);
		return $row["quantity"];
	}
}

?>
