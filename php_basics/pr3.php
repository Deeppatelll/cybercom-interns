<?php

class Employee {

    public $name;
    private $salary;

    // Constructor
    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    // Getter
    public function getSalary() {
        return $this->salary;
    }

    // Setter with validation
    public function setSalary($amount) {
        if ($amount > 0) {
            $this->salary = $amount;
        } else {
            echo "Invalid salary amount.";
        }
    }
}

$text = "  Hello World  ";

// Trim spaces
$trimmed = trim($text);

// Convert to lowercase
$lowercase = strtolower($trimmed);

// Replace World with PHP
$finalText = str_replace("World", "PHP", $lowercase);

echo "Original String: '$text' <br>";
echo "After Trim: '$trimmed' <br>";
echo "Lowercase: '$lowercase' <br>";
echo "After Replace: '$finalText' <br><br>";



$numbers = [1, 3, 5, 7];

// Check if 5 exists
if (in_array(5, $numbers)) {
    echo "5 exists in the array <br>";
}

// Add a number
array_push($numbers, 9);

// Merge with another array
$moreNumbers = [11, 13];
$mergedArray = array_merge($numbers, $moreNumbers);

echo "Final Merged Array: <br>";
print_r($mergedArray);


/
echo "<br><br>";

$email = "  User@Example.com  ";
$cleanEmail = strtolower(trim($email));

echo "Clean Email: $cleanEmail <br>";

$files = ["image.jpg", "script.js", "style.css"];
$parts = explode(".", $files[0]);

echo "File Parts: <br>";
print_r($parts);

?>
$employee = new Employee("John Doe", 50000);