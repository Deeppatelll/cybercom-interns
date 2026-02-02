<?php

$marks = 78;

if ($marks >= 80) {
    $grade = "A";
} elseif ($marks >= 60) {
    $grade = "B";
} elseif ($marks >= 40) {
    $grade = "C";
} else {
    $grade = "Fail";
}

echo "Marks: $marks <br>";
echo "Grade: $grade <br><br>";

$day = "Sat";

switch ($day) {
    case "Sat":
    case "Sun":
        echo "Weekend";
        break;

    case "Mon":
    case "Tue":
    case "Wed":
    case "Thu":
    case "Fri":
        echo "Weekday";
        break;

    default:
        echo "Invalid day";
}

//task 2 
for ($i = 1; $i <= 10; $i++) {
    echo "5 x $i = " . (5 * $i) . "<br>";
}

echo "<br>";

$student = [
    "name" => "Deep",
    "age" => 21,
    "course" => "Computer Science"
];

foreach ($student as $key => $value) {
    echo "$key: $value <br>";
}

echo "<br>";

$count = 1;

while ($count <= 5) {
    echo "Count: $count <br>";
    $count++;
}



?>
