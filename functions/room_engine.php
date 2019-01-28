<?php
// NOTE: For better human reading this engine is a bit out of my own naming structure.
// The reason is simple... Grows engine & Grow engine is harder to read than Room engine.

// lock room to owner
roomLock();

// NOTE: Local vars assigned on this area
$roomData = getRoomData();
$roomTrackIcons = getTrackIconsArray($roomData);
$roomTrackValues = getTrackValuesArray($roomData);
$roomAge = getAge($roomData["grow_start_date"]);
// end of local vars

// NOTE: Functions area


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
  // check 1 by 1 and insert value into array if active

  // check temperature
  if ($roomArray["grow_tracks_temperature"] == 1){
    // get temperature value from tracks table <> to do
    $valuesArray['<i class="fas fa-temperature-high"></i> temperature'] = "27&#x2103;";
  }

  // check humidity
  if ($roomArray["grow_tracks_humidity"] == 1){
    // get humidity value from tracks table <> to do
    $valuesArray['<i class="fas fa-tint"></i> humidity'] = "76.25%";
  }

  // check co2
  if ($roomArray["grow_tracks_co2"] == 1){
    // get co2 value from tracks table <> to do
    $valuesArray['<i class="fas fa-wind"></i> co2'] = "16.53%";
  }

  // return array
  return $valuesArray;
}
?>
