<?php
/*
	Section: HeroFlat
	Author: Enrique Chavez
	Author URI: http://enriquechavez.co
	Description: A responsive full width image and text area with button with a twist for Flat.
	Class Name: tmHeroFlat
	Workswith: templates, main, header, morefoot, content
	Cloning: true
	Filter: component
	Loading: active
*/

class tmHeroFlat extends PageLinesSection {

	function section_opts() {

		$opts = array(

			array(
				'title' => __( 'Hero Settings', 'pagelines' ),
				'type'  => 'multi',
				'opts'  => array(
					array(
						'key'   => 'flat_herounit_title',
						'type'  => 'text',
						'label' => __( 'Heading', 'pagelines' )
					),

					array(
						'key'   => 'flat_herounit_tagline',
						'type'  => 'text',
						'label' => __( 'Sub Heading', 'pagelines' )
					),
					array(
						'key'   => 'flat_herounit_text',
						'type'  => 'textarea',
						'label' => __( 'Description', 'pagelines' )
					),
					array(
						'key'   => 'flat_text_animation',
						'type'  => 'select',
						'label' => __('Incoming animation', 'pagelines'),
						'opts'  => array(
							'no-anim'			=> array( 'name' => 'No Animation'),
							'pla-fade'			=> array( 'name' => 'Fade'),
							'pla-scale'			=> array( 'name' => 'Scale'),
							'pla-from-left'		=> array( 'name' => 'From Left'),
							'pla-from-right'	=> array( 'name' => 'From Right'),
							'pla-from-bottom'	=> array( 'name' => 'From Bottom'),
							'pla-from-top'		=> array( 'name' => 'From Top')
						)
					),

				)
			),

            array(
				'title' => __( 'Hero Image', 'pagelines' ),
				'type'  => 'multi',
				'opts'  => array(
					array(
						'key'   => 'flat_herounit_image',
						'type'  => 'image_upload',
						'label' => __( 'Upload Custom Image', 'pagelines' )
					),
					array(
						'key'   => 'flat_image_animation',
						'type'  => 'select',
						'label' => __('Incoming animation', 'pagelines'),
						'opts'  => array(
							'no-anim'			=> array( 'name' => 'No Animation'),
							'pla-fade'			=> array( 'name' => 'Fade'),
							'pla-scale'			=> array( 'name' => 'Scale'),
							'pla-from-left'		=> array( 'name' => 'From Left'),
							'pla-from-right'	=> array( 'name' => 'From Right'),
							'pla-from-bottom'	=> array( 'name' => 'From Bottom'),
							'pla-from-top'		=> array( 'name' => 'From Top')
						)
					),
		            array(
						'key'     => 'flat_hero_unit_reverse',
						'type'    => 'check',
						'default' => false,
						'label'   => __( 'Reverse the Hero unit (image on left)', 'pagelines' )
					),
                )
            ),

			array(
				'title'			=> __( 'Content Widths', 'pagelines' ),
				'type'			=> 'multi',
				'opts'			=> array(

			array(
				'label'			=> __( 'Text Area Width', 'pagelines' ),
				'key'			=> 'flat_herounit_left_width',
				'default'		=> 'span6',
				'type'			=> 'select',
				'opts'			=> array(

				'span3'			=> array( 'name' => '25%' ),
				'span4'			=> array( 'name' => '33%' ),
				'span6'			=> array( 'name' => '50%' ),
				'span8'			=> array( 'name' => '66%' ),
				'span9'			=> array( 'name' => '75%' ),
				'span7'			=> array( 'name' => '90%' )
										)
									),

			array(
				'label'			=> __( 'Image Area Width', 'pagelines' ),
				'key'			=> 'flat_herounit_right_width',
				'default'		=> 'span6',
				'type'			=> 'select',
				'opts'			=> array(

				'span3'			=> array( 'name' => '25%' ),
				'span4'			=> array( 'name' => '33%' ),
				'span6'			=> array( 'name' => '50%' ),
				'span8'			=> array( 'name' => '66%' ),
				'span9'			=> array( 'name' => '75%' ),
				'span7'			=> array( 'name' => '90%' )
										)
									)
								)
							),

			array(
				'title'			=> __( 'Hero Action Button', 'pagelines' ),
				'type'			=> 'multi',
				'opts'			=> array(

			array(
				'key'			=> 'flat_herounit_button_link',
				'type'			=> 'text',
				'label'			=> __( 'Button link destination (URL - Required)', 'pagelines' ) ),

			array(
				'key'			=> 'flat_herounit_button_text',
				'type'			=> 'text',
				'label'			=> __( 'Hero Button Text', 'pagelines' ) ),

			array(
				'key'			=> 'flat_herounit_button_target',
				'type'			=> 'check',
				'default'		=> false,
				'label'			=> __( 'Open link in new window', 'pagelines' ) ),

			array(
				'label'			=> __( 'Select Button Color', 'pagelines' ),
				'key'			=> 'flat_herounit_button_theme',
				'default'		=> 'primary',
				'type'			=> 'select',
				'opts'			=> array(

				'primary'		=> array( 'name' => __( 'Blue', 'pagelines' ) ),
				'warning'		=> array( 'name' => __( 'Orange', 'pagelines' ) ),
				'important'		=> array( 'name' => __( 'Red', 'pagelines' ) ),
				'success'		=> array( 'name' => __( 'Green', 'pagelines' ) ),
				'info'			=> array( 'name' => __( 'Light Blue', 'pagelines' ) ),
				'reverse'		=> array( 'name' => __( 'Grey', 'pagelines' ) ),
				'white-flat'	=> array( 'name' => __( 'White Flat', 'pagelines' ) )
										)
									)
								)
							)
						);
	return $opts;
	}


