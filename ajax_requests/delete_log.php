<?php
session_start();
if ( isset($_POST["token"]) && $_SESSION["token"] === $_POST["token"]){
  print deleteLog();
}

// NOTE: requires POST params User ID, Log Id.
// returns string "ok" if all is ok otherwise gets mysql error string.

function deleteLog(){
  require "../config/dbConfig.php";
  // get db
  $con = connectDB();
  // prepare vars
  $userID = mysqli_real_escape_string($con, $_POST["user_id"]);
  $logID = mysqli_real_escape_string($con, $_POST["log_id"]);
  // prepare query
  $deleteLogQuery = mysqli_query($con, "DELETE FROM track_table WHERE
      track_user_id = '$userID' AND
      track_id = '$logID'");

  // run query
  if (!$deleteLogQuery){
    // there was error > return 'error'
    return mysqli_error($con);
  }
  else{
    // everything ok > return 'ok'
    return "ok";
  }
}
?>
