<?php
require "../config/dbConfig.php";

$con = connectDB();

if (isset($_POST["username_check"])){
  $user = mysqli_real_escape_string($con, $_POST["username_check"]);
  $usernameQ = mysqli_query($con, "SELECT user_name FROM user_table WHERE user_name = '$user'");

  // validate
  if (mysqli_num_rows($usernameQ) > 0){
    print "used";
  }
  else{
    print "free";
  }
}


?>
