<?php
/*
Section: Revolution Slider
Author: Enrique Chavez
Author URI: http://tmeister.net
Version: 1.0
Description: Port to the Great Revolution Slider jQuery Plugin for PageLines DMS, Add and animate anything you want: captions, images, videos.
Class Name: TMSORevolution
Filter: full-width, slider

*/


class TMSORevolution extends PageLinesSection
{


	var $domain               = 'tmRevolution';
	/**************************************************************************
	* SLIDES
	**************************************************************************/
	var $tax_id               = 'tm_so_tax';
	var $custom_post_type     = 'tm_so_slider';
	/**************************************************************************
	* CAPTIONS
	**************************************************************************/
	var $tax_cap_id           = 'tm_so_cap_tax';
	var $custom_cap_post_type = 'tm_so_caption';

	var $slides = null;

	function section_persistent()
	{
		$this->post_type_slider_setup();
		$this->post_type_caption_setup();
		$this->post_meta_setup();
	}
	function section_styles(){
		wp_enqueue_script( 'common-plugins', $this->base_url . '/js/jquery.plugins.min.js', array( 'jquery' ), '1.0',true );
		wp_enqueue_script( 'trslider', $this->base_url . '/js/jquery.revolution.min.js', array( 'common-plugins' ), '1.0', true );
	}

	function section_head(){
		if( !is_front_page() && !pl_draft_mode() ){
 			return;
 		}
 		$clone_id = null;
		global $post, $pagelines_ID;
		$oset            = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$tmrv_width      = ( $this->opt('tmrv_width', $oset) ) ? $this->opt('tmrv_width', $oset) : '900';
		$tmrv_height     = ( $this->opt('tmrv_height', $oset) ) ? $this->opt('tmrv_height', $oset) : '350';
		$tmrv_shadow     = ( $this->opt('tmrv_shadow', $oset) == 'on' ) ? '0' : '1';
		$tmrv_touch      = ( $this->opt('tmrv_touch', $oset) == 'on' ) ? 'off' : 'on';
		$tmrv_pause_over = ( $this->opt('tmrv_pause_over', $oset) == 'on' ) ? 'off' : 'on';
		$tmrv_items      = ( $this->opt('tmrv_items', $oset) ) ? $this->opt('tmrv_items', $oset) : '10';
		$tmrv_set        = ( $this->opt('tmrv_set', $oset) ) ? $this->opt('tmrv_set', $oset) : '';
		$tmrv_time       = ( $this->opt('tmrv_time', $oset) ) ? $this->opt('tmrv_time', $oset) : '8000';
		$this->slides    = $this->get_posts($this->custom_post_type, $this->tax_id, $tmrv_set, $tmrv_items);

		if( !count( $this->slides ) ){
			return;
		}

	?>
		<script type="text/javascript">
      		jQuery(document).ready(function() {
      			if (jQuery.fn.cssOriginal!=undefined)
					jQuery.fn.css = jQuery.fn.cssOriginal;

	            jQuery('.banner').revolution(
				{
					delay: <?php echo $tmrv_time; ?>,
					startheight: <?php echo $tmrv_height ?>,
					startwidth: <?php echo $tmrv_width ?>,
					navigationType:"bullet",
					navigationStyle:'navbar',
					navigationArrows:'verticalcentered',
					touchenabled: '<?php echo $tmrv_touch ?>',
					onHoverStop: '<?php echo $tmrv_pause_over ?>',
					shadow: '<?php echo $tmrv_shadow ?>',
					fullWidth: 'off'
                });
           });
		</script>
	<?php
	}

