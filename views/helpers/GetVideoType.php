<?php
class KalturaVideo_View_Helper_GetVideoType extends Zend_View_Helper_Abstract
{
	function getVideoType($vtype) {
		if (strlen($vtype)>0){
			if (strpos($vtype,"&")){
				return $vtype;
			}else{
				$position=strpos($vtype,"mp4");
  				return substr_replace($vtype,'&',$position,0);
			}
		}
	}

}
?>