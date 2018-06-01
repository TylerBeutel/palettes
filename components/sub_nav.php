<?php

  echo "<nav class='sub-nav' id='browse-nav'>
          <div class='content-wrap'>
            <select class='dropdown' id='sort-options'>
              <option value='recent'>RECENT</option>
              <option value='hottest'>HOTTEST</option>
            </select>
            <form method='get' action='/browse.php' id='nav-search'>
              <input type='text' name='q' id='nav-search-bar' />
              <input type='submit' value='' id='nav-search-submit'>
            </form>
          </div>
        </nav>";

?>
