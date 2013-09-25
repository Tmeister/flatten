<?php

// Load Framework - don't delete this
require_once( dirname(__FILE__) . '/setup.php' );

// Load our shit in a class cause we're awesome
class YourTheme {

	function __construct() {

		// Constants
		$this->url = sprintf('%s', PL_CHILD_URL);
		$this->dir = sprintf('/%s', PL_CHILD_DIR);

		// Add a filter so we can build a few custom LESS vars
		add_filter( 'pless_vars', array(&$this,'custom_less_vars'));

		$this->init();
	}

	function init(){

		// Run the theme options
		$this->theme_options();

		// Send the user to the Theme Config panel after they activate.
		add_filter('pl_activate_url', array(&$this,'activation_url'));
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
new YourTheme;