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
        <a onclick="window.location.href='logout.php'" class="button">Log out</a>
    </div>
    <div class="main">
        <div class="tweet-form">
            <form method="POST" action="post_tweet.php">
                <label for="username">Username:</label>
                <input type="text" name="user_id" required><br>
                <label for="tweet">Tweet:</label>
                <input type="text" name="tweet" maxlength="280" required><br>
                <input type="submit" value="Tweet">
            </form>
        </div>
        <div class="timeline">
            <?php
            session_start();
            $logged_in_user = $_SESSION["username"];
            $get_user = "SELECT user_id FROM users WHERE username = '$logged_in_user';";
            $result = $conn->query($get_user);
            $row = $result->fetch_assoc();
            $user_id = $row["user_id"];
            $sql = "SELECT t.tweet_id, t.user_id, u.username, t.content, t.timestamp
                FROM tweets AS t
                LEFT JOIN retweets r ON t.tweet_id = r.tweet_id
                JOIN users u ON t.user_id = u.user_id
                INNER JOIN follows f ON (t.user_id = f.followee_id OR r.user_id = f.followee_id)
                WHERE f.follower_id = $user_id
                ORDER BY COALESCE(r.timestamp, t.timestamp) DESC
                LIMIT 50";
            $result = $conn->query($sql);
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $action = $_POST["action"];
                $tweet_id = $_POST["tweet_id"];

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["action"]) && isset($_POST["tweet_id"])) {
                        $action = $_POST["action"];
                        $tweet_id = $_POST["tweet_id"];

                        if ($action == "favorite") {
                            $check_favorite = "SELECT * FROM favorites WHERE user_id = $user_id AND tweet_id = $tweet_id;";
                            $favorite_result = $conn->query($check_favorite);

                            if ($favorite_result->num_rows > 0) {
                                $unfavorite = "DELETE FROM favorites WHERE user_id = $user_id AND tweet_id = $tweet_id;";
                                $conn->query($unfavorite);
                            } else {
                                $favorite = "INSERT INTO favorites (user_id, tweet_id) VALUES ($user_id, $tweet_id);";
                                $conn->query($favorite);
                            }
                        } elseif ($action == "retweet") {
                            $check_retweet = "SELECT * FROM retweets WHERE user_id = $user_id AND tweet_id = $tweet_id;";
                            $retweet_result = $conn->query($check_retweet);

                            if ($retweet_result->num_rows > 0) {
                                $unretweet = "DELETE FROM retweets WHERE user_id = $user_id AND tweet_id = $tweet_id;";
                                $conn->query($unretweet);
                            } else {
                                $retweet = "INSERT INTO retweets (user_id, tweet_id) VALUES ($user_id, $tweet_id);";
                                $conn->query($retweet);
                            }
                        }
                    } else {
                        $tweet = $_POST["tweet"];
                        $insert_tweet = "INSERT INTO tweets (user_id, content) VALUES ($user_id, '$tweet');";
                        $conn->query($insert_tweet);
                    }
                }
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["tweet_id"];
                    $username = $row["username"];
                    $tweet = $row["content"];
                    $timestamp = $row["timestamp"];

                    echo "<div class='tweet'>";
                    echo "<p class='username'>$username</p>";
                    echo "<p class='tweet-text'>$tweet</p>";
                    echo "<p class='timestamp'>$timestamp</p>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='tweet_id' value='$id'>";
                    echo "<input type='hidden' name='action' value='favorite'>";
                    echo "<input type='submit' value='Favorite'>";
                    echo "</form>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='tweet_id' value='$id'>";
                    echo "<input type='hidden' name='action' value='retweet'>";
                    echo "<input type='submit' value='Retweet'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No tweets to display.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>