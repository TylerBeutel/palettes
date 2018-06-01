$(document).ready(function(){


  /*
  * Gets a parameter from the url.
  *
  * source: https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
  */
  function getParameterFromURL(name) {
    url = location.href;;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
  }


  // Detect changes in 'sort by'
  $('#sort-options').change(function() {
    // Maintain the query
    $q = '';
    if (getParameterFromURL('q')) {
      $q = '&q=' + getParameterFromURL('q');
    };
    // Maintain the page number
    $p = '';
    if(getParameterFromURL('page')) {
        $p = '&page=' + getParameterFromURL('page');
    }

    $sortRequested = $(this).val();
    $sortCurrent = 'recent';
    if (getParameterFromURL('sort')) {
      $sortCurrent = getParameterFromURL('sort');
    }

    // Get current sort value
    if ($sortRequested != $sortCurrent) {
      switch ($(this).val()) {
        case 'hottest':
          window.location.href = '/browse.php?sort=hottest'+$p+$q;
          break;
        case 'recent':
          window.location.href = '/browse.php?sort=recent'+$p+$q;
          break;
        default:
          window.location.href = '/browse.php?sort=recent'+$p+$q;
      }
    }

  });



  // Update 'sort by' value to reflect current value
  if (getParameterFromURL('sort')) {
    $('#sort-options').val(getParameterFromURL('sort')).change();
  } else {
    $('#sort-options').val('recent').change();
  }


});
