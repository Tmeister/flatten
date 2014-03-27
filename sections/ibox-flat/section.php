<?php
/*
	Section: iBoxFlatFlat
	Author: Enrique Chavez
	Author URI: http://enriquechavez.co
	Description: An easy way to create and configure several box type sections at once for Flatten.
	Class Name: tmiBox
	Filter: component
	Loading: active
*/


class tmiBox extends PageLinesSection {

	var $default_limit = 4;

	function section_opts(){

		$options = array();

		$options[] = array(

			'title' => __( 'iBoxFlat Configuration', 'pagelines' ),
			'key'	=> 'ibox_flat_config',
			'type'	=> 'multi',
			'opts'	=> array(
				array(
					'key'			=> 'ibox_flat_cols',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> '3',
					'label' 	=> __( 'Number of Columns for Each Box (12 Col Grid)', 'pagelines' ),
				),
				array(
					'key'			=> 'ibox_flat_media',
					'type' 			=> 'select',
					'opts'		=> array(
						'icon'	 	=> array( 'name' => __( 'Icon Font', 'pagelines' ) ),
						'text'		=> array( 'name' => __( 'Text Only, No Media', 'pagelines' ) )
					),
					'default'		=> 'icon',
					'label' 	=> __( 'Select iBoxFlat Media Type', 'pagelines' ),
				),
				array(
					'key'			=> 'ibox_flat_format',
					'type' 			=> 'select',
					'opts'		=> array(
						'top'		=> array( 'name' => __( 'Media on Top', 'pagelines' ) ),
						'left'	 	=> array( 'name' => __( 'Media at Left', 'pagelines' ) ),
					),
					'default'		=> 'top',
					'label' 	=> __( 'Select the iBoxFlat Media Location', 'pagelines' ),
				),
			)

		);

		$options[] = array(
			'key'		=> 'ibox_flat_array',
	    	'type'		=> 'accordion',
			'col'		=> 2,
			'title'		=> __('iBoxFlates Setup', 'pagelines'),
			'post_type'	=> __('iBoxFlat', 'pagelines'),
			'opts'	=> array(
				array(
					'key'		=> 'title',
					'label'		=> __( 'iBoxFlat Title', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'text',
					'label'	=> __( 'iBoxFlat Text', 'pagelines' ),
					'type'	=> 'textarea'
				),
				array(
					'key'		=> 'link',
					'label'		=> __( 'iBoxFlat Link (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'class',
					'label'		=> __( 'iBoxFlat Class (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'icon',
					'label'		=> __( 'iBoxFlat Icon', 'pagelines' ),
					'type'		=> 'select_icon'
				),
				array(
					'key'		=> 'color',
					'label'		=> __('iBoxFlat icon Color', 'pagelines'),
					'type'		=> 'color',
					'default'  	=> '#00C9FF'
				)


			)
	    );

		return $options;
	}

	function section_head(){
		$ibox_flat_array = $this->opt('ibox_flat_array');
		$i = 1;
	?>
		<style>
			<?php foreach ($ibox_flat_array as $box): $flatcolor = pl_hashify( $box['color'] ); ?>
				#ibox-flat<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> .ibox-flat-icon-border
                {
                    border: 1px solid <?php echo $flatcolor ?>;
                }
                #ibox-flat<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> .ibox-flat-icon-border i,
                #ibox-flat<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> h4{
                    color: <?php echo $flatcolor ?>;
                }
			<?php $i++; endforeach; ?>
		</style>
	<?php
	}


   function section_template( ) {

		// The boxes
		$ibox_flat_array = $this->opt('ibox_flat_array');

		$format_upgrade_mapping = array(
			'text'	=> 'ibox_flat_text_%s',
			'title'	=> 'ibox_flat_title_%s',
			'link'	=> 'ibox_flat_link_%s',
			'class'	=> 'ibox_flat_class_%s',
			'image'	=> 'ibox_flat_image_%s',
			'icon'	=> 'ibox_flat_icon_%s'
		);

		$ibox_flat_array = $this->upgrade_to_array_format( 'ibox_flat_array', $ibox_flat_array, $format_upgrade_mapping, $this->opt('ibox_flat_count'));

		// must come after upgrade
		if( !$ibox_flat_array || $ibox_flat_array == 'false' || !is_array($ibox_flat_array) ){
			$ibox_flat_array = array( array(), array(), array() );
		}

		// Keep
		$cols = ($this->opt('ibox_flat_cols')) ? $this->opt('ibox_flat_cols') : 4;
		$media_type = ($this->opt('ibox_flat_media')) ? $this->opt('ibox_flat_media') : 'icon';
		$media_format = ($this->opt('ibox_flat_format')) ? $this->opt('ibox_flat_format') : 'top';

		$width = 0;
		$output = '';
		$count = 1;

		if( is_array($ibox_flat_array) ){

			$boxes = count( $ibox_flat_array );

			foreach( $ibox_flat_array as $ibox ){

				$text = pl_array_get( 'text', $ibox, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id lectus sem. Cras consequat lorem.');
				$title = pl_array_get( 'title', $ibox, 'iBoxFlat '. $count);
				$link = pl_array_get( 'link', $ibox );
				$user_class = pl_array_get( 'class', $ibox );
				$image = pl_array_get( 'image', $ibox );
				$icon = pl_array_get( 'icon', $ibox );


				$text = sprintf('<div data-sync="ibox_flat_array_item%s_text">%s</div>', $count, $text );
				$title = sprintf('<h4 data-sync="ibox_flat_array_item%s_title">%s</h4>', $count, $title );
				$text_link = ($link) ? sprintf('<div class="ibox-link"><a href="%s">%s <i class="icon icon-angle-right"></i></a></div>', $link, __('More', 'pagelines')) : '';


				$format_class = ($media_format == 'left') ? 'media left-aligned' : 'top-aligned';
				$media_class = 'media-type-'.$media_type;

				$media_bg = '';
				$media_html = '';

				if( $media_type == 'icon' ){

					if(!$icon || $icon == ''){
						$icons = pl_icon_array();
						$icon = $icons[ array_rand($icons) ];
					}
					$media_html = sprintf('<i class="icon flat-icon icon-3x icon-%s"></i>', $icon);

				} elseif( $media_type == 'image' ){

					$media_html = '';

					$media_bg = ($image) ? sprintf('background-image: url(%s);', $image) : '';

				}

				$media_link = '';
				$media_link_close = '';

				if( $link ){
					$media_link = sprintf('<a href="%s">',$link);
					$media_link_close = '</a>';
				}

				if($width == 0)
					$output .= '<div class="row fix">';


				$output .= sprintf(
					'<div class="span%s fibox-%d ibox %s %s fix">
						<div class="ibox-flat-media img">
							%s
							<span class="ibox-flat-icon-border pl-animation pl-appear %s" style="%s">
								%s
							</span>
							%s
						</div>
						<div class="ibox-flat-text bd">
							%s
							<div class="ibox-flat-desc">
								%s
								%s
							</div>
						</div>
					</div>',
					$cols,
					$count,
					$format_class,
					$user_class,
					$media_link,
					$media_class,
					$media_bg,
					$media_html,
					$media_link_close,
					$title,
					$text,
					$text_link
				);

				$width += $cols;

				if($width >= 12 || $count == $boxes){
					$width = 0;
					$output .= '</div>';
				}


				$count++;
			}



		}

		printf('<div class="ibox-wrapper pl-animation-group">%s</div>', $output);

		$scopes = array('local', 'type', 'global');
	//	foreach($scopes as $scope)
	//		$this->opt_update( 'ibox_flat_array', false, $scope );

	}

	function old_section_template( ) {

			$boxes = ($this->opt('ibox_flat_count')) ? $this->opt('ibox_flat_count') : $this->default_limit;
			$cols = ($this->opt('ibox_flat_cols')) ? $this->opt('ibox_flat_cols') : 3;

			$media_type = ($this->opt('ibox_flat_media')) ? $this->opt('ibox_flat_media') : 'icon';
			$media_format = ($this->opt('ibox_flat_format')) ? $this->opt('ibox_flat_format') : 'top';

			$width = 0;
			$output = '';

			for($i = 1; $i <= $boxes; $i++):

				// TEXT
				$text = ($this->opt('ibox_flat_text_'.$i)) ? $this->opt('ibox_flat_text_'.$i) : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean id lectus sem. Cras consequat lorem.';

			$text = sprintf('<div data-sync="ibox_flat_text_%s">%s</div>', $i, $text );
			$user_class = ($this->opt('ibox_flat_class_'.$i)) ? $this->opt('ibox_flat_class_'.$i) : '';

				$title = ($this->opt('ibox_flat_title_'.$i)) ? $this->opt('ibox_flat_title_'.$i) : __('iBoxFlat '.$i, 'pagelines');
				$title = sprintf('<h4 data-sync="ibox_flat_title_%s">%s</h4>', $i, $title );

				// LINK
				$link = $this->opt('ibox_flat_link_'.$i);
				$text_link = ($link) ? sprintf('<div class="ibox-link"><a href="%s">%s <i class="icon icon-angle-right"></i></a></div>', $link, __('More', 'pagelines')) : '';


				$format_class = ($media_format == 'left') ? 'media left-aligned' : 'top-aligned';
				$media_class = 'media-type-'.$media_type;

				$media_bg = '';
				$media_html = '';

				if( $media_type == 'icon' ){
					$media = ($this->opt('ibox_flat_icon_'.$i)) ? $this->opt('ibox_flat_icon_'.$i) : false;
					if(!$media){
						$icons = pl_icon_array();
						$media = $icons[ array_rand($icons) ];
					}
					$media_html = sprintf('<i class="icon icon-3x icon-%s"></i>', $media);

				} elseif( $media_type == 'image' ){

					$media = ($this->opt('ibox_flat_image_'.$i)) ? $this->opt('ibox_flat_image_'.$i) : false;

					$media_html = '';

					$media_bg = ($media) ? sprintf('background-image: url(%s);', $media) : '';

				}

				$media_link = '';
				$media_link_close = '';

				if( $link ){
					$media_link = sprintf('<a href="%s">',$link);
					$media_link_close = '</a>';
				}

				if($width == 0)
					$output .= '<div class="row fix">';


				$output .= sprintf(
					'<div class="span%s ibox %s %s fix">
						<div class="ibox-media img">
							%s
							<span class="ibox-icon-border pl-animation pl-appear pl-contrast %s" style="%s">
								%s
							</span>
							%s
						</div>
						<div class="ibox-text bd">
							%s
							<div class="ibox-desc">
								%s
								%s
							</div>
						</div>
					</div>',
					$cols,
					$format_class,
					$user_class,
					$media_link,
					$media_class,
					$media_bg,
					$media_html,
					$media_link_close,
					$title,
					$text,
					$text_link
				);

				$width += $cols;

				if($width >= 12 || $i == $boxes){
					$width = 0;
					$output .= '</div>';
				}


			 endfor;

			printf('<div class="ibox-wrapper pl-animation-group">%s</div>', $output);

		}


}