 	function section_template( $clone_id = null ) {
		global $post, $pagelines_ID;
		$oset         = array('post_id' => $pagelines_ID, 'clone_id' => $clone_id);
		$tmrv_items   = ( $this->opt('tmrv_items', $oset) ) ? $this->opt('tmrv_items', $oset) : '10';
		$tmrv_set     = ( $this->opt('tmrv_set', $oset) ) ? $this->opt('tmrv_set', $oset) : '';

		$slides = ( $this->slides == null ) ? $this->get_posts($this->custom_post_type, $this->tax_id, $tmrv_set, $tmrv_items) : $this->slides;
		$current_page_post = $post;

		if( !count($slides) ){
			echo setup_section_notify($this, __('Sorry,there are no slides to display.', 'flatten'), get_admin_url().'edit.php?post_type='.$this->custom_post_type, __('Please create some slides', 'flatten'));
			return;
		}
 	?>
		<div class="fullwidthbanner-container">
			<div class="banner">
				<ul>
					<?php
						foreach ($slides as $post):
							$io          = array('post_id' => $post->ID);
							$transition  = ( plmeta('tmrv_transition', $io ) )  ? plmeta('tmrv_transition', $io) : 'boxfade';
							$slots       = ( plmeta('tmrv_slots', $io ) )  ? plmeta('tmrv_slots', $io) : '1';
							$use_image   = (plmeta('tmrv_transparent', $io) == 'off') ? true : false;
							$image       = ( plmeta('tmrv_background_slider', $io) ) ? plmeta('tmrv_background_slider', $io) : false;
							$img_src     = ( $image || ($use_image && $image ) ) ? plmeta('tmrv_background_slider', $io) : '/wp-content/themes/flatten/images/transparent.png';
							$masterspeed = ( plmeta('tmrv_masterspeed', $io ) )  ? plmeta('tmrv_masterspeed', $io) : '300';
							$link        = (plmeta('tmrv_link', $io)) ? 'data-link="' . plmeta('tmrv_link', $io). '"' : '';
							$link_target = (plmeta('tmrv_link_target', $io)) ? 'data-target="'. plmeta('tmrv_link_target', $io) . '"' : '';
							/**************************************************
							* CAPTIONS
							**************************************************/
							$caption_set = strlen( trim( plmeta('tmrv_caption_set', $io)) ) ? plmeta('tmrv_caption_set', $io) : 'null';
							$captions = $this->get_posts($this->custom_cap_post_type, $this->tax_cap_id, $caption_set);
					?>
						<li data-transition="<?php echo $transition ?>" data-slotamount="<?php echo $slots ?>" data-masterspeed="<?php echo $masterspeed ?>" <?php echo $link ?> <?php echo $link_target ?>>
							<img src="<?php echo $img_src ?>">
							<?php if ( count( $captions ) ): ?>
								<?php $current_inner_page_post = $post; ?>
								<?php foreach ( $captions as $post ):
									$ioc               = array('post_id' => $post->ID);
									//Types
									$tmrv_caption_type = ( plmeta('tmrv_caption_type', $ioc) ) ? plmeta('tmrv_caption_type', $ioc) : 'text';
									$tmrv_text         = ( plmeta('tmrv_text', $ioc) ) ? plmeta('tmrv_text', $ioc) : '';
									$tmrv_image        = ( plmeta('tmrv_image', $ioc) ) ? plmeta('tmrv_image', $ioc) : '';
									// Styles
									$tmrv_c_style      = ( plmeta('tmrv_c_style', $ioc) ) ? plmeta('tmrv_c_style', $ioc) : 'big_white';
									$tmrv_video        = ( plmeta('tmrv_video', $ioc) ) ? plmeta('tmrv_video', $ioc) : '';
									$tmrv_i_animation  = ( plmeta('tmrv_incomming_animation', $ioc) ) ? plmeta('tmrv_incomming_animation', $ioc) : 'sft';
									$tmrv_o_animation  = ( plmeta('tmrv_outgoing_animation', $ioc) ) ? plmeta('tmrv_outgoing_animation', $ioc) : 'stt';
									// Datas
									$tmrv_start_x      = ( plmeta('tmrv_start_x', $ioc) ) ? plmeta('tmrv_start_x', $ioc) : '0';
									$tmrv_start_y      = ( plmeta('tmrv_start_y', $ioc) ) ? plmeta('tmrv_start_y', $ioc) : '0';
									$tmrv_speed_intro  = ( plmeta('tmrv_speed_intro', $ioc) ) ? plmeta('tmrv_speed_intro', $ioc) : '300';
									$tmrv_speed_end    = ( plmeta('tmrv_speed_end', $ioc) ) ? plmeta('tmrv_speed_end', $ioc) : '300';
									$tmrv_start_after  = ( plmeta('tmrv_start_after', $ioc) ) ? plmeta('tmrv_start_after', $ioc) : '0';
									$tmrv_easing_intro = ( plmeta('tmrv_easing_intro', $ioc) ) ? plmeta('tmrv_easing_intro', $ioc) : 'linear';
									$tmrv_easing_out   = ( plmeta('tmrv_easing_out', $ioc) ) ? plmeta('tmrv_easing_out', $ioc) : 'linear';
								?>
									<div
										class="caption <?php echo $tmrv_i_animation; ?> <?php echo $tmrv_o_animation ?> <?php echo $tmrv_c_style ?>"
										data-x="<?php echo $tmrv_start_x ?>"
										data-y="<?php echo $tmrv_start_y ?>"
										data-speed="<?php echo $tmrv_speed_intro ?>"
										data-start="<?php echo $tmrv_start_after ?>"
										data-easing="<?php echo $tmrv_easing_intro ?>"
										data-endspeed="<?php echo $tmrv_speed_end ?>"
										data-endeasing="<?php echo $tmrv_easing_out?>"
									>
										<?php switch ($tmrv_caption_type) {
											case 'text':
												echo apply_filters( 'the_content', $tmrv_text );
												break;
											case 'image':
												echo "<img src='".$tmrv_image."' />";
												break;
											case 'video':
												echo $tmrv_video;
												break;
										} ?>
									</div>
								<?php endforeach; $post = $current_inner_page_post; ?>
							<?php endif ?>
						</li>
					<?php endforeach; $post = $current_page_post; ?>
				</ul>
				<div class="tp-bannertimer"></div>
			</div>
		</div>
 	<?php
	}

