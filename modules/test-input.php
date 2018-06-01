<?php

  /*
   * Takes a potentially dangerous string input
   * and converts it a safe string output.
   *
   * For preventing XSS and SQL injection attacks.
   */
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>
