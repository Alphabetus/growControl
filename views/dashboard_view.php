<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();
?>

<div id="dashboard" class="content-padding-top row">

  <!-- include sidebar -->
  <?php include "partials/sidebar.php"; ?>

  <!-- dasboard content -->
  <div class="col-12 col-sm-8 col-md-9 col-lg-10 px-5 pt-2 pt-sm-3">
    [DASHBOARD RELEVANT INFORMATION ABOUT YOUR GROWS]
  </div>
  <!-- end of dasboard content -->

</div>
