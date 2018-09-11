# KalturaVideo Omeka Plugin
<h4>Installation</h4>
<p>Download the zip file from the most recent release. After unzipping the file, make sure the directory is named <b>KalturaVideo</b> and then transfer this directory to your plugins directory in your Omeka installation.</p>
<h4>Documentation</h4>
<p>KalturaVideo is an Omeka plugin that allows you to stream video from either Kaltura Mediaspace or Youtube. In an item in Omeka, this video stream can have start and end points set in it allowing you to divide the stream into segments. Since each item in Omeka can contain a video segment, you can describe that segment using all the metadata fields available in Omeka for an item such as Description, Creator, Date, etc. </p>
<p>When you install KalturaVideo, it adds a tab, Kaltura Video, to the Edit Item screen. This tab has fields for</p>
<pre><code>Video File ID - Actual file ID from Kaltura Mediaspace
Video UI Conf - Actual ID for the Kaltura Mediaspace instance
Partner ID - The ID for the organization as set up for Kaltura Mediaspace</code></pre>
<p>These fields can all be seen in Kaltura when you go to a video. 
First, select the Share tab and then select the Embed tab. 
In the iframe code that appears, in the "src=" you can see, 
at the end of the line, the Video UI Conf value after ui_conf_id 
and the Partner_ID after partner_id. On the next line there is 
a value for entry_id which contains the value you need for 
Video File ID. </p>
<pre><code>Youtube URL - the embed URL from Youtube</code></pre>
<p>If you use the KalturaVideo fields, you don't need a value for Youtube URL and if you use the Youtube URL you don't need values in the KalturaVideo fields.</p>
<pre><code>Segment Start - the start point in the video stream either 
in hh:mm:ss format or as number of seconds from the beginning of the file.
Segment End - the end point in the video stream either in hh:mm:ss format 
or as number of seconds from the beginning of the file. 
Must be greater than Segment Start value.
Segment Type - Use segment type to help determine how segment 
is to be displayed in a hierarchy. For instance, an event may 
encompass many scenes, a scene may encompass many actions. Currently, 
the plugin only support 3 levels of hierarchy, event->scene->action.
Show Item - Should item be shown in a list. Can be useful in cetain 
types of displays where you may not want to have all items shown. Values are TRUE or FALSE.
Video Source - Source of video. Values are Kaltura or Youtube.</code></pre>
<p>Kaltura Video plugin is automatically fired whenever it encounters the "public_items_show" hook. In most themes this hook is in the show.php file for the item and viewing an item will automatically embed a Kaltura Video player if the correct values are set in the fields above. In addition, there is a kalplayer shortcode that takes several parameters and allows embedding of the player in Simple Pages. The shortcode would be embedded like this:</p>
<pre><code>
[kalplayer ids=302
float="left"
width="100%"
height="100%"
current="true"]
</code></pre>
<p>The parameter "current" will show the metadata for the item next to the video player if set to true. Does not show anything if set to false.
<p>In addition there is a Kaltura Player block layout that allows you to create a Kaltura video player block on an exhibit page. Under "Layout Options" for this block you can control the parameters for "float", "width", "show current" and "show description".
