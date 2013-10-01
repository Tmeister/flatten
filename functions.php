<?php

// Load Framework - don't delete this
require_once( dirname(__FILE__) . '/setup.php' );

// Load our shit in a class cause we're awesome
class Flatten {

	function __construct() {

		// Constants
		$this->url = sprintf('%s', PL_CHILD_URL);
		$this->dir = sprintf('/%s', PL_CHILD_DIR);

		// Add a filter so we can build a few custom LESS vars
		add_filter( 'pless_vars', 							array( &$this, 'custom_less_vars'));
		add_filter( 'pagelines_foundry', 					array( &$this, 'google_fonts' ) );
		add_action( 'pagelines_loop_before_post_content', 	array( &$this, 'add_pre_content'));
		add_action( 'pagelines_loop_after_post_content', 	array( &$this, 'add_post_content'));

		add_filter( 'widget_title', array(&$this, 'add_hr') );

		$this->init();
	}

	function init(){

		// Run the theme options
		$this->theme_options();

		// Send the user to the Theme Config panel after they activate.
		add_filter('pl_activate_url', array(&$this,'activation_url'));
	}

	function add_hr($title){
		return $title;
	}

	function add_pre_content($location){
		global $post;
	?>
		<div class="flat_date">
			<div class="day">
				<span><?php echo get_the_date('d') ?></span>
			</div>
			<div class="month">
				<?php echo get_the_date('M, Y') ?>
			</div>
		</div>
			<div class="content-wrap">
	<?php
	}

	function add_post_content($location){
	?>
		</div> <!-- End Content Wrap. -->
	<?php
	}

	// Send the user to the Theme Config panel after they activate. Note how link=nb_theme_config is the same name of the array settings. This must match.
	function activation_url( $url ){

	    $url = home_url() . '?tablink=theme&tabsublink=nb_theme_config';
	    return $url;
	}

	// Custom LESS Vars
	function custom_less_vars($less){
		return $less;
	}

	/**
	 * Adding a custom font from Google Fonts
	 * @param type $thefoundry
	 * @return type
	 */
	function google_fonts( $thefoundry ) {

		if ( ! defined( 'PAGELINES_SETTINGS' ) )
			return;

		$fonts = $this->get_fonts();
		return array_merge( $thefoundry, $fonts );
	}

	/**
	 * Parse the external file for the fonts source
	 * @return type
	 */
	function get_fonts( ) {
		$fonts = pl_file_get_contents( dirname(__FILE__) . '/fonts.json' );
		$fonts = json_decode( $fonts );
		$fonts = $fonts->items;
		$fonts = ( array ) $fonts;
		$out = array();
		foreach ( $fonts as $font ) {
			$out[ str_replace( ' ', '_', $font->family ) ] = array(
				'name'		=> $font->family,
				'family'	=> sprintf( '"%s"', $font->family ),
				'web_safe'	=> true,
				'google' 	=> $font->variants,
				'monospace' => ( preg_match( '/\sMono/', $font->family ) ) ? 'true' : 'false',
				'free'		=> true
			);
		}
		return $out;
	}


    // WELCOME MESSAGE - HTML content for the welcome/intro option field
	function welcome(){

		ob_start();

		?><div style="font-size:12px;line-height:14px;color:#444;"><p><?php _e('You can have some custom text here.','flatten');?></p></div><?php

		return ob_get_clean();
	}

	// Theme Options
	function theme_options(){

		$options = array();

		$options['flatten_config'] = array(
		   'pos'                  => 50,
		   'name'                 => __('Flatten Theme','flatten'),
		   'icon'                 => 'icon-rocket',
		   'opts'                 => array(
		   		array(
		       	    'type'        => 'template',
            		'title'       => __('Welcome to My Theme','flatten'),
            		'template'    => $this->welcome()
		       	),
		       	array(
		           'type'         => 'color',
		           'title'        => __('Sample Color','flatten'),
		           'key'          => 'my_custom_color',
		           'label'        => __('Sample Color','flatten'),
		           'default'      =>'#FFFFFF'
		       	),
		   )
		);
		pl_add_theme_tab($options);
	}

}
new Flatten;