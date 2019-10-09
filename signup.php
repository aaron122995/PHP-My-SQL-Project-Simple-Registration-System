<?php
include_once 'resource/Database.php';
include_once 'resource/utilities.php';

//process the Form
if(isset($_POST['signupBtn'])){
  // initialize an array to store any error messages from the date_create_from_format
  $form_errors = array();
  // form validation
  $required_fields = array("email","username","password");
  // call the check empty function
  $form_errors = array_merge($form_errors,check_empty_fields($required_fields));
  // initialize minimum length
  $fields_to_check_length = array("username"=>4,'password'=>6);
  // call the check minimum length and merge the return data into $form_errors_array
  $form_errors = array_merge($form_errors,check_min_length($fields_to_check_length));
  // email validation, merge the return data into form_error array
  $form_errors = array_merge($form_errors,check_email($_POST));

  // collect data from form
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  if(checkDuplicateEntries("users","username",$username,$db)){
    $result = flashMessage("Username is already taken!");

  }
  elseif (checkDuplicateEntries("users","email",$email,$db)) {
    $result = flashMessage("Email is already taken!");
  }



  // check if error array is empty if yes process form data and insert record
  else if(empty($form_errors)){

      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
      $sqlInsert = "INSERT INTO users (username, email, password, join_date)VALUES (:username,:email,:password,now())";
      $statement = $db->prepare($sqlInsert);
      $statement->execute(array(':username' => $username,':email'=> $email,':password'=>$hashed_password));

      if($statement->rowCount()==1){
        $result = flashMessage("Registration Successful","Pass");
      }
    } catch (PDOException $ex) {
      $result = flashMessage("An error occured!: ".$ex->getMessage());
    }

    }
  else {
        if(count($form_errors)==1){$result = flashMessage("There was 1 error in the form<br>");}
        else{
            $result = flashMessage("There were " .count($form_errors). " errors in the form <br>");
        }
  }




  }


?>
<?php
$page_title = 'User Authentication System-Registration';
include_once 'partials/headers.php';
 ?>

 <div class="container">
   <section class="col col-lg-7">
     <h2>Registration From</h2><hr>

     <?php if(isset($result)) echo $result; ?>
     <?php if(!empty($form_errors)) echo show_errors($form_errors);?>
     <form action="" method="post">
     <div class="form-group">
       <label for="emailField">Email</label>
       <input type="text" class="form-control" id="emailField" name="email"  placeholder="example@abc.com">
     </div>
     <div class="form-group">
       <label for="usernameField">Username</label>
       <input type="text" class="form-control" name="username" id="usernameField" placeholder="Username">
     </div>
     <div class="form-group">
       <label for="passwordField">Password</label>
       <input type="password" class="form-control" name="password" id="passwordField" placeholder="Password">
     </div>


     <button type="submit" name="signupBtn" class="btn btn-primary position">Sign up</button>
   </form>
     <p><a href="index.php">Back</a></p>
   </section>
 </div>

<?php include_once 'partials/footer.php'; ?>




  </body>
</html>
