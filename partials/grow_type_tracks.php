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
