<?php
/*
	Section: Flat Nav
	Author: Enrique Chavez
	Author URI: http://enriquechavez.co
	Description: Menu section for Flatten with a beautiful mobile design.
	Class Name: TMFlatNav
	Demo: http://dms.tmeister.net/flatten
	Version: 1.0
	Filter: full-width, nav
*/

class TMFlatNav extends PageLinesSection {

    var $section_name      = 'Flat Nav';
    var $section_version   = '1.0';
    var $section_key ;
    var $chavezShop;

    function section_persistent()
    {
    }

    function section_scripts(){
        wp_enqueue_script('SmartFlatMenu', $this->base_url.'/flat-nav.js', array('jquery'), $this->section_version );
    }

    function section_head() {
    ?>
        <script>
        jQuery(document).ready(function($) {
            jQuery('.menu-<?php echo $this->meta['clone']?>').smartFlatMenu();
        });
        </script>
    <?php
    }

   	function section_template() {
    ?>
        <div class="pl-content">
            <div class="row menu-<?php echo $this->meta['clone']?>">
                <div class="span3">
                    <div class="flat-logo">
                        <a href="<?php echo get_site_url(); ?>" class="flat_logo">
                            <img src="<?php echo $this->opt('flat_logotype') ?>" alt="" data-sync="flat_logotype">
                        </a>
                    </div>
                </div>
                <div class="span9">
                    <nav class="flat-nav">
                        <?php
                            if ( $this->opt( 'flat_main_menu' ) ) {
                                wp_nav_menu(
                                    array(
                                        'menu_class'  => 'flat-menu',
                                        'container' => 'div',
                                        'container_class' => 'flat-menu-holder clear',
                                        'depth' => 3,
                                        'menu' => $this->opt('flat_main_menu'),
                                    )
                                );
                            }else{
                                $this->flat_nav_fallback( 'flat-menu', 3 );
                            }
                        ?>
                        <div id="flat-mobile-icon" class="mobile-icon"></div>
                    </nav>
                </div>
            </div>
        </div>
        <div id="mobile-menu-template" class="remove-it mobile-visual-menu">
            <div class="row">
                <div class="span12">
                    <?php get_search_form( true ); ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    <?php
   	}



	function section_foot(){}

	function section_opts()
    {

        $opts = array(
            array(
                'type'  => 'select_menu',
                'title' => 'Main Menu',
                'key'   => 'flat_main_menu',
                'label' => __('Select the main menu', 'flatten')
            ),
            array(
                'type'  => 'image_upload',
                'title' => 'Site Logotype',
                'key'   => 'flat_logotype',
                'label' => 'Please select the site logotype.',
                'help'  => 'For better visualitation in a retina display devices, please use a 500x200px logo, the section will resize the logo according to the device.'
            )
        );
        return $opts;
    }
    function flat_nav_fallback($class = '', $limit = 6){

        $pages = wp_list_pages('echo=0&title_li=&sort_column=menu_order&depth=1');

        $pages_arr = explode("\n", $pages);

        $pages_out = '';
        for($i=0; $i < $limit; $i++){

            if(isset($pages_arr[$i]))
                $pages_out .= $pages_arr[$i];

        }

        printf('<div class="flat-menu-holder"><ul class="%s">%s</ul></div>', $class, $pages_out);
    }

}
