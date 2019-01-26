<?php
// get DB configuration
$directory_array = explode('/',__DIR__);
$dir = "/" . $directory_array[1] . "/" . $directory_array[2] . "/" . $directory_array[3] . "/config/dbConfig.php";
require $dir;
// get DB connection
$con = connectDB();

// settings > will probably further move this to a settings table on DB
// session max time in minutes:
$sessionMaxTimeMin = 20;


// math format of deadline
$sessionMaxTimeSeconds = $sessionMaxTimeMin * 60;
$now = time();
$sessionDeadline = $now - $sessionMaxTimeSeconds;
// query
$deleteQuery = mysqli_query($con, "DELETE FROM session_table WHERE session_time < $sessionDeadline");
// run query
if (!$deleteQuery){
  print "ERROR WITH SESSION_KILLER CRONTAB JOB:";
  print mysqli_error($con);
}

?>
