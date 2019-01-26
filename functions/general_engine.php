<?php
// NOTE: this function retrieves the string content from given filename and language excluding extension 'txt'.
// Strings should be stored on /strings/langFolder
// Logged in users will eventually be able to define $lang on their own settings / cookies.
function getString($fileName, $lang){
  $filePath = "strings/" . $lang . "/" . $fileName . ".txt";
  $out = file_get_contents($filePath);
  if ($out === false){
    print "ERROR.: String file '/".$lang."/". $fileName .".txt' not found.";
  }
  else{
    print $outFormated = str_replace("\n", "<br>", $out);
  }
}


// NOTE: this retrieves the <h3>stuf</h3> that is used on several parts between login and register on logged off page.
function getSplashTitle(){
  print '
    <h3 class="text-center">
      <i class="fas fa-cannabis text-danger"></i>
      <i class="fas fa-cannabis text-warning"></i>
      <i class="fas fa-cannabis text-success"></i>
      Grow Control
      <i class="fas fa-cannabis text-success"></i>
      <i class="fas fa-cannabis text-warning"></i>
      <i class="fas fa-cannabis text-danger"></i>
    </h3>
  ';
}


// NOTE: For user security i am forced to read IP in order to secure the logged in system.
// For better anonymously usage, im ecnrypting the IP with md5() encription and storing it this way.
function getIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';


    return md5($ipaddress);
}


// NOTE: For user security i am also forced to read useragent in order to secure the logged in system.
// for better anonymously usage, im encrypting the result with md5() and storing it this way.
function getAgent(){
  return md5($_SERVER['HTTP_USER_AGENT']);
}


// NOTE: This is a security function.
// Purpose > Destroy any DB session on given user ID.
// NO OUTPUT > Just execute. If there is session > kill, if else ignore.
function killSessionDB($uID){
  // get db
  $con = connectDB();
  // look for and delete session with given id.
  $sessionQuery = mysqli_query($con, "DELETE FROM session_table WHERE session_user_id = $uID");
  // done
  return;
}


// NOTE: This is the logout function.
// It destroys everything related and brings back the user to '/'
function logoutUser($uID){
  // delete session from DB
  killSessionDB($uID);
  // empty session array
  $_SESSION = array();
  // redirect to homepage
  header("Location: /");
  return;
}

// NOTE: Function to keep logged in users out of authenticated areas
function lock(){
  // get DB
  $con = connectDB();
  // get session vars
  $userID = $_SESSION["user_id"];
  $userGuid = $_SESSION["user"];
  $locale = $_SESSION["locale"];
  $agent = $_SESSION["agent"];
  // check session table for this entry > query
  $sessionQuery = mysqli_query($con, "SELECT * FROM session_table WHERE session_user_id = '$userID' AND session_user_cguid = '$userGuid' AND session_locale = '$locale' AND session_agent = '$agent'");
  // check session match
  if (mysqli_num_rows($sessionQuery) != 1){
    // no matching stuff > logout user
    logoutUser($userID);
    return;
  }

  // everything is ok! Let's refresh session lifetime .
  // lets get the session DB id
  $sessionArray = mysqli_fetch_array($sessionQuery);
  $sessionID = $sessionArray["session_id"];
  // get the unix time now
  $now = time();
  // prepare query
  $updateSessionQuery = mysqli_query($con, "UPDATE session_table SET session_time = $now WHERE session_id = $sessionID");
  // run query
  if (!$updateSessionQuery){
    print mysqli_error($con);
    return;
  }
  // done
  return;
}
?>
