<?php
// session start
session_start();
// error display if any
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// format timezone
date_default_timezone_set('Europe/Berlin');
// include functions
require "functions/general_engine.php";
require "config/dbConfig.php";
// logout general action
if (isset($_GET["logout"])){
  logoutUser($_SESSION["user_id"]);
}
// token autenticity validation
if (isset($_POST["token"]) && $_POST["token"] != $_SESSION["token"]){
  logoutUser($_SESSION["user_id"]);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap stuff -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <!-- end of bootstrap stuff -->
    <!-- jquery ui stuff -->
    <link rel="stylesheet" href="theme/jquery-ui.min.css"/>
    <!-- font awesome stuff -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <!-- /font awesome stuff -->
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Rubik|Ubuntu" rel="stylesheet">
    <!-- /google fonts -->
    <!-- get jquery braw -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- link custom theme -->
    <link rel="stylesheet" href="theme/theme01.css">
    <!-- /link custom theme -->
    <title>Grow Control</title>
  </head>
  <body>

    <!-- navigation partial -->
    <?php
      if (isset($_SESSION["user"])){
        include "partials/navigation_logged.php";
      }
      else{
        include "partials/navigation.php";
      }
    ?>
    <!-- /navigation partial -->
    <div class="container-fluid">

      <?php
        if (isset($_GET["view"])){
          // we have a view requested > lets deliver
          include "views/" . $_GET["view"] . "_view.php";
        }
        else{
          // we do not have a view requested > lets deliver landing
          include "views/landing_view.php";
        }
      ?>
    </div>
    <?php include "partials/footer.php"; ?>
    <!-- call scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <!-- end of call scripts -->
  </body>
</html>
