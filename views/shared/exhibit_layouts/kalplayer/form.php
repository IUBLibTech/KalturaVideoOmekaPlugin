<?php
$formStem = $block->getFormStem();
$options = $block->getOptions();
static $eid_suffix = 0;

?>
<div class="selected-items">
    <h4><?php echo __('Items'); ?></h4>
    <?php echo $this->exhibitFormAttachments($block); ?>
</div>

<div class="layout-options">
    <div class="block-header">
        <h4><?php echo __('Layout Options'); ?></h4>
        <div class="drawer"></div>
    </div>


    <div class="video-width">
        <?php echo $this->formLabel($formStem . '[options][video-width', __('Video Width')); ?>
        <?php
        echo $this->formSelect($formStem . '[options][video-width]',
            @$options['video-width'], array(),
            array(
                '100' => __('100%'),
	            '90' => __('90%'),
                '85' => __('85%'),
                '80' => __('80%'),
                '75' => __('75%'),
	            '70' => __('70%'),
                '65' => __('65%'),
                '60' => __('60%'),
                '55' => __('55%'),
                '50' => __('50%'),
                '45' => __('45%'),
                '40' => __('40%'),
                '35' => __('35%'),
                '30' => __('30%'),
                '25' => __('25%'),
            ));?>&nbsp;&nbsp;
        <?php echo $this->formLabel($formStem . '[options][video-float]', __('Float')); 
        echo $this->formSelect($formStem . '[options][video-float]',
            @$options['video-float'], array(),
            array(
                'left' => __('Left'),
	            'right' => __('Right'),
            ));
        ?>
	</div>
	<div class = "video-height">
        <?php echo $this->formLabel($formStem . '[options][video-height]', __('Video Height')); 
        echo $this->formSelect($formStem . '[options][video-height]',
            @$options['video-height'], array(),
            array(
                '480' => __('480px'),
				'800' => __('800px'),
	            '750' => __('750px'),
                '700' => __('700px'),
                '650' => __('650px'),
                '600' => __('600px'),
	            '550' => __('550px'),
                '500' => __('500px'),                
                '420' => __('420px'),
                '360' => __('360px'),
                '320' => __('320px'),
                '280' => __('280px'),
                '240' => __('240px'),
                '200' => __('200px'),
                '180' => __('180px'),
            ));?>&nbsp;&nbsp;
    </div>
	<div class = "current-seg">
        <?php echo $this->formLabel($formStem . '[options][current-seg]', __('Show Current Segment?')); 
        echo $this->formSelect($formStem . '[options][current-seg]',
            @$options['current-seg'], array(),
            array(
	            false => __('No'),
                true => __('Yes'),
            ));
        ?>&nbsp;&nbsp;
		<?php echo $this->formLabel($formStem . '[options][show_title]', __('Show Title?')); 
        echo $this->formSelect($formStem . '[options][show_title]',
            @$options['show_title'], array(),
            array(
                true => __('Yes'),
	            false => __('No'),
            ));
        ?>&nbsp;&nbsp;
    </div>

</div>
