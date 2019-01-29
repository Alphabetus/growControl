$(document).ready(function(){

  // populate track logs with new value
  $(".create-log").on("click", function(e){
    // prevent normal submit > i wanna do it here
    e.preventDefault();
    // get data vars
    var trackName = $(this).data("track");
    var trackValue = $("#log-" + trackName + "-input").val();
    var dataMin = $("#log-" + trackName + "-input").data("min");
    var dataMax = $("#log-" + trackName + "-input").data("max");
    var trackNaming = $("#track-" + trackName + "-display").data("naming");
    var trackSymbol = $(this).data("symbol");
    var userID = $(this).data("userid");
    var tableScope = $(this).data("table-scope");
    var parentScope = $(this).data("parent-scope");
    // format value to 2 decimal places
    trackValue = Number(trackValue).toFixed(2);
    // validate vars
    if (trackValue <= dataMax && trackValue > dataMin && trackValue != ""){
      // give the icon for the track

      // lets send ajax request to update DB
      jQuery.ajax({
      url: "ajax_requests/track_logs.php",
      data: {
            track_name: trackName,
            track_value: trackValue,
            userid: userID,
            table_scope: tableScope,
            parent_scope: parentScope
            },
      type: "POST",
      success:function(data){
        if (data === "ok"){
          // update on DB was ok > lets print new value on display
          $("#track-" + trackName + "-display").html(trackNaming + " : " + trackValue + trackSymbol);
          // lets remove invalid class from input in case it is there
          $("#log-" + trackName + "-input").removeClass("is-invalid");
          // hide error popover
          $("#input-group-" + trackName).popover("hide");
          // lets remove danger class from button and give the cute one
          // just in case it is there
          $("button", "#input-group-" + trackName).removeClass("btn-outline-danger");
          $("button", "#input-group-" + trackName).addClass("btn-outline-primary");

          // ok all done lets clear the input
          $("#log-" + trackName + "-input").val("");
          // displays ok on placeHolder
          $("#log-" + trackName + "-input").attr("placeholder", "OK");
          // close the input collapsible
          setTimeout(function(){
            $("#log-" + trackName).collapse("hide");

            // define inner timeout to change back the placeHolder
            setTimeout(function(){
              $("#log-" + trackName + "-input").attr("placeholder", "log value");
            }, 350);
          }, 350);
          // console.log($("#log-" + trackName));
        }
        else{

        }
      },
      error:function (){
        alert("Ajax request error on room.js\nPlease report.");
        return;
      }
      });

    }
    else {
      // data is not ok
      // give invalid class to input
      $("#log-" + trackName + "-input").addClass("is-invalid");
      // give danger class to btn
      $("button", "#input-group-" + trackName).addClass("btn-outline-danger");
      $("button", "#input-group-" + trackName).removeClass("btn-outline-primary");
      // shake input group
      $("#input-group-" + trackName).effect( "shake", {times:4, distance: 2}, 350 );
      // display error popover
      $("#input-group-" + trackName).popover("show");

    }
  });
  // end of pulate track logs with new value
});
