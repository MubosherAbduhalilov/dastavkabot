<?php
$servername = "sql12.freemysqlhosting.net";
$username = "sql12646623";
$password = "CThPbxe5m5";
$databasename = "sql12646623";

global $db;
setlocale(LC_ALL, "ru_RU.UTF8");

$db = new mysqli($servername, $username, $password, $databasename, 3306);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}