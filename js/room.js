$(document).ready(function(){

// create global var for pagination
pageNumber = 1;
pageLimit = parseInt($("#notes-nav").data("total-pages"));
noteLimitPerPage = parseInt($("#notes-nav").data("per-page"));
noteCount = parseInt($("#notes-nav").data("total-notes"));
// read necessary id's from DOM
userID = $("#notes-container").data("id");
roomID = $("#notes-container").data("room");
// poulate DOM with existant notes
populateNotes(userID, roomID, pageNumber, noteLimitPerPage);
isNavButtonVisible();
// NOTE: ADD TRACK LOG SCRIPT  [include AJAX]
//Script to insert track log values into DB and live-time update displays
// This script is activated with the .create-log button class.

  // populate track logs with new value
  $(".create-log").on("click", function(e){
    // prevent normal submit > i wanna do it here
    e.preventDefault();
    addTrackLog($(this));
  });
  // end of pulate track logs with new value

// NOTE: END OF > ADD TRACK LOG SCRIPT


// NOTE: DELETE GROW SCRIPT
// This script displays popOut to confirm deletion of the grow
// and displays the confirmation form.
// It is triggered by the #delete-grow-button

  // delete grow button listener
  $("#delete-grow-button").on("click", function(){
    // open modal
    $("#modal-grow-delete").modal("show");
  });

  // cancel delete button listener
  $("#cancel-delete-grow-button").on("click", function(){
    // close modal
    $("#modal-grow-delete").modal("hide");
  });



// NOTE: END OF > DELETE GROW SCRIPT


// NOTE: ADD LOG NOTE [include AJAX]
  // KEYUP EVENT
  // validate char length of note
  $("#input-note").on("keyup", function(){
    validateCharNote($(this));
  });

  // GENERAL LISTENERS BELOW

  // next note page button
  $("#note-next").on("click", function(e){
    e.preventDefault();
    pageNumber++;
    populateNotes(userID, roomID, pageNumber, noteLimitPerPage);
    isNavButtonVisible();
  });
  // previous page button
  $("#note-prev").on("click", function(e){
    e.preventDefault();
    pageNumber--;
    populateNotes(userID, roomID, pageNumber, noteLimitPerPage);
    isNavButtonVisible();
  });

  // listener open tools button
  $("#action-control").on("click", function(){
    resizeInfoBox();
  });

  // listener to close tools button
  $("#close-actions").on("click", function(){
    resizeInfoBox();
  });

  // listener to add log button
  $("#display-note-form").on("click", function(e){
    e.preventDefault();
    // get current button location
    var offsetTop = $(this).offset().top;
    // hides this button > bootstrap collapses the form
    $(this).hide();
    // scrolls to the right content
    $('html,body').animate({scrollTop: offsetTop},'slow');
  });

  // listener to cancel note button
  $("#cancel-note-form").on("click", function(e){
    e.preventDefault();
    // display the add button > bootstrap closes form
    $("#display-note-form").fadeIn();
  });

  // listener to ADD note button
  $("#save-note-form").on("click", function(e){
    e.preventDefault();
    addNote($(this));
  });

// NOTE: END OF > ADD LOG NOTE
});

// NOTE: FUNCTIONS

// prepare the note deletion by changing the DOM to confirm.
function notePreDelete(context){
  window.event.preventDefault();
  // get variables
  var context = $(context);
  var noteID = context.data("note");
  var userID = context.data("id");
  var parentElement = $("#note-container-id-" + noteID);
  // backup content before erasing DOM element
  oldHTMLnote = parentElement.html();
  // replace DOM content with confirmation form
  parentElement.html(`
    <div class="col-12 m-0 p-0 note-delete-confirm-container border-bottom border-dark pb-2">
      <h5 class="text-danger"><i class="fas fa-exclamation-circle"></i>&nbsp;Are you sure you want to delete this note?</h5>

      <div class="row m-0 p-0">

        <div class="col-6 m-0 p-0 text-center">
          <a href="#" id="note-delete-confirm-button" onClick="noteDeleteConfirm(${userID}, ${noteID})" class="btn btn-sm btn-danger rounded w-75">delete</a>
        </div>

        <div class="col-6 m-0 p-0 text-center">
          <a href="#" id="note-delete-cancel-button" onClick="noteDeleteCancel(${noteID})" class="btn btn-sm btn-outline-primary rounded w-75">cancel</a>
        </div>

      </div>
    </div>
    `);

}


