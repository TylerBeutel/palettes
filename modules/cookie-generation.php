<?php

  /*
   * Sets the cookie both locally to the browser
   * and to the database.
   */
  function setCookies($cookie_name, $user_id, $cookie_value, $cookie_expiry) {
    setcookie($cookie_name, $cookie_value, $cookie_expiry, "/");
    db_query("
      INSERT INTO `cookies`(`user_id`, `value`, `expiry`)
      VALUES ('".$user_id."', '".$cookie_value."', '".gmdate("Y-m-d H:i:s", $cookie_expiry)."')
      ON DUPLICATE KEY UPDATE `expiry`='".gmdate("Y-m-d H:i:s", $cookie_expiry)."', `value`='".$cookie_value."'
    ");
  }


/*
 * Takes information to set the cookie with
 * and runs setCookie with the correct duration
 * depending on if user selected 'remember me'
 */
  function manageCookies($remember_me, $user_id) {
    $cookie_name = 'user';
    $cookie_expiry = time();

    if ($remember_me) {
      $cookie_expiry += (365*24*60*60);
    } else {
      $cookie_expiry += (60*60);
    }

    $cookie_value = md5($user_id . gmdate("Y-m-d H:i:s", $cookie_expiry));
    setCookies($cookie_name, $user_id, $cookie_value, $cookie_expiry);
  }

?>
