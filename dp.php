<?php
// db.php
$servername = "localhost";
$username = "root";
$password = ""; // default is empty in XAMPP
$database = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
