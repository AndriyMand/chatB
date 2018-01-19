<?php

$baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$baseUrl = substr($actual_link, 0, strrpos($actual_link, "/")) . '/';

$servername = "localhost";
$username = "testapp";
$password = "testapp2017";
$dbname = "chat_bot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connected successful<br>";
}
//include $baseUrl . 'db_func.php';
include 'db_func.php';
echo "<pre>";


$sql = "delete from attach";
$result = $conn->query($sql);
if (!$result) {
    echo "Ошибка базы, не удалось получить список таблиц\n";
    echo 'Ошибка MySQL: ' . mysql_error();
    exit;
}


/*
echo "<pre>";
$result = $conn->query(selectAll('botmessage', 'keyboard_name'));
if ($result->num_rows > 0) { 
	while($tagset = $result->fetch_assoc()) {
		var_dump($tagset);
	}
}
*/


$sql = "SHOW TABLES";	
$result = $conn->query($sql);
if (!$result) {
    echo "Ошибка базы, не удалось получить список таблиц\n";
    echo 'Ошибка MySQL: ' . mysql_error();
    exit;
}
while ($row = $result->fetch_row()) {
    echo "Таблица: {$row[0]}\n";
}

$table = 'blocks';

echo "<br><h3>" . $table . "</h3>";

$result = $conn->query("SHOW COLUMNS FROM " . $table);
if (!$result) {
    echo 'Ошибка при выполнении запроса: ' . mysql_error();
    exit;
}
echo "<br>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . "<br>";
    }
}

//$sql = "UPDATE botmessage SET keyboard_name='' WHERE keyboard_name is not null;";
//$sql = "DELETE FROM keyboard where name='';";
//$result = $conn->query($sql);



/*
$result = $conn->query("SELECT * FROM botmessage");

echo "<br>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
*/
//$result = $conn->query("SELECT * FROM keyboard where id=2")->fetch_assoc();
//echo "<br>_________________________<br>";
//print_r($result);

echo "<pre>";


$keyboardBlocksNames = $conn->query(selectAll($table, '*'));
	if ($keyboardBlocksNames->num_rows > 0) { 
		while($row = $keyboardBlocksNames->fetch_assoc()) {
			var_dump($row);
		} 
	}

//$sql = "ALTER TABLE keyboard ADD block varchar(100);";
//$result = $conn->query($sql);
if (!$result) {
    echo 'Ошибка MySQL: ' . $conn->error;
    exit;
}

