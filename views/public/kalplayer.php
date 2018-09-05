<?php 
$float = isset($params['float'])
	? html_escape($params['float'])
	: 'left';
$width = isset($params['width'])
	? html_escape($params['width'])
	: get_option('kaltura_width_public').'%';
$height = isset($params['height'])
	? html_escape($params['height'])
	: get_option('kaltura_height_public').'px';
$current = isset($params['current'])?html_escape($params['current']) : get_option('kaltura_display_current');
$show_title = isset($params['show_title'])?html_escape($params['show_title']) : true;
$external = isset($params['ext'])?html_escape($params['ext']) : true;
?>
<?php 
	foreach($items as $item):
	set_current_record('Item',$item);
	if (metadata($item,'has files')){
	$files = $item->Files;
	    foreach($files as $file) {
	        if($file->hasThumbnail()) {
				$poster[]= file_display_url($file,'thumbnail');	
	        }
		}
	}
	endforeach;
	?>
	<?php if ($current && metadata('item',array('Kaltura Video','Show Item'))=="TRUE") { ?>
		<div class="displaySegmentLeftColumn">
	<?php } else { ?>
		<div style="width:100%; height:<?php echo $height;?>;  float:<?php echo $float;?>;">
	<?php }; ?>

			<script>
			var startTime<?php echo $id_suffix;?> = new Array();
			var endTime<?php echo $id_suffix;?> = new Array();
			var finalendTime<?php echo $id_suffix;?> = 0;
			<?php $a=0;
			foreach($items as $vitem):
				set_current_record('Item',$vitem); ?>
					startTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment Start'));?>");
					endTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
					if ((calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>")) > (finalendTime<?php echo $id_suffix;?>)){
						finalendTime<?php echo $id_suffix;?> = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
					};
					<?php $a++; ?>	
			<?php endforeach;?>
			</script>
<?php if (metadata('item', array('Kaltura Video','Video Source')) == 'Kaltura'){?>
	<!-- Kaltura video setup -->
		<div id="vid_player-<?php echo $id_suffix;?>" style="height:<?php echo $height;?>;" itemprop="video" itemscope itemtype="http://schema.org/VideoObject"><span itemprop="duration" content="120"></span></div>
		<div class="debug">Playback: <span id="pos-<?php echo $id_suffix;?>"> 0:00:00 </span> - <span id="finalend-<?php echo $id_suffix;?>"> 0:00:00 </span></div>
	<?php } else { ?>
	<!-- Youtube setup for Videojs -->	
		<div id="vid_player-<?php echo $id_suffix;?>" style="margin: -6px 15px;">

			<video id="video<?php echo $id_suffix;?>" title="Video Player <?php echo $id_suffix;?>" class="video-js vjs-default-skin" 
			controls 
			preload="auto" width=100% height=<?php echo $height;?>
			ytcontrols playsInline


				data-setup='{ "inactivityTimeout": 2000, "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "<?php echo metadata('item',array('Kaltura Video','Youtube URL'));?>"}], "youtube": { "iv_load_policy": 1 }, "youtube": { "ytControls": 2 } }'>
			</video>
								<div class="debug">Playback: <span id="pos-<?php echo $id_suffix;?>"> 0:00:00 </span> - <span id="finalend-<?php echo $id_suffix;?>"> 0:00:00 </span> </div>
		</div>
		
<script>
	videojs("video<?php echo $id_suffix;?>").ready(function(){
    var myPlayer<?php echo $id_suffix;?> = this;
   	var start = startTime<?php echo $id_suffix;?>[0];
	var end = endTime<?php echo $id_suffix;?>[0];
	var fend = finalendTime<?php echo $id_suffix;?>;
		$("#pos-<?php echo $id_suffix;?>").html(getFormattedTimeString(start));
		$("#finalend-<?php echo $id_suffix;?>").html(getFormattedTimeString(fend));
		jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
		//jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
	  // EXAMPLE: Start playing the video.
	  myPlayer<?php echo $id_suffix;?>.currentTime(startTime<?php echo $id_suffix;?>[0]);
	  myPlayer<?php echo $id_suffix;?>.pause();

	});
	var checkTime<?php echo $id_suffix;?> = function(){
	  var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
	  var ctime = "0:00:00";
	  var scenes;
	  var sel;
	  var i = 0;
	  ctime = calculateTime(videojs("video<?php echo $id_suffix;?>").currentTime());
		$("#pos-<?php echo $id_suffix;?>").html("<b>"+getFormattedTimeString(videojs("video<?php echo $id_suffix;?>").currentTime())+"</b>");	
		if (myPlayer<?php echo $id_suffix;?>.currentTime() > finalendTime<?php echo $id_suffix;?>) {
			myPlayer<?php echo $id_suffix;?>.pause();
			myPlayer<?php echo $id_suffix;?>.on("pause",newEndTime<?php echo $id_suffix;?>);
			};
		if (myPlayer<?php echo $id_suffix;?>.currentTime() < startTime<?php echo $id_suffix;?>[0]) {
			myPlayer<?php echo $id_suffix;?>.pause();
			myPlayer<?php echo $id_suffix;?>.on("pause",newStartTime<?php echo $id_suffix;?>);
			};
	        	scenes = document.getElementsByClassName("play<?php echo $id_suffix;?>");
	        	for (i; i < scenes.length; i++) {
	            	sel = scenes[i];
	            	if (calculateTime(sel.getAttribute('stime')) < ctime && calculateTime(sel.getAttribute('etime')) > ctime) 		
					{
						//$(sel).addClass('borderClass');
						$(sel).show();
						$("#pos-<?php echo $id_suffix;?>").html("<b>"+getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
						//$(sel).html("<b>Playing Segment</b>");
	            	} else {
						//$(sel).removeClass('borderClass');
						$(sel).hide();				
	            	}
	        	}
		};

	var newStartTime<?php echo $id_suffix;?> = function(){
		var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
		jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
		jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
		myPlayer<?php echo $id_suffix;?>.currentTime(startTime<?php echo $id_suffix;?>[0]);
		myPlayer<?php echo $id_suffix;?>.off("pause",newStartTime<?php echo $id_suffix;?>);
		myPlayer<?php echo $id_suffix;?>.off("pause",newEndTime<?php echo $id_suffix;?>);
			myPlayer<?php echo $id_suffix;?>.play();
	};
	var newEndTime<?php echo $id_suffix;?> = function(){
		var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
		jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
		jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));  
		myPlayer<?php echo $id_suffix;?>.currentTime(finalendTime<?php echo $id_suffix;?>);
		myPlayer<?php echo $id_suffix;?>.off("pause",newEndTime<?php echo $id_suffix;?>);
		myPlayer<?php echo $id_suffix;?>.off("pause",newStartTime<?php echo $id_suffix;?>);
	};

videojs("video<?php echo $id_suffix;?>").on("timeupdate",checkTime<?php echo $id_suffix;?>);

</script>
<?php }; ?>
</div>
<!-- Show current Sidebar -->
<?php if ($current && metadata('item',array('Kaltura Video','Show Item'))=="TRUE"){?>
		<div class="displaySegmentRightColumn" style="float:<?php echo $float;?>;" >
			<div class="roundMe" style="margin: 0 1em; height:<?php echo $height;?>; width: 100%;  float:<?php echo $float;?>">
<!--			<div id="currentTime<?php //echo metadata('item', array('Dublin Core', 'Identifier'));?>"><?php //echo $this->getFormattedTimeString(metadata(get_current_record('item'),array('Kaltura Video','Segment Start')));?></div>
-->
			<div class="tabContent" style="position:relative; top:0px; width: 100%; height:100%; display:block; float:<?php echo $float;?>" >
				<h4>media Segments</h4>
			        <?php $orig_item=get_current_record('item');
					$orig_video = metadata("item", array("Kaltura Video","Video File ID"));?>

				<?php
				foreach($items as $item):
				$estarttime = metadata('item',array('Kaltura Video','Segment Start'));	
				set_current_record('Item',$item);
		        if (metadata('item',array('Kaltura Video','Show Item'))=="TRUE"){ ?>
				<ul id="eventList<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="-webkit-padding-start:10px;">	    							
					<li id="<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="liBullet <?php echo metadata('item',array('Kaltura Video','Segment Type'));?>">
						<span class="bullet<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>"></span>
						<div class="event<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>">
						<div class="eventLabel<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>"><?php echo metadata('item',array('Kaltura Video','Segment Type'));?></br>
							<strong> <?php echo metadata('item',array('Dublin Core','Title'));?></strong><br>
							<p id="spanTime<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="timecode<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="margin: 0 0 0 0;"><?php echo $this->getFormattedTimeString(metadata('item',array('Kaltura Video','Segment Start')));?> - <?php echo $this->getFormattedTimeString(metadata('item',array('Kaltura Video','Segment End')));?></p>
							<a id="doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" title="Play Video Segment" stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>" href='#'>Seek</a>
							<p style="margin: 0 0 0 0;" class="timecode<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>"></p>
						</div>
				        
				        <a class="header<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" id="header<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" title="Show Description" href='#'>Show Description</a>									
						<div class="play<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" title="Video Segment Description" id="hierarchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="display:none; margin-top: 10px; border-top:1px dotted #333; border-bottom:1px dotted #333; padding: 5%; width: 90%;">
							<?php echo all_element_texts('item',array('show_element_set_headings'=>false,'show_element_sets'=>array('Dublin Core','Item Type Metadata'), 'partial'=>'common/record-metadata.php')); ?>
							<?php if(metadata('item','Collection Name')): ?>
						      <div id="collection" class="element">
						        <h7><label><?php echo __('Collection'); ?></label></h7>
						        <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
						      </div>
						   <?php endif; ?>
							<h7><label><?php echo __('Files'); ?></label></h7>
						    <div id="item-images">
						         <?php echo files_for_item(); ?>
						    </div>
						</div>
						<div id="<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="play<?php echo $id_suffix;?>" title="Play Video Segment" stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>" style="color:darkblue; display:none;">
							<strong> Playing Segment </strong></div>
						</div>
					</li>
			    </ul> <!-- end of loop ul for display -->
		        <?php } else { ?>
							<p style="float:left"><?php echo $item["caption"];?></p>
						<?php };?>			
		        <?php
		 		endforeach;?>
		        	<!-- <hr style="color:lt-gray;"/> -->
			</div>
			</div>
		</div>
		<?php };?>


