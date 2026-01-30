<?php

$marks = 78;

if ($marks >= 90) {
    echo "Grade: A";
} 
elseif ($marks >= 75) {
    echo "Grade: B";
} 
elseif ($marks >= 50) {
    echo "Grade: C";
} 
else {
    echo "Grade: Fail";
}
$marks = 78;

switch (true) {

    case ($marks >= 90):
        echo "Grade: A";
        break;

    case ($marks >= 75):
        echo "Grade: B";
        break;

    case ($marks >= 50):
        echo "Grade: C";
        break;

    default:
        echo "Grade: Fail";
}


for ($i = 1; $i <= 10; $i++) {
    echo "5 x $i = " . (5 * $i) . "<br>";
}



$k=1;

while($k<10)
{
    echo $k;
    $k++;
}



$percentage = 42;

if ($percentage >= 75) {
    echo "Distinction";
} elseif ($percentage >= 60) {
    echo "First Class";
} elseif ($percentage >= 40) {
    echo "Pass";
} else {
    echo "Fail";
}


//even numbers
for ($i = 2; $i <= 20; $i += 2) {
    echo $i . "<br>";
}

//
$role = "editor";

switch ($role) {
    case "admin":
        echo "Full Access";
        break;

    case "editor":
        echo "Edit Content Only";
        break;

    case "viewer":
        echo "View Only";
        break;

    default:
        echo "No Access";
}


?>
