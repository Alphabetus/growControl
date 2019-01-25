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
      <form class="w-75 mb-3">

        <div class="form-group">
          <label for="username_field">username</label>
          <input type="text" class="form-control" id="username_field">
        </div>

        <div class="form-group">
          <label for="username_field">password</label>
          <input type="password" class="form-control" id="password_field">
        </div>

        <div class="form-group">
          <label for="password_confirm_field">retype password</label>
          <input type="password" class="form-control" id="password_confirm_field">
        </div>

        <div class="col-12 text-right m-0 p-0">
          <a href="/" class="btn btn-sm btn-outline-info btn-auth">back</a>
          <a href="?view=login" class="btn btn-sm btn-outline-info btn-auth">login</a>
          <button type="submit" class="btn btn-sm btn-outline-info btn-auth">register</button>
        </div>

      </form>
    </div>



  </div>

</div>
