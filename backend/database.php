<?php
// Database configuration
$host = 'mysql.gb.stackcp.com:64464';  // Database host
$username = 'alyanka_whatsapp_db-353034374c42';  // Database username
$password = 'Ayupatel@$2310';  // Database password
$database = 'alyanka_whatsapp_db-353034374c42';  // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error . ' (Error Code: ' . $conn->connect_errno . ')');
}
?>