	function before_section_template( $clone_id = null ){}

	function after_section_template( $clone_id = null ){}

	function post_meta_setup()
	{
		/**********************************************************************
		* Slider meta options
		**********************************************************************/
		$pt_tab_options = array(

			'tmrv_background_slider' => array(
				'type'       => 'image_upload',
				'inputlabel' => __('Slide Background', 'flatten'),
				'title'      => __('Slide Background', 'flatten'),
				'shortexp'   => __('Background Image.', 'flatten'),
				'exp'        => __('Please select a image to use as a slide background.', 'flatten')
			),
			'tmrv_transparent' => array(
				'type'         => 'select',
				'inputlabel'   => __('', 'flatten'),
				'title'        => __('Transparent Backgound', 'flatten'),
				'shortexp'     => __('Do not use a background image', 'flatten'),
				'exp'          => __('With this option youcan choose if you don\'t want to use a background in the slide. If a image is upload this setting is override and will use the image as a background', 'flatten'),
				'selectvalues' => array(
					'off' => array('name' => __('Use the image provided', 'flatten')),
					'on'  => array('name' => __('Do not use a background', 'flatten'))
				)
			),
			'tmrv_transition' => array(
				'type' => 'select',
				'inputlabel' => __('Select the slide transition effect', 'flatten'),
				'title' => __('Slide transition effect', 'flatten'),
				'shortexp' => __('Transition effect', 'flatten'),
				'exp' => __('Every slide can have a different transition you can choose it in this option.', 'flatten'),
				'selectvalues' => array(
					'boxslide'             => array('name' => __('Box Slide', 'flatten')),
					'boxfade'              => array('name' => __('Box Fade', 'flatten')),
					'slotzoom-horizontal'  => array('name' => __('Slot Zoom Horizontal', 'flatten')),
					'slotslide-horizontal' => array('name' => __('Slot Slide Horizontal', 'flatten')),
					'slotfade-horizontal'  => array('name' => __('Slot Fade Horizontal', 'flatten')),
					'slotzoom-vertical'    => array('name' => __('Slot Zoom Vertical', 'flatten')),
					'slotslide-vertical'   => array('name' => __('Slot Slide Vertical', 'flatten')),
					'slotfade-vertical'    => array('name' => __('Slot Fade Vertical', 'flatten')),
					'curtain-1'            => array('name' => __('Curtain 1', 'flatten')),
					'curtain-2'            => array('name' => __('Curtain 2', 'flatten')),
					'curtain-3'            => array('name' => __('Curtain 3', 'flatten')),
					'slideleft'            => array('name' => __('Slide Left', 'flatten')),
					'slideright'           => array('name' => __('Slide Right', 'flatten')),
					'slideup'              => array('name' => __('Slide Up', 'flatten')),
					'slidedown'            => array('name' => __('Slide Down', 'flatten')),
					'fade'                 => array('name' => __('Fade', 'flatten')),
					'random'               => array('name' => __('Random', 'flatten')),
					'slidehorizontal'      => array('name' => __('Slide Horizontal', 'flatten')),
					'slidevertical'        => array('name' => __('Slide Vertical', 'flatten')),
					'papercut'             => array('name' => __('Papercut', 'flatten')),
					'flyin'                => array('name' => __('Flyin', 'flatten')),
					'turnoff'              => array('name' => __('Turnoff', 'flatten')),
					'cube'                 => array('name' => __('Cube', 'flatten')),
					'3dcurtain-vertical'   => array('name' => __('3d Curtain Vertical', 'flatten')),
					'3dcurtain-horizontal' => array('name' => __('3d Curtain Horizontal', 'flatten')),
				)
			),
			'tmrv_masterspeed' => array(
				'type'         => 'select',
				'inputlabel'   => __('Time', 'flatten'),
				'title'        => __('Slide Transition Duration', 'flatten') ,
				'shortexp'     => __('Default: 300', 'flatten') ,
				'exp'          => __('Transition speed.', 'flatten'),
				'selectvalues' => $this->getMasterSpeedOptions()
			),
			'tmrv_slots' => array(
				'type'         => 'count_select',
				'inputlabel'   => __('Slot Amount', 'flatten'),
				'title'        => __('Slot Amount', 'flatten'),
				'shortexp'     => __('How many slot use in the slide', 'flatten'),
				'exp'          => __('The number of slots or boxes the slide is divided into. If you use Box Fade, over 7 slots can be juggy', 'flatten'),
				'count_start'  => 1,
				'count_number' => 20
			),
			'tmrv_caption_set' 	=> array(
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> $this->tax_cap_id,
				'title' 		=> __('Caption Set', 'flatten'),
				'shortexp'		=> __('Select which <strong>caption set</strong> you want to show over the image.', 'flatten'),
				'inputlabel'	=> __('Caption Set', 'flatten'),
				'exp' 			=> __('Each slide can have several captions on it, choose a caption set to show on this slide.', 'flatten')
			),
			/*'tmrv_link' => array(
				'type'       => 'text',
				'inputlabel' => __('Slide link', 'flatten'),
				'title'      => __('Slide link', 'flatten'),
				'shortexp'   => __('Optional link for the slide', 'flatten'),
				'exp'        => __('A link on the whole slide pic', 'flatten')
			),
			'tmrv_link_target' => array(
				'type'         => 'select',
				'inputlabel'   => __('Slide link target', 'flatten'),
				'title'        => __('Slide link target', 'flatten'),
				'shortexp'     => __('Default: _self', 'flatten'),
				'exp'          => __('Link Target', 'flatten'),
				'selectvalues' => array(
					'_blank' => array('name' => '_blank'),
					'_self'  => array('name' => '_self')
				)
			),*/
		);

		$pt_panel = array(
			'id' 		=> $this->id . '-metapanel',
			'name' 		=> __('Slider Options', 'flatten'),
			'posttype' 	=> array( $this->custom_post_type ),
		);
		$pt_panel =  new PageLinesMetaPanel( $pt_panel );
		$pt_tab = array(
			'id' 		=> $this->id . '-metatab',
			'name' 		=> "Slider Options",
			'icon' 		=> $this->icon,
		);
		$pt_panel->register_tab( $pt_tab, $pt_tab_options );

		/**********************************************************************
		* Captions meta options
		**********************************************************************/
		$pt_tab_options_captions = array(

			'tmrv_caption_type' => array(
				'type'         => 'select',
				'inputlabel'   => __('Caption type', 'flatten'),
				'title'        => __('Caption type', 'flatten'),
				'shortexp'     => __('What kind of caption will be?, Default: "Text"', 'flatten'),
				'exp'          => __('The "Caption" can be one of three types (Text, Image or Video) please, choose what type of caption you will use, be aware, if you choose "Caption text" only the text\'s field value will be use, if you choose "Caption image" only the image\'s field value will be use and so on.', 'flatten'),
				'selectvalues' => array(
					'text'  => array('name' => __('Text', 'flatten')),
					'image' => array('name' => __('Image', 'flatten')),
					'video' => array('name' => __('Video', 'flatten')),
				)
			),
			'tmrv_text' => array(
				'type'       => 'text',
				'inputlabel' => __('Caption Text', 'flatten'),
				'title'      => __('Caption Text', 'flatten'),
				'shortexp'   => __('The caption text value', 'flatten'),
				'exp'        => __('If you chose "Text" in the "Caption type" option, the value on this field will be use, regardless of the value of the image or video fields.', 'flatten')
			),
			'tmrv_image' => array(
				'type'       => 'image_upload',
				'inputlabel' => __('Caption Image', 'flatten'),
				'title'      => __('Caption Image', 'flatten'),
				'shortexp'   => __('The caption image value', 'flatten'),
				'exp'        => __('If you chose "Image" in the "Caption type" option, the value on this field will be use, regardless of the value of the text or video fields.', 'flatten')
 			),
 			'tmrv_video' => array(
 				'type'       => 'textarea',
				'inputlabel' => __('Caption Video', 'flatten'),
				'title'      => __('Caption Video', 'flatten'),
				'shortexp'   => __('The caption video value', 'flatten'),
				'exp'        => __('If you chose "Video" in the "Caption type" option, the value on this field will be use, regardless of the value of the text or image fields.', 'flatten')
 			),
			'tmrv_incomming_animation' => array(
				'type'         => 'select',
				'inputlabel'   => __('Incoming Animation', 'flatten'),
				'title'        => __('Incoming Animation', 'flatten'),
				'shortexp'     => __('Select the incoming animation for the caption.', 'flatten'),
				'exp'          => __('You can set a incoming animation for each of the caption.','flatten'),
				'selectvalues' => array(
					'sft'          => array('name' => __('Short from Top', 'flatten') ),
					'sfb'          => array('name' => __('Short from Bottom', 'flatten') ),
					'sfr'          => array('name' => __('Short from Right', 'flatten') ),
					'sfl'          => array('name' => __('Short from Left', 'flatten') ),
					'lft'          => array('name' => __('Long from Top', 'flatten') ),
					'lfb'          => array('name' => __('Long from Bottom', 'flatten') ),
					'lfr'          => array('name' => __('Long from Right', 'flatten') ),
					'lfl'          => array('name' => __('Long from Left', 'flatten') ),
					'fade'         => array('name' => __('Fading', 'flatten') ),
					'randomrotate' => array('name' => __('Fade in, Rotate from a Random position and Degree', 'flatten') )
				)
			),
			'tmrv_outgoing_animation' => array(
				'type'         => 'select',
				'inputlabel'   => __('Outgoing Animation', 'flatten'),
				'title'        => __('Outgoing Animation', 'flatten'),
				'shortexp'     => __('Select the outgoing animation for the caption.', 'flatten'),
				'exp'          => __('You can set a outgoing animation for each of the caption.','flatten'),
				'selectvalues' => array(
					'stt'             => array('name' => __('Short to Top', 'flatten')),
					'stb'             => array('name' => __('Short to Bottom', 'flatten')),
					'str'             => array('name' => __('Short to Right', 'flatten')),
					'stl'             => array('name' => __('Short to Left', 'flatten')),
					'ltt'             => array('name' => __('Long to Top', 'flatten')),
					'ltb'             => array('name' => __('Long to Bottom', 'flatten')),
					'ltr'             => array('name' => __('Long to Right', 'flatten')),
					'ltl'             => array('name' => __('Long to Left', 'flatten')),
					'fadeout'         => array('name' => __('Fading', 'flatten')),
					'randomrotateout' => array('name' => __('Fade in, Rotate from a Random position and Degree', 'flatten'))
				)
			),
			'tmrv_start_x' => array(
				'type'       => 'text',
				'inputlabel' => __('Horizontal Position', 'flatten'),
				'title'      => __('Horizontal Position', 'flatten'),
				'shortexp'   => __('The initial horizontal position for the caption.', 'flatten'),
				'exp'        => __('The horizontal position based on the slider size, in the resposive view this position will be calculated.', 'flatten')
			),
			'tmrv_start_y' => array(
				'type'       => 'text',
				'inputlabel' => __('Vertical Position', 'flatten'),
				'title'      => __('Vertical Position', 'flatten'),
				'shortexp'   => __('The initial vertical position for the caption.', 'flatten'),
				'exp'        => __('The vertical position based on the slider size, in the resposive view this position will be calculated.', 'flatten')
			),
			'tmrv_c_style' => array(
				'type'         => 'select',
				'inputlabel'   => __('Caption style', 'flatten'),
				'title'        => __('Caption style', 'flatten'),
				'shortexp'     => __('Select the caption style', 'flatten'),
				'exp'          => __('This option will be used only for text captions.', 'flatten'),
				'selectvalues' => array(
					'big_base'        => array('name' => __('Big Base Color', 'flatten')),
					'big_link'        => array('name' => __('Big Link Color', 'flatten')),
					'gray_box_right'  => array('name' => __('Gray Box Color Right Align', 'flatten')),
					'gray_box'        => array('name' => __('Gray Box Color Left Align', 'flatten')),
					'big_white'       => array('name' => __('Big White', 'flatten')),
					'big_orange'      => array('name' => __('Big Orange', 'flatten')),
					'big_black'       => array('name' => __('Big Black', 'flatten')),
					'medium_white'    => array('name' => __('Medium Grey', 'flatten')),
					'medium_text'     => array('name' => __('Medium White', 'flatten')),
					'small_white'     => array('name' => __('Small White', 'flatten')),
					'large_text'      => array('name' => __('Large White', 'flatten')),
					'very_large_text' => array('name' => __('Very Large White', 'flatten')),
					'very_big_white'  => array('name' => __('Very Big White', 'flatten')),
					'very_big_black'  => array('name' => __('Very Big Black', 'flatten')),
				)
			),
			'tmrv_speed_intro' => array(
				'type'       => 'text',
				'inputlabel' => __('Animation duration intro', 'flatten'),
				'title'      => __('Animation duration intro', 'flatten'),
				'shortexp'   => __('Duration of the animation in milliseconds', 'flatten'),
				'exp'        => __('Take note that 1 second is equal to 1000 milliseconds.', 'flatten')
			),
			'tmrv_speed_end' => array(
				'type'       => 'text',
				'inputlabel' => __('Animation duration out', 'flatten'),
				'title'      => __('Animation duration out', 'flatten'),
				'shortexp'   => __('Duration of the out animation in milliseconds', 'flatten'),
				'exp'        => __('Take note that 1 second is equal to 1000 milliseconds.', 'flatten')
			),
			'tmrv_start_after' => array(
				'type'       => 'text',
				'inputlabel' => __('Time to wait', 'flatten'),
				'title'      => __('Time to wait to show this caption', 'flatten'),
				'shortexp'   => __('How many time should this caption start to show in milliseconds', 'flatten'),
				'exp'        => __('Take note that 1 second is equal to 1000 milliseconds.', 'flatten')
			),
			'tmrv_easing_intro' => array(
				'type'         => 'select',
				'inputlabel'   => __('Easing intro effect', 'flatten'),
				'title'        => __('Easing intro effect', 'flatten'),
				'shortexp'     => __('Easing effect of the intro animation', 'flatten'),
				'exp'          => __('You can set a different easing effect for each caption, default is linear', 'flatten'),
				'selectvalues' => $this->getEasing()
			),
			'tmrv_easing_out' => array(
				'type'         => 'select',
				'inputlabel'   => __('Easing out effect', 'flatten'),
				'title'        => __('Easing out effect', 'flatten'),
				'shortexp'     => __('Easing effect of the out animation', 'flatten'),
				'exp'          => __('You can set a different easing effect for each caption, default is linear', 'flatten'),
				'selectvalues' => $this->getEasing()
			),
		);
		$pt_panel_cap = array(
			'id' 		=> $this->id . 'cap-metapanel',
			'name' 		=> __('Revolution Caption Options', 'flatten'),
			'posttype' 	=> array( $this->custom_cap_post_type ),
		);
		$pt_panel_cap =  new PageLinesMetaPanel( $pt_panel_cap );
		$pt_tab_cap = array(
			'id' 		=> $this->id . 'cap-metatab',
			'name' 		=> "Caption Options",
			'icon' 		=> $this->icon,
		);
		$pt_panel_cap->register_tab( $pt_tab_cap, $pt_tab_options_captions );

	}

