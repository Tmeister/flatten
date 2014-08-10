<?php

// Load Framework - don't delete this
require_once( dirname(__FILE__) . '/setup.php' );

// Load our shit in a class cause we're awesome
add_filter('pl_activate_url', 'activation_url');
function activation_url( $url ){
    $url = home_url() . '?tablink=theme&tabsublink=flatten_config';
    return $url;
}

class Flatten {

	var $theme_name      = 'Flatten';
    var $theme_version   = '2.1';
    var $theme_key;
    var $chavezShop;

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
		//add_filter( 'pl_sorted_settings_array',     array( &$this, 'add_global_panel'));
		//add_filter( 'admin_init',                   array( &$this, 'autoupdate') );
		$this->init();
	}

	function init(){

		// Run the theme options
		$this->theme_options();

		// Send the user to the Theme Config panel after they activate.
		add_filter('pl_activate_url', array(&$this,'activation_url'));
	}

	function autoupdate(){
		if ( !class_exists( 'chavezShopThemeVerifier' ) ) {
			include( dirname( __FILE__ ) . '/inc/chavezShopThemeVerifier.php' );
		}

		$this->chavezShop = new chavezShopThemeVerifier( $this->theme_name, $this->theme_version, pl_setting( 'flatten_license_key' ) );
		$this->chavezShop->check_for_updates();
	}

	function add_global_panel($settings){
        $valid = "";
        if( get_option( $this->theme_key."_activated" ) ){
            $valid = ( $this->chavezShop->check_license() ) ? ' - Your license is valid' : ' - Your license is invalid';
        }

        if( !isset( $settings['eChavez'] ) ){
            $settings['eChavez'] = array(
                'name' => 'Enrique Chavez Shop',
                'icon' => 'icon-shopping-cart',
                'opts' => array()
            );
        }

        $collapser_opts = array(
            'key'   => 'flatten_license_key',
            'type'  => 'text',
            'title' => '<i class="icon icon-shopping-cart"></i> ' . __('Flatten License Key', 'flatten') . $valid,
            'label' => __('License Key', 'flatten'),
            'help'  => __('The theme is fully functional whitout a key license, this license is used only get access to autoupdates within your admin.', 'flatten')

        );

        array_push($settings['eChavez']['opts'], $collapser_opts);
        return $settings;

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

		$hi = "
			<h4>Thanks for your purchase.</h4>
			<div>Your new and shiny theme is ready to be used. <br/>Please be aware of the instructions for a optimal setup.</div>
		";

		$step1 = "
			<h4>Import the configuration</h4>
			<div>
					<p>
						1. Please click on the \"Import Config\" menu item on the left.<br>
						2. Locate the yellow button \"Load Child  Theme Config\" and click on it.<br>
						3. A popup will show, click on the \"Ok\" button.<br>
						4. Once you've completed this action, you may want to publish these changes to your live site.<br>
					</p>
			</div>
		";

		$step2 = "
			<h4>Import demo content</h4>
			<div>
					<p>
						1. Please <a href=\"" .home_url( "/wp-content/themes/flatten/flatten-demo-content.zip")."\">click here</a> to get the demo content file.<br>
						2. Unzip the file. A new file called flatten-demo-content.xml will be created.<br>
						3. Within your wp admin area, go to the Menu Tool -> Import.<br>
						4. From the list options, click on WordPress.<br>
						5. A popup will show asking for install the \"WordPress Importer\" plugin, click \"Install Now\".<br>
						6. Activate plugin and Run Importer<br>
						7. In the \"Choose a file from your computer: \" choose the file from the point 2.<br>
						8. Click Upload file and import.<br>
						9. In the \"Assign Authors\" check the \"Download and import file attachments\".<br>
						10. Click Submit.
					</p>
			</div>
		";
		$step3 = "
			<h4>Home and Blog</h4>
			<div>
					<p>
						1. Finally set the home and blog pages.<br>
						2. Go to the \"Settings\" > \"Reading menu\".<br>
						3. Select the \"A static page (select below)\".<br>
						4. In the \"Front Page\" option select \"Home\".<br>
						5. In the \"Posts Page\" option select \"Blog\".<br>
						6. Click  \"Save Changes\".<br>
						7. Reload your site.<br>



					</p>
			</div>
		";
		$soptions = array();
		$soptions['flatten_config'] = array(
			'pos'   => 1,
		    'name'  => 'Flatten',
		    'icon'  => 'icon-pagelines',
		    'opts'  => array(
		        array(
					'key'      => 'welcome',
					'type'     => 'template',
					'template' => $hi,
					'title'    => 'Hi, Welcome to Flatten'
		        ),
		        array(
					'key'      => 'step1',
					'type'     => 'template',
					'template' => $step1,
					'title'    => 'Step 1 - Child Theme configuration'
		        ),
		        array(
					'key'      => 'step2',
					'type'     => 'template',
					'template' => $step2,
					'title'    => 'Step 2 - Demo content',
					'col'      => 2,
		        ),
		        array(
					'key'      => 'step3',
					'type'     => 'template',
					'template' => $step3,
					'title'    => 'Step 3 - Home and Blog Pages',
					'col'      => 3
		        )


		    )
		);
		pl_add_theme_tab( $soptions );
	}

}
new Flatten;
