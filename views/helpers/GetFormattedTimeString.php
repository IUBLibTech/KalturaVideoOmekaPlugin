<?php
class KalturaVideo_View_Helper_GetFormattedTimeString extends Zend_View_Helper_Abstract
{
	public function getFormattedTimeString($seconds) {
		if (strpos($seconds,":")){
			return $seconds;
		}else{
  			$t = round($seconds);
  			return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
		}
	}

}
?>