	function section_opts(){
		$opts = array(
			array(
				'key' => 'tmrv_size',
				'type'         => 'multi',
				'title'        => __('Slider Size', 'flatten') ,
				'help'          => __('Fully resizable, you can set any size.', 'flatten'),
				'opts' => array(
					array(
						'key' => 'tmrv_width',
						'type' => 'text',
						'label' => 'Width',
					),
					array(
						'key' => 'tmrv_height',
						'type' => 'text',
						'label' => 'Height',
					)
				)
			),
			array(
				'key' => 'tmrv_set',
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> $this->tax_id,
				'title' 		=> __('Sliders Set', 'flatten'),
				'help'		=> __('Select the set you want to show.', 'flatten'),
				'ref' 			=> __('If don\'t select a set or you have not created a set, the slider will show all slides', 'flatten')
			),
			array(
				'key' => 'tmrv_items',
				'type' 			=> 'count_select',
				'label'	=> __('Number of Slides', 'flatten'),
				'title' 		=> __('Number of Slides', 'flatten'),
				'help'		=> __('Default value is 10', 'flatten'),
				'count_start'	=> 2,
 				'count_number'	=> 20,
			),
			array(
				'key' => 'tmrv_time',
				'type' 			=> 'select',
				'label'			=> __('Delay ', 'flatten'),
				'title' 		=> __('Slide delay time', 'flatten'),
				'shortexp'		=> __('Default value is 8000', 'flatten'),
				'help'			=> __('The time one slide stays on the screen in Milliseconds.', 'flatten'),
				'opts'			=> $this->getMasterSpeedOptions(20, 1000)
			),
			array(
				'key' => 'tmrv_shadow',
				'type'       => 'check',
				'label' => __('Disable shadow?', 'flatten'),
				'title'      => __('Shadow', 'flatten') ,
				'help'   => __('Set whether to use the shadow of the slider', 'flatten')
			),
			array(
				'key' => 'tmrv_touch',
				'type'       => 'check',
				'label' => __('Disable touch support for mobiles?', 'flatten'),
				'title'      => __('Touch Wipe', 'flatten') ,
				'help'   => __('Set whether to use the touch support for mobiles', 'flatten')

			),
			array(
				'key' => 'tmrv_pause_over',
				'type'       => 'check',
				'inputlabel' => __('Disable Pause on hover?', 'flatten'),
				'title'      => __('Pause on hover', 'flatten') ,
				'help'   => __('Set whether to use the pause on hover feature', 'flatten')

			)
		);
		return $opts;
	}

