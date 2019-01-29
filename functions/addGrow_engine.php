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

    // NOTE: in here i breakdown the function into 2 parts.
    // first part is a clean new grow.
    // second part is editing an existant grow and getting all possible tracks available again,
    // then checking the box of the ones that are already selected.
    // NOTE: Im using $_GET["view"] to filter de scenarios. addGrow or editGrow.

    if ($_GET["view"] === "editGrow"){
      // we are editing an existent grow. > lets populate the filled settings.
      // get DB
      $con = connectDB();
      // prepare db vars
      $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
      $roomID = mysqli_real_escape_string($con, $_GET["room"]);
      // prepare room info query
      $roomQuery = mysqli_query($con, "SELECT * FROM grow_table WHERE grow_user_id = '$userID' AND grow_id = '$roomID' LIMIT 1");
      // fetch the info into array
      $roomData = mysqli_fetch_array($roomQuery);

      // validate if user is tracking this settings
      $settingFieldName = "grow_tracks_" . strtolower($set);
      $settingStatus = $roomData[$settingFieldName];
      // init
      $selectedTag = "";
      if ($settingStatus === "1"){
        $selectedTag = 'checked="checked"';
      }

      $checkbox .= '
        <div class="col-auto d-inline ml-1">
          <input type="checkbox" class="form-check-input" id="'. $set .'_checkbox" value="'. $set .'" name="settings[]" '. $selectedTag .'>'.$set.'</input>
        </div>
      ';
      // print the generated checkbox options and return
    }

    else{
      $checkbox .= '
          <div class="col-auto d-inline ml-1">
            <input type="checkbox" class="form-check-input" id="'. $set .'_checkbox" value="'. $set .'" name="settings[]">'.$set.'</input>
          </div>
      ';
    }

  }
  print $checkbox;
  return;
}


// NOTE: create local vars to populate form if vars are already set
if (isset($_POST["grow_name"])){$input_growName = $_POST["grow_name"];} else {$input_growName = null;}
if (isset($_POST["grow_description"])){$input_growBio = $_POST["grow_description"];} else {$input_growBio = null;}
if (isset($_POST["grow_area"])){$input_growArea = $_POST["grow_area"];} else {$input_growArea = null;}
if (isset($_POST["grow_height"])){$input_growHeight = $_POST["grow_height"];} else {$input_growHeight = null;}
if (isset($_POST["grow_date"])){$input_growDate = $_POST["grow_date"];}
if (isset($_POST["grow_lamps"])){$input_growLamps = $_POST["grow_lamps"];}
if (isset($_POST["grow_power"])){$input_growPower = $_POST["grow_power"];}

// NOTE: Edit Grow Function
function editGrow($settingsAvailable){
  // db
  $con = connectDB();
  // prepare vars in readable format and secure data for mysql
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  $growName = mysqli_real_escape_string($con, $_POST["grow_name"]);
  $growBio = mysqli_real_escape_string($con, $_POST["grow_description"]);
  $growDate = mysqli_real_escape_string($con, $_POST["grow_date"]);
  $growType = mysqli_real_escape_string($con, $_POST["grow_type"]);
  $growArea = mysqli_real_escape_string($con, $_POST["grow_area"]);
  $growHeight = mysqli_real_escape_string($con, $_POST["grow_height"]);
  $growLamps = mysqli_real_escape_string($con, $_POST["grow_lamps"]);
  $growPower = mysqli_real_escape_string($con, $_POST["grow_power"]);
  $growPowerType = mysqli_real_escape_string($con, $_POST["grow_power_type"]);
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
  // validate grow Height
  if ($growHeight > 999 || $growArea < 0){
    array_push($errors, "wrong value for height given");
  }

  // NOTE: Validations are done. lets check for any value on errors array
  if (!empty($errors)){
    // there is error.
    return $errors;
  }

  else{
    // NOTE: No errors.. Lets store the data
    // prepare missing data
    $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
    $growStartDate = strtotime($growDate);
    $growStatus = 1;
    // prepare query values
    $growArea = round($growArea, 2);
    $growHeight = round($growHeight, 2);
    $growLamps = intval($growLamps);
    $growPower = intval($growPower);
    $updateGrowQuery = mysqli_query($con, "UPDATE grow_table SET
      grow_name = '$growName',
      grow_description = '$growBio',
      grow_start_date = $growStartDate,
      grow_type = '$growType',
      grow_area = $growArea,
      grow_height = $growHeight,
      grow_lamps = $growLamps,
      grow_power = $growPower,
      grow_power_type = '$growPowerType',
      grow_tracks_temperature = $track_temp,
      grow_tracks_humidity = $track_hum,
      grow_tracks_co2 = $track_co2

      WHERE grow_user_id = '$userID' AND grow_id = '$roomID'
    ");

    // NOTE: Query is ready lets run it
    if (!$updateGrowQuery){
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
  $growHeight = mysqli_real_escape_string($con, $_POST["grow_height"]);
  $growLamps = mysqli_real_escape_string($con, $_POST["grow_lamps"]);
  $growPower = mysqli_real_escape_string($con, $_POST["grow_power"]);
  $growPowerType = mysqli_real_escape_string($con, $_POST["grow_power_type"]);
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
  // validate grow Height
  if ($growHeight > 999 || $growArea < 0){
    array_push($errors, "wrong value for height given");
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
    // prepare query values
    $growArea = round($growArea, 2);
    $growHeight = round($growHeight, 2);
    $growLamps = intval($growLamps);
    $growPower = intval($growPower);
    $insertGrowQuery = mysqli_query($con,
    "INSERT INTO grow_table (
      grow_user_id,
      grow_name,
      grow_description,
      grow_start_date,
      grow_type,
      grow_area,
      grow_height,
      grow_lamps,
      grow_power,
      grow_power_type,
      grow_tracks_temperature,
      grow_tracks_humidity,
      grow_tracks_co2,
      grow_status
    ) VALUES (
      '$userID',
      '$growName',
      '$growBio',
      '$growStartDate',
      '$growType',
      $growArea,
      $growHeight,
      $growLamps,
      $growPower,
      '$growPowerType',
      '$track_temp',
      '$track_hum',
      '$track_co2',
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
