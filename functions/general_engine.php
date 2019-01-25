<?php
// NOTE: this function retrieves the string content from given filename and language excluding extension 'txt'.
// Strings should be stored on /strings/langFolder
// Logged in users will eventually be able to define $lang on their own settings / cookies.
function getString($fileName, $lang){
  $filePath = "strings/" . $lang . "/" . $fileName . ".txt";
  $out = file_get_contents($filePath);
  if ($out === false){
    print "ERROR.: String file '/".$lang."/". $fileName .".txt' not found.";
  }
  else{
    print $outFormated = str_replace("\n", "<br>", $out);
  }
}


// NOTE: this retrieves the <h3>stuf</h3> that is used on several parts between login and register on logged off page.
function getSplashTitle(){
  print '
    <h3 class="text-center">
      <i class="fas fa-cannabis text-danger"></i>
      <i class="fas fa-cannabis text-warning"></i>
      <i class="fas fa-cannabis text-success"></i>
      Grow Control
      <i class="fas fa-cannabis text-success"></i>
      <i class="fas fa-cannabis text-warning"></i>
      <i class="fas fa-cannabis text-danger"></i>
    </h3>
  ';
}
?>