	function post_type_slider_setup()
	{
		$args = array(
			'label'          => __('Rev. Slides', 'flatten'),
			'singular_label' => __('Slide', 'flatten'),
			'description'    => __('', 'flatten'),
			'taxonomies'     => array( $this->tax_id ),
			'menu_icon'      => $this->icon,
			'supports'       => array('title', 'editor')
		);
		$taxonomies = array(
			$this->tax_id => array(
				'label'          => __('Revolution Sets', 'flatten'),
				'singular_label' => __('Revolution Set', 'flatten'),
			)
		);
		$columns = array(
			"cb"              => "<input type=\"checkbox\" />",
			"title"           => "Title",
			$this->tax_id     => "Revolution Set"
		);
		$this->post_type = new PageLinesPostType( $this->custom_post_type, $args, $taxonomies, $columns, array(&$this, 'column_display') );
	}

	function post_type_caption_setup()
	{
		$args = array(
			'label'          => __('Rev. Captions', 'flatten'),
			'singular_label' => __('Caption', 'flatten'),
			'description'    => __('', 'flatten'),
			'taxonomies'     => array( $this->tax_cap_id ),
			'menu_icon'      => $this->icon,
			'supports'       => array('title', 'editor')
		);
		$taxonomies = array(
			$this->tax_cap_id => array(
				'label'          => __('Caption Sets', 'flatten'),
				'singular_label' => __('Caption Set', 'flatten'),
			)
		);
		$columns = array(
			"cb"              => "<input type=\"checkbox\" />",
			"title"           => "Title",
			$this->tax_cap_id => "Caption Set"
		);
		$this->post_type_cap = new PageLinesPostType( $this->custom_cap_post_type, $args, $taxonomies, $columns, array(&$this, 'column_cap_display') );
	}

