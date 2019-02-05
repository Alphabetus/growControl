<?php
// init
$errorDisplay = null;
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
      <div id="actions" class="col-12 col-md-4 col-lg-3 col-xl-2 m-0 p-0 room-container-inner room-container-action collapse border-right">

        <!-- action header -->
        <div class="row m-0 p-0 pt-3">

          <div class="col-12 room-container-action-header">
            <h3 class="text-left text-md-center"><i class="fas fa-tools text-secondary"></i>&nbsp;<strong>TOOLS</strong>&nbsp;<i class="fas fa-tools fa-flip-horizontal text-secondary"></i></h3>
          </div>

        </div>
        <!-- /action header -->

        <div class="row m-0 p-0 room-container-action-innser justify-content-around">

          <form>
            <input id="log-token" type="hidden" value="<?php print getPostToken();?>">
          <?php
          // NOTE: Generate the action buttons and print them accordingly


          foreach ($roomTrackButtons as $button) {
            print '
              <!-- action item -->
              <div class="col-12 m-0 p-2 pt-0 pt-md-3 text-center">
                '. $button .'
              </div>
              <!-- /action item -->
            ';
          }
          ?>
        </form>

          <!-- default actions -->

          <!-- edit grow -->
          <div class="col-12 m-0 p-2 pt-0 pt-md-3 text-center">
            <a href="?view=editGrow&room=<?php print $_GET["room"]; ?>" class="btn btn-sm btn-outline-primary text-left w-100"><i class="far fa-edit"></i>&nbsp;edit grow</a>
          </div>
          <!-- /edit grow -->

          <!-- delete grow -->
          <div class="col-12 m-0 p-2 pt-0 pt-md-3 text-center">
            <a id="delete-grow-button" href="#" class="btn btn-sm btn-outline-danger text-left w-100"><i class="far fa-trash-alt"></i>&nbsp;delete grow</a>
          </div>
          <!-- /delete grow -->

          <!-- close menu -->
          <div class="col-12 m-0 p-2 pt-0 pt-md-3 text-center">
            <a id="close-actions" href="#" data-toggle="collapse" data-target="#actions" class="btn btn-sm btn-outline-primary w-100 text-left"><i class="far fa-window-close"></i>&nbsp;close tools</a>
          </div>
          <!-- /close menu -->

          <!-- /default actions -->

        </div>


      </div>
      <!-- /col 1 > action box -->

      <!-- col 2 > info box -->
      <div id="grow-box" class="col-12 p-0 room-container-inner room-container-info">

        <!-- header row -->
        <div class="row m-0 p-0">

          <!-- error display -->
          <div class="col-12 m-0 p-0 px-2 text-danger">
            <?php print $errorDisplay; ?>
          </div>

          <!-- room title -->
          <div class="col-10 room-header m-0 p-0 px-2 px-md-3 pt-2">
            <h3 class="text-left text-truncate"><i class="fas fa-campground"></i><?php print ucfirst($roomData["grow_name"]); ?></h3>
          </div>
          <!-- /room title -->

          <!-- open tool box button -->
          <div class="col-1 m-0 p-0 pt-2 text-center">

            <a id="action-control" class="btn btn-sm btn-outline-primary w-75 rounded" data-toggle="collapse" data-target="#actions">
              <i class="fas fa-tools"></i>
            </a>

          </div>
          <!-- /open tool box button -->

          <!-- go back button -->
          <div class="col-1 room-close m-0 p-0 pt-2 text-center">
            <a href="?view=grows" class="btn btn-outline-primary btn-sm rounded w-75"><i class="fas fa-undo"></i></a>
          </div>
          <!-- /go back button -->

        </div>
        <!-- /header row -->

        <!-- description row -->
        <div class="row m-0 p-0 px-2 px-md-3 room-description">

          <p class="text-justify text-muted w-100">
            <i class="fas fa-circle description-bullet"></i> <?php print ucfirst($roomData["grow_description"]); ?>
          </p>

        </div>
        <!-- /description row -->

        <hr class="bg-light">

        <div class="col-12 m-0 p-0">
          <!-- grow type , age and tracks display -->
          <?php include "partials/grow_type_tracks.php"; ?>
          <!-- end of grow type , age and tracks display -->
        </div>

        <hr class="bg-light">

        <div class="col-12 m-0 p-0">
          <!-- GRID 2x >> last pick && Relevant info -->
          <?php include "partials/grow_grid_avatar_info.php"; ?>
          <!-- END OF GRID 2x >> last pick && Relevant info -->
        </div>

        <hr class="bg-light">

        <div class="col-12 m-0 p-0">
          <!-- Tracks list -->
          <?php include "partials/grow_tracks_list.php"; ?>
          <!-- end of tracks list -->
        </div>

        <hr class="bg-light">

        <!-- TAB NAVIGATION -->
        <ul class="nav nav-tabs shadow-sm" id="grow-info-tab-nav" role="tablist">

          <li class="ml-2 nav-item w-25">
            <a class="nav-link active rounded" id="notes-tab-button" data-toggle="tab" href="#notes-tab" role="tab" aria-controls="notes-tab" aria-selected="true">Notes</a>
          </li>

          <li class="ml-2 nav-item w-25">
            <a class="nav-link rounded" id="logs-tab-button" data-toggle="tab" href="#logs-tab" role="tab" aria-controls="logs-tab" aria-selected="false">Logs</a>
          </li>

        </ul>
        <!-- /TAB NAVIGATION -->


        <!-- TAB CONTENT -->
        <div class="tab-content" id="grow-info-tab-content">

          <!-- notes area -->
          <div class="col-12 m-0 p-0 tab-pane fade show active pt-3" id="notes-tab" role="tabpanel" aria-labelledby="notes-tab-button">
            <!-- note entries -->
            <?php include "partials/grow_note_entries.php"; ?>
            <!-- end of note entries -->
          </div>
          <!-- /notes area -->

          <!-- logs area -->
          <div class="col-12 m-0 p-0 tab-pane fade pt-3" id="logs-tab" role="tabpanel" aria-labelledby="logs-tab-button">
            <!-- log entries -->
            <?Php include "partials/grow_log_entries.php"; ?>
            <!-- end of log entries -->
          </div>
          <!-- /logs area -->

        </div>
        <!-- /TAB CONTENT -->

        <hr class="bg-light">

        <div class="col-12 m-0 p-0">
          <!-- plant table -->
          <?php include "partials/grow_plant_table.php"; ?>
          <!-- end of plant table -->
        </div>

      </div>

    </div>
    <!-- /grow view row container -->

  </div>

  <!-- /ROOM content -->
</div>

<!-- MODAL AREA -->
<?php include "partials/modal_grow_delete.php"; ?>
<!-- /MODAL AREA -->
