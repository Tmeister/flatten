<?php
/*
	Section: iBoxFlat
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
			'type'	=> 'multi',
			'opts'	=> array(
				array(
					'key'			=> 'ibox_flat_count',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> 4,
					'label' 	=> __( 'Number of iBoxes to Configure', 'pagelines' ),
				),
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
					'label' 	=> __( 'Select iBox Media Type', 'pagelines' ),
				),
				array(
					'key'			=> 'ibox_flat_format',
					'type' 			=> 'select',
					'opts'		=> array(
						'top'		=> array( 'name' => __( 'Media on Top', 'pagelines' ) ),
						'left'	 	=> array( 'name' => __( 'Media at Left', 'pagelines' ) ),
					),
					'default'		=> 'top',
					'label' 	=> __( 'Select the iBox Media Location', 'pagelines' ),
				),
			)

		);

		$slides = ($this->opt('ibox_flat_count')) ? $this->opt('ibox_flat_count') : $this->default_limit;
		$media = ($this->opt('ibox_flat_media')) ? $this->opt('ibox_flat_media') : 'icon';

		for($i = 1; $i <= $slides; $i++){

			$opts = array(

				array(
					'key'		=> 'ibox_flat_title_'.$i,
					'label'		=> __( 'iBox Title', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'ibox_flat_text_'.$i,
					'label'	=> __( 'iBox Text', 'pagelines' ),
					'type'	=> 'textarea'
				),
				array(
					'key'		=> 'ibox_flat_link_'.$i,
					'label'		=> __( 'iBox Link (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'ibox_flat_class_'.$i,
					'label'		=> __( 'iBox Class (Optional)', 'pagelines' ),
					'type'		=> 'text'
				),
			);

			if($media == 'icon'){
				$opts[] = array(
					'key'		=> 'ibox_flat_icon_'.$i,
					'label'		=> __( 'iBox Icon', 'pagelines' ),
					'type'		=> 'select_icon',
				);

				$opts[] = array(
					'key' => 'ibox_flat_color_'.$i,
					'label' => __('iBox icon Color', 'pagelines'),
					'type' => 'color',
					'default' => '#00C9FF'
				);

			}


			$options[] = array(
				'title' 	=> __( 'iBox ', 'pagelines' ) . $i,
				'type' 		=> 'multi',
				'opts' 		=> $opts,

			);

		}

		return $options;
	}

	function section_head(){
		$fboxes = $this->opt('ibox_flat_count');
	?>
		<style>
			<?php
				for ($i=1; $i<=$fboxes; $i++):
					$flatcolor = pl_hashify( $this->opt('ibox_flat_color_'.$i) );
			?>
				.ibox-<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> .ibox-flat-icon-border
				{
					border: 1px solid <?php echo $flatcolor ?>;
				}
				.ibox-<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> .ibox-flat-icon-border i,
				.ibox-<?php echo $this->meta['clone']?> .fibox-<?php echo $i;?> h4{
					color: <?php echo $flatcolor ?>;
				}
			<?php endfor ?>
		</style>
	<?php
	}


   function section_template( ) {

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
			$text_link = ($link) ? sprintf('<div class="ibox-flat-link"><a href="%s">%s <i class="icon-angle-right"></i></a></div>', $link, __('More', 'pagelines')) : '';


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
				$media_html = sprintf('<i class="flat-icon icon-%s"></i>', $media);

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
				'<div class="span%s ibox fibox-%d %s %s fix">
					<div class="ibox-flat-media img">
						%s
						<span class="ibox-flat-icon-border %s" style="%s">
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
				$i,
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

		$clone =  'ibox-'.$this->meta['clone'];
		printf('<div class="ibox-flat-wrapper pl-animation-group %s %s">%s</div>', 'flat-media-'.$media_type, $clone, $output);

	}


}