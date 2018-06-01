$(document).ready(function(){

  // Variables
  $sat = 255;
  $selected = '';
  $colours = {
    'colour-input-1': [255, 255, 255],
    'colour-input-2': [255, 255, 255],
    'colour-input-3': [255, 255, 255],
    'colour-input-4': [255, 255, 255],
    'colour-input-5': [255, 255, 255]
  };
  $cloned = false;
  $edit = false;


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
   * Converts a string into an integer array.
   */
  function strToArray(str) {
    $arr = str.split(',');
    for (var j=0; j<3; j++) {
      $arr[j] = parseInt($arr[j]);
    }
    return $arr;
  }



  /*
   * Loads colours from the URL
   */
  function loadColoursFromURL() {
    try {
      for (var i=1; i<6; i++) {
        changeColour('colour-input-'+i, hexToList( getParameterFromURL('c'+i) ));
        console.log(getParameterFromURL('c'+i));
      }
    } catch (err) {
      changeColour('colour-input-'+i, [255,255,255])
    }
    // window.history.pushState("", "Palettes", "/index.php");
  }



    /*
     * Detects when 'Next' is clicked. Takes user to
     * the details page with the palette they made.
     */
    $('#pre-publish').click(function(){
      // $('body').addClass('notransition');
      if ($edit) {
        window.location.href = '/create.php?edit=true&id='+
        getParameterFromURL('id')+'&c1='+
        listToHex($colours['colour-input-1'])+'&c2='+
        listToHex($colours['colour-input-2'])+'&c3='+
        listToHex($colours['colour-input-3'])+'&c4='+
        listToHex($colours['colour-input-4'])+'&c5='+
        listToHex($colours['colour-input-5']);
      } else {
        window.location.href = '/create.php?c1='+
        listToHex($colours['colour-input-1'])+'&c2='+
        listToHex($colours['colour-input-2'])+'&c3='+
        listToHex($colours['colour-input-3'])+'&c4='+
        listToHex($colours['colour-input-4'])+'&c5='+
        listToHex($colours['colour-input-5']);
      }

    });


  // Check if page is to load a cloned colour
  if ( getParameterFromURL('cloned') ) {
    loadColoursFromURL();
    $cloned = true;
  }
  // Check if page is to edit a colour
  else if ( getParameterFromURL('edit') ) {
    loadColoursFromURL();
    $edit = true;
  }
  // Next, check if colours exist in localStorage
  else {
    for (var i=1; i<6; i++) {
      if (localStorage['colour-input-'+i]) {
        $colour = strToArray( localStorage['colour-input-'+i] );
        changeColour('colour-input-'+i, $colour);
      } else {
        // Otherwise just load white palettes
        changeColour('colour-input-'+i, [255,255,255])
      }
    }
  }



  // Function to change the selected box for inputting colours
  function selectInputBox(boxID) {
    $selected = boxID;
    $('.colour-input').removeClass('selected-input');
    $('#'+boxID).addClass('selected-input');
  }

  // Start by pre-selecting the middle input
  selectInputBox('colour-input-3');

  // Detect input box changes
  $('.colour-input').mousedown(function(){
    selectInputBox( $(this).attr('id') );
  });



  // Update Saturation on slider input
  $('input[type=range]').on('input', function () {
    $(this).trigger('change');
  });
  $('#saturation-slider').on('change', function () {
    $sat = parseInt( $(this).val() );
    $saturationRGB = 'rgb('+$sat+','+$sat+','+$sat+')';
    $gradientColour = 'linear-gradient(to bottom, transparent 0%, '+$saturationRGB+' 100%)';
    $('#gradient-box').css('background-image', $gradientColour);
  });

  // Detect interaction with rainbow box
  var isMouseDown = false;
  $('#rainbow-box')
  .mousedown(function(event) {
    isMouseDown = true;
    updateColourFromEvent(event);
  })
  .mousemove(function(event) {
    if (isMouseDown) {
      updateColourFromEvent(event);
    }
  })
  .mouseup(function(e) {
    isMouseDown = false;
  });


  // Detect interaction with HEX input
  $(".hex-input").keyup(function(key){
    // If the value 6 digits long
    if ( $(this).val().length == 6 ) {
      // Save to $colours
      $colour = hexToList( $(this).val() );
      changeColour($selected, $colour)
    }
  });


  // Updates the colour
  function updateColourFromEvent(event) {
    $colour = eventToColour(event);
    changeColour($selected, $colour)
  }


  /*
   * Updates colour state and saves backup
   * to browsers memory.
   */
  function changeColour(key, colour) {
    // Change colour and update inputs
    console.log('changing '+key+' to '+colour);
    $colours[key] = colour;
    loadValuesIntoInputs();

    // Store to broswers memory
    localStorage.setItem(key, colour);

  }


  // Load colours from the list
  function loadValuesIntoInputs() {
    // Load colours
    $('#colour-input-1').css('background-color', listToRGB($colours['colour-input-1']) );
    $('#colour-input-2').css('background-color', listToRGB($colours['colour-input-2']) );
    $('#colour-input-3').css('background-color', listToRGB($colours['colour-input-3']) );
    $('#colour-input-4').css('background-color', listToRGB($colours['colour-input-4']) );
    $('#colour-input-5').css('background-color', listToRGB($colours['colour-input-5']) );


    // Load hex values
    for (var i=1; i<6; i++) {
      $value = listToHex($colours['colour-input-'+i]);
      $('#colour-input-'+i+' .hex-input').val( $value );
    }


  }

  // Load colours on page load
  loadValuesIntoInputs();


  /*
   * Takes mouse event and converts it into
   * a hex code;
   */
  function eventToColour(event) {

    // Get rainbow box dimensions
    $rbWidth = $('#rainbow-box').width();
    $rbHeight = $('#rainbow-box').height();

    // Get coordinates in rainbow box
    $mouseX = event.pageX - $('#rainbow-box').offset().left;
    $mouseY = event.pageY - $('#rainbow-box').offset().top;

    // Coordinates to percentage
    $percX = $mouseX / $rbWidth;
    $percY = $mouseY / $rbHeight;

    // x coord --> hue
    $hue = [];
    if ($percX < 1/6) {
      $var = Math.round($percX*1530);
      $hue = [255, $var, 0];
    } else if (1/6 < $percX && $percX < 2/6) {
      $var = Math.round(255-($percX*1530-255));
      $hue = [$var, 255, 0];
    } else if (2/6 < $percX && $percX < 3/6) {
      $var = Math.round($percX*1530-255*2);
      $hue = [0 ,255 , $var];
    } else if (3/6 < $percX && $percX < 4/6) {
      $var = Math.round(255-($percX*1530-255*3));
      $hue = [0 , $var, 255];
    } else if (4/6 < $percX && $percX < 5/6) {
      $var = Math.round($percX*1530-255*4);
      $hue = [$var, 0, 255];
    } else if (5/6 < $percX) {
      $var = Math.round(255-($percX*1530-255*5));
      $hue = [255, 0, $var];
    }

    // y coord --> adjust for saturation
    $huesat = [];
    for (var i=0; i<$hue.length; i++) {
      $original = $hue[i];
      $difference = $sat - $hue[i];
      $percentage = $sat/255;
      $huesat[i] = Math.round( $original + ($difference * $percY) );
    }
    return $huesat;
  }


  /*
   * Takes a hexidecimal value and converts it to a list of intigers
   * representing colours between 0 and 255.
   *
   * Based on solution from: https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
   */
  function hexToList(hex) {
      var bigint = parseInt(hex, 16);
      var r = (bigint >> 16) & 255;
      var g = (bigint >> 8) & 255;
      var b = bigint & 255;

      return [r, g, b];
  }


  /*
   * Takes a rgb value and converts it to a list of intigers
   * representing colours between 0 and 255.
   */
  function rgbToList(rgb) {
    return rgb.replace(' ', '').split(',');
  }


  /*
   * Converts a list of values into
   * a hexidecimal value (without #)
   *
   * Based on solution from: https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb
   */
  function listToHex(list) {
    red = list[0];
    green = list[1];
    blue = list[2];
    var rgb = blue | (green << 8) | (red << 16);
    return (0x1000000 + rgb).toString(16).slice(1)
  }


  /*
   * Converts a list of values into
   * a rgb (without the rgb and brackets)
   */
  function listToRGB(list) {
    return 'rgb('+list[0]+','+list[1]+','+list[2]+')';
  }



});
