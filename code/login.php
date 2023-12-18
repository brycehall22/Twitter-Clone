<?php
session_start();
include 'connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $_SESSION['username'] = $username;
    header('Location: home.php');
    exit();
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <h1>Login</h1>
  <form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>
    <label for="password">Password:</label>
    <input type="text" name="password" required><br>
    <input type="submit" value="Login">
  </form>
  <form method="get" action="create_user.php">
    <input type="submit" value="Create Account">
  </form>
  <?php echo $error; ?>
</body>

</html>