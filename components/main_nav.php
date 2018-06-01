<?php

  // If user IS logged in
  if(isset($_COOKIE['user'])) {
    echo "<nav id='main-nav'>
            <div class='content-wrap'>
              <a id='logo' href='/'>
                <img src='images/logo.svg' />
              </a>
              <div id='mobile-menu'>
                <img src='images/menu-icon.png' />
              </div>
              <div id='links'>
                <a href='/'>CREATE</a>
                <a href='browse.php'>DISCOVER</a>
                <a href='profile.php'>PROFILE</a>
              </div>
            </div>
          </nav>";
  }

  // If user is NOT logged in
  else {
    echo "<nav id='main-nav'>
            <div class='content-wrap'>
              <a id='logo' href='/'>
                <img src='images/logo.svg' />
              </a>
              <div id='mobile-menu'>
                <img src='images/menu-icon.png' />
              </div>
              <div id='links'>
                <a href='/'>CREATE</a>
                <a href='browse.php'>DISCOVER</a>
                <a href='login.php'>LOGIN</a>
              </div>
            </div>
          </nav>";
  }

?>
