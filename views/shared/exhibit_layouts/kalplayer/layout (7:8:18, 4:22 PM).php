 <?php
$float = isset($options['video-float'])
	? html_escape($options['video-float'])
	: 'left';
$width = isset($options['video-width'])
	? html_escape($options['video-width'])
	: '95%';
if ($width == '100'){
	$width = '95';
};
$height='325';
//$height=array(
//	"95%" => "42%",
//	"80%" => "36%",
//	"70%" => "3%",
//	"60%" => "24%",
//	"50%" => "20%",
//	"40%" => "15%",
//);
$current = isset($options['current-seg'])
	? html_escape($options['current-seg'])
	: false;
$external = isset($options['video-controls'])
	? html_escape($options['video-controls'])
	: false;
$eid_suffix = 0;
global $eid_suffix;
?>
<?php
	$poster=array();
	$caption=array();
	$ci = 0;
	foreach($attachments as $attItem):
	$item = $attItem->getItem();	
	set_current_record('Item',$item);
	if (metadata($item,'has files')){
				$files = $item->Files;
				    foreach($files as $file) {
				        if($file->hasThumbnail()) {
							$poster[$ci]= file_display_url($file,'thumbnail');
				        }
					}
				}
	endforeach;
	?>
	<?php if ($current) { ?>
		<div class="displaySegmentLeftColumn">
		<?php $width=100;?>
	<?php }; ?>
	<?php if (metadata('item', array('Kaltura Video','Video Source')) == 'Kaltura'){?>
		<div id="vid_player-<?php echo $eid_suffix;?>" Title="Video Player <?php echo $eid_suffix;?>" style="width:100%; height:325px; float:<?php echo $float;?>; padding: 0 7% 0% 3%;">
<script src="https://cdnapisec.kaltura.com/p/<?php echo metadata('item', array('Kaltura Video','Partner ID'));?>/sp/<?php echo metadata('item', array('Kaltura Video','Partner ID'));?>00/embedIframeJs/uiconf_id/<?php echo metadata('item', array('Kaltura Video','Video UI Conf'));?>/partner_id/<?php echo metadata('item', array('Kaltura Video','Partner ID'));?>"></script>
		<script>
		      kWidget.embed({
		         'targetId': 'vid_player-<?php echo $eid_suffix;?>',
		         'wid': "_<?php echo metadata('item',array('Kaltura Video','Video File ID'));?>",
		         'uiconf_id' : "<?php echo metadata('item',array('Kaltura Video','Partner ID'));?>",
		         'entry_id' : "<?php echo metadata('item',array('Kaltura Video','Video UI Conf'));?>",
		         'flashvars':{ // flashvars allows you to set runtime uiVar configuration overrides. 
		              'autoPlay': false,
		         },
		         'params':{ // params allows you to set flash embed params such as wmode, allowFullScreen etc
		              'wmode': 'transparent'
		         },
		         readyCallback: function( playerId ){

					var kdp = document.getElementById( playerId );
					var start = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment Start'));?>");
					var end = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>");
					kdp.kBind('playerSeekEnd', function(){
							// Pause player after 2 second 
							setTimeout(function(){
								kdp.sendNotification('doPause' );
							},250)
						});
						
						// Wait for "media ready" before starting playback: 
						kdp.kBind('mediaReady', function(){
							kdp.sendNotification("doSeek", end);
							kdp.sendNotification("doPlay");
						})
						var seekDone = false;
						kdp.kBind( 'playerUpdatePlayhead', function(event){
								if (!seekDone || JSON.stringify(event) >= end || JSON.stringify(event) < start) {
									kdp.sendNotification("doSeek", start);
									kdp.sendNotification("doPlay");
								}
								seekDone = true;
					    });
			         }
				 });
		</script>

		<?php }?>
	
			<video id="video<?php echo $eid_suffix;?>" Title="Video Player <?php echo $eid_suffix;?>"class="video-js vjs-default-skin" style="float:<?php echo $float?>;"
				<?php if (!$external) {?>  
				controls 
				<?php }; ?>
				preload="auto" width=<?php echo $width;?>% height=<?php echo $height;?>
				ytcontrols playsInline
				<?php if (isset($poster[0])){?>
				poster="<?php echo $poster[0];?>"
				<?php }?>
				<?php if (metadata('item', array('Kaltura Video','Video Source')) == 'Youtube'){?>
					data-setup='{ "inactivityTimeout": 2000, "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "<?php echo metadata('item',array('Kaltura Video','Youtube URL'));?>"}], "Youtube": { "iv_load_policy": 1 }, "Youtube": { "ytControls": 2 } }'>
				<?php } else { ?>
			  		data-setup='{"example_option":true, "inactivityTimeout": 2000, "nativeControlsForTouch": false}'>
					<?php if (metadata('item',array('Kaltura Video','Video File ID'))){?>
						<source src="<?php echo metadata('item',array('Kaltura Video','Video File ID'));?><?php echo metadata('item',array('Kaltura Video','Partner ID'));?><?php echo metadata('item',array('Kaltura Video','Video UI Conf'));?>" type='rtmp/mp4'/>
					<?php } ?>
				 <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
				<?php } ?>
			</video>
		<?php if ($current) { ?>
		</div>
		<?php }; ?>
			<?php if ($current) { ?>
			        <?php $orig_item=get_current_record('item');
					$orig_video = metadata("item", array("Kaltura Video","Video UI Conf"));?>
					<?php ?>

				<div class="displaySegmentRightColumn">
					<div class="roundMe" style="height:320px;">
					<div class="tabContent" style="position:relative; top:0px; display:block;" >
						<h4>media Segments</h4>
					<?php
					foreach($attachments as $attItem):
					$item = $attItem->getItem();
					$estarttime = metadata('item',array('Kaltura Video','Segment Start'));	
					set_current_record('Item',$item);
			        if (metadata('item',array('Kaltura Video','Show Item'))=="True"){ ?>
					<ul id="eventList" style="-webkit-padding-start:10px;">	    
								<li id="<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="liBullet <?php echo metadata('item',array('Kaltura Video','Segment Type'));?>">
									<span class="bullet"></span>
									<div class="event<?php echo $eid_suffix;?>" stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>">
										<div class="eventLabel"><?php echo metadata('item',array('Kaltura Video','Segment Type'));?> <span class="timecode"><?php echo $this->getFormattedTimeString(metadata('item',array('Kaltura Video','Segment Start')));?> - <?php echo $this->getFormattedTimeString(metadata('item',array('Kaltura Video','Segment End')));?></span>
										</div>
					            <strong> <?php echo metadata('item',array('Dublin Core','Title'));?></strong><br>
					            <a class="header<?php echo $eid_suffix;?>" title="Show Description" href='#'>Show Description | </a>
								<a id="doplay<?php echo $eid_suffix;?>" title="Play Video Segment"stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>" href='#'> Play Segment</a>
								
								<div class="hierarchyDesc" title="Video Segment Description" id="hiearchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="display:none; margin-top: 10px; border-top:1px dotted #333; border-bottom:1px dotted #333; padding: 5%; width: 90%;">

								<?php echo metadata('item',array('Dublin Core', 'Description'));?> 
								</div>
								<div class="play<?php echo $eid_suffix;?>" title="Play Video Segment"stime="<?php echo metadata('item',array('Kaltura Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Kaltura Video','Segment End'));?>"style="color:darkblue; display:none"><strong>Playing Segment </strong></font></div>
								</div>
								</li>
					        </ul> <!-- end of loop ul for display -->

			        <?php } else { ?>
								<p style="float:left"><?php echo $attItem["caption"];?></p>
							<?php };?>			
			        <?php
			 				endforeach;?>
			        <hr style="color:lt-gray;"/>
					</div>
					</div>
				</div>
		<?php }; ?>
