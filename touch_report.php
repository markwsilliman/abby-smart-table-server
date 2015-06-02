<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

$db = new db();
$result = $db->query("select * from touch order by ID desc limit 500");
echo "<table border='1'>";
echo "<tr><th>ID</th><th>X</th><th>Y</th><th>TYPE</th><th>DATE</th></tr>";
while($row = $db->fetch_array($result)) {
    echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['x_percent'] . "</td><td>" . $row['y_percent'] . "</td><td>" . type_to_english($row['type_percent']) . "</td><td>" . $row['date_created'] . "</td></tr>";
}
echo "</table>";

//type_to_english
function type_to_english($type) {
    if($type == "1") return "down";
    if($type == "2") return "up";
    if($type == "3") return "move";
    return $type;
}
//end type_to_english