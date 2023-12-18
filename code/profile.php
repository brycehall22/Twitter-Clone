<?php
session_start();
include 'connection.php';

$username = $_SESSION["username"];

$get_user = "SELECT user_id FROM users WHERE username = '$username';";
$result = $conn->query($get_user);
$row = $result->fetch_assoc();
$user_id = $row["user_id"];

// Retrieve the user's tweets from the database
$sql_tweets = "SELECT t.tweet_id, t.user_id, u.username, t.content, t.timestamp
    FROM tweets AS t
    LEFT JOIN retweets r ON t.tweet_id = r.tweet_id
    JOIN users u ON t.user_id = u.user_id
    INNER JOIN follows f ON (t.user_id = f.followee_id OR r.user_id = f.followee_id)
    WHERE f.follower_id = $user_id
    ORDER BY COALESCE(r.timestamp, t.timestamp) DESC
    LIMIT 50";
$result_tweets = $conn->query($sql_tweets);

// Query the database to get the number of followers
$sql = "SELECT COUNT(*) FROM follows WHERE followee_id='$user_id'";
$result = $conn->query($sql);
$num_followers = $result->fetch_row()[0];

// Query the database to get the number of users this user is following
$sql = "SELECT COUNT(*) FROM follows WHERE follower_id='$user_id'";
$result = $conn->query($sql);
$num_following = $result->fetch_row()[0];
?>

<!DOCTYPE html>
<html>

<head>
	<title><?php echo $username; ?>'s Profile</title>
	<link rel="stylesheet" type="text/css" href="profile.css">
</head>

<body>
	<div class="header">
		<h1><?php echo $username; ?>'s Profile</h1>
	</div>
	<div class="navbar">
		<a href="home.php">Home</a>
		<a href="profile.php">Profile</a>
	</div>
	<div class="main">
		<div class="profile-info">
			<p>Followers: <?php echo $num_followers; ?></p>
			<p>Following: <?php echo $num_following; ?></p>
			<p>Tweets:</p>
		</div>
		<div class="timeline">
			<?php
			if ($result_tweets->num_rows > 0) {
				while ($row_tweet = $result_tweets->fetch_assoc()) {
					$tweet_username = $row_tweet["username"];
					$tweet_content = $row_tweet["content"];
					$tweet_timestamp = $row_tweet["timestamp"];

					echo "<div class='tweet'>";
					echo "<p class='username'>$tweet_username</p>";
					echo "<p class='timestamp'>$tweet_timestamp</p>";
					echo "<p class='tweet-text'>$tweet_content</p>";
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