<?php
// NOTE: For better human reading this engine is a bit out of my own naming structure.
// The reason is simple... Grows engine & Grow engine is harder to read than Room engine.

// NOTE: Local vars assigned on this area
$roomData = getRoomData();
$roomTrackIcons = getTrackIconsArray($roomData);
$roomTrackValues = getTrackValuesArray($roomData);
$roomTrackNames = getTrackNamesArray($roomData);
$roomTrackButtons = getTrackButtonsArray($roomTrackNames, $roomTrackIcons);
$roomAge = getAge($roomData["grow_start_date"]);
$roomNotesPerPage = 4;
$roomLogsPerPage = 4;
$roomNotesPagesAndTotal = getNotesPagesNumber($roomNotesPerPage);
$roomLogsPagesAndTotal = getLogsPagesNumber($roomLogsPerPage);
// end of local vars

// NOTE: Room action buttons > delete grow
if (isset($_POST["delete_grow"])){
  $errorDisplay = deleteGrow();
  // check if number of lines deleted is 1 > otherwise something went quite wrong.
  if ($errorDisplay == 1){
    header("Location: ?view=grows");
  }
}

// NOTE: Functions area

// NOTE: Retrieves the number of existent notes on the room
// and calculates the number of needed pages.
// requires NUMBER OF NOTES PER PAGE  param. returns always an integer.
// lets say that we need 3,1 pages > we will get 4 pages! > it rounds UP
// OUTPUT IS:
// Array containing [0] > noteTotalCount ; [1]> numberOfPages
function getNotesPagesNumber($notesPerPage){
  //get db
  $con = connectDB();
  // prepare vars for query
  $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  // prepare query
  $notesQuery = mysqli_query($con, "SELECT * FROM note_table WHERE
      note_user_id = '$userID' AND
      note_parent_id = '$roomID' AND
      note_scope = 'grow' ");
  // count results
  $notesNumber = mysqli_num_rows($notesQuery);
  // divides by the number of NOTES PER PAGE [$notesPerPage]
  $notesPerPageRaw = ($notesNumber / $notesPerPage);
  // gets the next integer available
  $numberOfPages = ceil($notesPerPageRaw);
  // prepare array
  $outArray = array($notesNumber, $numberOfPages);
  // return array
  return $outArray;
}

// NOTE: Simillar to the previous one, retrieves the number of existent log entries on the room
// and calculates the number of needed pages.
// requires NUMBER OF LOG ENTRIES PER PAGE param, returns always an integer.
// OUTPUT IS:
// Array containing [0] > logTotalCount ; [1] > number of pages.
function getLogsPagesNumber($logsPerPage){
  // get db
  $con = connectDB();
  // prepare vars for query
  $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  // prepare query
  $logsQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
      track_user_id = '$userID' AND
      track_parent_scope = '$roomID' AND
      track_table_scope = 'grow' ");
  // count results
  $logsNumber = mysqli_num_rows($logsQuery);
  // divides by the number of LOGS PER PAGE
  $numberOFpagesRaw = ($logsNumber / $logsPerPage);
  // round to the next integer available
  $numberOFpages = ceil($numberOFpagesRaw);
  // prepare return output as Array
  $outArray = array($logsNumber, $numberOFpages);
  // return array
  return $outArray;
}

// NOTE: Delete grow
function deleteGrow(){
  // get db
  $con = connectDB();
  // prepare and secure data
  $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  // prepare query
  $deleteQuery = mysqli_query($con, "DELETE FROM grow_table WHERE grow_id = '$roomID' AND grow_user_id = '$userID'");
  // run query
  if (!$deleteQuery){
    return mysqli_error($con);
  }
  // done without errors > return true
  return true;
}

// NOTE: retrieve room array info
function getRoomData(){
  // get db
  $con = connectDB();
  // prepare vars for query
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  $uID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  // prepare query
  $roomQuery = mysqli_query($con, "SELECT * FROM grow_table WHERE grow_id = '$roomID'");
  // fetch array
  $roomArray = mysqli_fetch_array($roomQuery);

  // return array
  return $roomArray;
}

// NOTE: get track icons and retrieve array empty or filled with right icons
// requires array with at least `tracks` single-room data.
function getTrackIconsArray($roomDataArray){
  // init array
  $iconArray = array();

  // check tracks > temperature
  if ($roomDataArray["grow_tracks_temperature"]){
    // push icon into array
    array_push($iconArray, '<i class="fas fa-temperature-high room-type-tracks-icon" title="This room tracks temperature"></i>');
  }

  // check tracks > humidity
  if ($roomDataArray["grow_tracks_humidity"]){
    // push icon into array
    array_push($iconArray, '<i class="fas fa-tint room-type-tracks-icon" title="This room tracks humidity"></i>');
  }

  // check tracks > co2
  if ($roomDataArray["grow_tracks_co2"]){
    // push icon into array
    array_push($iconArray, '<i class="fas fa-wind room-type-tracks-icon" title="This room tracks Co2"></i>');
  }

  return $iconArray;
}


// NOTE: give array with roomData and retrieve track values
// returns hash array 'track' => value;
function getTrackValuesArray($roomArray){
  // get db
  $con = connectDB();
  // init empty array
  $valuesArray = array();
  // prepare common vars in readable format and safe
  $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  $parentScope = mysqli_real_escape_string($con, $_GET["room"]);
  $tableScope = "grow";
  $timestamp = time();
  // NOTE: check 1 by 1 and insert value into array if active

  // check temperature
  if ($roomArray["grow_tracks_temperature"] == 1){
    // prepare specific vars
    $trackScope = "temperature";
    // prepare query
    $getTempQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
      track_user_id = '$userID' AND
      track_table_scope = '$tableScope' AND
      track_parent_scope = '$parentScope' AND
      track_scope = '$trackScope'
      ORDER BY track_timestamp DESC
      LIMIT 1");
    // fetch result (singular)
    $tempResult = mysqli_fetch_array($getTempQuery);
    // count results to paint correct and 'cute' data
    if (empty($tempResult)){
      // there is no log yet. lets display '--'
      $valuesArray['<i class="fas fa-temperature-high"></i> temperature'] = "--";
    }
    else{
      // there is result > lets display
      $valuesArray['<i class="fas fa-temperature-high"></i> temperature'] = $tempResult["track_value"] . "&#x2103;";
    }

  }

  // check humidity
  if ($roomArray["grow_tracks_humidity"] == 1){
    // prepare specific vars
    $trackScope = "humidity";
    // prepare query
    $getHumidityQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
      track_user_id = '$userID' AND
      track_table_scope = '$tableScope' AND
      track_parent_scope = '$parentScope' AND
      track_scope = '$trackScope'
      ORDER BY track_timestamp DESC
      LIMIT 1");
    // fetch result (singular)
    $humidityResult = mysqli_fetch_array($getHumidityQuery);
    // count results to paint correct and 'cute' data
    if (empty($humidityResult)){
      // there is no log yet. lets display '--'
      $valuesArray['<i class="fas fa-tint"></i> humidity'] = "--";
    }
    else{
      // there is result > lets display
      $valuesArray['<i class="fas fa-tint"></i> humidity'] = $humidityResult["track_value"] . "%";
    }
  }

  // check co2
  if ($roomArray["grow_tracks_co2"] == 1){
    // prepare specific vars
    $trackScope = "co2";
    // prepare query
    $getCo2Query = mysqli_query($con, "SELECT * FROM track_table WHERE
      track_user_id = '$userID' AND
      track_table_scope = '$tableScope' AND
      track_parent_scope = '$parentScope' AND
      track_scope = '$trackScope'
      ORDER BY track_timestamp DESC
      LIMIT 1");
    // fetch result (singular)
    $co2Result = mysqli_fetch_array($getCo2Query);
    // count results to paint correct and 'cute' data
    if (empty($co2Result)){
      // there is no log yet. lets display '--'
      $valuesArray['<i class="fas fa-wind"></i> Co2'] = "--";
    }
    else{
      // there is result > lets display
      $valuesArray['<i class="fas fa-wind"></i> Co2'] = $co2Result["track_value"] . "%";
    }

  }

  // return array
  return $valuesArray;
}

// NOTE: retrieve array with activated track names. receives room full data.
function getTrackNamesArray($roomArray){
  // init
  $nameArray = array();

  // validate 1 by 1

  // check temperature
  if ($roomArray["grow_tracks_temperature"] == 1){
    // insert temperature
    array_push($nameArray, "temperature");
  }

  // check humidity
  if ($roomArray["grow_tracks_humidity"] == 1){
    // insert humidity
    array_push($nameArray, "humidity");
  }

  // check co2
  if ($roomArray["grow_tracks_co2"] == 1){
    // insert Co2
    array_push($nameArray, "co2");
  }


  // return array
  return $nameArray;
}

// NOTE: retrieve array with action button & form for each available track
// the origin data is 2 arrayy (trackName) & (trackIcon).
function getTrackButtonsArray($roomTrackNames, $roomTrackIcons){
  // init
  $buttonArray = array();
  // read array and add activated track buttons
  $index = 0;
  foreach ($roomTrackNames as $track) {

    // prepare to pass track math symbol as data-symbol
    switch ($track) {
      case "temperature":
        $trackSymbol = "&#x2103;";
        break;

      default:
        $trackSymbol = "%";
        break;
    }

    // push html button form into array
    array_push($buttonArray, '
        <a href="#" class="btn btn-sm text-left btn-outline-primary w-100 text-truncate px-0" data-toggle="collapse" data-target="#log-'. $track .'">'. $roomTrackIcons[$index] .'&nbsp;log '. $track .'</a>
        <div id="log-'.$track.'" class="collapse row m-0 p-0">
          <form method="POST" class="w-100">


            <div class="col-12 m-0 p-0 pt-2">
              <div id="input-group-'. $track .'" class="input-group" data-placement="bottom" data-content="Wrong value given" data>
                <input type="hidden" name="log" value="'. $track .'"/>
                <input id="log-'.$track.'-input" type="number" step="0.01" data-min="-100" data-max="100" class="form-control rounded form-control-sm" name="track-value" placeholder="log value" required/>

                <div class="input-group-append">
                  <button data-track="'. $track .'" data-symbol="'. $trackSymbol .'" data-userid="'. $_SESSION["user_id"] .'" data-table-scope="grow" data-parent-scope="'. $_GET["room"] .'" type="submit" class="create-log btn btn-sm btn-success px-5"><i class="far fa-save"></i></button>
                </div>
              </div>
            </div>

          </form>
        </div>
    ');
    // increase
    $index ++;
  }

  // return array
  return $buttonArray;
}
?>
