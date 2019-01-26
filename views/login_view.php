<?php
// initialize local output var
$loginOutput = null;
// require engine
require "functions/login_engine.php";

// action on login button
if (isset($_POST["username"])){
  $loginOutput = loginUser();
  if ($loginOutput === true){
    // user logged in > relocate view
    header("Location: ?view=dashboard");
  }
}
?>
<div id="userauth" class="content-padding-top row justify-content-center section-box">

  <div class="col-12 col-md-8 align-self-center p-0 auth-container rounded shadow">

    <!-- header row -->
    <div class="row justify-content-center">
      <div class="col-12 mx-0 p-0 mt-3">

        <?php getSplashTitle(); ?>

      </div>
    </div>

    <!-- login form row -->
    <div class="row justify-content-center">

      <form method="POST" class="w-75 mb-3">

        <div class="form-group">
          <label for="username_field">username</label>
          <input name="username" type="text" class="form-control" id="username_field">
        </div>

        <div class="form-group">
          <label for="password_field">password</label>
          <input name="password" type="password" class="form-control" id="password_field">
        </div>

        <div class="col-12 text-right m-0 p-0">
          <a href="/" class="btn btn-sm btn-outline-info btn-auth">back</a>
          <a href="?view=register" class="btn btn-sm btn-outline-info btn-auth">register</a>
          <button type="submit" class="btn btn-sm btn-outline-info btn-auth">enter</button>
        </div>
        <div class="col-12 text-center text-warning m-0 p-0">
          <?php print $loginOutput; ?>
        </div>

      </form>

    </div>


  </div>

</div>
