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
        $trackName = strip_tags($track); // remove html icon
        $trackName = str_replace(" ", "", $trackName); // remove space
        print "<div id='track-". strtolower($trackName) ."-display' data-naming='". $track ."' class='col-12 col-lg-4 m-0 p-0 room-tracks-item text-left'>";
        print $track . " : " . $value;
        print "</div>";
      }
    ?>
    </div>
    <!-- /track list -->

  </div>
</div>
