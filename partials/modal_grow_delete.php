<div class="modal fade" id="modal-grow-delete" tabindex="-1" role="dialog" aria-labelledby="modal-grow-delete-title" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered" role="document">
    <!-- modal content -->
    <div class="modal-content rounded shadow">

      <!-- header -->
      <div class="modal-header">
        <h5 class="modal-title" id="modal-grow-delete-title">
          <strong>
            Delete Grow Room
          </strong>
        </h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>
      <!-- /header -->

      <!-- body -->
      <div class="modal-body text-justify">

        <!-- content row -->
        <div class="row m-0 p-0">
          <div class="col-12 m-0 p-0 pb-3">
            <p>
              <strong class="text-danger"><?php print getString("delete_grow_intro", "en"); ?> '&nbsp;<?php print ucfirst($roomData["grow_name"]); ?>&nbsp;'</strong>
            </p>
            <p>
              <?php print getString("delete_grow_confirm", "en"); ?>
            </p>
          </div>
        </div>
        <!-- /content row -->

        <!-- buttons row -->
        <div class="row m-0 p-0">

          <!-- confirm & delete form -->
          <div class="col-6 m-0 p-0 text-center">
            <form method="POST">

              <!-- autenticity -->
              <input type="hidden" name="token" value="<?php print getPostToken(); ?>">
              <!-- /autenticity -->
              
              <input type="hidden" name="delete_grow">
              <button type="submit" class="btn btn-sm btn-danger w-75">delete grow room</button>
            </form>
          </div>
          <!-- /confirm & delete form -->

          <!-- cancel grow deletion > dismiss popout -->
          <div class="col-6 m-0 p-0 text-center">
            <a id="cancel-delete-grow-button" href="#" class="btn btn-sm btn-info w-75">go back</a>
          </div>
          <!-- /cancel grow deletion -->

        </div>
        <!-- /buttons row -->

      </div>
      <!-- /body -->

    </div>
    <!-- /modal content -->
  </div>

</div>
