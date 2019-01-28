<?php
// NOTE:
//    Grows are meant to have dynamic stats tracking.
//    This means that we need a list of possible settings from where the user can pick which ones to activate.
//    Settings are then stored in DB as `varchar` in array format, keeping setting name.
//    To better optimization, to `avoid retrieve array > deal with data > save array` which sounds heavy,
//    I decided to prepare the `grow_table` for all possible scenarios.


// NOTE: AVAILABLE SETTINGS TO ACTIVATE PER GROW

$settingsAvailable = array(
    "temperature",
    "humidity",
    "Co2",
);

// NOTE: Generate $settingsAvailable checkbox form
function genGrowSettings($settings){
  $checkbox = null;

  foreach ($settings as $set) {
    $checkbox .= '
        <div class="col-auto d-inline ml-1">
          <input type="checkbox" class="form-check-input" id="'. $set .'_checkbox" value="'. $set .'" name="settings[]">'.$set.'</input>
        </div>
    ';
  }
  print $checkbox;
  return;
}


// NOTE: create local vars to populate form if vars are already set
if (isset($_POST["grow_name"])){$input_growName = $_POST["grow_name"];} else {$input_growName = null;}
if (isset($_POST["grow_description"])){$input_growBio = $_POST["grow_description"];} else {$input_growBio = null;}
if (isset($_POST["grow_area"])){$input_growArea = $_POST["grow_area"];} else {$input_growArea = null;}
if (isset($_POST["grow_volume"])){$input_growVolume = $_POST["grow_volume"];} else {$input_growVolume = null;}
if (isset($_POST["grow_date"])){$input_growDate = $_POST["grow_date"];}

// NOTE: Create grow function
function addGrow($settingsAvailable){
  // db
  $con = connectDB();
  // prepare vars in readable format and secure data for mysql
  $growName = mysqli_real_escape_string($con, $_POST["grow_name"]);
  $growBio = mysqli_real_escape_string($con, $_POST["grow_description"]);
  $growDate = mysqli_real_escape_string($con, $_POST["grow_date"]);
  $growType = mysqli_real_escape_string($con, $_POST["grow_type"]);
  $growArea = mysqli_real_escape_string($con, $_POST["grow_area"]);
  $growVolume = mysqli_real_escape_string($con, $_POST["grow_volume"]);
  // initialze tracking stuff
  $track_temp = 0; $track_hum = 0; $track_co2 = 0;
  // double check for defined settings.. otherwise if empty it buggs out.
  if (isset($_POST["settings"])){
    $growTracks = $_POST["settings"];
  }
  else{
    $growTracks = array();
  }
  // NOTE: validations start here
  // initialize error array
  $errors = array();
  // validate name size
  if (strlen($growName) > 32 || strlen($growName) < 4){
    array_push($errors, "grow name must be between 4 and 32 characters");
  }
  // validate bio size
  if (strlen($growBio) > 0){
    if (strlen($growBio) > 160 || strlen($growBio) < 4){
      array_push($errors, "grow bio must be between 4 and 160 characters");
    }
  }
  // validate grow type
  if ($growType != "indoor" && $growType != "outdoor"){
    array_push($errors, "something went wrong with grow type selection");
  }
  // validate grow tracks if there are any selected
  if (!empty($growTracks)){
    foreach ($growTracks as $track) {
      if (!in_array($track, $settingsAvailable)){
        array_push($errors, $track . " is not a valid tracking option");
      }
    }
    // NOTE: Lets prepare the tracks for DB

    if (in_array("temperature", $growTracks)){
      // mark temperature as readable
      $track_temp = 1;
    }
    if (in_array("humidity", $growTracks)){
      // mark humidity as readable
      $track_hum = 1;
    }
    if (in_array("Co2", $growTracks)){
      // mark humidity as readable
      $track_co2 = 1;
    }
  }
  // validate grow area
  if ($growArea > 999 || $growArea < 0){
    array_push($errors, "wrong value for area given");
  }
  // validate grow volume
  if ($growVolume > 999 || $growArea < 0){
    array_push($errors, "wrong value for volume given");
  }

  // NOTE: Validations are done. lets check for any value on errors array
  if (!empty($errors)){
    // there is error.
    return $errors;
  }

  else{
    // NOTE: No errors.. Lets store the data
    // prepare missing data
    $userID = $_SESSION["user_id"];
    $growStartDate = strtotime($growDate);
    $growStatus = 1;
    // prepare query
    $insertGrowQuery = mysqli_query($con,
    "INSERT INTO grow_table (
      grow_user_id,
      grow_name,
      grow_description,
      grow_start_date,
      grow_type,
      grow_area,
      grow_volume,
      grow_tracks_temperature,
      grow_tracks_humidity,
      grow_tracks_co2,
      grow_status
    ) VALUES (
      $userID,
      '$growName',
      '$growBio',
      $growStartDate,
      '$growType',
      $growArea,
      $growVolume,
      $track_temp,
      $track_hum,
      $track_co2,
      $growStatus
    )");

    // NOTE: Query is ready lets run it
    if (!$insertGrowQuery){
      // error running query
      array_push($errors, "DB ERROR><br>" . mysqli_error($con));
    }
  }

  // NOTE: EVERYTHING DONE... Time to check for errors again and give return.
  if (!empty($errors)){
    return $errors;
  }
  else{
    // no error! Grow added successfully!
    return true;
  }
}
?>
