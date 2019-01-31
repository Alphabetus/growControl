<?php

if (isset($_POST["input_message"])){
  print createNote();
}


// NOTE: create grow room note function
function createNote(){
  require "../config/dbConfig.php";
  // get db
  $con = connectDB();
  // prepare and secure vars
  $userID = mysqli_real_escape_string($con, $_POST["user_id"]);
  $scope = mysqli_real_escape_string($con, $_POST["scope"]);
  $parentID = mysqli_real_escape_string($con, $_POST["parent_id"]);
  $message = mysqli_real_escape_string($con, $_POST["input_message"]);
  $title = mysqli_real_escape_string($con, $_POST["input_title"]);
  $time = time();

  // prepare query
  $insertNoteQuery = mysqli_query($con, "INSERT INTO note_table
  (
    note_user_id,
    note_scope,
    note_parent_id,
    note_timestamp,
    note_title,
    note_message
  ) VALUES
  (
    '$userID',
    '$scope',
    '$parentID',
    $time,
    '$title',
    '$message'
  )");

  // run quuery
  if (!$insertNoteQuery){
    return print mysqli_error($con);
  }

  // everything ok! return OK
  return "ok";
}
?>
