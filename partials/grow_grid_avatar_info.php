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
        <!-- ilumination info -->
        <li class="list-group-item bg-transparent border rounded">
          <div class="row m-0 p-0">
            <!-- number of lamps and type -->
            <div class="col-12 col-lg-6 m-0 p-0">
              <i class="far fa-lightbulb"></i> lamps : <?php print $roomData["grow_lamps"]; ?>&nbsp;x&nbsp;<?php print $roomData["grow_power_type"];?>&nbsp;<span class="room-display-light-potency">(<?php print $roomData["grow_power"]; ?>w)</span>
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
