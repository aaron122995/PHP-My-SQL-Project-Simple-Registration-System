<?php

include_once 'resource/session.php';
include_once 'resource/Database.php';
include_once 'resource/utilities.php';


if(isset($_POST['loginBtn'])){
  // array to hold $errors
  $form_errors = array();

  // validate the form
  $required_fields = array('username','password');
  $form_errors = array_merge($form_errors,check_empty_fields($required_fields));

  if(empty($form_errors)){
    //collect form datab
    $username = $_POST['username'];
    $password = $_POST['password'];

    isset($_POST['remember'])? $remember = $_POST['remember']:$remember ='';


    // check if user exist in the database
    $sqlQuery = "SELECT * FROM users WHERE username = :username";
    // prepate $statement
    $statement = $db -> prepare($sqlQuery);
    $statement -> execute(array(':username' => $username));
    //if there is any row return
    while($row = $statement->fetch()){
      $id = $row['id'];
      $hashed_password = $row['password'];
      $username = $row['username'];

      if(password_verify($password,$hashed_password)){
          //create session variables
          $_SESSION['id'] = $id;
          $_SESSION['username'] = $username;

          $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
          $_SESSION['last_active'] = time();
          $_SESSION['fingerprint'] = $fingerprint;




          if($remember === 'yes'){
            rememberMe($id);
          }
          redirectTo("index");//redirect user to homepage




      }else{
           $result = flashMessage("Invalid username or password","Fail");
      }

    }



  }else{
      if(count($form_errors)==1){
        $result = flashMessage("There was one error in the form",'Fail');

      }else{
            $result = flashMessage("There were ".count($form_errors)." error in the form",'Fail');


      }
  }
}
 ?>

 <?php
 $page_title = "User Authentication System - Login Page";
 include_once 'partials/headers.php';


   ?>

<div class="container">
<section class="col col-lg-7">
  <h2>Login Form</h2><hr>
  <?php if(isset($result))echo $result;
        if(!empty($form_errors))echo show_errors($form_errors);
   ?>
  <form action="" method="post">
  <div class="form-group">
    <label for="usernameField">Username</label>
    <input type="text" class="form-control" id="usernameField" name="username"  placeholder="Username">
  </div>
  <div class="form-group">
    <label for="passwordField">Password</label>
    <input type="password" class="form-control" name="password" id="passwordField" placeholder="Password">
  </div>
  <div class="form-group form-check">

    <label class="form-check-label" for="exampleCheck1">

    <input name="remember"type="checkbox" value="yes" class="form-check-input" id="exampleCheck1">Remember Me!
  </label>
  </div>
  <a href="forgotpassword.php">Forgot Password?</a>
  <button type="submit" name="loginBtn" class="btn btn-primary position">Sign in</button>
</form>
<a href="index.php">Back</a>
</section>
</div>
<?php include_once 'partials/footer.php'; ?>
</body>
</html>
