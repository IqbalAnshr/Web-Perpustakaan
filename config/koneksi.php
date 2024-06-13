<?php

$config = require_once 'config.php';

$host = $config['db']['host'];
$user = $config['db']['username'];
$pass = $config['db']['password'];
$db = $config['db']['database'];

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

function closeConnection() {
  global $conn;
  mysqli_close($conn);
}