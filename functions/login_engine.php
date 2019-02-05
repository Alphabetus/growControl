<?php
// NOTE: Function reads POST data username & password. encrypts accordingly and check for existante user & match. If OK creates the local session cookie + DB safe cookie.
function loginUser(){
  //get db
  $con = connectDB();
  // make given data vars readable and safe
  $username = mysqli_real_escape_string($con, $_POST["username"]);
  $password = mysqli_real_escape_string($con, $_POST["password"]);
  // lets check if the given username exists on the DB.
  // if it does... give me the salts and the password cake.
  $userQuery = mysqli_query($con, "SELECT * FROM user_table WHERE user_name = '$username'");
  $userCheck = mysqli_num_rows($userQuery);
  // is user there?
  if ($userCheck < 1){
    // he is not. return error description
    return "username not found.";
  }
  else{
    // user is there > get his data
    $userInfoArray = mysqli_fetch_array($userQuery);
  }
  // lets compare post data with user info
  $passwordCake_user = $userInfoArray["user_password"];
  $salt_user = $userInfoArray["user_salt"];
  // lets make the post password cake
  $passwordCake_given = md5($salt_user . $password . $salt_user);
  // lets validate both password cakes and look for a match
  if ($passwordCake_user != $passwordCake_given){
    // passwords dont match
    return "wrong login details.";
  }
  else{
    // data does match > lets loggin user
    // preare readable vars
    $maskedIP = getIP();
    $maskedAgent = getAgent();
    $userGuid = $userInfoArray["user_cguid"];
    $userID = $userInfoArray["user_id"];
    $username = $userInfoArray["user_name"];
    $sessionTime = time();
    // generate POST autenticity token
    $sessionUid = uniqid();
    $sessionToken = md5($sessionTime . $sessionUid . $userID);
    // kill any existant user session > db sided
    // This will eventually logout any fake user.
    killSessionDB($userID);
    // create SESSION session and store relevant user data
    $_SESSION["user_name"] = $username;
    $_SESSION["user_id"] = $userID;
    $_SESSION["user"] = $userGuid;
    $_SESSION["locale"] = $maskedIP;
    $_SESSION["agent"] = $maskedAgent;
    $_SESSION["token"] = $sessionToken;
    // create DB session query
    $sessionInsertQuery = mysqli_query($con, "INSERT INTO session_table (
          session_user_id,
          session_user_cguid,
          session_agent,
          session_locale,
          session_time,
          session_token
        ) VALUES (
          $userID,
          '$userGuid',
          '$maskedAgent',
          '$maskedIP',
          $sessionTime,
          '$sessionToken'
        )");
    // run query
    if (!$sessionInsertQuery){
      return "error with DB:<br>" . mysqli_error($con);
    }

    // so far so good.. lets update the 'user_last_login' field on user_table
    // prepara update query
    $updateLoginTimeQuery = mysqli_query($con, "UPDATE user_table SET user_last_login = $sessionTime WHERE user_id = $userID");
    // run update last login time query
    if (!$updateLoginTimeQuery){
      return "error with DB:<br>" . mysqli_error($con);
    }
  }
  // all done > return true
  return true;
}


?>
