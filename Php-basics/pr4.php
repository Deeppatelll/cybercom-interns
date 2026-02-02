<?php

class Employee {
    public $name;
    private $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }
}

$emp = new Employee("Deep", 45000);

echo "Name: " . $emp->name;

?>