	function column_display($column){
		global $post;
		switch ($column){
			case $this->tax_id:
				echo get_the_term_list($post->ID, $this->tax_id, '', ', ','');
				break;
		}
	}

	function column_cap_display($column){
		global $post;
		switch ($column){
			case $this->tax_cap_id:
				echo get_the_term_list($post->ID, $this->tax_cap_id, '', ', ','');
				break;
		}
	}

	function get_posts( $custom_post, $tax_id, $set = null, $limit = null){
		$query                 = array();
		$query['orderby']      = 'ID';
		$query['post_type']    = $custom_post;
		$query[ $tax_id ] = $set;

		if(isset($limit)){
			$query['showposts'] = $limit;
		}

		$q = new WP_Query($query);

		if(is_array($q->posts))
			return $q->posts;
		else
			return array();
	}

	function getMasterSpeedOptions($times = 20, $multiple = 100)
	{
		$out = array();
		for ($i=2; $i <= $times ; $i++) {
			$mill = $i * $multiple;
			$out[(string)$mill] = array('name' => $mill);
		}
		return $out;
	}

	function getEasing()
	{
		return array(
			'easeEasOutBack'      => array('name' => __('OutBack', 'flatten')),
			'easeInQuad'       => array('name' => __('InQuad', 'flatten')),
			'easeOutQuad'      => array('name' => __('OutQuad', 'flatten')),
			'easeInOutQuad'    => array('name' => __('InOutQuad', 'flatten')),
			'easeInCubic'      => array('name' => __('InCubic', 'flatten')),
			'easeOutCubic'     => array('name' => __('OutCubic', 'flatten')),
			'easeInOutCubic'   => array('name' => __('InOutCubic', 'flatten')),
			'easeInQuart'      => array('name' => __('InQuart', 'flatten')),
			'easeOutQuart'     => array('name' => __('OutQuart', 'flatten')),
			'easeInOutQuart'   => array('name' => __('InOutQuart', 'flatten')),
			'easeInQuint'      => array('name' => __('InQuint', 'flatten')),
			'easeOutQuint'     => array('name' => __('OutQuint', 'flatten')),
			'easeInOutQuint'   => array('name' => __('InOutQuint', 'flatten')),
			'easeInSine'       => array('name' => __('InSine', 'flatten')),
			'easeOutSine'      => array('name' => __('OutSine', 'flatten')),
			'easeInOutSine'    => array('name' => __('InOutSine', 'flatten')),
			'easeInExpo'       => array('name' => __('InExpo', 'flatten')),
			'easeOutExpo'      => array('name' => __('OutExpo', 'flatten')),
			'easeInOutExpo'    => array('name' => __('InOutExpo', 'flatten')),
			'easeInCirc'       => array('name' => __('InCirc', 'flatten')),
			'easeOutCirc'      => array('name' => __('OutCirc', 'flatten')),
			'easeInOutCirc'    => array('name' => __('InOutCirc', 'flatten')),
			'easeInElastic'    => array('name' => __('InElastic', 'flatten')),
			'easeOutElastic'   => array('name' => __('OutElastic', 'flatten')),
			'easeInOutElastic' => array('name' => __('InOutElastic', 'flatten')),
			'easeInBack'       => array('name' => __('InBack', 'flatten')),
			'easeOutBack'      => array('name' => __('OutBack', 'flatten')),
			'easeInOutBack'    => array('name' => __('InOutBack', 'flatten')),
			'easeInBounce'     => array('name' => __('InBounce', 'flatten')),
			'easeOutBounce'    => array('name' => __('OutBounce', 'flatten')),
			'easeInOutBounce'  => array('name' => __('InOutBounce', 'flatten'))
		);
	}
}