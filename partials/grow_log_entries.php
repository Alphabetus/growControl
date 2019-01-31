<div class="row m-0 p-0 px-2 px-md-3 position-relative">
  <div class="col-12 m-0 p-0 room-logs-container">

    <!-- header -->
    <div class="row m-0 p-0">
      <div class="col-10 m-0 p-0 room-logs-header">
        <h4>Grow notes</h4>
      </div>
      <div class="col-2 m-0 p-0 room-logs-pagination">
        <div id="notes-nav" data-total-notes="<?php print $roomNotesPagesAndTotal[0]; ?>" data-total-pages="<?php print $roomNotesPagesAndTotal[1]; ?>" data-per-page="<?php print $roomNotesPerPage; ?>" class="row m-0 p-0">

          <div class="col-6 m-0 p-0 text-center">
            <a id="note-prev" href="#" class="btn btn-sm btn-outline-primary w-75 rounded"><i class="fas fa-angle-left"></i></a>
          </div>

          <div class="col-6 m-0 p-0 text-center">
            <a id="note-next" href="#" class="btn btn-sm btn-outline-primary w-75 rounded"><i class="fas fa-angle-right"></i></a>
          </div>

        </div>
      </div>
    </div>
    <!-- /header -->

    <!-- notes container -->
    <div id="notes-container" data-room="<?php print $_GET["room"]; ?>" data-id="<?php print $_SESSION["user_id"]; ?>" class="row m-0 p-0">

    </div>
    <!-- /notes container -->

    <!-- add log collapse form -->
    <div id="add-note-log-container" class="row m-0 p-0 mt-3 collapse">
      <!-- log form content -->
      <div class="col-12 m-0 p-0">

        <div class="col-12 m-0 p-0">
          <input id="input-note-title" name="note_title" type="text" maxlength="51" placeholder="title" class="form-control text-left">
        </div>

        <div class="col-12 m-0 p-0">
          <label for="input-note" class="note-input-label">Create note:</label>
          <textarea id="input-note" name="note_message" rows="5" maxlength="321" class="form-control text-left" placeholder="enter your message..."></textarea>
        </div>

      </div>

      <div class="col-12 m-0 p-0 text-right pr-1">
        <small id="input-note-length">0 / 320</small>
      </div>

      <!-- log actions content -->
      <div id="note-actions" class="col-12 m-0 p-0">

        <!-- buttons row -->
        <div class="row m-0 p-0 mt-3">

          <!-- cancel log button -->
          <div class="col-6 m-0 p-0 text-center">
            <a id="cancel-note-form" href="#" class="btn btn-sm btn-outline-danger w-75" data-toggle="collapse" data-target="#add-note-log-container">cancel</a>
          </div>

          <!-- save log button -->
          <div class="col-6 m-0 p-0 text-center">
            <a id="save-note-form" href="#" data-room="<?php print $_GET["room"]; ?>" data-id="<?php print $_SESSION["user_id"]; ?>" class="btn btn-sm btn-outline-primary w-75">save</a>
          </div>

        </div>
        <!-- /buttons row -->

      </div>
      <!-- /log actions content -->

    </div>
    <!-- /add log collapse form -->

    <!-- add log button -->
    <div class="row m-0 p-0 mt-3">
      <div class="col-12 m-0 p-0 room-logs-buttons">

        <a id="display-note-form" href="#" class="btn btn-sm btn-outline-primary w-25" data-toggle="collapse" data-target="#add-note-log-container">add note</a>

      </div>
    </div>
    <!-- /add log button -->

  </div>
</div>
