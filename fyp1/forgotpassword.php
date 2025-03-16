<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";  // Change this to your actual database password
$dbname = "fyp"; //change it based on your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$error = array();

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$mode = "enter_email";
if(isset($_GET['mode'])){
  $mode = $_GET['mode'];
}

//something is posted
if(count($_POST) > 0){

  switch ($mode) {
    case 'enter_email':
      $email = $_POST['email'];
      //validate email
      if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $error[] = "Please enter a valid email";
      }elseif(!valid_email($email,FILTER_VALIDATE_EMAIL)){
        $error[] = "That email was not found";
      }else{

        $_SESSION['forgot']['email'] = $email;
        send_email($email);
        header("Location: forgotpassword.php?mode=enter_code");
        die;
      }
    break ;

    case 'enter_code':
      $code = $_POST['code'];
      $result = is_code_correct($code);

      if($result == "the code is correct."){
        
        $_SESSION['forgot']['code'] = $code;
        header("Location: forgotpassword.php?mode=enter_password");
        die;
      }else{
        $error[] = $result;
      }
    break ;

    case 'enter_password':
      $password = $_POST['password'];
      $password2 = $_POST['password2'];
      
      if($password !== $password2){
        $error[] = "Password do not match";
      }elseif(!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])){
        header("Location: index.php.");
        die;
      }else{
        save_password($password);
        if(isset($_SESSION['forgot'])){
          unset($_SESSION['forgot']);
        }
        
        header("Location: index.php.");
        die;
      }

    break ;

    default:
      
    break ;

  }

}

function send_email($email){
  global $conn;

  $expire = time() + (60 * 1);
  $code = rand(100000,999999);
  $email = addslashes($email);

  $query = "INSERT into codes (email,code,expire) VALUES ('$email','$code','$expire')";
  mysqli_query($conn,$query);

  //send email here
  //mail($mail,'reset password','your code is: '.$code);
}

function save_password($password){
  global $conn;

  $password = password_hash($password,PASSWORD_DEFAULT);
  $email = addslashes($_SESSION['forgot']['email']);

  $query = "UPDATE user_signup SET password = '$password' WHERE email ='$email' limit 1";
  mysqli_query($conn,$query);
}

function valid_email($email){
  global $conn;

  $email = addslashes($email);

  $query = "SELECT * from user_signup WHERE email ='$email' limit 1";
  $result = mysqli_query($conn,$query);
  if($result){
    if(mysqli_num_rows($result) > 0){
      return true;
    }
  }

  return false;

}


function is_code_correct($code){
  global $conn;

  $code = addslashes($code);
  $expire = time();
  $email = addslashes($_SESSION['forgot']['email']);

  $query = "SELECT * from codes WHERE code = '$code' && email = '$email' ORDER by id desc limit 1";
  $result = mysqli_query($conn,$query);
  if($result){
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
      if($row['expire'] > $expire){

        return "the code is correct.";
      }else{
        return "the code is expired.";
      }
    }else{
      return "the code is incorrect.";
    }
  }

  return "the code is incorrect.";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <title>Forgot Password Page </title>
</head>

<body>
  <header>
    <div class="logo">Rainyday</div>
    <nav>
      <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="#">Movies</a></li>
          <li><a href="#">History</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Profile</a></li>
      </ul>
    </nav>
  </header>
  <!--sign-up page-->
  <div class="container" id="container">
    <!--<div class="form-container sign-up">
      <form>
        <h1>Create Account</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-weixin"></i></a>
        </div>
        <span>Or use your email for registeration</span>
        <input type="text" placeholder="Name" required>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Password" required>
        <button>&nbsp;&nbsp;Reset&nbsp;&nbsp;</button>
        <button>Sign Up</button>
      </form>
    </div>-->

    <!--sign-in page-->

    <?php
    
      switch ($mode) {
        case 'enter_email':
          ?>
          <!-- input email and sent the code-->
          <div class="form-container forgot-password">
          <form method="post" action="forgotpassword.php?mode=enter_email">
            <h1>Find the Password</h1>
            <div class="social-icons">
              <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-weixin"></i></a>
            </div>
            <span>Enter Email and find the password...</span>
            <span style="font-size: 12px; color:red;"><?php
            foreach ($error as $err) {
              # code...
              echo $err . "<br>";
            }
            ?></span>
            <br>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <br>
            <button type="submit">&nbsp;&nbsp;&nbsp;NEXT&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <a href="index.php" class="button">&nbsp;&nbsp;Login&nbsp;&nbsp;</a>
          </form>
        </div>
        <?php
        break ;
    
        case 'enter_code':
          ?>
          <!-- input code and to reset password-->
          <div class="form-container forgot-password">
          <form method="post" action="forgotpassword.php?mode=enter_code">
            <h1>Find the Password</h1>
            <div class="social-icons">
              <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-weixin"></i></a>
            </div>
            <span>Enter the Code sent to your email.</span>
            <span style="font-size: 12px; color:red;"><?php
            foreach ($error as $err) {
              # code...
              echo $err . "<br>";
            }
            ?></span>
            <br>
            <input type="textbox" id="code" name="code" placeholder="123456" required>
            <br>
            <button type="submit">&nbsp;&nbsp;&nbsp;NEXT&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <button type="reset">&nbsp;&nbsp;RESET&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <a href="index.php" class="button">&nbsp;&nbsp;Login&nbsp;&nbsp;</a>
          </form>
        </div>
        <?php
        break ;
    
        case 'enter_password':
          ?>
          <!-- input new password-->
          <div class="form-container forgot-password">
          <form method="post" action="forgotpassword.php?mode=enter_password">
            <h1>Find the Password</h1>
            <div class="social-icons">
              <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
              <a href="#" class="icon"><i class="fa-brands fa-weixin"></i></a>
            </div>
            <span>Enter your new password.</span>
            <span style="font-size: 12px; color:red;"><?php
            foreach ($error as $err) {
              # code...
              echo $err . "<br>";
            }
            ?></span>
            <br>
            <input type="textbox" id="code" name="password" placeholder="Password" required>
            <input type="textbox" id="code" name="password2" placeholder="Retype password" required>
            <br>
            <button type="submit">&nbsp;&nbsp;&nbsp;NEXT&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <button type="reset">&nbsp;&nbsp;RESET&nbsp;&nbsp;&nbsp;&nbsp;</button>
            <a href="index.html" class="button">&nbsp;&nbsp;Login&nbsp;&nbsp;</a>
          </form>
        </div>
        <?php
        break ;
    
        default:
          
        break ;
    
      }

    ?>

    <!--<div class="form-container forgot-password">
      <form action="forgotpassword.php" method="post">
        <h1>Find the Password</h1>
        <div class="social-icons">
          <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-weixin"></i></a>
        </div>
        <span>Enter Email and find the password...</span>
        <br>
        <input type="email" id="email" name="email" placeholder="Email" required>
        <br>
        <button type="submit">&nbsp;&nbsp;&nbsp;NEXT&nbsp;&nbsp;&nbsp;&nbsp;</button>
        <a href="index.html" class="button">&nbsp;&nbsp;Login&nbsp;&nbsp;</a>
      </form>
    </div>-->

  </div>
  <footer>
    <p>&copy; 2136 StreamLab</p>
  </footer>

  <script src="script.js"></script>
</body>
</html>