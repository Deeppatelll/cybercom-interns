<?php

class Employee {
    public $name;
    protected $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function getDetails() {
        return "Name: $this->name, Salary: $this->salary";
    }
}

class Manager extends Employee {
    public $department;

    public function __construct($name, $salary, $department) {
        parent::__construct($name, $salary);
        $this->department = $department;
    }

    public function getDetails() {
        return parent::getDetails() . ", Department: $this->department";
    }

    public function report() {
        return $this->name . " manages the " . $this->department . " department.";
    }
}

$mgr = new Manager("Deep", 75000, "IT");

echo $mgr->getDetails() . "<br>";
echo $mgr->report();

//task 2
class User {
    private $data = [];

    public function __set($property, $value) {
        $this->data[$property] = $value;
    }

    public function __get($property) {
        return "The property '$property' does not exist.";
    }

    public function __toString() {
        return json_encode($this->data);
    }
}

$u = new User();

$u->name = "Deep";
$u->email = "deep@example.com";

echo $u . "<br>";
echo $u->address;

?>
