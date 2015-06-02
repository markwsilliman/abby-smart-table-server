<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

header('Content-Type: application/json');

if(!isset($_GET['touch_api_x'])) {
    echo json_encode(array("success" => false));
    return false;
}

if(!isset($_GET['touch_api_y'])) {
    echo json_encode(array("success" => false));
    return false;
}

if(!isset($_GET['touch_api_type'])) {
    echo json_encode(array("success" => false));
    return false;
}

$db = new db();
$x = $_GET['touch_api_x'];
$y = $_GET['touch_api_y'];
$type = $_GET['touch_api_type'];

//all numbers?
$db->nc($x);
$db->nc($y);
$db->nc($type);

//qc values
if($x < 0 || $x > 1) {
    return false;
}

if($y < 0 || $y > 1) {
    return false;
}

if(!($type == 1 || $type == 2 || $type == 3)) {
    return false;
}

$db->query("insert into touch values(NULL,$x,$y,$type,NOW())");
echo json_encode(array("success" => true));