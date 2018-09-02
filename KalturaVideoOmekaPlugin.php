<?php

class KalturaVideoPlugin extends Omeka_Plugin_AbstractPlugin
{
	const DEFAULT_VIEWER_WIDTH = 640;
    const DEFAULT_VIEWER_HEIGHT = 480;
    const DEFAULT_VIEWER_CONTROL = 0;
    const DEFAULT_VIEWER_DISPLAY = 0;
    const DEFAULT_VIEWER_AUTOSTART = 0;
	const DEFAULT_VIEWER_UICONF = "";
	const DEFAULT_VIEWER_PARTNER = "";
	
    protected $_hooks = array(
	'public_head',
	'install',
	'uninstall',
	'initialize',
	'config_form',
	'config',
	'public_items_show',
	);

	protected $_options = array(
        'kaltura_width_public' => 640,
        'kaltura_height_public' => 480,
        'kaltura_external_control' => 0,
        'kaltura_display_current' => 0,
        'kaltura_autostart' => 0,
		'kaltura_uiconf' => '',
		'kaltura_partner' => '',
        'kaltura_elements_ids' => '',
    );

    protected $_filters = array(
        'exhibit_layouts',
		'admin_items_form_tabs'
		);

    public function setUp()
    {
        add_shortcode('kalplayer', array('KalturaVideoPlugin', 'kalplayer'));
        parent::setUp();
    }
    
