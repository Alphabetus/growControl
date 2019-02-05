<?php
// NOTE: check for autenticity on POST requests.
// this function reads $_SESSION["token"] and compares with given on $_POST["token"];
//
// Main engine is make to deny POST requests without token autenticity by logging out the user.
// log system will be applied later together with all errors.
function checkPost(){
  if ($_SESSION["token"] != $_POST["token"]){
    logoutUser($_SESSION["user_id"]);
  }
  return;
}

// NOTE: retrieve the POST autenticity token as string.
// require logged in user. retrieves token from $_SESSION["token"].
function getPostToken(){
  return $_SESSION["token"];
}


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
      <i class="fas fa-leaf text-success"></i>
      <i class="fas fa-leaf text-success"></i>
      <i class="fas fa-leaf text-success"></i>
      Grow Control
      <i class="fas fa-leaf fa-flip-horizontal text-success"></i>
      <i class="fas fa-leaf fa-flip-horizontal text-success"></i>
      <i class="fas fa-leaf fa-flip-horizontal text-success"></i>
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

// // NOTE: Keep users out of the rooms that are not theirs
function roomLock(){
  $con = connectDB();
  // get user session var
  $userID = mysqli_real_escape_string($con, $_SESSION["user_id"]);
  $roomID = mysqli_real_escape_string($con, $_GET["room"]);
  // query to check if user owns room
  $checkQuery = mysqli_query($con, "SELECT grow_id, grow_user_id FROM grow_table WHERE grow_user_id = '$userID' AND grow_id = '$roomID'");
  // count matces > to be true must be === 1 > else redirects user to grows view. just like that.
  // no info if the room exists or whatever else.
  // he simply shouldn't be there.
  if (mysqli_num_rows($checkQuery) != 1){
    header("Location: ?view=grows");
  }
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


// NOTE: function to retrieve view title
function getViewTitle(){
  // divide string into array, exploding on the first uppercase.
  $viewArray = preg_split('/(?=[A-Z])/',$_GET["view"]);
  // count the number of words
  $numberOfWords = count($viewArray);
  // format accordingly
  if ($numberOfWords > 1){
    // there is 2 words.
    //lets uppercase all and merge them into single string with a space in between.
    $viewName = strtoupper($viewArray[0] . " " . $viewArray[1]);
  }
  else{
    // there is only 1 word.
    // lets uppercase.
    $viewName = strtoupper($viewArray[0]);
  }

  // NOTE: Lets check if we are on a grow room > if we are, lets give the room name instead of the default view name
  if ($_GET["view"] === "grow"){
    // get db
    $con = connectDB();
    // prepare query
    $gID = mysqli_real_escape_string($con, $_GET["room"]);
    $roomNameQuery = mysqli_query($con, "SELECT grow_id, grow_name FROM grow_table WHERE grow_id = '$gID'");
    // fetch grow name
    $gArray = mysqli_fetch_array($roomNameQuery);
    $gName = $gArray["grow_name"];
    // output the grow room name and override previous setting
    $viewName = $gName;
  }

  return $viewName;
}


// NOTE: Retrieve age based on given unix timestamp. Age is calculated based on time()
// output is in days. with 1 decimal value.
function getAge($timestamp){
  $now = time();
  $diff = $now - $timestamp;
  // we got diff > lets get minutes > hours > days
  $minutes = $diff / 60;
  $hours = $minutes / 60;
  $days = round(($hours / 24), 1);

  // return days
  return $days;
}



?>
