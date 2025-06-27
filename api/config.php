<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'Nadroj09!';
$dbname = 'jwcreative';

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}