<?php
/*
	Section: iCalloutFlat
	Author: Enrique Chavez
	Author URI: http://enriquechavez.co
	Description: A quick call to action for your users fot Flatten
	Class Name: tmiCalloutFlat
	Edition: pro
	Filter: component, full-width
	Loading: active
*/

class tmiCalloutFlat extends PageLinesSection {

	function section_opts(){
		$opts = array(
			array(
				'type'  => 'multi',
				'title' => __( 'Callout Text', 'pagelines' ),
				'opts'  => array(
					array(
						'key'     => 'icallout_flat_text',
						'version' => 'pro',
						'type'    => 'text',
						'label'   => __( 'Callout Text', 'pagelines' ),
					),
					array(
						'key'     => 'icallout_flat_bg_color',
						'version' => 'pro',
						'type'    => 'color',
						'default' => '#0079FF',
						'label'   => __( 'Callout Background Color', 'pagelines' ),
					),
					array(
						'key'     => 'icallout_flat_text_color',
						'version' => 'pro',
						'type'    => 'color',
						'default' => '#ffffff',
						'label'   => __( 'Callout Text Color', 'pagelines' ),
					),

				)
			),
			array(
				'type'  => 'multi',
				'title' => 'Link/Button',
				'opts'  => array(

					 array(
						'key'   => 'icallout_flat_link',
						'type'  => 'text',
						'label' => __( 'URL', 'pagelines' )
					),
					array(
						'key'   => 'icallout_flat_link_text',
						'type'  => 'text',
						'label' => __( 'Text on Button', 'pagelines' )
					),
					array(
						'key'   => 'icallout_flat_btn_theme',
						'type'  => 'select_button',
						'label' => __( 'Button Color', 'pagelines' ),
					),

				)
			)

		);

		return $opts;

	}

	function section_template() {

		$text      = $this->opt('icallout_flat_text');
		$format    = ( $this->opt('icallout_flat_format') ) ? 'format-'.$this->opt('icallout_flat_format') : 'format-inline';
		$link      = $this->opt('icallout_flat_link');
		$theme     = ($this->opt('icallout_flat_btn_theme')) ? $this->opt('icallout_flat_btn_theme') : 'btn-primary';
		$link_text = ( $this->opt('icallout_flat_link_text') ) ? $this->opt('icallout_flat_link_text') : 'Learn More <i class="icon icon-angle-right"></i>';
		$bg        = ( $this->opt('icallout_flat_bg_color') ) ? pl_hashify($this->opt('icallout_flat_bg_color')) : '#0079FF';
		$color     = ( $this->opt('icallout_flat_text_color') ) ? pl_hashify($this->opt('icallout_flat_text_color')) : '#ffffff';
		if(!$text && !$link){
			$text = __("Call to action!", 'pagelines');
		}

		?>
		<div class="flat-wrapper" style="background: <?php echo $bg ?>;">
			<div class="pl-content">
				<div class="icallout_flat-container <?php echo $format;?> row">
					<div class="span9 zmb zmt" style="color:<?php echo $color ?>;">
						<h2 class="icallout_flat-head" data-sync="icallout_flat_text"><?php echo $text; ?></h2>
					</div>
					<div class="span3 zmb zmt">
						<a class="icallout_flat-action btn <?php echo $theme;?> btn-large" href="<?php echo $link; ?>"  data-sync="icallout_flat_link_text"><?php echo $link_text; ?></a>
					</div>
				</div>
			</div>
		</div>
	<?php

	}
}