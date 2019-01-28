<?php


// display for sm and above
$section_header_sm = '
  <div id="section-header-sm" class="row justify-content-center d-none d-sm-flex">
    <div class="col-6 align-self-center text-center text-truncate">
      <i class="fas fa-link"></i>&nbsp;'. getViewTitle() .'&nbsp;<i class="fas fa-link fa-flip-horizontal"></i>
    </div>
  </div>
';

// display below sm
$section_header_xs = '
  <div id="section-header-xs" class="row d-flex d-sm-none">
    <div class="col-12 align-self-center text-right text-truncate">
      <i class="fas fa-link"></i>&nbsp;'. getViewTitle() .'
    </div>
  </div>
';

?>
