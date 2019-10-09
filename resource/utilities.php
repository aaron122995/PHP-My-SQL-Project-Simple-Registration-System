<?php
/**
 * @param $required_fields_array, n array containing the list of all required fields
 * @return array, containing all errors
 */


/* a function check whether the field is empty*/
 function check_empty_fields($required_fields_array){
   /* initialize a empty error array*/
   $form_errors = array();
   /* loop through the require field array and add it to error array is the field is empty*/
   foreach($required_fields_array as $name_of_field){
     if (!isset($_POST[$name_of_field])||$_POST[$name_of_field] == NULL){
        $form_errors[] = $name_of_field." is a required field";

    }
   }

   return $form_errors;

 }

 /**
  * @param $fields_to_check_length, an array containing the name of fields
  * for which we want to check min required length e.g array('username' => 4, 'email' => 12)
  * @return array, containing all errors
  */
  /* function to check length of input*/
  function check_min_length($fields_to_check_length){
    /* initialize an array to store error messages*/
    $form_errors = array();
    foreach($fields_to_check_length as $name_of_field => $minimum_length_required){
        $length_of_trim_field = strlen(trim($_POST[$name_of_field]));
        if($length_of_trim_field < $minimum_length_required){
          $form_errors[] = $name_of_field." is too short,must be {$minimum_length_required} characters long";
        }

    }

    return $form_errors;


  }


  /**
   * @param $data, store a key/value pair array where key is the name of the form control
   * in this case 'email' and value is the input entered by the user
   * @return array, containing email error
   */

   function check_email($data){
      /* initalize an array to store error messages*/
      $form_errors = array();
      $key = 'email';
      // check if the key email exists in data array
      if(array_key_exists($key,$data)){

        // check if the email has array_count_values
        if($_POST[$key] != null){
          // Remove all illegal characters from Email
          $key = filter_var($key,FILTER_SANITIZE_EMAIL);



          //check if input is a valid email address
          if(filter_var($_POST[$key],FILTER_VALIDATE_EMAIL)==false){
            $form_errors[] = $key." is not a valid email address";
          }



        }






      }
      return $form_errors;

   }
   /**
    * @param $form_errors_array, the array holding all
    * errors which we want to loop through
    * @return string, list containing all error messages
    */
   function show_errors($form_errors_array){
     $errors = "<p><ul style='color:red;'>";
     foreach($form_errors_array as $the_error){
       $errors.="<li>{$the_error}</li>";
     }

     $errors.="</ul></p>";

     return $errors;
   }

   function flashMessage($message,$passOrFail='Fail'){
     if($passOrFail === 'Pass'){
        $data = "<div class='alert alert-success'>
        {$message}
        </div>";






     }else{

        $data = "<div class='alert alert-danger'>
        {$message}
        </div>";



     }

     return $data;





   }
   // redirect Page
   function redirectTo($page){
      header("location: {$page}.php");

   }

   function checkDuplicateEntries($table,$column_name,$value,$db){
     // create a sql $sqlQuery
     try {
       $sqlQuery = "SELECT * FROM".$table. "WHERE".$column_name." =:$column_name";
       $statement = $db->prepare($sqlQuery);
       $statement->execute(array(":$column_name"=>$value));
       //see if any row return
       if($row = $statement->fetch()){return true;}
       return false;
     } catch (PDOException $ex) {
        //handle execption




     }







   }

   function rememberMe($user_id){
     $encryptCookieData = base64_encode('ahwfkjhawsfk{$user_id}');
     //cookie set to 30 days
     setcookie('rememberUserCookie',$encryptCookieData,time()+60*60*24*100,'/');



   }
   function isCookieValid($db){
     $isValid = false;
     if(isset($_COOKIE['rememberUserCookie'])){
       /* decode cookies and extract userid*/
       $decryptCookieData = base64_decode($_COOKIE['rememberUserCookie']);
       /*break the decryptCookieData in to an array*/
       $user_id = explode('ahwfkjhawsfk',$decryptCookieData);
       $userID = $user_id[1];

       /*check id extracted from cookie exist in database*/
       $sqlQuery = 'SELECT * FROM users WHERE id = :id';
       $statement = $db->prepare($sqlQuery);
       $statement->execute(array(":id"=>$userID));
       /*if theres any row return*/

       if($row = $statement->fetch()){
          $id = $row['username'];
          $username = $row['username'];
          /* create session user variable*/
          $_SESSION['id'] = $id;
          $_SESSION['username'] = $username;
          $isValid = true;




       }
       else{
         /*cookie id is invalid destroy session and logout users*/
         $isValid = false;
         signout();



       }

       return $isValid;




     }






   }

   function signout(){
     unset($_SESSION['username']);
     unset($_SESSION['id']);

     if(isset($_COOKIE['rememberUserCookie]'])){
       unset($_COOKIE['rememberUserCookie']);
       setcookie('rememberUserCookie',null,-1,'/');
       //destroy the cookies
     }
     session_destroy();
     session_regenerate_id(true);
     redirectTo('index');




   }

   function guard(){
     $isValid = true;
     $inactive = 60*2;
     $fingerprint =md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

     if(isset($_SESSION['fingerprint'])&& $_SESSION['fingerprint'] != $fingerprint){
       $isValid = false;
       signout();


     }
     else if(isset($_SESSION['last_active'])&&(time()-$_SESSION['last_active'])>$inactive&&isset($_SESSION['username'])){
       $isValid = false;
       signout();
     }
     else{$_SESSION['last_active'] = time();}
     return $isValid;
   }


























?>
