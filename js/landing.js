
// READ MORE BUTTON HANDLING ################################################################################
$(document).ready(function(){
  // store data before anything else
  content_splashContent = $("#splash_content").html();
  content_splashFooter = $("#splash_footer").html();
  content_splashShout = $("#splash_shout").html();
  // on click listener
  $("#read-more-splash").on("click", changeSplash);
  function changeSplash(){
    // remove content from splash_shout
    // $("#splash_shout").empty();
    // populate with new content.
    $.ajax({
      url: "strings/en/read_more_splash.txt",
      success: function(data){
        var output = data.replace("\n", "<br>");
        $("#splash_content").html(output);
      }
    });
    // populate back button
    $("#splash_footer").html("\
    <a id='go-back-splash' href='#' class='btn btn-sm btn-outline-info'>go back</a>");
    // activate back button
    $("#go-back-splash").on("click", function(){
      // change content
      $("#splash_content").html(content_splashContent);
      $("#splash_footer").html(content_splashFooter);
      $("#splash_shout").html(content_splashShout);
      // reactivate button
      $("#read-more-splash").on("click", changeSplash);
    });
  }
});
// END OF READ MORE BUTTON  ################################################################################
