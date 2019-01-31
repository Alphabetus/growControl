<?php
// validate user authentication > keep out the ones who do not!
// Must have this on all authenticated pages in worder to keep the system safe.
lock();
// include local engine
// NOTE: This view inherits other controllers instead of having its own
// main function to edit the grow runs inside addgrow engine.

include "functions/room_engine.php"; // inherits room engine
include "functions/addGrow_engine.php"; // inherits add grow engine
// format time default value for datetime-local input
$timeInput = strftime('%Y-%m-%dT%H:%M', time());
// initialize local vars
$editGrowDisplay = array();
$errorArray = array();
// action > add grow submit
if (isset($_POST["grow_name"])){
  $editGrowDisplay = editGrow($settingsAvailable);
  if ($editGrowDisplay === true){
    // grow was successfully created > redirect to grows view
    header("Location: ?view=grows&room=" . $_GET["room"]);
  }
  else{
    // there was a problem with the grow creation > display errors
    // place on temp array
    foreach ($editGrowDisplay as $error){
      array_push($errorArray, $error);
    }
    $editGrowDisplay = $errorArray;
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
        <input id="grow-name-input" name="grow_name" type="text" class="form-control text-left" minlength="4" maxlength="32" placeholder="between 4 and 32 characters" value="<?php print $roomData["grow_name"]; ?>" required>
      </div>

      <!-- grow start date -->
      <div class="form-group col-12 m-0 p-0">
        <label for="grow-start-input"><strong>grow start date and time:</strong> <span class="mandatory-field text-danger">*</span></label><br>
        <input id="grow-start-input" name="grow_date" type="datetime-local" class="form-control text-left" value="<?php print date("Y-m-d\TH:m", $roomData["grow_start_date"]) ?>" min="1990-01-01T00:00" required>
      </div>

      <!-- grow description -->
      <div class="form-group col-12 m-0 p-0">
        <label for="grow-description-input"><strong>grow bio:</strong></label>
        <textarea id="grow-description-input" name="grow_description" class="form-control text-left" minlength="4" maxlength="160" placeholder="between 4 and 160 characters"><?php print $roomData["grow_description"]; ?></textarea>
      </div>

      <!-- grow type -->
      <div class="form-check p-0">
        <div class="col-12 m-0 p-0 mt-3">
          <strong>grow type:</strong> <span class="mandatory-field text-danger">*</span>
        </div>

        <div class="row m-0 px-4 justify-content-start mt-3">
          <div id="grow-type-radio" class="col-6 col-md-4 col-lg-3 col-xl-2 p-0 m-0 text-center text-md-left">
            <input class="form-check-input" type="radio" name="grow_type" id="grow-type-input-A" value="indoor" <?php if($roomData["grow_type"] === "indoor"){print 'checked="checked"';} ?>>
            <label for="grow-type-input-A">indoor</label>
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 p-0 m-0 text-center text-md-left">
            <input class="form-check-input" type="radio" name="grow_type" id="grow-type-input-B" value="outdoor" <?php if($roomData["grow_type"] === "outdoor"){print 'checked="checked"';} ?>>
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
            <input id="grow-area-input" type="number" step="0.01" class="form-control w-50 text-left" value="<?php print $roomData["grow_area"]; ?>" min="0" max="999" name="grow_area" placeholder="0 m&#xb2;">
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0 mt-2">
            <label for="grow-area-input">height</label>
            <input id="grow-area-input" type="number" step="0.01" class="form-control w-50 text-left" value="<?php print $roomData["grow_height"]; ?>" min="0" max="999" name="grow_height" placeholder="0 m">
          </div>

        </div>
      </div>
      <!-- /grow sizing options -->

      <!-- grow lamps number & power  -->
      <div class="form-group">
        <div class="row m-0 p-0 mt-3">

          <div class="col-12 m-0 p-0">
            <strong>grow lights:</strong>
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0 mt-2">
            <label for="grow-lamps-input">number</label>
            <input id="grow-lamps-number-input" type="number" class="form-control w-50 text-left" value="<?php print $roomData["grow_lamps"]; ?>" min="0" max="999" name="grow_lamps" placeholder="0">
          </div>

          <div class="col-6 col-md-4 col-lg-3 col-xl-2 m-0 p-0 mt-2">
            <label for="grow-lamps-input">power (total watts)</label>
            <input id="grow-lamps-power-input" type="number" class="form-control w-50 text-left" value="<?php print $roomData["grow_power"]; ?>" min="0" max="999999" name="grow_power" placeholder="0 w">
          </div>

        </div>
      </div>
      <!-- /grow lamps number & power -->

      <!-- grow light type -->
      <div class="form-group">
        <div class="row m-0 p-0 mt-3">

          <div class="col-12 m-0 p-0">
            <strong>light type:</strong>
          </div>

          <div class="col-12 m-0 p-0 mt-2">
            <select name="grow_power_type" class="custom-select">
              <option value="" <?php if ($roomData["grow_power_type"] === ""){print "selected";} ?> > --- </option>
              <option value="Mixed" <?php if ($roomData["grow_power_type"] === "Mixed"){print "selected";} ?> >Mixed</option>
              <option value="Fluorescent (CFL)" <?php if ($roomData["grow_power_type"] === "Fluorescent (CFL)"){print "selected";} ?> >Fluorescent (CFL)</option>
              <option value="HID (MH & HPS)" <?php if ($roomData["grow_power_type"] === "HID (MH & HPS)"){print "selected";} ?> >HID (MH & HPS)</option>
              <option value="LED" <?php if ($roomData["grow_power_type"] === "LED"){print "selected";} ?> >LED</option>
            </select>
          </div>

        </div>
      </div>
      <!-- /grow light type -->

      <!-- grow tracking settings -->
      <div class="form-check form-group p-0">
        <div class="row m-0 p-0 mt-3">

          <div class="col-12 m-0 p-0">
            <strong>grow tracking options:</strong>
          </div>

          <div class="col-12 m-0 p-0">
            <?php  ?>
            <?php genGrowSettings($settingsAvailable) ?>
          </div>

        </div>
      </div>

      <!-- error display container -->
      <div class="row justify-content-center m-0 p-0">
        <div class="col-12 text-center text-danger">
          <?php // loop the error display
          foreach ($editGrowDisplay as $display){
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
          <a href="?view=grow&room=<?php print $_GET["room"]; ?>" class="btn btn-outline-danger rounded">cancel</a>
        </div>
      </div>
      <!-- /submit & cancel buttons -->


    </form>

  </div>
  <!-- end of add grow form content -->

</div>