	/**
	* Section template.
	*/
   function section_template() {

		$hero_lt_width = $this->opt( 'flat_herounit_left_width', $this->oset );
		$hero_rt_width = $this->opt( 'flat_herounit_right_width', $this->oset );
   		$hero_title = $this->opt( 'flat_herounit_title', $this->tset );
		$hero_tag = $this->opt( 'flat_herounit_tagline', $this->tset );
		$hero_text = $this->opt( 'flat_herounit_text', $this->tset );
		$hero_img = $this->opt( 'flat_herounit_image', $this->tset );
		$hero_butt_link = $this->opt( 'flat_herounit_button_link', $this->oset );
		$hero_butt_text = $this->opt( 'flat_herounit_button_text', $this->oset );
		$hero_butt_target = ( $this->opt( 'flat_herounit_button_target', $this->oset ) ) ? ' target="_blank"': '';
		$hero_butt_theme = $this->opt( 'flat_herounit_button_theme', $this->oset );
        $hero_reverse = ( $this->opt( 'flat_hero_unit_reverse', $this->oset ) ) ? 'pl-hero-reverse': '';
        $hero_text_animation = ( $this->opt('flat_text_animation') ) ? $this->opt('flat_text_animation') : '';
        $hero_image_animation = ( $this->opt('flat_image_animation') ) ? $this->opt('flat_image_animation') : '';

		if ( ! $hero_rt_width )
			$hero_rt_width = 'span6';

		if ( ! $hero_lt_width )
			$hero_lt_width = 'span6';

		$hero_title = ($hero_title) ? $hero_title : __('The Flat Hero!', 'pagelines');
		$hero_tag = ($hero_tag) ? $hero_tag : __('Now just set up your Hero section options', 'pagelines');
		$hero_text = ($hero_text) ? apply_filters( 'the_content', $hero_text) : __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', 'pagelines');



	   	printf( '<div class="pl-hero-wrap row %s pl-animation-group">', $hero_reverse);


	   	if( $hero_lt_width )
			printf( '<div class="pl-hero %s pl-animation %s" >', $hero_lt_width, $hero_text_animation );
			?>
				<?php

					if( $hero_title )
						printf( '<h1 class="m-bottom" data-sync="flat_herounit_title">%s</h1>', $hero_title );

					if( $hero_tag )
		  				printf( '<h4 data-sync="flat_herounit_tagline">%s</h4>', $hero_tag );

					if( $hero_text )
		  				printf( '<p data-sync="flat_herounit_text">%s</p>', $hero_text );

	  			    if( $hero_butt_link )
					printf( '<a %s class="btn btn-%s btn-large" href="%s" data-sync="flat_herounit_button_text">%s</a> ', $hero_butt_target, $hero_butt_theme, $hero_butt_link, $hero_butt_text );
	  			?>
			</div>

	   	<?php
	   	if( $hero_rt_width )
			printf( '<div class="pl-hero-image %s pl-animation %s">', $hero_rt_width, $hero_image_animation);

		if( $hero_img )
			printf( '<div class="hero_image"><img class="pl-imageframe" data-sync="flat_herounit_image" src="%s" /></div>', apply_filters( 'pl_hero_image', $hero_img ) );

		?>
			</div>

		</div>

		<?php

	}

}