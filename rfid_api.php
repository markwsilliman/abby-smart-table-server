<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

header('Content-Type: application/json');

if(!isset($_GET['rfid_api_guid']) || trim($_GET['rfid_api_guid']) == "") {
    echo json_encode(array("success" => false));
    return false;
}

$db = new db();
$guid = $db->cl($_GET['rfid_api_guid']);

$db->query("insert into rfid values(NULL,'$guid',NOW())");
echo json_encode(array("success" => true));