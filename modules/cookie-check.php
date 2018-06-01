<?php
  include_once "test-input.php";

  /*
   * Returns user's cookie from db (if its valid)
   */
  function get_db_cookie() {
    $db_cookie_query = "
      SELECT *
      FROM `cookies`
      WHERE `value`='".test_input($_COOKIE["user"])."'
      AND `expiry` > '".gmdate("Y-m-d H:i:s", time())."'
    ";
    return db_select($db_cookie_query)[0];
  }


  /*
   * Returns user's cookie value from specified column
   * of database table (if cookie is valid).
   */
  function get_db_cookie_value($column) {
    if (isset($_COOKIE["user"])) {
      return get_db_cookie()[$column];
    } else {
      return '';
    }
  }


  /*
   * Checks if user's browser cookie matches the
   * user's cookie from the database.
   *
   * If cookie matches => runs given callback function
   */
  function cookie_check($callback_Function) {
    if (isset($_COOKIE["user"]) && test_input($_COOKIE["user"]) == get_db_cookie_value("value") && test_input($_COOKIE["user"]) != "") {
      $callback_Function();
    } else {
      // User does not have a valid cookie
      return false;
    }
  }

?>
