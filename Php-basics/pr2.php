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

?>
