<?php
// NOTE: register user
// outputs true or false
function createUser(){
  $con = connectDB();
  // validate user info
  if (validateLoginInfo() != "ok"){
    // not valid
    return validateLoginInfo();
  }
  // user is valid lets get it ready
  // make readable vars
  $username = $_POST["username"];
  // retrieve and encrypt password.
  // function retrieves array with pass at[0] & salt at[1].
  $passArray = encryptPass($_POST["password"]);
  $password = $passArray[0];
  $salt = $passArray[1];
  $guid = genUniqueID();
  $time = time();
  // query
  $createQuery = mysqli_query($con, "INSERT INTO user_table (user_cguid, user_name, user_password, user_salt, user_created_at, user_last_login) VALUES ('$guid', '$username', '$password', '$salt', $time, $time)");
  // run query
  if (!$createQuery){
    print mysqli_error($con);
    return false;
  }
  // at this point user was created successfully. > redirect
  header("Location: ?view=welcome");
  return true;
}

// NOTE: this function is to validate user data registration
// retrieves true or the error according to POST data validation.
function validateLoginInfo(){
  $con = connectDB();
  // organise data into readable vars and escape injection
  $username = mysqli_real_escape_string($con, $_POST["username"]);
  $password = mysqli_real_escape_string($con, $_POST["password"]);
  $password2 = mysqli_real_escape_string($con, $_POST["password_confirm"]);
  // validate password length
  if (strlen($password) < 6 || strlen($password) > 32){
    return "password must be betwen 6 and 32 characters";
  }
  // validate password match
  if ($password != $password2){
    return "passwords doesn't match";
  }
  // validate username length
  if (strlen($username) < 4 || strlen($username) > 16){
    return "username must be between 4 and 16 characters";
  }
  // validate username characters
  if(!preg_match('/^[a-zA-Z0-9_-]{4,}$/', $username)) {
    return "username can only contain alpha numberic characters, '-' and '_'.";
  }
  // validate free username
  $checkQuery = mysqli_query($con, "SELECT user_name FROM user_table WHERE user_name = '$username'");
  $checkStatus = mysqli_num_rows($checkQuery);
  if ($checkStatus > 0){
    return "username already taken... try again.";
  }

  // validations should be above this line.
  // if validatios passed, then user is ok, return true!
  return "ok";
}

// NOTE: encpryt password with salt and md5 encryption
// returns array $out
// $out[0] => encrypted password
// $out[1] => used salt
// NOTE: salt is used in the beginning and in the end of the password.
function encryptPass($pass){
  // generate salt
  $salt = uniqid();
  $passMd5 = md5($salt . $pass . $salt);
  // from now on the encrypted pass object is called password cake. why not?
  // output password and salt
  $out = array($passMd5, $salt);
  return $out;
}

// NOTE: this function generates an unique hash type ID to the user.
// ATTENTION:
// It is possible that on a very very very uncommon scenario the id is not unique.
// Therefore it still validates the existance of the ID on the db before giving result.
// returns unique id.
function genUniqueID(){
  $con = connectDB();
  // get salt to gen unique ID
  $saltA = uniqid();
  $saltB = rand(100000000000, 9999999999999);
  $saltC = uniqid();
  $saltD = rand(100000000000, 9999999999999);;
  // generate the unique ID from salt
  $genID = $saltA . "-" . $saltB . "-" . $saltC . "-" . "$saltD";

  // validate if genID exists on user_table
  $checkQuery = mysqli_query($con, "SELECT user_cguid FROM user_table WHERE user_cguid = '$genID'");
  $checkResult = mysqli_num_rows($checkQuery);
  // validates answer
  if ($checkResult < 1){
    // the ID is unique
    return $genID;
  }
  else{
    // the id is not unique... what on earth!!
    // lets run the function again.
    genUniqueID();
  }
}


?>
