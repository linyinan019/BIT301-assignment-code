<?php
// process_signin.php

$servername = "localhost";
$username = "root";
$password = "";  // Change this to your actual database password
$dbname = "fyp"; //change it based on your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate input
  if (empty($email) || empty($password)) {
      echo "Email and Password are required fields.";
  } else {
      // Prepare a select statement
      $stmt = $conn->prepare("SELECT id, password FROM user_signup WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
          // Bind result variables
          $stmt->bind_result($id, $hashedPassword);
          $stmt->fetch();

          // Verify password
          if (password_verify($password, $hashedPassword)) {
              //echo "Login successful.";
               echo "<script type='text/javascript'>
                        alert('Login successful.');
                        window.location.href = 'userdashboard.php';
                      </script>";
              // start a session and store user information
              session_start();
              $_SESSION['user_id'] = $id;
              // Redirect to a protected page or dashboard
              //header("Location: index.php");
              //exit();
          } else {
              //echo "Invalid email or password.";
              echo "<script type='text/javascript'>
                        alert('Invalid email or password.');
                        window.location.href = 'index.php';
                      </script>";
          }
      } else {
          echo "No account found with that email.";
      }

      $stmt->close();
  }
}

$conn->close();

?>