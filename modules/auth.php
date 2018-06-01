<?php
  include_once "db.php";
  include_once "cookie-check.php";
  include_once "test-input.php";

  /*
   * Sends user to the LOGIN page if they have not got a valid cookie.
   */
  if (!isset($_COOKIE["user"]) || test_input($_COOKIE["user"]) != get_db_cookie_value("value") || test_input($_COOKIE["user"]) == "") {
    header("Location: login.php");
  }

?>
