$(document).ready(function(){

// create global var for pagination > notes
pageNumberNotes = 1;
pageLimitNotes = parseInt($("#notes-nav").data("total-pages"));
limitPerPageNotes = parseInt($("#notes-nav").data("per-page"));
noteCount = parseInt($("#notes-nav").data("total-notes"));
// create global var for pagination > logs
pageNumberLogs = 1;
pageLimitLogs = parseInt($("#logs-nav").data("total-pages"));
limitPerPageLogs = parseInt($("#logs-nav").data("per-page"));
logCount = parseInt($("#logs-nav").data("total-notes"));
// read necessary id's from DOM
userID = $("#notes-container").data("id");
roomID = $("#notes-container").data("room");
authToken = $("#log-token").val();
// poulate DOM with existant notes
populateNotes(userID, roomID, pageNumberNotes, limitPerPageNotes);


// populate DOM with existant log entries
populateLogs(userID, roomID, pageNumberLogs, limitPerPageLogs);



// NOTE: ADD TRACK LOG SCRIPT  [include AJAX]
//Script to insert track log values into DB and live-time update displays
// This script is activated with the .create-log button class.

  // populate track logs with new value
  $(".create-log").on("click", function(e){
    // prevent normal submit > i wanna do it here
    e.preventDefault();
    addTrackLog($(this));
    // populate DOM with updated log entries
    populateLogs(userID, roomID, pageNumberLogs, limitPerPageLogs);
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

  // next log page button
  $("#logs-next").on("click", function(e){
    e.preventDefault();
    pageNumberLogs++;
    populateLogs(userID, roomID, pageNumberLogs, limitPerPageLogs);
    isNavButtonVisibleLogs();
  });
  // previous note page button
  $("#logs-prev").on("click", function(e){
    e.preventDefault();
    pageNumberLogs--;
    populateLogs(userID, roomID, pageNumberLogs, limitPerPageLogs);
    isNavButtonVisibleLogs();
  });

  // next note page button
  $("#note-next").on("click", function(e){
    e.preventDefault();
    pageNumberNotes++;
    populateNotes(userID, roomID, pageNumberNotes, limitPerPageNotes);
    isNavButtonVisibleNotes();
  });
  // previous note page button
  $("#note-prev").on("click", function(e){
    e.preventDefault();
    pageNumberNotes--;
    populateNotes(userID, roomID, pageNumberNotes, limitPerPageNotes);
    isNavButtonVisibleNotes();
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

// prepare the log deletion and require user confirmation
function logPreDelete(context){
  window.event.preventDefault();
  // get variables
  var context = $(context);
  var logID = context.data("log-id");
  var element = $("#log-entry-" + logID);
  // backup content before erasing DOM element contents.
  oldHTMLlog = element.html();
  // replace dom element content with confirmation / cancel buttons
  element.html(`
    <td colspan="4">

      <div class="row m-0 p-0">

        <div class="col-10 m-0 p-0 text-danger">
          Are you sure you want to remove this entry?
        </div>

        <div class="col-1 m-0 p-0 px-1 text-right">
          <a href="#" class="btn btn-sm btn-danger rounded" onClick="logDeleteConfirm(${userID}, ${logID})"><i class="fas fa-times"></i></a>
        </div>

        <div class="col-1 m-0 p-0 px-1 text-right">
          <a href="#" class="btn btn-sm btn-success rounded" onClick="logDeleteCancel(${logID})">cancel</a>
        </div>

      </div>


    </td>
    `);
}

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
    <div class="col-12 m-0 p-0 note-delete-confirm-container border-bottom border-light pb-2">
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

// deletes the log entry and repopulates data from DOM container
function logDeleteConfirm(user, log){
  window.event.preventDefault();
  // ajax request to delete log entry from DB
  jQuery.ajax({
    url: "ajax_requests/delete_log.php",
    data: {
      token: authToken,
      user_id: user,
      log_id: log
    },
    type: "POST",
    success: function(data){
      // Ajax request worked as intended.
      if (data === "ok"){
        // deletion was OK
        // decrease logCount
        logCount--;
        // recalculate log limit per page
        var pageLimitLogsCalc = eval(logCount / limitPerPageLogs);
        // gets the next integer of that value
        // same process has done on bEND to deliver the initial DOM
        // define the new page Limit on note add.
        pageLimitLogs = (Math.ceil(pageLimitLogsCalc));
        // repaint container
        populateLogs(userID, roomID, pageNumberLogs, limitPerPageLogs);
      }
      else{
        // output was something else besides 'ok' string.
        // lets deliver what happened
        alert(data);
      }
    },
    error: function(){
      alert("Error on ajax request. room.js while deleting log entry.\nPlease report");
    }
  });

  return;
}

// deletes the note entry and repopulates data from DOM container
function noteDeleteConfirm(user, note){
  window.event.preventDefault();
  // ajax request to delete entry from DB
  jQuery.ajax({
    url: "ajax_requests/delete_notes.php",
    data: {
      token: authToken,
      user_id: user,
      note_id: note
    },
    type: "POST",
    success: function(data){
      if (data === "ok"){
        // increase noteCount
        noteCount--;
        // recalculates notes limit per page
        var pageLimitNotesCalc = eval(noteCount / limitPerPageNotes);
        // gets the next integer of that value
        // same process has done on bEND to deliver the initial DOM
        // define the new page Limit on note add.
        pageLimitNotes = (Math.ceil(pageLimitNotesCalc));
        populateNotes(userID, roomID, pageNumberNotes, limitPerPageNotes);
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

function logDeleteCancel(logID){
  window.event.preventDefault();
  // replace html for right container
  $("#log-entry-" + logID).html(oldHTMLlog);
  return;
}
// calculates visible logs page displays OR hides the navigation buttons
// for logs area.
function isNavButtonVisibleLogs(){
  // handles with NEXT button
  if(pageNumberLogs < pageLimitLogs){
    $("#logs-next").show();
  }
  else{
    $("#logs-next").hide();
  }

  // handles with PREV button
  if (pageNumberLogs > 1){
    $("#logs-prev").show();
  }
  else{
    $("#logs-prev").hide();
  }

  // on top of that, if there is not enough posts to reach the second page
  // the buttons are hidden
  if (logCount <= limitPerPageLogs){
    $("#logs-nav").hide();
  }
  else{
    $("#logs-nav").show();
  }
}

// calculates visible note page displays OR hides the navigation buttons
// for notes area.
function isNavButtonVisibleNotes(){
  // handles with NEXT button
  if(pageNumberNotes < pageLimitNotes){
    $("#note-next").show();
  }
  else{
    $("#note-next").hide();
  }

  // handles with PREV button
  if (pageNumberNotes > 1){
    $("#note-prev").show();
  }
  else{
    $("#note-prev").hide();
  }

  // on top of that, if there is not enough posts to reach the second page
  // the buttons are hidden
  if (noteCount <= limitPerPageNotes){
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
          token: authToken,
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
        // increase logCount
        logCount++;
        // recalculates logs limit per page
        var pageLimitLogsCalc = eval(logCount / limitPerPageLogs);
        // gets the next integer of that value
        // same process has done on bEND to deliver the initial DOM
        // define the new page Limit on note add.
        pageLimitLogs = (Math.ceil(pageLimitLogsCalc));
        // reChecks local nav buttons visibility
        isNavButtonVisibleLogs();
      }
      else{
        alert(data);
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
          token: authToken,
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
            populateNotes(user, room, 1, limitPerPageNotes);
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
  var pageLimitNotesCalc = eval(noteCount / limitPerPageNotes);
  // gets the next integer of that value
  // same process has done on bEND to deliver the initial DOM
  // define the new page Limit on note add.
  pageLimitNotes = (Math.ceil(pageLimitNotesCalc));
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
// @params [userID, roomID, pageNumberNotes, limitPerPageNotes]
// returns nothing .
function populateNotes(user, room, page, limitPerPage){
// run ajax post request to get data array
  jQuery.ajax({
    url: "ajax_requests/get_notes.php",
    data: {
      token: authToken,
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

            <div class="col-12 m-0 p-0 pt-3 notes-content border-bottom border-light">
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
  isNavButtonVisibleNotes();
}


// populate the DOM with the logs elements.
// @params [userID, roomID, pageNumberLogs, limitPerPageLogs]
// returns nothing .
function populateLogs(user, room, page, limitPerPage){
  // run ajax request to get data array .
  jQuery.ajax({
    url: "ajax_requests/get_logs.php",
    data: {
      token: authToken,
      user_id: user,
      parent_id: room,
      page_number: page,
      post_limit: limitPerPage,
      scope: "grow"
    },
    type: "POST",
    success: function (data){
      // AJAX REQUEST WORKED JUST FINE AS IT SHOULD LETS DELIVER
      // decode json
      var logsArray = JSON.parse(data);
      // empty container
      $("#log-table").html("");
      // validate content length.. deliver default if empty
      if (logsArray.length < 1){
        $("#log-table").append(`
          <tr>
            <td colspan="*">
              -- <i>empty</i> --
            </td>
          </tr>
          `);
      }
      // loop data array and populate table
      for(i = 0; i < logsArray.length; i++){
        // prepare vars
        // format time
        var logTimestamp = new Date(logsArray[i]["track_timestamp"] * 1000);
        var logDay = "0" + logTimestamp.getDay();
        var logMonth = "0" + logTimestamp.getMonth() + 1;
        var logYear = logTimestamp.getFullYear();
        var logHours = logTimestamp.getHours();
        var logMinutes = "0" + logTimestamp.getMinutes();
        var logFormatedTime = logDay.substr(-2) + "/" + logMonth.substr(-2) + "/" + logYear + " - " + logHours + ":" + logMinutes.substr(-2);
        // format type > from string to icon
        switch (logsArray[i]["track_scope"]) {
          case 'temperature':
            trackIcon = `<i class="fas fa-temperature-high" title="log type temperature"></i>`;
            break;
          case 'humidity':
            trackIcon = `<i class="fas fa-tint" title="log type humidity"></i>`;
            break;
          case 'co2':
            trackIcon = `<i class="fas fa-wind" title="log type co2"></i>`;
            break;
          default:
            trackIcon = `<i class="fas fa-circle" title="icon not defined"></i>`;
        }
        // populate table
        $("#log-table").append(`
          <tr id="log-entry-${logsArray[i]["track_id"]}">

            <td>
              ${trackIcon}
            </td>

            <td>
              ${logFormatedTime}
            </td>

            <td>
              ${logsArray[i]["track_value"]}
            </td>

            <td class="text-center">
              <a href="#" class="btn btn-sm btn-outline-danger" onClick="logPreDelete(this)" data-log-id="${logsArray[i]["track_id"]}"><i class="fas fa-times"></i></a>
            </td>
          </tr>
          `);
      }




    },
    error: function(){
      alert("Ajax request error on room.js while populating logs content.\nPlease report.");
      return;
    }
  });

  // repaint buttons
  isNavButtonVisibleLogs();
}
