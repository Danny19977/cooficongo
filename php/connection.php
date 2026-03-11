<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cooficongo";

// $servername = "localhost";
// $username = "n6i9y4c6sgdq";
// $password = "SS#Sfd%#5tAh";
// $dbname = "cafeblog";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
?>