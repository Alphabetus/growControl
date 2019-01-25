<?php

function connectDB(){
  // SETTINGS FOR THE DB CONNECTION
  $dbLocal = "localhost";
  $dbUser = "username";
  $dbPass = "1234"
  $dbTableName = "tablename";
  // RUN > TEST AND RETRIEVE
  $con = mysqli_connect($dbLocal, $dbUser, $dbPass, $dbTableName);
  if (mysqli_connect_errno()){
    print "failed to connecto MySQL:" . mysqli_connect_error();
    return;
  }

  // return connection 
  return $con;
}
?>
