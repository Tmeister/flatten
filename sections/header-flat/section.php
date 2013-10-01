<?php
/*
    Section: HeaderFlat
    Author: Enrique Chavez
    Author URI: http://enriquechavez.co
    Description: Inline Description
    Class Name: TMHeaderFlat
    Demo:
    Version: 1.0
    Filter: full-width, misc
*/

/**
*
*/
class TMHeaderFlat extends PageLinesSection{

    function section_persistent(){}

    function section_scripts(){
        wp_enqueue_script('stellar-flat', $this->base_url.'/jquery.stellar.min.js', array('jquery'), '0.6.2', true );
    }

    function section_foot(){
    ?>
        <script>
            jQuery(document).ready(function($) {

                jQuery.stellar({});

            });
        </script>
    <?php
    }

    function section_template(){
        $title = ( $this->opt($this->id.'_title') ) ? $this->opt($this->id.'_title') : 'Parallax Title Section';
        $subtitle = ( $this->opt($this->id.'_sub_title') ) ? $this->opt($this->id.'_sub_title') : 'Great title section';
        $title_color = ( $this->opt($this->id.'_title_color') )   ? pl_hashify( $this->opt($this->id.'_title_color')) : '#fff';
        $sub_title_color = ( $this->opt($this->id.'_sub_color') ) ? pl_hashify( $this->opt($this->id.'_sub_color' ))  : '#fff';

    ?>
        <div class="header-wrapper" data-stellar-background-ratio="0.5" style="background-image:url('<?php echo $this->opt($this->id.'_image') ?>')">
            <div class="pl-content">
                <h1 data-sync="<?php echo $this->id.'_title' ?>" style="color:<?php echo $title_color ?>" class="zmb"><?php echo $title ?></h1>
                <h4 data-sync="<?php echo $this->id.'_sub_title' ?>" style="color:<?php echo $sub_title_color ?>"><?php echo $subtitle ?></h4>
            </div>
        </div>
    <?php
    }

    function section_opts(){

        $options = array();

        $options[] = array(
            'key'       => $this->id.'_image',
            'type'      => 'image_upload',
            'title'     => __('Background Image','flatten')
        );

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Titles','flatte'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_title',
                    'type'      => 'text',
                    'title'     => __('Title','flatten')
                ),
                array(
                    'key'       => $this->id.'_sub_title',
                    'type'      => 'text',
                    'title'     => __('Sub Title','flatten')
                )
            )
        );

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Colors','flatten'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_title_color',
                    'type'      => 'color',
                    'title'     => __('Title Color','flatten'),
                    'default'   => '#FFFFFF'
                ),
                array(
                    'key'       => $this->id.'_sub_color',
                    'type'      => 'color',
                    'title'     => __('Sub Title Color','flatten'),
                    'default'   => '#FFFFFF'
                )
            )
        );




        return $options;

    }

}



