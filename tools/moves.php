<html>
<head>
<style>
body { font-family: "Courier New"; }
</style>
</head>
<body>
<?php
    $startX = 1;
    $startY = 1;
    $currentX = $startX;
    $currentY = $startY;
    $endX = 9;
    $endY = 9;
    $map = createMap(10, 10);
    $dirDice = array (
        "Forward",
        "Backward",
        "Left",
        "Right"
    );

    travel($endX, $endY, 100);
    displayMap($map);

    function move($move) {
        global $map, $currentX, $currentY, $dirDice;
        $invalidMove = true;
        $tmpDice = array_merge(array(), $dirDice);
        while ($invalidMove) {
            $tmpX = $currentX;
            $tmpY = $currentY;
            if (!empty($tmpDice)) { 
	            $index = array_rand($tmpDice, 1); 
	            $direction = $tmpDice[$index];
	            if ($direction == "Forward") {
	                $tmpX--;
	            } else if ($direction == "Backward") {
	                $tmpX++;
	            } else if ($direction == "Left") {
	                $tmpY--;
	            } else if ($direction == "Right") {
	                $tmpY++;
	            } 
	            unset($tmpDice[$index]);
            } else {
                echo "No more moves...<br />";
                break;
            }
            if ($tmpX <= 0 || $tmpY <= 0 || $tmpX > count($map) || $tmpY > count($map)) {
                $invalidMove = true;
            } else if ($map[$tmpX][$tmpY] != -1) {
                $invalidMove = true;
            } else {
                $currentX = $tmpX;
                $currentY = $tmpY;
                $map[$currentX][$currentY] = $move; 
                echo "$move.$direction: ($currentX, $currentY)<br />";
                $invalidMove = false;
            }
        }
    }

    function travel($endX, $endY, $limit) {
        global $map, $currentX, $currentY;
        $map[$currentX][$currentY] = 0;
        $move = 1;
        while($move <= $limit) {
            move($move);
            if ($currentX == $endX && $currentY == $endY) { 
                echo "Congratulation!<br />";
                break; 
            } else if ($move == $limit) {
                echo "Sorry, not found!<br />";
            }
            $move++;
        }
    }

    function createMap($length, $width) {
        $map = array();
	    for ($i = 1; $i <= $length; $i++) {
	        for ($j = 1; $j <= $width; $j++) {
	            $map[$i][$j] = -1; 
	        }
	    }
        return $map;
    }

    function resetMap() {
        global $map;
	    for ($i = 1; $i <= count($map); $i++) {
	        for ($j = 1; $j <= count($map[$i]); $j++) {
	            $map[$i][$j] = -1; 
	        }
	    }
    }

    function displayMap($map) {
        for ($i = 1; $i <= count($map); $i++) {
            for ($j = 1; $j <= count($map[$i]); $j++) {
                if ($map[$i][$j] == -1) {
                    echo "(__)";
                } else if ($map[$i][$j] <= 9) {
                    echo "(_" . $map[$i][$j] . ")";
                } else if ($map[$i][$j] >= 10) {
                    echo "(" . $map[$i][$j] . ")";
                }
                if ($j == count($map[$i])) {
                    echo "<br />";
                }
            }
        }
    }
?>
</body>
</html>
