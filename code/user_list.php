<!DOCTYPE html>
<html>

<head>
    <title>Twitter Web Interface</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include 'connection.php'; ?>
    <div class="header">
        <h1>Twitter Web Interface</h1>
    </div>
    <div>
        <a href="home.php" class="button">Home</a>
        <a href="profile.php" class="button">Profile</a>
        <a href="user_list.php" class="button">User List</a>
    </div>
    <div class="main">
        <div>
            <?php
            session_start();
            include('connection.php');

            // Get the user ID of the logged in user
            $logged_in_user = $_SESSION["username"];
            $logged_in_user_id = "SELECT user_id FROM users WHERE username = '$logged_in_user';";
            $result = $conn->query($logged_in_user_id);
            $row = $result->fetch_assoc();
            $logged_in_user_id = $row["user_id"];

            // Query the users table for all users except the logged in user
            $query = "SELECT * FROM users WHERE user_id != '$logged_in_user_id'";
            $result = mysqli_query($conn, $query);

            // Display the list of users with a follow button next to each one
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p>" . $row['username'] . "</p>";
                $user_id = $row['user_id'];

                // Check if the logged in user is already following this user
                $query2 = "SELECT * FROM follows WHERE follower_id = '$logged_in_user_id' AND followee_id = '$user_id'";
                $result2 = mysqli_query($conn, $query2);
                if (mysqli_num_rows($result2) == 0) {
                    // If not, display a follow button
                    echo "<form method='post' action='follow.php'>";
                    echo "<input type='hidden' name='user_id' value='" . $logged_in_user_id . "'>";
                    echo "<input type='hidden' name='follows_user_id' value='" . $user_id . "'>";
                    echo "<button type='submit'>Follow</button>";
                    echo "</form>";
                } else {
                    // If so, display an unfollow button
                    echo "<form method='post' action='follow.php'>";
                    echo "<input type='hidden' name='user_id' value='" . $logged_in_user_id . "'>";
                    echo "<input type='hidden' name='follows_user_id' value='" . $user_id . "'>";
                    echo "<button type='submit'>Unfollow</button>";
                    echo "</form>";
                }
            }
            ?>
        </div>
    </div>
</body>

</html>