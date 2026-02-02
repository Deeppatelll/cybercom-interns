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

?>
