<?php  ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
  </head>
  <body>

    <div class='container'>

      <?php
        include_once "modules/db.php";
        include_once "modules/cookie-generation.php";
        include_once "modules/cookie-check.php";
        include_once "modules/gen-uuid.php";
        include_once "modules/test-input.php";
        require "components/main_nav.php";


        // define variables and set to empty values
        $usernameErr = $emailErr = $passwordErr = "";
        $username = $email = $password = "";


        // FORM HANDLING
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          // USERNAME
          if ( !isset($_POST["username"]) ) {
            $usernameErr = "Username is required";
          } else {
            $username = test_input($_POST["username"]);
            // Check it doesn't already exists in db
            $username_number_of = db_select("
              SELECT *
              FROM `users`
              WHERE `username` = '".$username."'
            ");
            $username_check = db_select("SELECT count(*) FROM `users` WHERE `username` = '".$username."'");
            $username_number_of = $username_check[0]['count(*)'];
            if ($username_number_of != 0) {
              $usernameErr = "Username already exists";
            }
          }

          // EMAIL
          if ( !isset($_POST["email"]) ) {
            $emailErr = "Email is required";
          } else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format";
            }
            // Check it doesn't already exists in db
            $email_check = db_select("SELECT count(*) FROM `users` WHERE `email` = '".$email."'");
            $email_number_of = $email_check[0]['count(*)'];
            if ($email_number_of != 0) {
              $emailErr = "Email already exists";
            }
          }
          // PASSWORD
          if ( !isset($_POST["password"]) ) {
            $password = "";
          } else {
            $password = test_input($_POST["password"]);
            // check if password has at least 8 characters
            if( strlen( $password) < 8 ) {
              $passwordErr = "Password must have at least 8 characters";
            }
          }

          // If there are any errors
          if ($usernameErr == '' && $emailErr == '' && $passwordErr == '') {

            $uuid = gen_uuid();
            $salt = md5($uuid);
            $password_hash = hash('sha512', $password.$salt);

            // Add to the database
            $query = "
              INSERT INTO `users`(`user_id`, `email`, `password_hash`, `salt`, `username`, `date_created`)
              VALUES ('".$uuid."', '".$email."', '".$password_hash."', '".$salt."', '".$username."', NOW())
            ";
            db_query( $query );

            // Set cookie and send to BROWSE
            $remember_me = test_input($_POST["remember_me"]);
            manageCookies($remember_me, $user_id);
            header("Location: browse.php");

          }
        }

      ?>

      <div class='content-wrap'>
        <h2>Sign Up to Palettes</h2>

        Note: Please avoid using real passwords.
        <br /><br />

        <form method="post" action="<?php echo test_input($_SERVER["PHP_SELF"]);?>">

          E-mail: <span class="error">* <?php echo $emailErr;?></span>
          <br /><input type="text" name="email" />
          <br /><br />

          Username: <span class="error">* <?php echo $usernameErr;?></span>
          <br /><input type="text" name="username" />
          <br /><br />

          Password: <span class="error">* <?php echo $passwordErr;?></span>
          <br /><input type="password" name="password" />
          <br /><br />

          <input type="checkbox" name="remember_me" />Keep me signed in
          <br /><br />

          <input type="submit" name="submit" value="Submit" />

        </form>
      </div>


      <?php require "components/footer.php"; ?>

    </div><!-- container END -->

  </body>
</html>