function noteDeleteConfirm(user, note){
  window.event.preventDefault();
  // ajax request to delete entry from DB
  jQuery.ajax({
    url: "ajax_requests/delete_notes.php",
    data: {
      user_id: user,
      note_id: note
    },
    type: "POST",
    success: function(data){
      if (data === "ok"){
        // increase noteCount
        noteCount--;
        // recalculates notes limit per page
        var pageLimitCalc = eval(noteCount / noteLimitPerPage);
        // gets the next integer of that value
        // same process has done on bEND to deliver the initial DOM
        // define the new page Limit on note add.
        pageLimit = (Math.ceil(pageLimitCalc));
        populateNotes(userID, roomID, pageNumber, noteLimitPerPage);
      }
      else{
        alert(data);
      }
    },
    error: function(){
      alert("ERROR");
    }
  });
}

// cancels the note deletion display on DOM and restores the `oldHTMLnote` backup
// that is generated when the confirmation display pops.
function noteDeleteCancel(noteID){
  window.event.preventDefault();
  // replace html of right container
  $("#note-container-id-" + noteID).html(oldHTMLnote);
  return;
}

// calculates visible note page displays OR hides the navigation buttons
// for notes area.
function isNavButtonVisible(){
  // handles with NEXT button
  if(pageNumber < pageLimit){
    $("#note-next").show();
  }
  else{
    $("#note-next").hide();
  }

  // handles with PREV button
  if (pageNumber > 1){
    $("#note-prev").show();
  }
  else{
    $("#note-prev").hide();
  }

  // on top of that, if there is not enough posts to reach the second page
  // the buttons are hidden
  if (noteCount <= noteLimitPerPage){
    $("#notes-nav").hide();
  }
  else{
    $("#notes-nav").show();
  }
}

// add track log eg temperature, humidity etc.
// requires context (form where the data is being pulled off)
// returns nothing. Errors are triggered by alert boxes.
// this will use AJAX requests to handle with data
// validation for the data is done inside and interacts with DOM
// to display the current stage.
function addTrackLog(context){
  // get data vars
  var trackName = context.data("track");
  var trackValue = $("#log-" + trackName + "-input").val();
  var dataMin = $("#log-" + trackName + "-input").data("min");
  var dataMax = $("#log-" + trackName + "-input").data("max");
  var trackNaming = $("#track-" + trackName + "-display").data("naming");
  var trackSymbol = context.data("symbol");
  var userID = context.data("userid");
  var tableScope = context.data("table-scope");
  var parentScope = context.data("parent-scope");
  // format value to 2 decimal places
  trackValue = Number(trackValue).toFixed(2);
  // validate vars
  if (trackValue <= dataMax && trackValue > dataMin && trackValue != 0){
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
        $("button", "#input-group-" + trackName).removeClass("btn-danger");
        $("button", "#input-group-" + trackName).addClass("btn-success");

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
    $("button", "#input-group-" + trackName).addClass("btn-danger");
    $("button", "#input-group-" + trackName).removeClass("btn-success");
    // shake input group
    $("#input-group-" + trackName).effect( "shake", {times:4, distance: 2}, 350 );
    // display error popover
    $("#input-group-" + trackName).popover("show");

  }
}

// validates note char count to match requirements
// returns nothing, interacts with the DOM to display state.
function validateCharNote(context){
  var counter = context.val().length;
  // paint new char count
  $("#input-note-length").html(counter + " / 320");

  // validate colors
  if (counter > 320 || counter <= 4){
    $("#input-note-length").css("color", "var(--danger)");
  }
  else{
    $("#input-note-length").css("color", "var(--color-green-dark)");
  }
}

