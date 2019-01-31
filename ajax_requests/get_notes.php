<?php

if(isset($_POST["parent_id"])){
  print getNotesArray();
}

function getNotesArray(){
  require "../config/dbConfig.php";
  // get db
  $con = connectDB();
  // init array
  $resultsArray = array();
  // prepare and secure vars
  $userID = mysqli_real_escape_string($con, $_POST["user_id"]);
  $parentID = mysqli_real_escape_string($con, $_POST["parent_id"]);
  $scope = mysqli_real_escape_string($con, $_POST["scope"]);
  $pageNumberRaw = mysqli_real_escape_string($con, $_POST["page_number"]);
  $pageNumber = intval($pageNumberRaw);
  $limitRaw = mysqli_real_escape_string($con, $_POST["limit_post"]);
  $limit = intval($limitRaw);
  // calculate offset
  $offset = (($limit * $pageNumber) - $limit);
  // prepare query
  $getNotesQuery = mysqli_query($con, "SELECT * FROM note_table WHERE
    note_user_id= '$userID' AND
    note_parent_id = '$parentID' AND
    note_scope = '$scope'
    ORDER BY note_timestamp DESC LIMIT $limit OFFSET $offset");

  // loop results and create array
  while ($note = mysqli_fetch_array($getNotesQuery)){
    array_push($resultsArray, $note);
  }
  $output = json_encode($resultsArray);
  return $output;

}

?>
