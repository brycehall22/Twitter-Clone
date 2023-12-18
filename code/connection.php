<?php
$host = "localhost:3306";
$username = "root";
$password = "Brycehall22";
$database = "twitter_clone";

// create connection
$conn = new mysqli($host, $username, $password, $database);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/*
// SQL query
$sql = "SELECT * FROM twitter_clone.tweets";

// execute query
$result = $conn->query($sql);

// fetch results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Tweet: " . $row["content"] . "<br>";
    }
} else {
    echo "0 results";
}

// close connection
$conn->close();
*/
?>
