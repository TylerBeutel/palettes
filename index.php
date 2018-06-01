<!DOCTYPE html>
<!-- zone info: https://help.eait.uq.edu.au/smartos/webproject/getting-started.html -->
<html>
  <head>
    <title>Palettes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='./css/style.css' />
    <link type='text/css' rel='stylesheet' href='./css/index.css' />
    <link type='text/css' rel='stylesheet' href='./css/nav.css' />
    <link type='text/css' rel='stylesheet' href='./css/footer.css' />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script type='text/javascript' src='js/colourpicker.js'></script>
    <script type='text/javascript' src='js/jquery.sticky.js'></script>
    <script type='text/javascript' src='js/nav.js'></script>
    <script>
      $(document).ready(function(){
        $("#browse-nav").sticky({
          topSpacing: 0,
          zIndex: 1
        });
      });
    </script>
  </head>
  <body>
    <!-- TODO: Setup colour rules -->

    <div class='container'>

      <?php
        require "components/main_nav.php";
        require "components/create_nav.php";
      ?>

      <!-- Create Container -->
      <div class='content-wrap'>

        <!-- Rainbow Box -->
        <div id='rainbow-box'>
          <div id='gradient-box'></div>
        </div><!-- Rainbow Box END -->

        <!-- Saturation Slider -->
        <input type='range' value='255' min='0' max='255' id='saturation-slider'/>

        <!-- Input Container -->
        <div id='colour-input-container'>

          <div id='colour-input-1' class='colour-input'>
            <div class='hex-value-container'>
              <span class='input-tag'>#</span><input class='hex-input' />
            </div>
          </div>

          <div id='colour-input-2' class='colour-input'>
            <div class='hex-value-container'>
              <span class='input-tag'>#</span><input class='hex-input' />
            </div>
          </div>

          <div id='colour-input-3' class='colour-input'>
            <div class='hex-value-container'>
              <span class='input-tag'>#</span><input class='hex-input' />
            </div>
          </div>

          <div id='colour-input-4' class='colour-input'>
            <div class='hex-value-container'>
              <span class='input-tag'>#</span><input class='hex-input' />
            </div>
          </div>

          <div id='colour-input-5' class='colour-input'>
            <div class='hex-value-container'>
              <span class='input-tag'>#</span><input class='hex-input' />
            </div>
          </div>


        </div><!-- Input Container END -->

      </div><!-- Content Wrap END -->

      <?php require "components/footer.php"; ?>

    </div><!-- container END -->

  </body>
</html>
