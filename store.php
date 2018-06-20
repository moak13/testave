<?php
session_start();
require_once('connect.php');
$db = new dbConnect();
$conn = $db->connect();

    echo 'We are coming soon..';
?>