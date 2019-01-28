<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();

// require local engine
require "functions/room_engine.php";
?>

<!-- section header -->
<?php include "partials/section_header.php"; print $section_header_sm; ?>
<!-- /section header -->


<div id="room" class="content-padding-top row">

  <!-- include sidebar -->
  <?php include "partials/sidebar.php"; ?>

  <!-- ROOM content -->

  <div class="col-12 col-sm-8 col-md-9 col-lg-10 px-0 px-sm-5 pt-2 pt-sm-3">

    <!-- grow view row container -->
    <div class="row section-box mb-5 room-container rounded shadow">

      <!-- NOTE:
        2 main col > container system
      -->

      <!-- col 1 > action box -->
      <div class="col-12 col-md-4 col-lg-3 col-xl-2 m-0 p-0 border room-container-inner room-container-action">
        [button A]<br>
        [button B]<br>
        [button C]<br>
        [button D]<br>
      </div>

      <!-- col 2 > info box -->
      <div class="col-12 col-md-8 col-lg-9 col-xl-10 m-0 p-0 border room-container-inner room-container-info">

        <!-- header row -->
        <div class="row m-0 p-0">
          <div class="col-11 room-header m-0 p-0 px-2 px-md-3 pt-2">
            <h3><i class="fas fa-campground"></i><?php print ucfirst($roomData["grow_name"]); ?></h3>
          </div>
          <div class="col-1 room-close m-0 p-0 text-center align-self-center">
            <a href="?view=grows" class="btn btn-outline-info btn-sm rounded"><i class="fas fa-undo"></i></a>
          </div>
        </div>

        <!-- description row -->
        <div class="row m-0 p-0 px-2 px-md-3 room-description">
          <p class="text-justify text-muted">
            <?php print ucfirst($roomData["grow_description"]); ?>
          </p>

        </div>
        <!-- /description row -->

        <hr class="bg-light">

        <!-- grow type , age and logs display -->
        <div class="row m-0 p-0 px-2 px-md-3 room-type">

          <!-- type -->
          <div class="col-4 m-0 p-0">
            <h4 class="text-justify align-self-center col m-0 p-0">
              <?php print ucfirst($roomData["grow_type"]); ?>s <!-- add an S to the default type  -->
            </h4>
          </div>

          <!-- age -->
          <div class="col-4 m-0 p-0 text-center align-self-center">
            <i class="far fa-clock"></i> <?php print $roomAge; ?> days
          </div>

          <!-- log tracks -->
          <div class="col-4 m-0 p-0 text-right room-type-tracks align-self-center">
            <?php
            // NOTE: print icon array for tracks info
            foreach ($roomTrackIcons as $icon) {
              print $icon;
            }
            ?>
          </div>

        </div>
        <!-- grow type , age and logs display -->

        <hr class="bg-light">

        <!-- GRID 2x >> last pick && Relevant info -->
        <div class="row m-0 p-0 px-2 px-md-3 room-display-container">

          <!-- default pic >> last from DB -->
          <div class="col-5 col-md-4 col-lg-3 m-0 p-1">

            <div class="col-12 p-0 room-display-image-container h-100 rounded border">
              <img src="img/grow_default.jpg" class="room-display-image"/>
            </div>

          </div>

          <!-- relevant info -->
          <div class="col-7 col-md-8 col-lg-9 p-0 room-display-info-continer">
            <div class="col-12 m-0 p-2 room-display-info">

              <!-- info list -->
              <ul class="list-group">
                <!-- start date -->
                <li class="list-group-item bg-transparent border rounded"><i class="far fa-calendar-alt"></i> start date : <?php print date("d-M-Y H:i", $roomData["grow_start_date"]); ?></li>
                <!-- sizing >> height and area -->
                <li class="list-group-item bg-transparent border rounded">
                  <div class="row m-0 p-0">
                    <!-- area -->
                    <div class="col-12 col-lg-6 m-0 p-0">
                      <i class="far fa-square"></i> area : <?php print $roomData["grow_area"]; ?>&#x33a1;
                    </div>
                    <!-- height -->
                    <div class="col-12 col-lg-6 m-0 p-0">
                      <i class="fas fa-arrows-alt-v"></i> height : <?php print $roomData["grow_height"]; ?>m
                    </div>
                  </div>
                </li>
                <!-- number of plants -->
                <li class="list-group-item bg-transparent border rounded"><i class="fas fa-tree"></i><i class="fas fa-tree"></i> num of trees : 0</li>
                <!-- status -->
              </ul>
            </div>
          </div>

        </div>
        <!-- END OF GRID 2x >> last pick && Relevant info -->

        <hr class="bg-light">

        <!-- Tracks list -->
        <div class="row m-0 p-0 px-2 px-md-3">
          <div class="col-12 m-0 p-0 room-tracks-container">

            <!-- header -->
            <div class="row m-0 p-0">
              <div class="col-12 m-0 p-0 room-tracks-header">
                <h4>Grow statistics</h4>
              </div>
            </div>
            <!-- /header -->

            <!-- track list -->
            <div class="row m-0 p-0">
            <?php
              // NOTE: loops value from array with key.
              // key is the name of the active track, value is the... value.

              foreach ($roomTrackValues as $track => $value) {
                print '
                  <div class="col-12 col-lg-4 m-0 p-0 room-tracks-item text-left text-lg-center">
                    '. ucfirst($track) .' : '. $value .'
                  </div>
                ';
              }
            ?>
            </div>
            <!-- /track list -->

          </div>
        </div>
        <!-- end of tracks list -->

        <hr class="bg-light">

        <!-- text log entries -->
        <div class="row m-0 p-0 px-2 px-md-3">
          <div class="col-12 m-0 p-0 room-logs-container">

            <!-- header -->
            <div class="row m-0 p-0">
              <div class="col-12 m-0 p-0 room-logs-header">
                <h4>Grow text logs</h4>
              </div>
            </div>
            <!-- /header -->

            [listing with text notes added by users]

          </div>
        </div>
        <!-- end of text log entries -->

      </div>

    </div>
    <!-- /grow view row container -->

  </div>

  <!-- /ROOM content -->
</div>