<script type="text/javascript">
var startTime<?php echo $eid_suffix;?> = new Array();
var endTime<?php echo $eid_suffix;?> = new Array();
var finalendTime<?php echo $eid_suffix;?> = 0;
<?php $a=0;
foreach($attachments as $vattItem):
	$vitem = $vattItem->getItem();	
	set_current_record('Item',$vitem); ?>

	startTime<?php echo $eid_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment Start'));?>");
	endTime<?php echo $eid_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
	if ((calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>")) > (finalendTime<?php echo $eid_suffix;?>)){
		finalendTime<?php echo $eid_suffix;?> = calculateTime("<?php echo metadata('item', array('Kaltura Video','Segment End'));?>") ;
	};
	<?php $a++; ?>	
<?php endforeach;?>
videojs("video<?php echo $eid_suffix;?>").ready(function(){
  var myPlayer<?php echo $eid_suffix;?> = this;    // Store the video object
  var aspectRatio = 3/4;
	jQuery('#video<?php echo $eid_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $eid_suffix;?>));
	jQuery("#CurrentPos<?php echo $eid_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $eid_suffix;?>.currentTime()));  // EXAMPLE: Start playing the video.
  myPlayer<?php echo $eid_suffix;?>.currentTime(startTime<?php echo $eid_suffix;?>[0]);
  myPlayer<?php echo $eid_suffix;?>.pause();
});
var checkTime<?php echo $eid_suffix;?> = function(){
  var myPlayer<?php echo $eid_suffix;?> = videojs("video<?php echo $eid_suffix;?>");
  var ctime = "0:00:00";
  var scenes;
  var sel;
  var i = 0;
  ctime = calculateTime(videojs("video<?php echo $eid_suffix;?>").currentTime());
	jQuery('#video<?php echo $eid_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $eid_suffix;?>));
	jQuery("#CurrentPos<?php echo $eid_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $eid_suffix;?>.currentTime()));
	if (myPlayer<?php echo $eid_suffix;?>.currentTime() > finalendTime<?php echo $eid_suffix;?>) {
		myPlayer<?php echo $eid_suffix;?>.pause();
		myPlayer<?php echo $eid_suffix;?>.on("pause",newEndTime<?php echo $eid_suffix;?>);
		};
	if (myPlayer<?php echo $eid_suffix;?>.currentTime() < startTime<?php echo $eid_suffix;?>[0]) {
		myPlayer<?php echo $eid_suffix;?>.pause();
		myPlayer<?php echo $eid_suffix;?>.on("pause",newStartTime<?php echo $eid_suffix;?>);
		};
 
		<?php if ($current) {
		        ?>
        scenes = document.getElementsByClassName("play<?php echo $eid_suffix;?>");
        for (i; i < scenes.length; i++) {
            sel = scenes[i];
            if (calculateTime(sel.getAttribute('stime')) < ctime && calculateTime(sel.getAttribute('etime')) > ctime) 			
			{
                //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
				//$(sel).addClass('borderClass');
                //$(sel).html("<b>Playing Segment</b>");
				$(sel).show();
            } else {
                //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
				//$(sel).removeClass('borderClass');
                //$(sel).html("Play Video");
				$(sel).hide();
            }
        }
		<?php }?>
  	};
var newStartTime<?php echo $eid_suffix;?> = function(){
	var myPlayer<?php echo $eid_suffix;?> = videojs("video<?php echo $eid_suffix;?>");
	jQuery('#video<?php echo $eid_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $eid_suffix;?>));
	jQuery("#CurrentPos<?php echo $eid_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $eid_suffix;?>.currentTime()));  
	
	myPlayer<?php echo $eid_suffix;?>.currentTime(startTime<?php echo $eid_suffix;?>[0]);
	myPlayer<?php echo $eid_suffix;?>.off("pause",newStartTime<?php echo $eid_suffix;?>);
	myPlayer<?php echo $eid_suffix;?>.off("pause",newEndTime<?php echo $eid_suffix;?>);
		myPlayer<?php echo $eid_suffix;?>.play();
};
var newEndTime<?php echo $eid_suffix;?> = function(){
	var myPlayer<?php echo $eid_suffix;?> = videojs("video<?php echo $eid_suffix;?>");
	jQuery('#video<?php echo $eid_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $eid_suffix;?>));
	jQuery("#CurrentPos<?php echo $eid_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $eid_suffix;?>.currentTime()));  
	
	myPlayer<?php echo $eid_suffix;?>.currentTime(finalendTime<?php echo $eid_suffix;?>);
	myPlayer<?php echo $eid_suffix;?>.off("pause",newEndTime<?php echo $eid_suffix;?>);
	myPlayer<?php echo $eid_suffix;?>.off("pause",newStartTime<?php echo $eid_suffix;?>);
	
};
        function getElementsByClass(searchClass, domNode, tagName)
        {
            if (domNode == null) {
                domNode = document;
            }
            if (tagName == null) {
                tagName = '*';
            }
            var el = new Array();
            var tags = domNode.getElementsByTagName(tagName);
            var tcl = " "+searchClass+" ";
            for (i=0,j=0; i<tags.length; i++) {
                var test = " " + tags[i].className + " ";
                if (test.indexOf(tcl) != -1) {
                    el[j++] = tags[i];
                }
            }
            return el;
        };
         $(".header<?php echo $eid_suffix;?>").click(function (event) {
				event.preventDefault();
			$header = $(this);
         if ($header.html()=="Show Description | ") {
         $header.html("Hide Description | ");
			$header.attr("title","Hide Description");
         } else {
         $header.html("Show Description | ");
			$header.attr("title","Show Description");
         };
        //getting the next element
         $content = $header.next().next();
         //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
         $content.toggle();
         })

         $("a#doplay<?php echo $eid_suffix;?>").on("click", function (event) {
				event.preventDefault();
				$player = $(this);
				videojs("video<?php echo $eid_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
				videojs("video<?php echo $eid_suffix;?>").play();
			});

		videojs("video<?php echo $eid_suffix;?>").on("timeupdate",checkTime<?php echo $eid_suffix++;?>);

</script>

