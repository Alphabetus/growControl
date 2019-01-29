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
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item">
        <i class="fas fa-temperature-high"></i>&nbsp;Temperature : 0&#8451;
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
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item">
        <i class="fas fa-tint"></i>&nbsp;Humidity : 0%
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
    $out = '
      <div class="col-12 m-0 p-0 panel-body-item">
        <i class="fas fa-wind"></i>&nbsp;Co2 : 0%
      </div>
    ';
  }
  // return out
  return $out;
}
?>
