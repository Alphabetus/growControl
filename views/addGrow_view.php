<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();
// include local engine
include "functions/addGrow_engine.php";
// format time default value for datetime-local input
$timeInput = strftime('%Y-%m-%dT%H:%M', time());
// initialize local vars
$addGrowDisplay = array();
$errorArray = array();
// action > add grow submit
if (isset($_POST["grow_name"])){
  $addGrowDisplay = addGrow($settingsAvailable);
  if ($addGrowDisplay === true){
    // grow was successfully created > redirect to grows view
    header("Location: ?view=grows");
  }
  else{
    // there was a problem with the grow creation > display errors
    // place on temp array
    foreach ($addGrowDisplay as $error){
      array_push($errorArray, $error);
    }
    $addGrowDisplay = $errorArray;
  }
}
?>

<div id="form-add" class="content-padding-top row">

  <!-- section header -->
  <?php include "partials/section_header.php"; print $section_header_sm; ?>
  <!-- /section header -->

  <!-- include sidebar -->
  <?php include "partials/sidebar.php"; ?>

  <!-- add grow form content -->
  <div class="col-12 col-sm-8 col-md-9 col-lg-10 px-5 pt-2 pt-sm-3">

    <form method="POST">

      <!-- grow name -->
      <div class="form-group col-12 m-0 p-0">
        <label for="grow-name-input"><strong>grow name:</strong> <span class="mandatory-field text-danger">*</span></label>
        <input id="grow-name-input" name="grow_name" type="text" class="form-control" minlength="4" maxlength="32" placeholder="between 4 and 32 characters" value="<?php print $input_growName; ?>" required>
      </div>

      <!-- grow start date -->
      <div class="form-group col-12 m-0 p-0">
        <label for="grow-start-input"><strong>grow start date and time:</strong> <span class="mandatory-field text-danger">*</span></label><br>
        <input id="grow-start-input" name="grow_date" type="datetime-local" class="form-control" value="<?php if (isset($input_growDate)){print $input_growDate;} else{print $timeInput;} ?>" min="1990-01-01T00:00" required>
      </div>

      <!-- grow description -->
      <div class="form-group col-12 m-0 p-0">
        <label for="grow-description-input"><strong>grow bio:</strong></label>
        <textarea id="grow-description-input" name="grow_description" class="form-control" minlength="4" maxlength="160" placeholder="between 4 and 160 characters"><?php print $input_growBio; ?></textarea>
      </div>

      <!-- grow type -->
      <div class="form-check p-0">
        <div class="col-12 m-0 p-0 mt-3">
          <strong>grow type:</strong> <span class="mandatory-field text-danger">*</span>
        </div>

        <div class="row m-0 px-4 justify-content-start mt-3">
          <div id="grow-type-radio" class="col-6 col-md-4 col-lg-3 col-xl-2 p-0 m-0 text-center text-md-left">
            <input class="form-check-input" type="radio" name="grow_type" id="grow-type-input-A" value="indoor" checked="checked">
            <label for="grow-type-input-A">indoor</label>
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 p-0 m-0 text-center text-md-left">
            <input class="form-check-input" type="radio" name="grow_type" id="grow-type-input-B" value="outdoor">
            <label for="grow-type-input-A">outdoor</label>
          </div>

        </div>
      </div>

      <!-- grow sizing options -->
      <div class="form-group">
        <div class="row m-0 p-0 mt-3">

          <div class="col-12 m-0 p-0">
            <strong>grow dimensions:</strong>
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0 mt-2">
            <label for="grow-area-input">area</label>
            <input id="grow-area-input" type="number" step="0.01" class="form-control w-50" value="<?php print $input_growArea; ?>" min="0" max="999" name="grow_area" placeholder="0 m&#xb2;">
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0 mt-2">
            <label for="grow-area-input">height</label>
            <input id="grow-area-input" type="number" step="0.01" class="form-control w-50" value="<?php print $input_growHeight; ?>" min="0" max="999" name="grow_height" placeholder="0 m">
          </div>

        </div>
      </div>
      <!-- /grow sizing options -->

      <!-- grow tracking settings -->
      <div class="form-check form-group p-0">
        <div class="row m-0 p-0 mt-3">

          <div class="col-12 m-0 p-0">
            <strong>grow tracking options:</strong>
          </div>

          <div class="col-12 m-0 p-0">
            <?php genGrowSettings($settingsAvailable) ?>
          </div>

        </div>
      </div>

      <!-- error display container -->
      <div class="row justify-content-center m-0 p-0">
        <div class="col-12 text-center text-danger">
          <?php // loop the error display
          foreach ($addGrowDisplay as $display){
            print $display . ".<br>";
          }

          ?>
        </div>
      </div>
      <!-- /error display container -->

      <!-- submit & cancel buttons -->
      <div class="row m-0 p-0 mt-3">
        <div class="col-auto m-0 mb-5 p-0 d-inline">
          <button type="submit" class="btn btn-outline-success rounded">save grow</button>
        </div>

        <div class="col-auto m-0 mb-5 p-0 d-inline pl-2">
          <a href="?view=grows" class="btn btn-outline-danger rounded">cancel</a>
        </div>
      </div>
      <!-- /submit & cancel buttons -->


    </form>

  </div>
  <!-- end of add grow form content -->

</div>