// add note text log into grow.
// returns noting, requires context to settle DOM elements to interact with.
// uses AJAX requests to handle data and deal with errors with alert if any.
// will deal with validation and interact with DOM to display validation status
// if no errors > will scroll the user back to the new note offset position.
function addNote(context){
  // get input val
  var inputTitle = $("#input-note-title").val();
  var inputMessage = $("#input-note").val();
  var room = context.data("room");
  var user = context.data("id");
  var scope = "grow";
  var errorCounter = 0;

  // VALIDATIONS
  // validate input message
  if (inputMessage.length <= 4 || inputMessage.length > 320){
    // validation fails
    errorCounter++;
    // give invalid calss
    $("#input-note").addClass("is-invalid");
    // shake container
    $("#input-note").effect("shake", {times: 4, distance: 2}, 350);
  }
  else{
    // validation is ok !
    // remove invalid class
    $("#input-note").removeClass("is-invalid");
  }
  // validate input title
  if (inputTitle.length < 1 || inputTitle.length > 50){
    // validation fails
    errorCounter++;
    // give invalid class
    $("#input-note-title").addClass("is-invalid");
    // shake container
    $("#input-note-title").effect("shake", {times: 4, distance: 2}, 350);
  }
  else{
    // validation of title is ok
    // remove invalid class
    $("#input-note-title").removeClass("is-invalid");
  }

  // VALIDATIONS ARE OVER!
  if (errorCounter === 0){
      // no errors were found > proceed with ajax request
      jQuery.ajax({
        url: "ajax_requests/add_note.php",
        data: {
          user_id: user,
          parent_id: room,
          input_message: inputMessage,
          input_title: inputTitle,
          scope: scope
        },
        type: "POST",
        success: function(data){
          if (data === "ok"){
            // execute second ajax request to get data
            // populateNotes(user, room);
            // clear input data
            $("#input-note").val("");
            $("#input-note-title").val("");
            // close form collapse
            $("#add-note-log-container").collapse("hide");
            // display ok signal
            var defaultButton = $("#display-note-form").html();
            $("#display-note-form").html("<span class='text-success'><i class=\"far fa-check-circle\"></i> note created</span>");
            $("#display-note-form").fadeIn();
            // start timeout to make button default again
            setTimeout(function(){
              $("#display-note-form").html(defaultButton);
            }, 1200);
            // repopulate container with new data
            populateNotes(user, room, 1, noteLimitPerPage);
            // scroll to the new inserted note
            if ($("#notes-container div:first-child").length > 0){
              var offsetNoteTop = $("#notes-container div:first-child").offset().top - 80;
            }
            else{
              var offsetNoteTop = $("#notes-container").offset().top - 80;
            }

            $('html,body').animate({scrollTop: offsetNoteTop},'slow');

          }
          else{
            alert(data);
          }
        },
        error: function(){
          alert("Ajax request error on room.js while saving note.\nPlease report.");
          return;
        }
      });
  // increase noteCount
  noteCount++;
  // recalculates notes limit per page
  var pageLimitCalc = eval(noteCount / noteLimitPerPage);
  // gets the next integer of that value
  // same process has done on bEND to deliver the initial DOM
  // define the new page Limit on note add.
  pageLimit = (Math.ceil(pageLimitCalc));
  }
  return;
}

// resizes infobox into responsive breakdowns after opening / closing toolbox
// NOTE: I want to collapse horizontally and then expand the main container to full witdh.
// returns nothing > no params needed
function resizeInfoBox(){
  // so first i check if it has the expanded class
  if ($("#grow-box").hasClass("col-md-8")){
    // IT IS NOT EXPANDED > lets wait bootstrap to complete collapse > then expand
    setTimeout(function(){
      $("#grow-box").toggleClass("col-md-8 col-lg-9 col-xl-10");
    }, 500);
  }

  else{
    // IT IS EXPANDED > lets just toggle.
    $("#grow-box").toggleClass("col-md-8 col-lg-9 col-xl-10");
  }
  return;
};


