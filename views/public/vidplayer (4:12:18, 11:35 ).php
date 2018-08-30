1<?php 
$float = isset($params['float'])
	? html_escape($params['float'])
	: 'left';
$width = isset($params['width'])
	? html_escape($params['width'])
	: '100%';
$height = isset($params['height'])
	? html_escape($params['height'])
	: '330';
$current = isset($params['current'])?html_escape($params['current']) : true;
$external = isset($params['ext'])?html_escape($params['ext']) : false;
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
	<?php if ($current==true && metadata('item',array('Streaming Video','Show Item'))=="True") { ?>
		<div style="width:<?php echo $width;?>;">
		<div class="displaySegmentLeftColumn">
	<?php } else { ?>
		<div style="width:100%;">
		<div style="width:<?php echo $width;?>; height:<?php echo $height;?>;  float:<?php echo $float;?>;">
	<?php }; ?>
	<?php if (metadata('item', array('Streaming Video','Video Source')) == 'kaltura'){?>
		<div id="vid_player-<?php echo $id_suffix;?>" style="width:100%; height:325px; float:<?php echo $float;?>; padding: 2% 0% 0% 5%;" itemprop="video" itemscope itemtype="http://schema.org/VideoObject"><span itemprop="duration" content="460"></span>
		
			<script>
			var startTime<?php echo $id_suffix;?> = new Array();
			var endTime<?php echo $id_suffix;?> = new Array();
			var finalendTime<?php echo $id_suffix;?> = 0;
			<?php $a=0;
			foreach($items as $vitem):
				set_current_record('Item',$vitem); ?>
					startTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment Start'));?>");
					endTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
					if ((calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>")) > (finalendTime<?php echo $id_suffix;?>)){
						finalendTime<?php echo $id_suffix;?> = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
					};
					<?php $a++; ?>	
			<?php endforeach;?>
			</script>
 <script
 src="https://cdnapisec.kaltura.com/p/<?php echo metadata('item', array('Streaming Video','Partner ID'));?>/sp/<?php echo metadata('item', array('Streaming Video','Partner ID'));?>00/embedIframeJs/uiconf_id/<?php echo metadata('item', array('Streaming Video','Video UI Conf'));?>/partner_id/<?php echo metadata('item', array('Streaming Video','Partner ID'));?>"></script>
	<script>
		      kWidget.embed({
		         'targetId': 'vid_player-<?php echo $id_suffix;?>',
		         'wid': '_<?php echo metadata('item', array('Streaming Video','Partner ID'));?>',
		         'uiconf_id' : '<?php echo metadata('item', array('Streaming Video','Video UI Conf'));?>',
		         'entry_id' : '<?php echo metadata('item', array('Streaming Video','Video File ID'));?>',
		         'flashvars':{ // flashvars allows you to set runtime uiVar configuration overrides. 
//					 "customKaltura":{
//					            "plugin": true, // plugins should be enabled with plugin=true attribute 
//				                "iframeHTML5Js" : "../../plugins/ShortcodeVideo/views/public/javascripts/customKaltura.js"
//					 },  
//					"IframeCustomPluginJs1" :  "<?php echo WEB_PLUGIN?>/ShortcodeVideo/views/public/javascript/customKaltura.js",      
//						'autoPlay': true,
//						'controlBarContainer.plugin': true,
//							'largePlayBtn.plugin': true,
//							'loadingSpinner.plugin': true,
		         },
//		         'params':{ // params allows you to set flash embed params such as wmode, allowFullScreen etc
//		              'wmode': 'transparent'
//		         },
		         readyCallback: function( playerId ){

					var kdp = document.getElementById( playerId );
					var start = startTime<?php echo $id_suffix;?>[0];
					var end = finalendTime<?php echo $id_suffix;?>;
						// Wait for "media ready" before starting playback: 
						kdp.kBind('mediaReady', function(){
							kdp.sendNotification("doPlay");
							
							//kdp.sendNotification("doPlay");
							//kdp.setKDPAttribute('durationLabel', '', 375);
							//kdp.setKDPAttribute('scrubber', 'visible', false);	
							kdp.setKDPAttribute('durationLabel', 'visible', false);
						 $(".header<?php echo $id_suffix;?>").click(function (event) {								
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
					           $content = $header.next();
					           //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
					           $content.toggle();
					        });

			                $("a#doplay<?php echo $id_suffix;?>").on("click", function (event) {
								event.preventDefault();
								if ($("a#doplay<?php echo $id_suffix;?>").html()=="Play") {
									$("a#doplay<?php echo $id_suffix;?>").html("Pause");
									kdp.sendNotification("doPlay");
								} else {
									$("a#doplay<?php echo $id_suffix;?>").html("Play");
									kdp.sendNotification("doPause");
								}
							});
						})
						// Add a binding for when seek is completed: 
							kdp.kBind('playerSeekEnd', function(){
								// Pause player 
								setTimeout(function(){
									kdp.sendNotification('doPause' );
								},1000)
							});
						kdp.kBind( 'playerUpdatePlayhead', function(event){
								if (JSON.stringify(event) >= end || JSON.stringify(event) < start) {
									kdp.sendNotification("doSeek", start);
								} else {
								$("#currentTime").html("<b>"+getFormattedTimeString(JSON.stringify(event))+"</b> - "+getFormattedTimeString(end));
								};
					    });
			         }
				 });
		</script>

		<?php }; ?>

			<?php if (metadata('item', array('Streaming Video','Video Source')) == 'youtube'){?>
		<div id="vid_player-<?php echo $id_suffix;?>" style="width:100%; height:<?php echo $height;?>;  float:<?php echo $float;?>; padding: 0 7% 0% 3%;">

			<video id="video<?php echo $id_suffix;?>" title="Video Player <?php echo $id_suffix;?>" class="video-js vjs-default-skin" 
			<?php if (!$external) {?>
			  controls 
			<?php };?>
			preload="auto" width=100% height=<?php echo $height;?>
			ytcontrols playsInline
			<?php if (isset($poster[0])){?>
			poster="<?php echo $poster[0];?>"
			<?php }?>

				data-setup='{ "inactivityTimeout": 2000, "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "<?php echo metadata('item',array('Streaming Video','HTTP Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HTTP Video Filename'));?>"}], "youtube": { "iv_load_policy": 1 }, "youtube": { "ytControls": 2 } }'>
			<?php } else { ?>
		  		data-setup='{"example_option":true, "inactivityTimeout": 2000, "nativeControlsForTouch": false}'>
				<?php if (metadata('item',array('Streaming Video','HLS Streaming Directory'))){?>
					<source src="<?php echo metadata('item',array('Streaming Video','HLS Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HLS Video Filename'));?>" type='application/x-mpegurl'/>
				<?php } ?>
				<?php if (metadata('item',array('Streaming Video','HTTP Streaming Directory'))){?>
					<source src="<?php echo metadata('item',array('Streaming Video','HTTP Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HTTP Video Filename'));?>" type='video/mp4'/>
				<?php } ?>
			 <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			<?php } ?>
			</video>
			</div>
			<?php if ($current && metadata('item',array('Streaming Video','Show Item'))=="True") { ?>
				</div>
				</div>
			<script type="text/javascript">
			var startTime<?php echo $id_suffix;?> = new Array();
			var endTime<?php echo $id_suffix;?> = new Array();
			var finalendTime<?php echo $id_suffix;?> = 0;
			<?php $a=0;
			foreach($items as $vitem):
				set_current_record('Item',$vitem); ?>
					startTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment Start'));?>");
					endTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
					if ((calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>")) > (finalendTime<?php echo $id_suffix;?>)){
						finalendTime<?php echo $id_suffix;?> = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
					};
					<?php $a++; ?>	
			<?php endforeach;?>
			videojs("video<?php echo $id_suffix;?>").ready(function(){
			  var myPlayer<?php echo $id_suffix;?> = this;
				jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
				jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
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
			  $("#currentTime").html("<b>"+getFormattedTimeString(videojs("video<?php echo $id_suffix;?>").currentTime())+"</b> - "+getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
				jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
				jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
				if (myPlayer<?php echo $id_suffix;?>.currentTime() > finalendTime<?php echo $id_suffix;?>) {
					myPlayer<?php echo $id_suffix;?>.pause();
					myPlayer<?php echo $id_suffix;?>.on("pause",newEndTime<?php echo $id_suffix;?>);
					};
				if (myPlayer<?php echo $id_suffix;?>.currentTime() < startTime<?php echo $id_suffix;?>[0]) {
					myPlayer<?php echo $id_suffix;?>.pause();
					myPlayer<?php echo $id_suffix;?>.on("pause",newStartTime<?php echo $id_suffix;?>);
					};
					<?php if ($current) {?>
			        	scenes = document.getElementsByClassName("play<?php echo $id_suffix;?>");
			        	for (i; i < scenes.length; i++) {
			            	sel = scenes[i];
			            	if (calculateTime(sel.getAttribute('stime')) < ctime && calculateTime(sel.getAttribute('etime')) > ctime) 		
							{
								//$(sel).addClass('borderClass');
								$(sel).show();
								//$(sel).html("<b>Playing Segment</b>");
			            	} else {
								//$(sel).removeClass('borderClass');
								$(sel).hide();				
			            	}
			        	}
					<?php 
					}
					?>
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

					</script>
					<?php }; ?>	
					
				<?php if ($current && metadata('item',array('Streaming Video','Show Item'))=="True"){?>
						<div class="displaySegmentRightColumn" style="float:<?php echo $float;?>;" >
							<div class="roundMe" style="height: 320px; width: 70%; float:<?php echo $float;?>">
							<div class="tabContent" style="position:relative; top:0px; width: 100%; display:block; float:<?php echo $float;?>" >
								<h4>media Segments</h4>
						        <?php $orig_item=get_current_record('item');
								$orig_video = metadata("item", array("Streaming Video","Video File ID"));?>
							<?php
							foreach($items as $item):
							$estarttime = metadata('item',array('Streaming Video','Segment Start'));	
							set_current_record('Item',$item);
					        if (metadata('item',array('Streaming Video','Show Item'))=="True"){ ?>
							<ul id="eventList" style="-webkit-padding-start:10px;">	    								<li id="<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="liBullet <?php echo metadata('item',array('Streaming Video','Segment Type'));?>">
									<span class="bullet"></span>
									<div class="event<?php echo $id_suffix;?>" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>">
									<div class="eventLabel"><?php echo metadata('item',array('Streaming Video','Segment Type'));?></br>
										<strong> <?php echo metadata('item',array('Dublin Core','Title'));?></strong><br>
										<p id="currentTime" class="timecode" style="margin: 0 0 0 0;"><?php echo $this->getFormattedTimeString(metadata('item',array('Streaming Video','Segment Start')));?> - <?php echo $this->getFormattedTimeString(metadata('item',array('Streaming Video','Segment End')));?></p>
										<a id="doplay<?php echo $id_suffix;?>" title="Play Video Segment" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>" href='#'>Play</a>
										<p style="margin: 0 0 0 0;" class="timecode"></p>
									</div>
							        
							        <a class="header<?php echo $id_suffix;?>" id="header<?php echo $id_suffix;?>x" title="Show Description" href='#'>Show Description</a>									
									<div class="play<?php echo $id_suffix;?>x" title="Video Segment Description" id="hiearchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="display:none; margin-top: 10px; border-top:1px dotted #333; border-bottom:1px dotted #333; padding: 5%; width: 90%;">
										<?php echo metadata('item',array('Dublin Core', 'Description'));?> 
									</div>
									<div class="hierarchyDesc" title="Play Video Segment" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>" style="color:darkblue; display:none;">
										<strong> Playing Segment </strong></font></div>
									</div>
								</li>
						    </ul> <!-- end of loop ul for display -->
					        <?php } else { ?>
										<p style="float:left"><?php echo $item["caption"];?></p>
									<?php };?>			
					        <?php
					 		endforeach;?>
					        	<hr style="color:lt-gray;"/>
							</div>
							</div>
						</div>
					<?php }; ?>
			</div>
			<?php if (metadata('item', array('Streaming Video','Video Source')) == 'youtube'){?>

<script>
	 $("#header<?php echo $id_suffix;?>x").click(function (event) {								
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
           $content = $("#hiearchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>");
           //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
           $content.toggle();
        });

        $("#doplay<?php echo $id_suffix;?>").click(function (xevent) {
			xevent.preventDefault();
			if ($("#doplay<?php echo $id_suffix;?>").html()=="Play") {
				$("#doplay<?php echo $id_suffix;?>").html("Pause");
				//videojs("video<?php echo $id_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
				videojs("video<?php echo $id_suffix;?>").play();
			} else {
				$("#doplay<?php echo $id_suffix;?>").html("Play");
				//videojs("video<?php echo $id_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
				videojs("video<?php echo $id_suffix;?>").pause();
			}
		});

  		videojs("video<?php echo $id_suffix;?>").on("timeupdate",checkTime<?php echo $id_suffix;?>);

</script>
<?php };?>