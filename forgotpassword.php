<?php
//Connect to our database
include_once 'resource/Database.php';
include_once 'resource/utilities.php';
if(isset($_POST['passwordResetBtn'])){

// initialize an array that store the errors
$form_errors = array();
//Form validation
$required_fields = array("email",'new_password','confirm_password');
// check whether the required field is filled, if not,merge the error to $form_errors
$form_errors = array_merge($form_errors,check_empty_fields($required_fields));
//Fields that required checkng of minimum mysql_fetch_lengths
$fields_to_check_length = array('new_password'=>6,'confirm_password'=>6);
//call the function to check minimum required length and merge the return data into form_error array
$form_errors = array_merge($form_errors,check_min_length($fields_to_check_length));

//call the check email function to check the validity of email
$form_errors = array_merge($form_errors,check_email($_POST));
// check if error array is empty, if empty,process form data and  insert the record.
if(empty($form_errors)){
  //collect form data and process the form
  $email = $_POST['email'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if($new_password != $confirm_password){
    $result = flashMessage("New password and confirm password does not match!",'Fail');
  }

  else{
    try{
      //create select statement to verify if email address input exist in the database
      $sqlQuery = "SELECT * FROM users WHERE email = :email";
      //use PDO prepared to sanitize data
      $statement = $db->prepare($sqlQuery);
      //execute the query
      $statement->execute(array(':email'=>$email));
      //check if record exists
      if($statement->rowCount()==1){
          //hash the password
          $hashed_password = password_hash($new_password,PASSWORD_DEFAULT);
          //SQL statement to update the record
          $sqlQuery = "UPDATE users SET password = :password WHERE email = :email";
          //use PDO prepared to sanitize SQL statement
          $statement = $db->prepare($sqlQuery);
          //execute the sql statement
          $statement->execute(array(':password'=>$hashed_password,':email'=>$email));
          $result = flashMessage("Password reset is successful","Pass");


      }
      else{
            $result = flashMessage("The email does not exist in our database. Please try again!",'Fail');
      }
    }catch(PDOException $ex){
            $result = flashMessage("An error occur".$ex->getMessage());


    }

  }
}
else{
  if(count($form_errors)==1){
    $result = flashMessage("There was 1 error in the form","Fail");
  }
  else{
    $result = flashMessage("There were ".count($form_errors)." errors in the form",'Fail');
  }

}
}
 ?>
<?php
$page_title = 'User Authentication System - Forgot Password';
include_once 'partials/headers.php';

 ?>

 <div class="container">
   <section class="col col-lg-7">
     <h2>Password Reset Form</h2>
     <hr>

     <?php if(isset($result)) echo $result; ?>
     <?php if(!empty($form_errors)) echo show_errors($form_errors);?>
     <form action="" method="post">
     <div class="form-group">
       <label for="emailField">Email</label>
       <input type="text" class="form-control" id="emailField" name="email"  placeholder="example@abc.com">
     </div>
     <div class="form-group">
       <label for="newpasswordField">New Password</label>
       <input type="password" class="form-control" name="new_password" id="newpasswordField" placeholder="new password">
     </div>
     <div class="form-group">
       <label for="confirmpasswordField">Confirmed Password</label>
       <input type="password" class="form-control" name="confirm_password" id="confirmpasswordField" placeholder="confirm password">
     </div>


     <button type="submit" name="passwordResetBtn" class="btn btn-primary position">Reset Password</button>
   </form>
   <p> <a href="index.php">Back</a> </p>
   </section>


 </div>



<?php include_once 'partials/footer.php'; ?>
  </body>
</html>
