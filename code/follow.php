<?php
session_start();
include 'connection.php';

// Get the user ID of the logged in user
$logged_in_user = $_SESSION["username"];
$logged_in_user_id = "SELECT user_id FROM users WHERE username = '$logged_in_user';";
$result = $conn->query($logged_in_user_id);
$row = $result->fetch_assoc();
$logged_in_user_id = $row["user_id"];

// Get user ID of user to follow/unfollow
$user_id = $_POST["user_id"];

// Check if user is already following
$check_follow = $conn->prepare("SELECT * FROM follows WHERE follower_id = ? AND followee_id = ?");
$check_follow->bind_param("ii", $logged_in_user_id, $user_id);
$check_follow->execute();
$result = $check_follow->get_result();

// If user is already following, unfollow
if ($result->num_rows > 0) {
    $delete_follow = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followee_id = ?");
    $delete_follow->bind_param("ii", $logged_in_user_id, $user_id);
    $delete_follow->execute();
    echo "unfollowed";
    header("Location: user_list.php");
    exit;
}
// If user is not following, follow
else {
    $add_follow = $conn->prepare("INSERT INTO follows (follower_id, followee_id) VALUES (?, ?)");
    $add_follow->bind_param("ii", $logged_in_user_id, $user_id);
    $add_follow->execute();
    echo "followed";
    header("Location: user_list.php");
    exit;
}
?>
