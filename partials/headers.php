<?php include_once 'resource/session.php';
      include_once 'resource/Database.php';
      include_once 'resource/utilities.php';
?>

<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



<title><?php if(isset($page_title)) echo $page_title; ?></title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">User Authentication</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto"> <i class="hide"><?php echo guard(); ?></i>
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <?php
            if(isset($_SESSION['username'])||isCookieValid($db)){
              echo'<li class="nav-item">
                <a class="nav-link" href="#">My Profile</a>
              </li>';

              echo'<li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
              </li>';


            }
            else {
            echo'  <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>';
            echo ' <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
              </li>';
            echo ' <li class="nav-item">
                <a class="nav-link" href="signup.php">Signup</a>
              </li>';
            echo ' <li class="nav-item">
                <a class="nav-link" href="#">Contact</a>
              </li>';




            }
         ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
      </form>
    </div>
  </nav>
