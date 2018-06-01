<?php

  /*
   * Generates a unique 10 character id using alphanumeric values.
   *
   * 42^10 (839,299,365,868,340,224) possible combinations.
   */
  function gen_uuid() {
    $values = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $uuid = "";
    for ($i=0; $i<10; $i++) {
      $uuid .= $values[rand(0, strlen($values)-1)];
    }
    return $uuid;
  }

?>
