<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "tv_shows_db"; // Change this if your database name is different

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

