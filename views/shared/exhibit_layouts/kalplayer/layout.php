 <?php
$float = isset($options['video-float'])
	? html_escape($options['video-float'])
	: 'left';
$alt_height = get_option('kaltura_height_public');
$height = isset($options['video-height'])
	? html_escape($options['video-height'])
	: $alt_height;
$width = isset($options['video-width'])
	? html_escape($options['video-width'])
	: '100';
//if ($width == '100'){
//	$width = '100';
//};
//$height= get_option('kaltura_height_public');
//$height=array(
//	"95%" => "42%",
//	"80%" => "36%",
//	"70%" => "3%",
//	"60%" => "24%",
//	"50%" => "20%",
//	"40%" => "15%",
//);
$alt_current = get_option('kaltura_display_current');
$current = isset($options['current-seg'])
	? html_escape($options['current-seg'])
	: true;
$show_title = isset($options['show_title'])
	? html_escape($options['show_title'])
	: true;	
	
//$external = isset($options['video-controls'])
//	? html_escape($options['video-controls'])
//	: false;
$eid_suffix = 0;

?>

	<div class="vid_player" Title="Video Player" style="width:<?php echo $width;?>%; height:<?php echo $height;?>px; float:<?php echo $float;?>; margin: 1em 1em;">

	<?php
		$sitems='';
		foreach($attachments as $attItem):
			$item = $attItem->getItem();
			set_current_record('Item',$item);
			$sitems = $sitems.','.metadata('item','id');
		endforeach;
		echo $this->shortcodes('[kalplayer ids='.$sitems.' ext='.true.' current='.$current.' width='.$width.'%'.' height='.$height.'px'.' float='.$float.' show_title='.$show_title.']'); 
		$eid_suffix++; 
	?>	
</div>
