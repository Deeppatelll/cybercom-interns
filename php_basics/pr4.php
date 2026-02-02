<?php
declare(strict_types=1);

function calculateTotal(float $price, int $qty): float {
    return $price * $qty;
}

$total = calculateTotal(250.75, 4);
echo "Total Amount: ₹" . $total . "<br><br>";

$company = "Urban Estate";

function showScope() {
    global $company;
    echo "Global variable inside function: $company <br>";
    $city = "Ahmedabad";
    echo "Local variable inside function: $city <br>";
}

showScope();

function getFullName(string $first, string $last): string {
    return $first . " " . $last;
}

echo "<br>Full Name: " . getFullName("John", "Doe");

echo "<br><br>";

$text = "  Hello World  ";
$trimmed = trim($text);
$lower = strtolower($trimmed);
$final = str_replace("World", "PHP", $lower);

echo "Original: '$text' <br>";
echo "After Trim: '$trimmed' <br>";
echo "Lowercase: '$lower' <br>";
echo "After Replace: '$final' <br><br>";

$numbers = [2, 4, 5, 8];

if (in_array(5, $numbers)) {
    echo "5 exists in the array <br>";
}

array_push($numbers, 10);

$extra = [12, 14];
$merged = array_merge($numbers, $extra);

echo "Merged Array: <br>";
print_r($merged);

echo "<br><br>";

$email = "  User@Example.com  ";
$cleanEmail = strtolower(trim($email));
echo "Clean Email: $cleanEmail <br>";

$files = ["image.jpg", "script.js", "style.css"];
$parts = explode(".", $files[0]);
print_r($parts);
?>
