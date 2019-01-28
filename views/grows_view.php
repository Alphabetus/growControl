<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();

// include local engine
include "functions/grows_engine.php";
?>

<!-- section header -->
<?php include "partials/section_header.php"; print $section_header_sm; ?>
<!-- /section header -->


<div id="grows" class="content-padding-top row">

  <!-- include sidebar -->
  <?php include "partials/sidebar.php"; ?>

  <!-- GROWS content -->
  <div class="col-12 col-sm-8 col-md-9 col-lg-10 px-0 px-sm-5 pt-2 pt-sm-3">

    <!-- panel grid row container -->
    <div class="row section-box mb-5">

      <!-- default grid square > add grow -->
      <div class="col-6 col-md-4 col-xl-3 m-0 p-0">

        <div class="row panel-container shadow-sm border rounded m-0 m-md-2 m-lg-3 p-0">
          <div class="col-12 m-0 p-2 align-self-center panel-container-inner text-center">
            <a href="?view=addGrow">
              <span class="panel-button">
                <i class="fas fa-plus-circle"></i>
              </span>
            </a>
          </div>
        </div>

      </div>
      <!-- /default grid square > add grow -->

      <!-- generated grow grid square area -->
      <?php
          foreach (getGrowsArray() as $grow){
              $growAge = getAge($grow["grow_start_date"]);
              print '
                <div class="col-6 col-md-4 col-xl-3 m-0 p-0">
                  <div class="row panel-container shadow-sm border rounded m-0 m-md-2 m-lg-3 p-0">

                    <div class="col-12 m-0 p-2 panel-container-inner">
                      <div class="row justify-content-center">

                        <div class="col-12 panel-header-container text-center">
                          <h5 class="panel-header-content">'. $grow["grow_name"] .'</h5>
                          <hr class="bg-light">
                        </div>

                        <div class="col-12 m-0 p-0 panel-body-container mt-2">

                          <div class="col-12 m-0 p-0 panel-body-item">
                            <i class="fas fa-birthday-cake"></i>&nbsp;Age : '. $growAge .' days
                          </div>

                          <div class="col-12 m-0 p-0 panel-body-item">
                            <i class="fas fa-tree"></i>&nbsp;N. of Plants : 0
                          </div>

                          <div class="col-12 m-0 p-0 panel-body-item">
                            <i class="fas fa-grip-vertical"></i>&nbsp;Type : '. $grow["grow_type"] .'
                          </div>

                          '. growTemperature($grow["grow_id"], $grow["grow_tracks_temperature"]) .'

                          '. growHumidity($grow["grow_id"], $grow["grow_tracks_humidity"]) .'

                          '. growCo2($grow["grow_id"], $grow["grow_tracks_co2"]) .'
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              ';
          }
      ?>
      <!-- /generated grow grid square area -->


    </div>
    <!-- end of panel grid row container -->

  </div>
  <!-- end of GROWS content -->

</div>
