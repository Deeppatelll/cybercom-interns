<?php
declare(strict_types=1);

// Function with strict types
function calculateTotal(float $price, int $qty): float {
    return $price * $qty;
}

$total = calculateTotal(200.50, 3);
echo "Total Amount: ₹" . $total . "<br><br>";


// Global variable
$company = "Urban Estate";

// Showing global vs local scope
function showScope() {
    global $company;
    echo "Global inside function: $company <br>";

    $city = "Ahmedabad";
    echo "Local inside function: $city <br>";
}

showScope();


// Example function
function getFullName(string $first, string $last): string {
    return $first . " " . $last;
}

echo "<br>Full Name: " . getFullName("John", "Doe");

echo "<br><br>";


// String functions
$text = "  Hello World  ";
$trimmed = trim($text);
$lower = strtolower($trimmed);
$final = str_replace("World", "PHP", $lower);

echo "Original: '$text' <br>";
echo "Trimmed: '$trimmed' <br>";
echo "Lowercase: '$lower' <br>";
echo "Replaced: '$final' <br><br>";


// Array functions
$numbers = [2, 4, 5, 8];

if (in_array(5, $numbers)) {
    echo "5 exists in array <br>";
}

array_push($numbers, 10);

$extra = [12, 14];
$merged = array_merge($numbers, $extra);

print_r($merged);

echo "<br><br>";
?>
