<!DOCTYPE html>
<html>
  <head>
    <title>Discover</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='css/style.css' />
    <link type='text/css' rel='stylesheet' href='css/nav.css' />
    <link type='text/css' rel='stylesheet' href='css/footer.css' />
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

      <!-- The nav bar -->
      <?php require "components/main_nav.php"; ?>

      <div class='content-wrap'>
        <?php
          include_once "modules/db.php";
          include_once "modules/test-input.php";
          include_once "modules/cookie-check.php";


          cookie_check(function(){
            // Get palette id
            $palette_id = test_input($_GET["id"]);

            // Get palette owner id
            $palette_owner_id_query = "
              SELECT `user_id`
              FROM `created_palettes`
              WHERE `palette_id` = '".$palette_id."'
            ";
            $palette_owner_id = db_select($palette_owner_id_query)[0]['user_id'];

            // Check if user owns palette
            if ($palette_owner_id == get_db_cookie_value("user_id")) {

              // Try to delete
              try {
                db_query("DELETE FROM `favourited_palettes` WHERE `palette_id` ='".$palette_id."';");
                db_query("DELETE FROM `created_palettes` WHERE `palette_id` ='".$palette_id."';");
                db_query("DELETE FROM `palettes` WHERE `palette_id` ='".$palette_id."';");
                echo "<h2>Palette deleted successfully.</h2>";
              } catch (Exception $e) {
                echo "<h2>Palette could not be deleted.</h2>";
                // echo 'Caught exception: ', $e->getMessage(), "\n";
              }

            } else {
              echo "<h2>You are not the owner of this palette.</h2>";
            }

          });
        ?>
      </div><!--content wrap END -->

    </div><!-- container END -->

    <!-- The page footer -->
    <?php require "components/footer.php"; ?>

  </body>
</html>
