<?php
  include_once 'modules/db.php';
  include_once "modules/test-input.php";
  $complete = false;


  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get palette id
    $palette_id = test_input( $_POST['palette_id'] );

    // Get user id
    $user_id = '';
    if (isset($_COOKIE['user'])) {
      $db_cookie_query = "
        SELECT *
        FROM `cookies`
        WHERE `value`='".test_input($_COOKIE["user"])."'
        AND `expiry` > '".gmdate("Y-m-d H:i:s", time())."';
      ";
      $user_id = db_select($db_cookie_query)[0]['user_id'];
    }

    // If user has a user_id
    if ($user_id != '') {

      // Check if favourite already exists
      $exists = db_select("
        SELECT count(*)
        FROM `favourited_palettes`
        WHERE `user_id` = '".$user_id."'
        AND `palette_id` = '".$palette_id."'
      ")[0]['count(*)'];

      $fav_query = "";
      $unfav_query = "";

      // If it does NOT EXISTS in db --> ADD IT
      if ( (int)$exists == 0 ) {
        $fav_query = "
          INSERT INTO `favourited_palettes`(`user_id`, `palette_id`, `date_added`)
          VALUES ('".$user_id."', '".$palette_id."', NOW())
        ";
        db_query($fav_query);
      }
      // If it DOES EXISTS in db --> DELETE IT
      else {
        $unfav_query = "
          DELETE FROM `favourited_palettes`
          WHERE `user_id` = '".$user_id."'
          AND `palette_id` = '".$palette_id."'
        ";
        db_query($unfav_query);
      }

      // Mark as complete
      $complete = true;
    }



    // Get the number of likes
    $q = "
      SELECT count(*)
      FROM `favourited_palettes`
      WHERE `palette_id` = '".$palette_id."'
    ";
    $number_of_favs = db_select($q)[0]['count(*)'];

    // Update the number of likes in palettes
    db_query("
      UPDATE `palettes`
      SET `favourites`= '".$number_of_favs."'
      WHERE `palette_id`= '".$palette_id."'
    ");

    $return_arr[] = array(
      "favs" => $number_of_favs,
      "complete" => $complete
    );

    echo json_encode($return_arr);

  }
?>
