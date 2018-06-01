$(document).ready(function(){


  /*
   * Gets a parameter from the url.
   *
   * source: https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
   */
    function getParameterFromURL(name) {
      url = location.href;
      name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
      var regexS = "[\\?&]"+name+"=([^&#]*)";
      var regex = new RegExp( regexS );
      var results = regex.exec( url );
      return results == null ? null : results[1];
    }



  /*
   * When favouriting from the browse page
   */
  $('.fav').click(function(){
    $btn = $(this);

    // Data to be sent in fav POST
    var data = { "palette_id": $(this).attr('value') };

    // Send fav POST
    $.ajax({
        url: "favourite.php",
        type: "POST",
        data: data,
        dataType: 'json',
        complete: function (data) {
          if (data['responseJSON'][0]['complete']) {
            $btn.children('span').text( data['responseJSON'][0]['favs'] );
            $btn.toggleClass('faved');
          } else {
            window.location.href = '/login.php';
          }
        }
    });
    return false;
  })



  /*
   * When favouriting from the palette page
   */
  $('#favourite').click(function(){
    $btn = $(this);

    // Data to be sent in fav POST
    var data = { "palette_id": getParameterFromURL('id') };

    // Send fav POST
    $.ajax({
        url: "favourite.php",
        type: "POST",
        data: data,
        dataType: 'json',
        complete: function (data) {
          if (data['responseJSON'][0]['complete']) {
            $btn.children('span').text( data['responseJSON'][0]['favs'] );
            $btn.toggleClass('faved');
          } else {
            window.location.href = '/login.php';
          }
        }
    });
    return false;
  })

});
