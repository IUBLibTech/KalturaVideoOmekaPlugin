<?php
class KalturaVideo_View_Helper_GetCalculatedTime extends Zend_View_Helper_Abstract
{
	public function getCalculatedTime($timestring) {
		if (!strpos($timestring,":")){
			return $timestring;
		}else{
			sscanf($timestring, "%d:%d:%d", $hours, $minutes, $seconds);
			return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 3600 + $minutes * 60;
		}
	}
}
?>