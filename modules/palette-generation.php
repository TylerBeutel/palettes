<?php
  include_once "modules/cookie-check.php";
  include_once "modules/db.php";
  include_once "modules/test-input.php";


  /*
   * Takes an array of palettes and turns them
   * into HTML ready for viewing.
   */
  function generate_palettes_html($palettes) {

    // Get user id from cookies
    $user_id = get_db_cookie_value('user_id');

    $html = "";
    foreach ($palettes as $palette) {

      // Determine whether user has favourited any palettes
      $faved = "";
      if ($user_id != '') {
        if ($palette['user_id'] == $user_id) {
          $faved = " faved";
        }
      }
      // Generate and append HTML
      $html .= "
        <div class='palette-result'>
          <a class='palette-container' href='/palette.php?id=".$palette['palette_id']."'>
            <div class='palette-colour' style='background-color:#".$palette['colour_1']."'></div>
            <div class='palette-colour' style='background-color:#".$palette['colour_2']."'></div>
            <div class='palette-colour' style='background-color:#".$palette['colour_3']."'></div>
            <div class='palette-colour' style='background-color:#".$palette['colour_4']."'></div>
            <div class='palette-colour' style='background-color:#".$palette['colour_5']."'></div>
          </a>
          <p>".$palette['title']."
              <a class='fav".$faved."' value='".$palette['palette_id']."'>
                <span>".$palette['favourites']."</span>
                <svg xmlns=/'http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Capa_1' x='0px' y='0px' viewBox='0 0 492.719 492.719' style='enable-background:new 0 0 492.719 492.719;' xml:space='preserve'>
              		<path d='M492.719,166.008c0-73.486-59.573-133.056-133.059-133.056c-47.985,0-89.891,25.484-113.302,63.569    c-23.408-38.085-65.332-63.569-113.316-63.569C59.556,32.952,0,92.522,0,166.008c0,40.009,17.729,75.803,45.671,100.178    l188.545,188.553c3.22,3.22,7.587,5.029,12.142,5.029c4.555,0,8.922-1.809,12.142-5.029l188.545-188.553    C474.988,241.811,492.719,206.017,492.719,166.008z'/>
                </svg>
              </a>
          </p>
        </div>
      ";
    }

    echo "<div class='content-wrap'>".$html."</div>";
  }



  /*
   * Queries the database and returns an array of palettes.
   *
   *    $sort_type -> sorts by "hottest" or "latest"
   *    $page_number -> The page to return data from
   *    $total -> The number of results on a page
   *    $query -> query to search for
   */
  function get_palettes($sort_type, $page_number, $total, $query) {

    // Get user id from cookies
    $user_id = get_db_cookie_value('user_id');

    // Determine sort method
    switch ($sort_type)  {
      case "latest":
        $condition = "`date_created` DESC, `title` ASC";
        break;
      case "hottest":
        $condition = "`favourites` DESC, `date_created` DESC";
        break;
      default:
        $condition = "`date_created` DESC, `title` ASC";
    }

    // Determine which palettes for current page number
    $limit = ($page_number*$total-18).", ".$total ;

    // If user is using a query
    if ($query) {
      $where_like = "
        WHERE `title` LIKE '%". $query ."%'
        OR `description` LIKE '%". $query ."%'
      ";
    }

    // If user has a cookie
    $left_join_favourites = "";
    if ($user_id != '') {
      $palettes_query = "
        SELECT `palettes`.*, `favourited_palettes`.`user_id`, `favourited_palettes`.`palette_id` as fav_palette_id
        FROM `palettes`
        LEFT JOIN `favourited_palettes`
          ON `favourited_palettes`.`palette_id` = `palettes`.`palette_id`
          AND `favourited_palettes`.`user_id` = '".$user_id."'
        ".$where_like."
        ORDER BY ". $condition ."
        LIMIT ". $limit;
    } else {
      $palettes_query = "
        SELECT *
        FROM `palettes`
        ".$where_like."
        ORDER BY ". $condition ."
        LIMIT ". $limit;
    }

    // Query the database with generated query
    try {
      $palettes = db_select( $palettes_query );
      return $palettes;
    } catch (Exception $e) {
      // echo $e;
    }

  }



  /*
   * Takes a single palettes and turns it
   * into HTML ready for viewing.
   */
  function generate_palette_html($palette) {
    $palette_tags = explode(',', $palette['description']);
    $palette_tags_html = "";

    foreach ($palette_tags as $tag) {
      $palette_tags_html .= "<a href='/browse.php?q=".$tag."' class='description-tag'>".$tag."</a>";
    }

    echo "
    <div id='palette-info-wrapper'>
      <h2>".$palette['title']."</h2>
      <a class='palette-container''>
        <div class='palette-colour' style='background-color:#".$palette['colour_1']."'></div>
        <div class='palette-colour' style='background-color:#".$palette['colour_2']."'></div>
        <div class='palette-colour' style='background-color:#".$palette['colour_3']."'></div>
        <div class='palette-colour' style='background-color:#".$palette['colour_4']."'></div>
        <div class='palette-colour' style='background-color:#".$palette['colour_5']."'></div>
      </a>
      <div class='description-tag-box'>".$palette_tags_html."</div>
    </div>
    ";
  }



  /*
   * Takes a palette_id and returns
   * the palette to user.
   */
  function get_palette_by_id($palette_id) {

    $palette = db_select("
      SELECT *
      FROM `palettes`
      WHERE `palette_id` = '". $palette_id ."'"
    );

    return $palette[0];
  }



  /*
   * Returns an array of users created or favourited palettes.
   *
   * Takes 'created' or 'favourited' as inputs.
   */
  function get_own_palettes($value) {

    if (isset($_COOKIE["user"]) && test_input($_COOKIE["user"]) == get_db_cookie_value("value") && test_input($_COOKIE["user"]) != "") {

      // If requesting created palettes
      if ($value == 'created') {
        $user_id = get_db_cookie_value('user_id');
        $palettes_query = "
          SELECT *
          FROM `created_palettes`
          LEFT JOIN palettes ON `created_palettes`.palette_id = palettes.palette_id
          WHERE user_id = '".$user_id."'
        ";
        $palettes = db_select( $palettes_query );
        return $palettes;
      }

      // If requesting favourited palettes
      else if ($value == 'favourited') {
        $user_id = get_db_cookie_value('user_id');
        $palettes_query = "
          SELECT *
          FROM `favourited_palettes`
          LEFT JOIN palettes ON `favourited_palettes`.palette_id = palettes.palette_id
          WHERE user_id = '".$user_id."'
        ";
        $palettes = db_select( $palettes_query );
        return $palettes;
      }
    }

  }


?>
