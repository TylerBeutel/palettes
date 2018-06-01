<!DOCTYPE html>
<html>
  <head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script type='text/javascript' src='js/nav.js'></script>
  </head>
  <body>

    <div class='container'>

      <?php
        include_once "modules/db.php";
        include_once "modules/cookie-generation.php";
        include_once "modules/cookie-check.php";
        include_once "modules/test-input.php";

        // Define variables and set to empty values
        $usernameErr = $passwordErr = $authError = "";
        $username = $password = "";


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          // USERNAME
          if ( !isset($_POST["username"]) ) {
            $usernameErr = "Username is required";
          } else {
            $username = test_input($_POST["username"]);
          }
          // PASSWORD
          if ( !isset($_POST["password"]) ) {
            $passwordErr = "Password is required";
          } else {
            $password = test_input($_POST["password"]);
          }

          // Get user_id from given username
          $user_id_query = db_select("SELECT `user_id` FROM `users` WHERE `username` = '".$username."'");
          $user_id = "";
          if (sizeof($user_id_query) > 0) {
            $user_id = $user_id_query[0]["user_id"];
          }

          // Generate salt
          $salt = md5($user_id);

          // Returns 1 or 0 depending if there is a match
          $authQuery = "
            SELECT 1
            FROM `users`
            WHERE `username` = '".$username."'
            AND `password_hash` = '".hash('sha512', $password . $salt)."'
          ";

          // If there is a username/password match in database
          if ( db_select($authQuery) ) {
            $authError = "Username and password match.<br />";
            $remember_me = test_input($_POST["remember_me"]);
            manageCookies($remember_me, $user_id);
            header("Location: browse.php");
          } else {
            $authError = "Username or password incorrect.<br />";
            // TODO: Set attempt limit
            // TODO: Password recovery
          }

        }

        require "components/main_nav.php";
      ?>


      <div class='content-wrap'>
        <h2>Login to Palettes</h2>
        <form method="post" action="<?php echo test_input($_SERVER["PHP_SELF"]);?>">

          Username: <span class="error">* <?php echo $usernameErr;?></span>
          <br /><input type="text" name="username">
          <br /><br />

          Password: <span class="error">* <?php echo $passwordErr;?></span>
          <br /><input type="password" name="password">
          <br /><br />

          <span class="error"><?php echo $authError;?></span>
          <br />

          <input type="checkbox" name="remember_me" />Keep me signed in
          <br /><br />

          <input type="submit" name="submit" value="Submit">
        </form>

        <br />
        <p>Don't have an account? <a href='/signup.php'>Sign up here.</a></p>

      </div>

      <?php require "components/footer.php"; ?>

    </div><!-- container END -->

  </body>
</html>
