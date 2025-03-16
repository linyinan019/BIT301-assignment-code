<?php


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login in page <!-- Here will put the System name --></title>
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="wrapper">

    <span class="bg-animate"></span>
    <span class="bg-animate2"></span>

    <!--  This is a login section -->
    <div class="form-box login">
      <h2 class="animation" style="--i:0; --j:21">Login</h2>
      <form action="process-signin.php" method="post"><!-- action to link the PHP and MySQL -->
        <div class="input-box animation" style="--i:1; --j:22" id="#">
          <input type="email" id="email" name="email" required>
          <label>Email</label>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="input-box animation" style="--i:2; --j:23" id="#">
          <input type="password" id="password" name="password" required>
          <label>Password</label>
          <i class='bx bxs-lock-open'></i>
        </div>
        <div class="logreg-link animation" style="--i:5; --j:26">
          <p><a href="forgotpassword.php" class="reset-link">Forgot your passwoard?</a></p>
        </div>
        <button type="submit" class="btn animation" style="--i:3; --j:24">Login</button>
        <div class="logreg-link animation" style="--i:4; --j:25">
          <p>Don't have an account? <a href="#" class="register-link">Sign Up</a></p>
        </div>
        
      </form> 
    </div>

    <!--This is login page the right side the content -->
    <div class="info-text login">
      <h2 class="animation" style="--i:0; --j:20">Welcome Back!</h2>
      <p class="animation" style="--i:1; --j:21">Qucik to login and go explore~~~</p>
    </div>

      <!--  This is a Sign Up section -->
    <div class="form-box register" >
      <h2 class="animation" style="--i:17; --j:0">Sign Up</h2>
      <form action="process-signup.php" method="post"><!-- action to link the PHP and MySQL -->
        <div class="input-box animation" style="--i:18; --j:1" id="#">
          <input type="text" id="name" name="userName" required>
          <label>Username</label>
          <i class='bx bxs-user-circle'></i>
        </div>
        <div class="input-box animation" style="--i:19; --j:2" id="#">
          <input type="email" id="email" name="email" required>
          <label>Email</label>
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="input-box animation" style="--i:20; --j:3" id="#">
          <input type="password" id="password" name="password" required>
          <label>Password</label>
          <i class='bx bxs-lock-open'></i>
        </div>
        <button type="submit" class="btn animation" value="Signup" style="--i:21; --j:4">Sign Up</button>
        <div class="logreg-link animation" style="--i:22; --j:5">
          <p>Already have an account? <a class="login-link">Login</a></p>
        </div>
      </form>
    </div>

    <!--This is Sign Up page the left side the content -->
    <div class="info-text register">
      <h2 class="animation" style="--i:17; --j:0">Hi There!</h2>
      <p class="animation" style="--i:18; --j:1">Just easy fill the information and qucik to sign Up :D</p>
    </div>

  </div>

  <script src="script.js"></script>

</body>
</html>