	public function hookInitialize()
	{
		get_view()->addHelperPath(dirname(__FILE__) . '/views/helpers','');
	}
	public function hookInstall()
    {
	    set_option('kaltura_width_public', KalturaVideoPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('kaltura_height_public', KalturaVideoPlugin::DEFAULT_VIEWER_HEIGHT);
        set_option('kaltura_external_control', KalturaVideoPlugin::DEFAULT_VIEWER_CONTROL);
        set_option('kaltura_display_current', KalturaVideoPlugin::DEFAULT_VIEWER_DISPLAY);
        set_option('kaltura_autostart', KalturaVideoPlugin::DEFAULT_VIEWER_AUTOSTART);
		set_option('kaltura_uiconf', KalturaVideoPlugin::DEFAULT_VIEWER_UICONF);
		set_option('kaltura_partner', KalturaVideoPlugin::DEFAULT_VIEWER_PARTNER);

	    $db = get_db();
		//if (!$db->query("Select name from {$db->prefix}plugins where name = 'VideoStream'")) {
		// Don't install if an element set named "Streaming Video" already exists.
	    if (!$db->getTable('ElementSet')->findByName('Kaltura Video')) {
			
			$elementSetMetadata = array(
				'record_type'        => "Item", 
				'name'        => "Kaltura Video", 
				'description' => "Elements needed for streaming video for Kaltura or Youtube."
			);
			$elements = array(
				array(
					'name'           => "Video File ID",
					'description'    => "Actual file ID of the video on Kaltura Media Space"
				), 
				array(
					'name'           => "Video UI Conf",
					'description'    => "Actual ID of the Kaltura Media Space"
				), 
				array(
					'name'           => "Partner ID",
					'description'    => "Partner ID from Kaltura Media Space"
				), 
				array(
					'name'           => "Youtube URL",
					'description'    => "Youtube url for video file to stream."
				), 
				array(
					'name'           => "Segment Start",
					'description'    => "Start point in video in either seconds or hh:mm:ss"
				), 
				array(
					'name'           => "Segment End",
					'description'    => "End point in video in either seconds or hh:mm:ss"
				), 
				array(
					'name'           => "Segment Type",
					'description'    => "Use segment type to help determine how segment is to be displayed. For instance, an event may encompass many scenes, etc."
				), 
				array(
					'name'           => "Show Item",
					'description'    => "Should item be shown in a list. Can be useful in cetain types of displays where you may not want to have all items shown."
				), 
				array(
					'name'           => "Video Source",
					'description'    => "Source of video. Kaltura or Youtube."
				) 
				// etc.
			);
			insert_element_set($elementSetMetadata, $elements);
		}
		
		$elementIds = array();
        $elementsTable = $this->_db->getTable('Element');
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Title');
        $elementIds['Dublin Core:Title'] = $element->id;
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Description');
        $elementIds['Dublin Core:Description'] = $element->id;
        $elements = $elementsTable->findBySet('Kaltura Video');
        foreach ($elements as $element) {
            $elementIds['Kaltura Video:' . $element->name] = $element->id;
        }
        $this->_options['kaltura_elements_ids'] = json_encode($elementIds);
        $this->_installOptions();

    }
	
	public function hookUninstall()
	{
		delete_option('kaltura_width_public');
        delete_option('kaltura_height_public');
        delete_option('kaltura_external_control');
        delete_option('kaltura_display_current');
        delete_option('kaltura_autostart');
		delete_option('kaltura_uiconf');
		delete_option('kaltura_partner');
        delete_option('kaltura_elements_ids');

		$db=get_db();
       	//if (!$db->query("Select name from {$db->prefix}plugins where name = 'VideoStream'")) {
			if ($elementSet = $db->getTable('ElementSet')->findByName("Kaltura Video")) {
            	$elementSet->delete();
        	}
		//}
	}
/**
* Appends a warning message to the uninstall confirmation page.
*/
    public static function admin_append_to_plugin_uninstall_message()
    {
        echo '<p><strong>Warning</strong>: This will permanently delete the Streaming Video element set and all its associated metadata. You may deactivate this plugin if you do not want to lose data.</p>';
    }	

    public function hookConfigForm()
    {
        include 'config_form.php';
    }

    public function hookConfig()
    {
        if (!is_numeric($_POST['kaltura_width_public']) ||
        !is_numeric($_POST['kaltura_height_public'])) {
            throw new Omeka_Validator_Exception('The width and height must be numeric.');
        }
	    set_option('kaltura_width_public', $_POST['kaltura_width_public']);
        set_option('kaltura_height_public', $_POST['kaltura_height_public']);
        set_option('kaltura_external_control', $_POST['kaltura_external_control']);
        set_option('kaltura_display_current', $_POST['kaltura_display_current']);
        set_option('kaltura_autostart', $_POST['kaltura_autostart']);
		set_option('kaltura_uiconf', $_POST['kaltura_uiconf']);
		set_option('kaltura_partner', $_POST['kaltura_partner']);
    }
	
    public function hookPublicHead($args)
    {
        echo queue_css_file("res/evia");
        echo queue_css_file("res/rbox");
        echo queue_css_file("res/mktree");
        echo queue_css_file("res/displaysegment");
        echo queue_css_file("res/tabber");       
        echo queue_css_file("vidStyle");
        echo queue_css_file("video-js"); 
        echo queue_css_file("jquery-ui");
        echo queue_css_file("bootstrap");
		echo js_tag('video');
		echo js_tag('youtube');
        echo js_tag('pfUtils');
        echo js_tag('jquery');
        echo js_tag('jquery-ui');
        echo js_tag('bootstrap');
    }

    public function hookExhibitBuilderPageHead($args)
    {
        echo queue_css_file("res/evia.css");
        echo queue_css_file("res/rbox.css");
        echo queue_css_file("res/mktree.css");
        echo queue_css_file("res/displaysegment.css");
        echo queue_css_file("res/tabber.css");
        echo queue_css_file("video-js"); 
        echo queue_css_file("jquery-ui");
		echo js_tag('video');
		echo js_tag('youtube');
        echo js_tag('pfUtils');
        echo js_tag('jquery');
        echo js_tag('jquery-ui');
    }

    public function filterExhibitLayouts($layouts)
    {
        $layouts['kalplayer'] = array(
            'name' => __('Kaltura Player'),
            'description' => __('Select ids for items with video to display in the player')
        );
        return $layouts;
    }

    public function hookPublicItemsShow($args)
    {	
	   if (!is_null(metadata($args['item'], array('Kaltura Video', 'Segment Start')))) {
        	$this->append($args);
		}
    }
	
    public function append($args)
	{
		 echo $args['view']->shortcodes('[kalplayer ids='.metadata('item','id').' ext='.get_option('kaltura_external_control').' current='.get_option('kaltura_display_current').']');
//		$args['current'] = get_option('kaltura_display_current');
//		$args['height'] = get_option('kaltura_height_public');
//		$args['width'] = get_option('kaltura_width_public');
//		$args['ids'] = metadata($args['item'],'id');
//
//		return self::kalplayer($args, $args['view']);
	}		

    /**
     * Build HTML for the carousel
     * @param array $args
     * @param Zend_View $view
     * @return string HTML to display
     */
    public static function kalplayer($args, $view)
   {
       static $id_suffix = 0;
        if (isset($args['float'])) {
            $params['float'] = $args['float'];
        }else{
			$params['float'] = 'left';
		}

        if (isset($args['width'])) {
            $params['width'] = $args['width'];
        }
		
        if (isset($args['height'])) {
            $params['height'] = $args['height'];
		}
		
        if (isset($args['ids'])) {
            $params['range'] = $args['ids'];
        }

        if (isset($args['ext'])) {
            $params['ext'] = $args['ext'];
        }

        if (isset($args['current'])) {
            $params['current'] = $args['current'];
        }

        if (isset($args['show_title'])) {
            $params['show_title'] = $args['show_title'];
        }

		$result = preg_replace_callback('/(\d+)-(\d+)/', function($m) {
		    return implode(',', range($m[1], $m[2]));
		}, $args['ids']);
		$ids = explode(',',$result);
		foreach ($ids as $key => $item){
			if (get_record_by_id('item',$item)):
        		$items[$key] = get_record_by_id('item', $item);
			endif;
		};

        $html = $view->partial('kalplayer.php', array('items' => $items, 'id_suffix' => $id_suffix, 'params' => $params));
        $id_suffix++;
        return $html;
    }

	public function filterAdminItemsFormTabs($tabs, $args)
    {
		$item = $args['item'];

       	return $tabs;
	}
}
