<h3>Public Theme</h3>
<label style="font-weight:bold;" for="kaltura_width_public">Viewer width, in pixels:</label>
<p><?php echo get_view()->formText('kaltura_width_public', 
                              get_option('kaltura_width_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="kaltura_height_public">Viewer height, in pixels:</label>
<p><?php echo get_view()->formText('kaltura_height_public', 
                              get_option('kaltura_height_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="kaltura_uiconf">The UI Conf for Media Space:</label>
<p><?php echo get_view()->formText('kaltura_uiconf', 
                              get_option('kaltura_uiconf'), 
                              array('size' => 15));?></p>
<label style="font-weight:bold;" for="kaltura_partner">The Partner ID for Media Space:</label>
<p><?php echo get_view()->formText('kaltura_partner', 
							   get_option('kaltura_partner'), 
							   array('size' => 15));?></p>
<label style="font-weight:bold;" for="kaltura_display_current">Display information about currently playing video segment</label>
<ul style="list-style-type:none;">
<li>Display Current?&nbsp<?php 
echo get_view()->formCheckbox('kaltura_display_current',null, array('checked' => (get_option('kaltura_display_current'))));?></li>
</ul>
<p class="explanation">Whether the Kaltura plugin should display information about the current video segment. Use this option with or without external controls to see information about the current video segment appear below the video player. This information may be different from the current item being displayed because it is based on where you are in the video file.</p>