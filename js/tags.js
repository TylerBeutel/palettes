$(document).ready(function(){

    /*
     * Detects the addition of tags and
     * adds runs the addTag() method.
     */
    $('#tag-add').click(function(){
      addTag();
    });



    /*
     * Add tag
     */
    function addTag() {
      // Grab tag value and process it
      $tag = $('#tag-input').val();
      $tag = $tag.replace(',', '');
      $tag = $tag.toLowerCase();
      // Check if exists and append to hidden
      $tagArray = $('#tags-hidden').val().split(',');
      // Empty input
      $('#tag-input').val('');
      // Add tag to array
      $tagArray.push($tag);
      // Remove any empty values
      $tagArray = $tagArray.filter(function(x){
        return (x !== '');
      });
      // Add array to hidden input
      $('#tags-hidden').val($tagArray.join(','));
      // Display values in tagbox
      $tagboxHTML = '';
      for (var i=0; i<$tagArray.length; i++) {
        $tagboxHTML += "<div class='tag'><div class='tag-value'>"
        $tagboxHTML += $tagArray[i]
        $tagboxHTML += "</div><div class='tag-close'><span></span><span></span></div></div>";
      }
      $('#tag-box').html($tagboxHTML);
      // Add listener for tag removal
      $('.tag-close').on('click', function(){
        // Determine tag value
        $tag = $(this).siblings('.tag-value').text();
        console.log($tag);
        removeTag($tag);
      });
    }


    /*
     * When document has loaded, load any tags
     * that are currently in hidden tags.
     */
     // Get current values from hidden input
     $tagArray = $('#tags-hidden').val().split(',');
     // Remove any empty values
     $tagArray = $tagArray.filter(function(x){
       return (x !== '');
     });
     // Display values in tagbox
     $tagboxHTML = '';
     for (var i=0; i<$tagArray.length; i++) {
       $tagboxHTML += "<div class='tag'><div class='tag-value'>"
       $tagboxHTML += $tagArray[i]
       $tagboxHTML += "</div><div class='tag-close'><span></span><span></span></div></div>";
     }
     $('#tag-box').html($tagboxHTML);
     // Add listener for tag removal
     $('.tag-close').on('click', function(){
       // Determine tag value
       $tag = $(this).siblings('.tag-value').text();
       console.log($tag);
       removeTag($tag);
     });


     /*
      * Removes given tag
      */
      function removeTag(tag){
        // Get current values from hidden input
        $tagArray = $('#tags-hidden').val().split(',');
        // Remove tag from current values
        for (var i=$tagArray.length-1; i>=0; i--) {
          console.log('loop');
          if ($tagArray[i] == tag) {
            $tagArray.splice(i, 1);
          }
        }
        // Add array to hidden input
        $('#tags-hidden').val($tagArray.join(','));
        // Display values in tagbox
        $tagboxHTML = '';
        for (var i=0; i<$tagArray.length; i++) {
          $tagboxHTML += "<div class='tag'><div class='tag-value'>"
          $tagboxHTML += $tagArray[i]
          $tagboxHTML += "</div><div class='tag-close'><span></span><span></span></div></div>";
        }
        $('#tag-box').html($tagboxHTML);
        // Add listener for tag removal
        $('.tag-close').on('click', function(){
          // Determine tag value
          $tag = $(this).siblings('.tag-value').text();
          removeTag($tag);
        });
      };


});
