<?php
// process_registration.php

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
  $userName = $_POST['userName'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validate input
  if (empty($userName) || empty($email) || empty($password)) {
      echo "Name, Email, and Password are required fields.";
  } else {

      // Hash the password before storing it
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Prepare an insert statement
      $stmt = $conn->prepare("INSERT INTO user_signup (userName, email, password) VALUES (?,?,?)");
      $stmt->bind_param("sss", $userName, $email, $hashedPassword);

      if ($stmt->execute()) {
        // set an Cookie or LocalStorage to mark registration successful
        echo '<script type="text/javascript">
                alert("register successfuly! and please login.");
                window.location.href = "index.php"; 
              </script>';
      } else {
          echo "Error: " . $stmt->error;
      }

      $stmt->close();
  }
}
$conn->close(); 

?>