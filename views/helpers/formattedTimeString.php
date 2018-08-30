<?php
function getFormattedTimeString($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
?>