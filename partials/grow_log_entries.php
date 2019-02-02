<div class="row m-0 p-0 px-2 px-md-3">
  <div class="col-12 m-0 p-0 room-logs-container">

    <!-- header -->
    <div class="row m-0 p-0">
      <div class="col-10 m-0 p-0 room-info-header">
        <h4>Grow log entries</h4>
      </div>
      <div class="col-2 m-0 p-0 room-logs-pagination">
        <div id="logs-nav" data-total-notes="<?php print $roomLogsPagesAndTotal[0]; ?>" data-total-pages="<?php print $roomLogsPagesAndTotal[1]; ?>" data-per-page="<?php print $roomLogsPerPage; ?>" class="row m-0 p-0">

          <div class="col-6 m-0 p-0 text-center">
            <a id="logs-prev" href="#" class="btn btn-sm btn-outline-primary w-75 rounded"><i class="fas fa-angle-left"></i></a>
          </div>

          <div class="col-6 m-0 p-0 text-center">
            <a id="logs-next" href="#" class="btn btn-sm btn-outline-primary w-75 rounded"><i class="fas fa-angle-right"></i></a>
          </div>

        </div>
      </div>
    </div>
    <!-- /header -->

    <!-- notes container -->
    <div id="logs-container" data-room="<?php print $_GET["room"]; ?>" data-id="<?php print $_SESSION["user_id"]; ?>" class="row m-0 p-0">
      <table class="table table-hover">

        <!-- table header -->
        <thead>
            <tr>
              <th scope="col">type</th>
              <th scope="col">date</th>
              <th colspan="2" scope="col">value</th>
            </tr>
        </thead>

        <!-- table body -->
        <tbody id="log-table">
          <!-- populated by room.js -->

        </tbody>
        <!-- /table body -->

      </table>
      <!-- /table header -->

    </div>
    <!-- /notes container -->

  </div>
</div>
