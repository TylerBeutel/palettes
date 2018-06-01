<?php
  include_once 'modules/db.php';
  include_once 'modules/test-input.php';

  // If cookie is set, delete it from db and browser
  if(isset($_COOKIE['user'])) {
    db_query("DELETE FROM `cookies` WHERE `value` = '".test_input($_COOKIE['user'])."';");
    setcookie("user", "", time() - 3600);
    header("Location: logout.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Log Out</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
  </head>
  <body>

    <div class='container'>

      <?php require "components/main_nav.php"; ?>

      <div class='content-wrap'>
        <h2>You have successfully logged out of Palettes</h2>

        <p>Want to sign back in? <a href='/login.php'>Login here.</a></p>
        <p>Don't have an account? <a href='/signup.php'>Sign up here.</a></p>
      </div>

      <?php require "components/footer.php"; ?>

    </div><!-- container END -->

  </body>
</html>
