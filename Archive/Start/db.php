<?php

$servername = "localhost";
$username = "testapp";
$password = "testapp2017";
$dbname = "testapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo 'Успешно соединились';

if ($conn->query("CREATE TABLE testtable") === TRUE) {
    printf("Таблица testtable успешно создана.\n");
}

$result = $conn->query("SHOW TABLES");
var_dump($result->fetch_array(MYSQLI_NUM));
while ($row = mysql_fetch_row($result)) {
    echo "Таблица: {$row[0]}\n";
}

mysql_free_result($result);