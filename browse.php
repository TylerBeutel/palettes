<!DOCTYPE html>
<html>
  <head>
    <title>Discover</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- STYLESHEETS -->
    <link type='text/css' rel='stylesheet' href='css/style.css' />
    <link type='text/css' rel='stylesheet' href='css/palette.css' />
    <link type='text/css' rel='stylesheet' href='css/nav.css' />
    <link type='text/css' rel='stylesheet' href='css/footer.css' />
    <link type='text/css' rel='stylesheet' href='css/pagination.css' />
    <!-- JAVASCRIPT -->
    <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
    </script>
    <script type='text/javascript' src='js/browse.js'></script>
    <script type='text/javascript' src='js/jquery.sticky.js'></script>
    <script type='text/javascript' src='js/nav.js'></script>
    <script type='text/javascript' src='js/favourite.js'></script>
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

    <div class='container'>

    <?php
      include_once 'modules/palette-generation.php';
      include_once 'modules/test-input.php';
      require 'components/main_nav.php';
      require 'components/sub_nav.php';


      // Set default page to 1
      $page = 1;
      // Check if GET variable 'page' was defined
      if (isset($_GET['page'])) {
        $page_input = test_input($_GET['page']);
        if (is_numeric($page_input)) {
          $page = $page_input;
        }
      }

      // Number of results to be returned
      $number_of_results = 18;

      // Check if Get variable 'sort' was defined
      $sort = 'latest';
      if (isset($_GET['sort'])) {
        $sort = test_input($_GET['sort']);
      }

      // Get palettes and generate palettes html
      $palettes = [];
      if (isset($_GET['q'])) {
        $query = test_input($_GET['q']);
        echo '<div class="content-wrap"><h2>Showing results for: <b>'. $query .'</b></h2></div>';
        $palettes = get_palettes($sort, $page, $number_of_results, $query);
      } else {
        $palettes = get_palettes($sort, $page, $number_of_results, false);
      };
      generate_palettes_html($palettes);


      // Previous page button
      $previous_html = "";
      if ($page > 1) {
        $previous_html = "<a class='pagination-button' href='/browse.php?page=".($page-1)."&sort=".$sort."'>
                            <div class='pagination-arr pagination-left'><span></span><span></span></div>
                          </a>";
      } else {
        $previous_html = "<a class='pagination-button inactive'>
                            <div class='pagination-arr pagination-left'><span></span><span></span></div>
                          </a>";
      }

      // Next page button
      if (sizeof($palettes) == $number_of_results) {
        $next_html = "<a class='pagination-button' href='/browse.php?page=".($page+1)."&sort=".$sort."'>
                        <div class='pagination-arr pagination-right'><span></span><span></span></div>
                      </a>";
      } else {
        $next_html = "<a class='pagination-button inactive'>
                        <div class='pagination-arr pagination-right'><span></span><span></span></div>
                      </a>";
      }

      // Generate pagination html
      echo "<div class='content-wrap'>
              <div class='pagination-wrap'>
                ".$previous_html."
                <div class='pagination-current'>".$page."</div>
                ".$next_html."
              </div>
            </div>";


      require 'components/footer.php';

    ?>

    </div><!-- container END -->

  </body>
</html>
