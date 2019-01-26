<?php
// local var initialisation
$registerOutput = null;
require "functions/register_engine.php";
// action > register
if (isset($_POST["username"])){
  $registerOutput = createUser();
  // run user creation
  if ($registerOutput === true){
    header("Location: ?view=welcome");
  }
}


?>
<script type="text/javascript" src="js/register.js"></script>

<div id="userauth" class="content-padding-top row justify-content-center section-box">

  <div class="col-12 col-md-8 align-self-center p-0 auth-container rounded shadow">

    <!-- header row -->
    <div class="row justify-content-center">
      <div class="col-12 mx-0 p-0 mt-3">

        <?php getSplashTitle(); ?>

      </div>
    </div>

    <!-- register form -->
    <div class="row justify-content-center">
      <form id="register" class="w-75 mb-3" method="POST">

        <div class="form-group">
          <label for="username_field">username</label>
          <input name="username" type="text" class="form-control" id="username_field" required>
        </div>

        <div class="form-group">
          <label for="username_field">password</label>
          <input name="password" type="password" class="form-control" id="password_field" required>
        </div>

        <div class="form-group">
          <label for="password_confirm_field">re-type password</label>
          <input name="password_confirm" type="password" class="form-control" id="password_confirm_field" required>
        </div>

        <div class="col-12 text-right m-0 p-0">
          <a href="/" class="btn btn-sm btn-outline-info btn-auth">back</a>
          <a href="?view=login" class="btn btn-sm btn-outline-info btn-auth">login</a>
          <button type="submit" class="btn btn-sm btn-outline-info btn-auth">register</button>
        </div>
        <div class="col-12 text-center text-warning m-0 p-0">
          <?php print $registerOutput; ?>
        </div>

      </form>
    </div>



  </div>

</div>