// populate the DOM with the notes elements.
// @params [userID, roomID, pageNumber, noteLimitPerPage]
// returns nothing .
function populateNotes(user, room, page, limitPerPage){
// run ajax post
  jQuery.ajax({
    url: "ajax_requests/get_notes.php",
    data: {
      user_id: user,
      parent_id: room,
      scope: "grow",
      limit_post: limitPerPage,
      page_number: page
    },
    type: "POST",
    success: function(data){
      // decode json
      var notesArray = JSON.parse(data);
      // empty the #notes-container before anything else.
      $("#notes-container").html("");
      for(i = 0; i < notesArray.length; i++){
        // prepare vars
        // format time
        var noteTimestamp = new Date(notesArray[i]["note_timestamp"] * 1000);
        var noteDay = "0" + noteTimestamp.getDay();
        var noteMonth = "0" + noteTimestamp.getMonth() + 1;
        var noteYear = noteTimestamp.getFullYear();
        var noteHours = noteTimestamp.getHours();
        var noteMinutes = "0" + noteTimestamp.getMinutes();
        var noteFormatedTime = noteDay.substr(-2) + "/" + noteMonth.substr(-2) + "/" + noteYear + " - " + noteHours + ":" + noteMinutes.substr(-2);
        // check if read more button applies
        var readMoreButton = `<a href="#" class="btn btn-sm btn-outline-primary read-more rounded" data-div="note-${notesArray[i]["note_id"]}">read more</a>`;
        // populate #notes-container with notes html code
        $("#notes-container").append(`
          <div id="note-container-id-${notesArray[i]["note_id"]}" class="col-12 m-0 p-2 notes-body">

            <div class="row m-0 p-0">
              <div class="col-auto m-0 p-0 note-delete text-center">
                <a href="#" class="btn btn-sm btn-danger rounded note-delete-button" onClick="notePreDelete(this)" data-id="${user}" data-note="${notesArray[i]["note_id"]}">x</a>
              </div>
              <div class="col-10 m-0 p-0 notes-title">
                <h5><i class="fas fa-paperclip"></i> ${notesArray[i]["note_title"]}</h5>
                <div class="col-12 m-0 p-0 notes-time">
                  <span class="notes-time text-muted">${noteFormatedTime}</span>
                </div>
              </div>

              <div id="read-note-${notesArray[i]["note_id"]}" class="col-2 m-0 p-0 notes-read-more text-right">

              </div>

            </div>

            <div class="row m-0 p-0">

            </div>

            <div class="col-12 m-0 p-0 pt-3 notes-content border-bottom border-dark">
              <p id="note-${notesArray[i]["note_id"]}" class="text-justify notes-content-inner">
                ${notesArray[i]["note_message"]}
              </p>
            </div>
          </div>
          `);
          // truncates text if needed
          var divHeight = $("#note-" + notesArray[i]["note_id"]).height();
          var lineHeightStr = $("#note-" + notesArray[i]["note_id"]).css("line-height");
          var lineHeight = Number(lineHeightStr.replace("px", ""));
          var numberOflines = divHeight / lineHeight;

          if (numberOflines > 1){
            // append read-more button
            $("#read-note-" + notesArray[i]["note_id"]).html(readMoreButton);
            // truncate text
            $("#note-" + notesArray[i]["note_id"]).addClass("text-truncate");
          }
      }
      // listener to read more button (from notes)
      $(".read-more").on("click", function(e){
        e.preventDefault();
        // get div id
        var divID = $(this).data("div");
        // toggle class
        $("#" + divID).toggleClass("text-truncate");
      });
    },
    // dataType: "json",
    error: function(){
      alert("Ajax request error on room.js while populating notes content.\nPlease report.");
      return;
    }
  });

  // repaint buttons
  isNavButtonVisible();
}
