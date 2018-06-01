<?php require "modules/auth.php"; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Palettes | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/palette.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script type='text/javascript'>
      $(document).ready(function(){
        $('.fav').css('display', 'none');
      });
    </script>
  </head>
  <body>

    <div class='container'>

      <?php
        include_once 'modules/palette-generation.php';
        include_once 'modules/cookie-check.php';
        require 'components/main_nav.php';
      ?>

      <div class='content-wrap'>

        <h2>Profile</h2>
        <a href='/logout.php'>Log out of your account.</a><br />

        <h2>Created</h2>
        <?php
          $created_palettes = get_own_palettes('created');
          generate_palettes_html( $created_palettes );
         ?>

        <h2>Favourited</h2>
        <?php
          $favourited_palettes = get_own_palettes('favourited');
          generate_palettes_html( $favourited_palettes );
        ?>

      </div>

      <?php require 'components/footer.php'; ?>

    </div>

  </body>
</html>
