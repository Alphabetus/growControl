<?php
if(isset($_POST["user_id"])){
  print delNote();
}

// NOTE: uses params userID and noteID, looks for matching result and deletes.

function delNote(){
  require "../config/dbConfig.php";
  // get db
  $con = connectDB();
  // prepare vars for query
  $userID = mysqli_real_escape_string($con, $_POST["user_id"]);
  $noteID = mysqli_real_escape_string($con, $_POST["note_id"]);
  // prepare query
  $deleteQuery = mysqli_query($con, "DELETE FROM note_table WHERE
      note_id = '$noteID' AND
      note_user_id = '$userID'");
  // run query
  if (!$deleteQuery){
    return mysqli_error($con);
  }
  return "ok";
}
?>