<!-- Javascript setup and controlling Sidebar for Kaltura -->
<?php if (metadata('item', array('Kaltura Video','Video Source')) == 'Kaltura'){
$partner = empty(metadata('item', array('Kaltura Video','Partner ID'))) ? get_option('kaltura_partner') : metadata('item', array('Kaltura Video','Partner ID'));
$uiconf = empty(metadata('item', array('Kaltura Video','Video UI Conf'))) ? get_option('kaltura_uiconf') : metadata('item', array('Kaltura Video','Video UI Conf')); 
?>
<script src="https://cdnapisec.kaltura.com/p/<?php echo $partner;?>/sp/<?php echo $partner;?>00/embedIframeJs/uiconf_id/<?php echo $uiconf;?>/partner_id/<?php echo $partner;?>"></script>
<script>
  kWidget.embed({
 	'targetId': 'vid_player-<?php echo $id_suffix;?>',
 	'wid': '_<?php echo $partner;?>',
	'uiconf_id' : '<?php echo $uiconf;?>',
	'entry_id' : '<?php echo metadata('item', array('Kaltura Video','Video File ID'));?>',
	'flashvars':{ 		'controlBarContainer': {
        	'plugin': true,
        	'hover': true,
   	 		},    
			'autoPlay': false,
	},// flashvars allows you to set runtime uiVar configuration overrides. 


	readyCallback: function( playerId ){
		var kdp = document.getElementById( playerId );
		var start = startTime<?php echo $id_suffix;?>[0];
		var end = endTime<?php echo $id_suffix;?>[0];
		var fend = finalendTime<?php echo $id_suffix;?>;
		// Wait for "media ready" before starting playback: 
		$("#pos-<?php echo $id_suffix;?>").html(getFormattedTimeString(start));
		$("#finalend-<?php echo $id_suffix;?>").html(getFormattedTimeString(fend));
		<?php
		if ($current){
		foreach($items as $item):
			set_current_record('Item',$item);?>
		$("#header<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").click(function (event) 
			{								
			event.preventDefault();
			$header = $(this);
		    if ($header.html()=="Show Description") {
		    	$header.html("Hide Description");
				$header.attr("title", "Hide Description");
			 } else {
			    $header.html("Show Description");
				$header.attr("title","Show Description");
			 };
		 //getting the next element
		 $content = $("#hierarchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>");
		 //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
		 $content.toggle();
		});

		$("a#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").on("click", function (event) {
			event.preventDefault();
			if ($("a#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html()=="Seek") {
				$("a#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html("Seek");
				kdp.sendNotification("doSeek", calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment Start'));?>"));
				//kdp.sendNotification("doPlay");
			} else {
				$("a#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html("Seek");
				kdp.sendNotification("doPause");
			}
		});
		<?php endforeach;
		}?>
		kdp.kBind('mediaReady', function(){
			//kdp.sendNotification("doPlay");
			kdp.setKDPAttribute('scrubber', 'visible', true);	
			kdp.setKDPAttribute('durationLabel', 'visible', false);
			kdp.sendNotification("doSeek", start);
			
			//kdp.sendNotification("doPlay");
			
		});
		
		// Add a binding for when seek is completed: 
		//kdp.kBind('playerSeekEnd', function(){
		//Pause player 
			//setTimeout(function(){
			//	kdp.sendNotification('doPause' );
			//},5000)
		//});
		
		kdp.kBind( 'playerUpdatePlayhead', function(event){
			var curTime = Math.floor(JSON.stringify(event));
			$("#pos-<?php echo $id_suffix;?>").html("<b>"+getFormattedTimeString(JSON.stringify(event)));
			
			if (curTime >= fend || curTime < start) {
				kdp.sendNotification("doSeek", start);
				//kdp.sendNotification('doPause');
			} else {
			  	var ctime = "00:00:00";
				var scenes;
				var sel;
				var i = 0;
				ctime = calculateTime(JSON.stringify(event));
				<?php if ($current) {?>
					scenes = document.getElementsByClassName("play<?php echo $id_suffix;?>");
					for (i; i < scenes.length; i++) {
					  	sel = scenes[i];
					   	if (calculateTime(sel.getAttribute('stime')) < ctime && calculateTime(sel.getAttribute('etime')) > ctime){
							//$(sel).addClass('borderClass');	
							$(sel).show();
							//$(sel).html("<b>Playing Segment</b>");
						 } else {
							//$(sel).removeClass('borderClass');
							$(sel).hide();				
						}
					}
			<?php }?>
			};
		});
		}
		});
		
		//videojs("video<?php echo $id_suffix;?>").on("timeupdate",checkTime<?php echo $id_suffix;?>);
  		
</script>
<?php } 
	if (metadata('item', array('Kaltura Video','Video Source')) == 'Youtube'){?>
		<!-- Controls sidebar for Youtube and other videos -->
		<script type="text/javascript">
					var startTime<?php echo $id_suffix;?> = new Array();
					var endTime<?php echo $id_suffix;?> = new Array();
					var finalendTime<?php echo $id_suffix;?> = 0;
					<?php $a=0;
					foreach($items as $vitem):
						set_current_record('Item',$vitem); ?>
							startTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment Start'));?>");
							endTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
							if ((calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>")) > (finalendTime<?php echo $id_suffix;?>)){
								finalendTime<?php echo $id_suffix;?> = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
							};
							$("#header<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").click(function (event) {								
								event.preventDefault();
								$header = $(this);
					           if ($header.html()=="Show Description") {
					           $header.html("Hide Description");
								$header.attr("title", "Hide Description");
					           } else {
					           $header.html("Show Description");
								$header.attr("title","Show Description");
					           };
					          //getting the next element
					           $content = $("#hierarchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>");
					           //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
					           $content.toggle();
					        });

					        $("#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").click(function (event) {
								event.preventDefault();
								if ($("#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html()=="Seek") {
									$("#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html("Seek");
									videojs("video<?php echo $id_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
									videojs("video<?php echo $id_suffix;?>").play();
								} else {
									$("#doplay<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>").html("Seek");
									videojs("video<?php echo $id_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
									videojs("video<?php echo $id_suffix;?>").pause();
								}
							});
							<?php $a++; ?>	
					<?php endforeach;?>
		</script>
		<?php };?>

