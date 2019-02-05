<?php
session_start();
if ( isset($_POST["token"]) && $_SESSION["token"] === $_POST["token"]){
  print getLogsArray();
}

// NOTE: reads post data to retrieve complete logs array according to limit.
// POST params:
//
// > userid
// > parentID [room id]
// > scope [room / plant / photo etc.]
// > page number to deal with offset
// > post limit per page to deal with limit
//
// Even tho i am not YET using the same file to retrieve all scopes of logs
// that is the final objective.
//
// you will notice that this function is repeated per scope.
// This will be removed upon release.

// I was just building it from scratch, without any idea of the final table form.
// Once i achieve the required final DB structure I'll transform all the redundant
// `notes` `logs` `etc` retrieve `get_fileName` ajax requests into a single file / function
// that filters based on the SCOPE!

function getLogsArray(){
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
  $limitRaw = mysqli_real_escape_string($con, $_POST["post_limit"]);
  $limit = intval($limitRaw);
  // calculate offset
  $offset = (($limit * $pageNumber) - $limit);
  // prepare query
  $getNotesQuery = mysqli_query($con, "SELECT * FROM track_table WHERE
    track_user_id= '$userID' AND
    track_parent_scope = '$parentID' AND
    track_table_scope = '$scope'
    ORDER BY track_timestamp DESC LIMIT $limit OFFSET $offset");
  // loop results and create array
  while ($note = mysqli_fetch_array($getNotesQuery)){
    array_push($resultsArray, $note);
  }
  // encode to JSON
  $output = json_encode($resultsArray);
  // return encoded to ajax var data
  return $output;
}
?>
