<!-- call custom landing scripts -->
<script type="text/javascript" src="js/landing.js"></script>

<!-- print content -->
<div id="landing" class="content-padding-top row justify-content-center section-box">
  <div class="splash-container col-10 col-md-7 col-lg-5 align-self-center rounded shadow">

    <div class="row">
      <div class="col-12 m-0 p-0">

        <?php getSplashTitle(); ?>

        <p id="splash_content" class="text-justify pt-4">
          <?php getString("splash_welcome", "en"); ?>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-4 m-0 p-0 text-center">
        <a href="?view=login" class="btn btn-sm btn-outline-info">login</a>
      </div>
      <div id="splash_footer" class="col-4 m-0 p-0 text-center">
        <a id="read-more-splash" href="#" class="btn btn-sm btn-outline-info">read more</a>
      </div>
      <div class="col-4 m-0 p-0 text-center">
        <a href="?view=register" class="btn btn-sm btn-outline-info">register</a>
      </div>
    </div>

  </div>
</div>
