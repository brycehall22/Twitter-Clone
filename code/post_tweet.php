<?php
    include 'connection.php'; 
    // Get the submitted tweet
    $username = $_POST["user_id"];
    $tweet = $_POST["tweet"];
    $timestamp =  date("Y-m-d") . date(" h:i:s"); 

    // Insert the tweet into the database
    $sql = "INSERT INTO tweets (user_id, content, timestamp)
    VALUES ('$username', '$tweet', '$timestamp')";

    if ($conn->query($sql) === TRUE) {
        echo "Tweet posted successfully";
        header("Location: home.php");
        exit;
    } else {
        echo "Error posting tweet: " . $conn->$error;
    }

    $conn->close();
