$(document).ready(function(){

  // Toggle mobile menu
  $('#mobile-menu').click(function(){
    $('#links').slideToggle();
  });

  // On window resize
  $(window).resize(function(){
    // If menu button hidden
    if ( $('#mobile-menu').css('display') == 'none' ) {
        // And menu is hidden
        if ( $('#links').css('display') == 'none' ) {
          // Make menu visible
          $('#links').css('display', 'block');
        }
    }
  });

});
