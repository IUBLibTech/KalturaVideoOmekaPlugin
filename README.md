# KalturaVideo
<p>KalturaVideo is an Omeka plugin that allows you to stream video from either Kaltura Mediaspace or Youtube. In an item in Omeka, this video stream can have start and end points set in it allowing you to divide the stream into segments. Since each item in Omeka can contain a video segment, you can describe that segment using all the metadata fields available in Omeka for an item such as Description, Creator, Date, etc. </p>
<p>When you install KalturaVideo, it adds a tab, Kaltura Video, to the Edit Item screen. This tab has fields for</p>
<li>Video File ID - Actual file ID from Kaltura Mediaspace</li>
<li>Video UI Conf - Actual ID for the Kaltura Mediaspace instance</li>
<li>Partner ID - The ID for the organization as set up for Kaltura Mediaspace</li>
<p>These fields can all be seen in Kaltura when you go to a video. First, select the Share tab and then select the Embed tab. In the iframe code that appears, in the "src=" you can see, at the end of the line, the Video UI Conf value after ui_conf_id and the Partner_ID after partner_id. On the next line is there is a value for entry_id which contains the value you need for Video File ID. </p>
<li>Youtube URL - the embed URL from Youtube</li>
<p>If you use the KalturaVideo fields, you don't need a value for Youtube URL and if you use the Youtube URL you don't need values in the KalturaVideo fields.</p>
<li>Segment Start - the start point in the video stream either in hh:mm:ss format or as number of seconds from the beginning of the file.</li>
<li>Segment End - the end point in the video stream either in hh:mm:ss format or as number of seconds from the beginning of the file. Must be greater than Segment Start value.</li>
