<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();
// lock room to owner
roomLock();
// require local engine
require "functions/room_engine.php";
?>

<!-- section header -->
<?php include "partials/section_header.php"; print $section_header_sm; ?>
<!-- /section header -->

<!-- link local scripts -->
<script type="text/javascript" src="js/room.js"></script>

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
      <div id="actions" class="col-12 col-md-4 col-lg-3 col-xl-2 m-0 p-0 room-container-inner room-container-action collapse">

        <!-- action header -->
        <div class="row m-0 p-0 pt-3">

          <div class="col-12 room-container-action-header">
            <h3 class="text-left text-md-center"><i class="fas fa-tools text-secondary"></i>&nbsp;<strong>TOOLS</strong>&nbsp;<i class="fas fa-tools fa-flip-horizontal text-secondary"></i></h3>
          </div>

        </div>
        <!-- /action header -->

        <div class="row m-0 p-0 room-container-action-innser justify-content-around">


          <?php
          // NOTE: Generate the action buttons and print them accordingly


          foreach ($roomTrackButtons as $button) {
            print '
              <!-- action item -->
              <div class="col-12 m-0 p-2 pt-3 text-center">
                '. $button .'
              </div>
              <!-- /action item -->
            ';
          }
          ?>

          <!-- default actions -->

          <!-- edit grow -->
          <div class="col-12 m-0 p-2 pt-3 text-center">
            <a href="?view=editGrow&room=<?php print $_GET["room"]; ?>" class="btn btn-sm btn-outline-primary text-left w-100"><i class="far fa-edit"></i>&nbsp;edit grow</a>
          </div>
          <!-- /edit grow -->

          <!-- delete grow -->
          <div class="col-12 m-0 p-2 pt-3 text-center">
            <a href="#" class="btn btn-sm btn-outline-danger text-left w-100"><i class="far fa-edit"></i>&nbsp;delete grow</a>
          </div>
          <!-- /delete grow -->

          <!-- /default actions -->

        </div>


      </div>
      <!-- /col 1 > action box -->

      <!-- col 2 > info box -->
      <div class="col-12 col-sm p-0 border-left room-container-inner room-container-info">

        <!-- header row -->
        <div class="row m-0 p-0">
          <!-- open tool box button -->
          <div class="col-1 m-0 p-0 pt-2 text-center">

            <a id="action-control" class="btn btn-sm btn-outline-primary w-75 rounded" data-toggle="collapse" data-target="#actions">
              <i class="fas fa-tools"></i>
            </a>

          </div>
          <!-- /open tool box button -->

          <!-- room title -->
          <div class="col-10 room-header m-0 p-0 px-2 px-md-3 pt-2">
            <h3 class="text-center"><i class="fas fa-campground"></i><?php print ucfirst($roomData["grow_name"]); ?></h3>
          </div>
          <!-- /room title -->

          <!-- go back button -->
          <div class="col-1 room-close m-0 p-0 text-center align-self-center">
            <a href="?view=grows" class="btn btn-outline-primary btn-sm rounded w-75"><i class="fas fa-undo"></i></a>
          </div>
          <!-- /go back button -->

        </div>
        <!-- /header row -->

        <!-- description row -->
        <div class="row m-0 p-0 px-2 px-md-3 room-description">

          <p class="text-justify text-muted px-5">
            <?php print ucfirst($roomData["grow_description"]); ?>
          </p>

        </div>
        <!-- /description row -->

        <hr class="bg-light">

        <!-- grow type , age and tracks display -->
        <?php include "partials/grow_type_tracks.php"; ?>
        <!-- end of grow type , age and tracks display -->

        <hr class="bg-light">

        <!-- GRID 2x >> last pick && Relevant info -->
        <?php include "partials/grow_grid_avatar_info.php"; ?>
        <!-- END OF GRID 2x >> last pick && Relevant info -->

        <hr class="bg-light">

        <!-- Tracks list -->
        <?php include "partials/grow_tracks_list.php"; ?>
        <!-- end of tracks list -->

        <hr class="bg-light">

        <!-- text log entries -->
        <?php include "partials/grow_log_entries.php"; ?>
        <!-- end of text log entries -->

        <hr class="bg-light">

        <!-- plant table -->
        <?php include "partials/grow_plant_table.php"; ?>
        <!-- end of plant table -->

      </div>

    </div>
    <!-- /grow view row container -->

  </div>

  <!-- /ROOM content -->
</div>
