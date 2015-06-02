<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

$db = new db();
$db->query('delete from touch');
echo "delete from touch";