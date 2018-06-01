<!DOCTYPE html>
<html>
  <head>
    <title>Palette</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/palette.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
    <link type="text/css" rel="stylesheet" href="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css" />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script
      src="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js"
      type="text/javascript"
      charset="utf-8">
    </script>
    <script type='text/javascript' src='js/nav.js'></script>
    <script type='text/javascript' src='js/favourite.js'></script>
  </head>
  <body>

    <div class='container'>

      <?php
        include_once "modules/cookie-check.php";
        include_once "modules/test-input.php";
        include_once "modules/palette-generation.php";
        require "components/main_nav.php";
      ?>

      <div class='content-wrap'>

        <?php

          $palette_id = test_input($_GET["id"]);
          if ($palette_id) {
            // Get palette by id, then generate palette html
            $palette = get_palette_by_id($palette_id);
            generate_palette_html( $palette );
            $views = db_query("
              UPDATE `palettes`
              SET `views` = `views` + 1
              WHERE `palette_id` = '".test_input($_GET['id'])."';
            ");
          } else {
            // TODO: error 404
          }

          // Check if user has favourited this palette
          $faved = false;
          cookie_check(function(){
            $user_id = get_db_cookie_value("user_id");
            $fav_query = db_select("
              SELECT count(*)
              FROM `favourited_palettes`
              WHERE `user_id` = '".$user_id."'
              AND `palette_id` = '".$palette_id."'
            ")[0]['count(*)'];
            if ( (int)$fav_query > 0 ) {
              $fav_query = true;
            }
          });

        ?>

        <div class="btn-row">

          <!-- Favourite -->
          <a class='btn <?php if ($fav_query) { echo " faved"; } ?>' id='favourite'>
            <svg xmlns='/http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Capa_1' x='0px' y='0px' viewBox='0 0 492.719 492.719' style='enable-background:new 0 0 492.719 492.719;' xml:space='preserve'>
              <path d='M492.719,166.008c0-73.486-59.573-133.056-133.059-133.056c-47.985,0-89.891,25.484-113.302,63.569    c-23.408-38.085-65.332-63.569-113.316-63.569C59.556,32.952,0,92.522,0,166.008c0,40.009,17.729,75.803,45.671,100.178    l188.545,188.553c3.22,3.22,7.587,5.029,12.142,5.029c4.555,0,8.922-1.809,12.142-5.029l188.545-188.553    C474.988,241.811,492.719,206.017,492.719,166.008z'/>
            </svg>
            Favourite
          </a>

          <!-- Clone -->
          <a class='btn' href="<?php echo 'index.php?cloned=true'
            .'&c1='.$palette['colour_1']
            .'&c2='.$palette['colour_2']
            .'&c3='.$palette['colour_3']
            .'&c4='.$palette['colour_4']
            .'&c5='.$palette['colour_5']
          ?>"><img src='/images/clone.svg' /> Clone</a>

          <!-- Embed -->
          <a class='btn' data-featherlight='#embed-code' href='#'><img src='/images/code.svg' /> Embed</a>

          <?php
            if (isset($_COOKIE["user"]) && test_input($_COOKIE["user"]) == get_db_cookie_value("value") && test_input($_COOKIE["user"]) != "") {

              // Get palette owner id
              $palette_id = test_input($_GET["id"]);
              $palette_owner_id = db_select("
                SELECT `user_id`
                FROM `created_palettes`
                WHERE `palette_id` = '".$palette_id."'
              ")[0]['user_id'];

              // Check if user owns palette
              if ($palette_owner_id == get_db_cookie_value('user_id')) {

                // Display EDIT and DELETE buttons
                $personal_btn_html = "
                <a class='btn' href='index.php?edit=true&id=".$palette_id
                  .'&c1='.$palette['colour_1']
                  .'&c2='.$palette['colour_2']
                  .'&c3='.$palette['colour_3']
                  .'&c4='.$palette['colour_4']
                  .'&c5='.$palette['colour_5']."'>
                  <img src='/images/edit.svg' /> Edit
                </a>
                <a class='btn' href='/delete.php?id=".$palette_id."'>
                  <img src='/images/delete.svg' /> Delete
                </a>";
                echo $personal_btn_html;

              }

            }

          ?>









        </div> <!-- END OF BUTTON ROW -->


        <h2>Colour Codes</h2>
        <div class='colour-code' style='background-color:#<?php echo $palette['colour_1'] ?>;'>
          <span>#<?php echo $palette['colour_1'] ?></span>
        </div>
        <div class='colour-code' style='background-color:#<?php echo $palette['colour_2'] ?>;'>
          <span>#<?php echo $palette['colour_2'] ?></span>
        </div>
        <div class='colour-code' style='background-color:#<?php echo $palette['colour_3'] ?>;'>
          <span>#<?php echo $palette['colour_3'] ?></span>
        </div>
        <div class='colour-code' style='background-color:#<?php echo $palette['colour_4'] ?>;'>
          <span>#<?php echo $palette['colour_4'] ?></span>
        </div>
        <div class='colour-code' style='background-color:#<?php echo $palette['colour_5'] ?>;'>
          <span>#<?php echo $palette['colour_5'] ?></span>
        </div>



        <div id='embed-code'>

          <h2>Embed this palette into your website:</h2>
          <?php
            $palette_html = "
<style type='text/css'>
.pal-wrp {
  position:relative;
  width:100%;
  height:100px;
  overflow:hidden;
  border-radius:10px;
}
.pal-wrp::after {
  content:'';
  background-color:rgba(0,0,0,0.2);
  position:absolute;
  height:15px;
  left:0px;
  right:0px;
  bottom:0px;
}
.pal-col {
  width:20%;
  height:100px;
  margin:0px;
  padding:0px;
  float:left;
}
</style>
<div class='pal-wrp'>
  <div class='pal-col' style='background-color:#".$palette['colour_1']."'></div>
  <div class='pal-col' style='background-color:#".$palette['colour_2']."'></div>
  <div class='pal-col' style='background-color:#".$palette['colour_3']."'></div>
  <div class='pal-col' style='background-color:#".$palette['colour_4']."'></div>
  <div class='pal-col' style='background-color:#".$palette['colour_5']."'></div>
</div>
";
            echo $palette_html;
          ?>

          <pre>
            <!-- Embed Code -->
            <code><?php echo htmlspecialchars($palette_html); ?></code>
          </pre>

        </div><!-- embed code END -->

      </div><!-- content wrap END -->

      <?php require "components/footer.php"; ?>
    </div><!-- container END -->

  </body>
</html>
