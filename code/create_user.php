<!DOCTYPE html>
<html>

<head>
    <title>Create User</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php
    include 'connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $bio = $_POST['bio'];
        $location = $_POST['location'];
        $website = $_POST['website'];
        $email = $_POST['email'];
        $join_date = date('Y-m-d');

        $sql = "INSERT INTO users (username, password, email, bio, location, website, join_date)
			VALUES ('$username', '$password', '$email', '$bio', '$location', '$website', '$join_date')";
        if ($conn->query($sql) === TRUE) {
            header("location: login.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->$error;
        }
    }
    $conn->close();
    ?>
    <div class="header">
        <h1>Create User</h1>
    </div>
    <div class="main">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label>
            <input type="text" name="password" required><br>
            <label for="email">Email:</label>
            <input type="text" name="email" required><br>
            <label for="bio">Bio:</label>
            <input type="text" name="bio"></input><br>
            <label for="location">Location:</label>
            <input type="text" name="location"><br>
            <label for="website">Website:</label>
            <input type="text" name="website"><br>
            <input type="submit" value="Create User">
        </form>
    </div>
</body>

</html>