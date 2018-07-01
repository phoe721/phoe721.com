<?php
class myClass {
    public $prop1 = "I'm a class property!";
    public static $count = 0;
    public function __construct() {
        echo 'The class "', __CLASS__, '" was initiated!<br />';
    }
    public function __destruct() {
        echo 'The class "', __CLASS__, '" was destroyed!<br />';
    }
    public function __toString() {         
        echo "Using the toString method: ";
        return $this->getProperty();
    }
    public function setProperty($newval) {
        $this->prop1 = $newval;
    }
    public function getProperty() {
        return $this->prop1 . "<br />";
    }
    public static function plusOne() {
        return "The count is " . ++self::$count . "<br />";
    }
}
class myOtherClass extends myClass {
    public function __construct() {
        parent::__construct();
        echo "A new constructor in " . __CLASS__ . "<br />";
    }
    public function newMethod() {
        echo "From a new method in " . __CLASS__ . "<br />";
    }
}

/*
$obj = new myClass;
var_dump($obj);
echo $obj->getProperty();
echo $obj;
unset($obj);
echo "End of file.<br />";
*/

/*
$newobj = new myOtherClass;
echo $newobj->newMethod();
echo $newobj->getProperty();
*/

do {
    echo myClass::plusOne();
} while (myClass::$count < 10);
?>
