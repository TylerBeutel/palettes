<?php require "modules/auth.php"; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Create</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/create.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script type='text/javascript' src='js/nav.js'></script>
  </head>
  <body>

    <?php
      include_once "modules/gen-uuid.php";
      include_once "modules/cookie-check.php";
      include_once "modules/test-input.php";

      // define variables and set to empty values
      $titleErr = $tagsErr = "";
      $title = $tags = "";
      $palette_id = "";


      // FORM HANDLING
      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // TITLE
        if ( !isset($_POST["title"]) ) {
          $titleErr = "A title is required";
        } else {
          $title = test_input($_POST["title"]);
          if (strlen($title) < 1) {
            $titleErr = "A title is required";
          }
        }
        // TAGS
        if ( !isset($_POST["tags"]) ) {
          $tagsErr = "At least one tag is required";
        } else {
          $tags = test_input($_POST["tags"]);
          if (strlen($tags) < 1) {
            $tagsErr = "At least one tag is required";
          }
        }

        // If there are any errors
        if ( strlen($titleErr) < 1 && strlen($tagsErr) < 1) {

          // If palette is an edit
          if ($_POST["edit"] == 'true') {

            // Check user has valid cookie
            if (isset($_COOKIE["user"]) && test_input($_COOKIE["user"]) == get_db_cookie_value("value") && test_input($_COOKIE["user"]) != "") {

              // Get user_id from cookie
              $user_id = get_db_cookie_value("user_id");

              // Check if user owns palette
              $palette_id = test_input($_POST["id"]);
              $palette_owner_id = db_select("
                SELECT `user_id`
                FROM `created_palettes`
                WHERE `palette_id` = '".$palette_id."'
              ")[0]['user_id'];
              if ($palette_owner_id == get_db_cookie_value("user_id")) {

                // Get palette id
                $palette_id = test_input($_POST["palette_id"]);

                // Update palette
                $query = "
                  UPDATE `palettes`
                  SET
                    `title` = '".$title."',
                    `description` = '".$tags."',
                    `colour_1` = '".test_input($_POST["c1"])."',
                    `colour_2` = '".test_input($_POST["c2"])."',
                    `colour_3` = '".test_input($_POST["c3"])."',
                    `colour_4` = '".test_input($_POST["c4"])."',
                    `colour_5` = '".test_input($_POST["c5"])."'
                  WHERE `palette_id` = '".test_input($_POST["id"])."'
                ";
                db_query($query);
                // Set location header
                header("Location: palette.php?id=" . test_input($_POST["id"]));
              }

            }
          }


          // If palette is new
          else {
            // Create palette
            $palette_id = gen_uuid();
            $create_query = "
              INSERT INTO `palettes`(`title`, `description`, `date_created`, `favourites`, `views`, `palette_id`, `colour_1`, `colour_2`, `colour_3`, `colour_4`, `colour_5`)
              VALUES ('".$title."', '".$tags."', NOW(), 0, 0, '".$palette_id."', '".test_input($_POST["c1"])."', '".test_input($_POST["c2"])."', '".test_input($_POST["c3"])."', '".test_input($_POST["c4"])."', '".test_input($_POST["c5"])."')
            ";
            db_query($create_query);
            // Link palette to user
            $link_query = "
              INSERT INTO `created_palettes`(`user_id`, `palette_id`)
              VALUES ('".get_db_cookie_value('user_id')."', '".$palette_id."')
            ";
            db_query($link_query);
            // Set location header
            header("Location: palette.php?id=" . $palette_id);
          }

        }

      }
    ?>



    <div class='container'>
      <?php require "components/main_nav.php"; ?>

      <div class='content-wrap'>
        <h2>Before you publish...</h2>

        <?php
          // Palette
          if (isset($_GET['c1']) && isset($_GET['c2']) && isset($_GET['c3'])
              && isset($_GET['c4']) && isset($_GET['c5'])) {
            echo "<script type='text/javascript' src='js/tags.js'></script>
              <div class='palette-result'>
                <a class='palette-container'>
                  <div class='palette-colour' style='background-color:#".test_input($_GET['c1'])."'></div>
                  <div class='palette-colour' style='background-color:#".test_input($_GET['c2'])."'></div>
                  <div class='palette-colour' style='background-color:#".test_input($_GET['c3'])."'></div>
                  <div class='palette-colour' style='background-color:#".test_input($_GET['c4'])."'></div>
                  <div class='palette-colour' style='background-color:#".test_input($_GET['c5'])."'></div>
                </a>
              </div>";
          } else {

          }

          // Get palette details
          $palette_id = '';
          $palette_title = '';
          $palette_tags = '';
          $palette_tags_hidden = '';
          if (isset($_GET['edit']) && isset($_GET['id'])) {
            if ($_GET['edit'] == true) {
              $palette_id = test_input($_GET['id']);
              $palette = db_select("
                SELECT *
                FROM `palettes`
                WHERE `palette_id` = '".$palette_id."'
              ")[0];
              $palette_title = $palette['title'];
              $palette_tags_hidden = $palette['description'];
            }
          }


        ?>



        <form method="post" action="/create.php<?php if($_GET){ echo '?'.http_build_query($_GET); }?>">

          Give your palette a title:
          <span class="error">* <?php echo $titleErr;?></span><br />
          <input type="text" name="title" value="<?php echo $palette_title ?> "/>
          <br /><br />

          And some tags to describe it:
          <span class="error">* <?php echo $tagsErr;?></span><br />
          <!-- Tag Input -->
          <div id='tag-input-container'>
            <input type="text" id="tag-input" /><a id="tag-add">
              <span></span>
              <span></span>
            </a>
          </div>
          <!-- Tag Box -->
          <div id="tag-box">
            <?php  ?>
          </div>
          <!-- Hidden Tag Values -->
          <input type="hidden" name="tags" id="tags-hidden" value="<?php echo $palette_tags_hidden ?>"/>

          <br /><br />

          <!-- Colours -->
          <input type="hidden" name="c1" value="<?php echo test_input($_GET['c1']);?>" />
          <input type="hidden" name="c2" value="<?php echo test_input($_GET['c2']);?>" />
          <input type="hidden" name="c3" value="<?php echo test_input($_GET['c3']);?>" />
          <input type="hidden" name="c4" value="<?php echo test_input($_GET['c4']);?>" />
          <input type="hidden" name="c5" value="<?php echo test_input($_GET['c5']);?>" />

          <!-- Determine new or edited palette -->
          <!-- Edited -->
          <input type="hidden" name="edit" value="<?php
            if (isset($_GET['edit'])) {echo test_input($_GET['edit']);}
            else {echo "false";} ?>"
          />
          <!-- palette id -->
          <input type="hidden" name="id" value="<?php
            if (isset($_GET['id'])) {echo test_input($_GET['id']);} ?>"
          />

          <!-- Submit the form -->
          <input type="submit" name="submit" value="Publish" />

        </form>
      </div>

      <?php require "components/footer.php"; ?>
    </div><!-- container END -->

  </body>
</html>
