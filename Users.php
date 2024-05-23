<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login or Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 400px;
      margin: 100px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"],
    button {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    #message {
      text-align: center;
      color: red;
    }

    #message.success {
      color: green;
    }
  </style>
</head>
<body>

<a href="index.html">index</a>
<a href="Updates.php">Emergencies& Events</a>
<a href="CampusNavigation.php">LIMKOKWING CAMPUSES</a>

<div class="container">
    <h2>Register</h2>
    <div id="register-form">
      <!-- Registration Form -->
      <form id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
          <label for="reg_username">Username:</label>
          <input type="text" id="reg_username" name="reg_username" required>
        </div>
        <div class="form-group">
          <label for="reg_password">Password:</label>
          <input type="password" id="reg_password" name="reg_password" required>
        </div>
        <button type="submit" name="register">Register</button>
      </form>
    </div>
</div>

<div class="container">
    <h2>Login</h2>
    <div id="login-form">
      <!-- Login Form -->
      <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" name="login">Login</button>
      </form>
    </div>
    <p id="message"><?php echo isset($message) ? $message : ''; ?></p>
</div>



<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
  $reg_username = $_POST["reg_username"];
  $reg_password = $_POST["reg_password"];

  // Check if the username already exists in the users table
  $sql = "SELECT * FROM users WHERE username = '$reg_username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Username already exists
    $message = "Username already exists";
  } else {
    // Insert new registration into the users table
    $hashed_password = password_hash($reg_password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES ('$reg_username', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
      $message = "Registration successful. You can now login.";
      echo "<script>alert('Registration successful. You can now login.');</script>";
      echo "<script>window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    } else {
      $message = "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Check if the user exists in the users table
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User exists, verify password
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
      // Password is correct, login successful
      session_start();
      $_SESSION["username"] = $username;
      header("Location: CampusNavigation.php"); // Redirect to the CampusNavigation page
      exit();
    } else {
      // Password is incorrect
      $message = "Invalid password";
    }
  } else {
    // User not found
    $message = "User not found";
  }
}

$conn->close();
?>

</body>
</html>
