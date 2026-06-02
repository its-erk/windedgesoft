<?php
// Database connection parameters
$servername = "127.0.0.1";
$db_username = "wg_admin";
$db_password = "XHmSOuWIZcIYVata";
$database_name = "windedgesoft";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $database_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>