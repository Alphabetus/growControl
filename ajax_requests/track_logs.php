<?php
// NOTE: create track log on Db.
// we need:
// > table_table_scope (which table is parent of our log target?)
// > track_parent_scope (ID from origin item. eg. growRoom, plantId, etc)
// > track_scope (name of the track)

if (isset($_POST["track_name"])){
  print $do = createTrackLog($_POST['table_scope'], $_POST["parent_scope"], $_POST["track_name"]);
}

function createTrackLog($tableScope, $parentScope, $trackScope){
  require "../config/dbConfig.php";
  // get db
  $con = connectDB();
  // prepare vars in readable naming and secure for mysql
  $userID = mysqli_real_escape_string($con, $_POST["userid"]);
  $tableScope = mysqli_real_escape_string($con, $tableScope);
  $parentScope = mysqli_real_escape_string($con, $parentScope);
  $trackScope = mysqli_real_escape_string($con, $trackScope);
  // pre format value and round to 2 decimals
  $trackValue = round($_POST["track_value"], 2);
  $trackValue = mysqli_real_escape_string($con, $trackValue);
  $timestamp = time();
  // prepare query
  $insertLogQuery = mysqli_query($con, "INSERT INTO track_table (
    track_user_id,
    track_table_scope,
    track_parent_scope,
    track_scope,
    track_value,
    track_timestamp
  ) VALUES (
    '$userID',
    '$tableScope',
    '$parentScope',
    '$trackScope',
    '$trackValue',
    $timestamp
  )");
  // run query
  if (!$insertLogQuery){
    return "ERROR DB > " . mysqli_error($con);
  }
  // everything done ok! return true
  return "ok";
}
?>
