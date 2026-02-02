<?php
declare(strict_types=1);

function calculateTotal(float $price, int $qty): float {
    return $price * $qty;
}

$total = calculateTotal(150.75, 2);
echo "Total Amount: ₹" . $total . "<br><br>";

$company = "Urban Estate";

function showScope() {
    global $company;
    echo "Global inside function: $company <br>";

    $city = "Ahmedabad";
    echo "Local inside function: $city <br>";
}

showScope();

echo "<br>";

function getFullName(string $first, string $last): string {
    return $first . " " . $last;
}

echo getFullName("John", "Doe");
//task 2 
$text = "  Hello World  ";

$trimmed = trim($text);
$lower = strtolower($trimmed);
$final = str_replace("World", "PHP", $lower);

echo "Original: '$text' <br>";
echo "Trimmed: '$trimmed' <br>";
echo "Lowercase: '$lower' <br>";
echo "Replaced: '$final' <br><br>";

$numbers = [1, 3, 5, 7];

if (in_array(5, $numbers)) {
    echo "5 exists in the array <br>";
}

array_push($numbers, 9);

$extra = [11, 13];
$merged = array_merge($numbers, $extra);

print_r($merged);

echo "<br><br>";

$email = "  User@Example.com  ";
$cleanEmail = strtolower(trim($email));
echo "Clean Email: $cleanEmail <br>";

$files = ["image.jpg", "script.js", "style.css"];
$parts = explode(".", $files[0]);
print_r($parts);

?>
