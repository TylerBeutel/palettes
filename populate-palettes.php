<!DOCTYPE html>
<html>
  <head>
    <title>PROFILE</title>
  </head>
  <body>
    <h4>starting...</h4>

    <?php
      // To prevent public usage
      header("Location: index.php");

      include_once "modules/db.php";
      include_once "modules/gen-uuid.php";


      /* Generates a random hex colour
       *
       * Based on solution from: https://stackoverflow.com/questions/5614530/generating-a-random-hex-color-code-with-php/5614583
       */
      function rand_color() {
          return str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
      }



      for ($x = 0; $x < 36; $x++) {

        // Generate the variables
        $title = "random_pallete_" . $x;
        $description = "randomly generated,random";
        $random_date = db_select("SELECT FROM_UNIXTIME(UNIX_TIMESTAMP('2016-01-01 01:01:00') + FLOOR(0 + (RAND() * 63072000))) AS dt")[0]["dt"];
        $views = 0;
        $favourites = 0;
        $palette_id = gen_uuid();
        $colour_1 = rand_color();
        $colour_2 = rand_color();
        $colour_3 = rand_color();
        $colour_4 = rand_color();
        $colour_5 = rand_color();

        // Add the stuff to the database
        $query = "
          INSERT INTO `palettes`(`title`, `description`, `date_created`, `views`, `favourites`, `palette_id`, `colour_1`, `colour_2`, `colour_3`, `colour_4`, `colour_5`)
          VALUES ('".$title."', '".$description."', '".$random_date."', '".$views."', '".$favourites."', '".$palette_id."', '".$colour_1."', '". $colour_2."', '".$colour_3."', '".$colour_4."', '".$colour_5."')";
        echo $query."<br /><br />";
        db_query( $query );

      }


    ?>

    <h4>finished!</h4>
</body>
</html>
