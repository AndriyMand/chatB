<?php

//$con = mysqli_connect("127.0.0.1","testapp","testapp2017","testapp");

//$mysqli = new mysqli("127.0.0.1","testapp","testapp2017","testapp");

/*
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_interface";
*/
$servername = "localhost";
$username = "testapp";
$password = "testapp2017";

//$dbname = "testapp";
$dbname = "chat_bot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
