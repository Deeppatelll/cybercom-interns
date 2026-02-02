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
//task 2 

class Employee {
    public $name;
    private $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function getSalary() {
        return $this->salary;
    }

    public function setSalary($amount) {
        if ($amount > 0) {
            $this->salary = $amount;
        } else {
            echo "Invalid salary amount.";
        }
    }
}

$emp = new Employee("Deep", 40000);

echo "Salary: " . $emp->getSalary() . "<br>";

$emp->setSalary(50000);

echo "Updated Salary: " . $emp->getSalary();



?>
