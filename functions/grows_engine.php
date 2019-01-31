<?php
// NOTE: Retrieve grow array per user
function getGrowsArray(){
  // grows array init
  $growArray = array();
  // connect db
  $con = connectDB();
  // prepare vars
  $uID = $_SESSION["user_id"];
  // prepare query
  $getGrowsQuery = mysqli_query($con, "SELECT * FROM grow_table WHERE grow_user_id = $uID AND grow_status = 1 ORDER BY grow_start_date DESC");
  // fetch query and return as array
  while ($row = mysqli_fetch_array($getGrowsQuery)){
    array_push($growArray, $row);
  }
  if (empty($growArray)){
    // array is empty. return empty array!
    return array();
  }
  else{
    return $growArray;
  }
}

// NOTE: retrieve temperature display if enabled
function growTemperature($growID, $trackStatus){
  // get db
  $con = connectDB();
  // init out
  $out = null;
  // verify status
  if ($trackStatus > 0){
    // temperature is active > populate $out
    // prepare vars
    $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
    // prepare queries
    // sum query
    $getTrackTotalQuery = mysqli_query($con, "SELECT sum(track_value) AS value_temp FROM track_table WHERE
        track_scope = 'temperature' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // count query results
    $getTrackCounterQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
        track_scope = 'temperature' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // fetch assoc
    $getTrackTotal = mysqli_fetch_array($getTrackTotalQuery);
    $getTrackCounter = mysqli_num_rows($getTrackCounterQuery);
    if ($getTrackCounter < 1){
      // there is no records for this track > give default value
      $trackTotal = "--";
    }
    else{
      // there is at least 1 record > lets calculate average
      $trackTotal = round(($getTrackTotal["value_temp"] / $getTrackCounter), 1);
    }
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item" title="Average temperature">
        <i class="far fa-dot-circle"></i>&nbsp;<i class="fas fa-temperature-high"></i>&nbsp;Temperature : '. $trackTotal .'&#8451;
      </div>
    ';
  }
  // return out
  return $out;
}


// NOTE: retrieve humidity display if enabled
function growHumidity($growID, $trackStatus){
  // get db
  $con = connectDB();
  // init out
  $out = null;
  // verify status
  if ($trackStatus > 0){
    // humidity is active > populate $out
    // prepare vars
    $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
    // prepare queries
    // sum query
    $getTrackTotalQuery = mysqli_query($con, "SELECT sum(track_value) AS value_temp FROM track_table WHERE
        track_scope = 'humidity' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // count query results
    $getTrackCounterQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
        track_scope = 'humidity' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // fetch assoc
    $getTrackTotal = mysqli_fetch_array($getTrackTotalQuery);
    $getTrackCounter = mysqli_num_rows($getTrackCounterQuery);
    if ($getTrackCounter < 1){
      // there is no records for this track > give default value
      $trackTotal = "--";
    }
    else{
      // there is at least 1 record > lets calculate average
      $trackTotal = round(($getTrackTotal["value_temp"] / $getTrackCounter), 1);
    }
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item" title="Average humidity">
        <i class="far fa-dot-circle"></i>&nbsp;<i class="fas fa-tint"></i>&nbsp;Humidity : '. $trackTotal .'%
      </div>
    ';
  }
  // return out
  return $out;
}


// NOTE: retrieve humidity display if enabled
function growCo2($growID, $trackStatus){
  // get db
  $con = connectDB();
  // init out
  $out = null;
  // verify status
  if ($trackStatus > 0){
    // CO2 is active > populate $out
    // prepare vars
    $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
    // prepare queries
    // sum query
    $getTrackTotalQuery = mysqli_query($con, "SELECT sum(track_value) AS value_temp FROM track_table WHERE
        track_scope = 'co2' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // count query results
    $getTrackCounterQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
        track_scope = 'co2' AND
        track_user_id = '$userID' AND
        track_parent_scope = $growID");
    // fetch assoc
    $getTrackTotal = mysqli_fetch_array($getTrackTotalQuery);
    $getTrackCounter = mysqli_num_rows($getTrackCounterQuery);
    if ($getTrackCounter < 1){
      // there is no records for this track > give default value
      $trackTotal = "--";
    }
    else{
      // there is at least 1 record > lets calculate average
      $trackTotal = round(($getTrackTotal["value_temp"] / $getTrackCounter), 1);
    }
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item" title="Average Co2">
        <i class="far fa-dot-circle"></i>&nbsp;<i class="fas fa-wind"></i>&nbsp;Co2 : '. $trackTotal .'%
      </div>
    ';
  }
  // return out
  return $out;
}
?>
