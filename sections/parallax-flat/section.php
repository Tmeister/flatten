<?php
/*
    Section: ParallaxFlat
    Author: Enrique Chavez
    Author URI: http://enriquechavez.co
    Description: Parallax background section to show relevant information, the section support: title, subtitle and custom content, also support shortcodes.
    Class Name: TMParallaxFlat
    Demo: http://dms.tmeister.net/flatten
    Version: 1.0
    Filter: full-width, misc
*/

class TMParallaxFlat extends PageLinesSection{

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
        $title           = ( $this->opt($this->id.'_title') ) ? $this->opt($this->id.'_title') : 'Parallax Title Section';
        $subtitle        = ( $this->opt($this->id.'_sub_title') ) ? $this->opt($this->id.'_sub_title') : 'Great title section';
        $title_color     = ( $this->opt($this->id.'_title_color') )   ? pl_hashify( $this->opt($this->id.'_title_color')) : '#000';
        $sub_title_color = ( $this->opt($this->id.'_sub_color') ) ? pl_hashify( $this->opt($this->id.'_sub_color' ))  : '#000';
        $t_animation     = ( $this->opt($this->id.'_title_animation') ) ? $this->opt($this->id.'_title_animation') : '';
        $s_animation     = ( $this->opt($this->id.'_subtitle_animation') ) ? $this->opt($this->id.'_subtitle_animation') : '';
        $d_animation     = ( $this->opt($this->id.'_desc_animation') ) ? $this->opt($this->id.'_desc_animation') : '';
        $description     = $this->opt($this->id.'_description');
        $desc_color      = ( $this->opt($this->id.'_desc_color') )   ? pl_hashify( $this->opt($this->id.'_desc_color')) : '#000';


    ?>
        <div class="header-wrapper" data-stellar-background-ratio="0.5" style="background-image:url('<?php echo $this->opt($this->id.'_image') ?>')">
            <div class="pl-content pl-animation-group">
                <h1 data-sync="<?php echo $this->id.'_title' ?>" style="color:<?php echo $title_color ?>" class="pl-animation zmb <?php echo $t_animation; ?>"><?php echo $title ?></h1>
                <h4 data-sync="<?php echo $this->id.'_sub_title' ?>" style="color:<?php echo $sub_title_color ?>" class="pl-animation <?php echo $s_animation ?>"><?php echo $subtitle ?></h4>
                <div class="description <?php echo $d_animation ?> pl-animation">
                    <?php echo apply_filters( 'the_content', $description ); ?>
                </div>
            </div>
        </div>
    <?php
    }

    function section_opts(){

        $options = array();

        $options[] = array(
            'key'       => $this->id.'_image',
            'type'      => 'image_upload',
            'title'     => __('Background Image','flatten'),
            'help'      => __('This image will use to create the parallax effect, if the section width is 300px the image need to be 600px height.', 'flatten')
        );

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Title Options','flatten'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_title',
                    'type'      => 'text',
                    'title'     => __('Title','flatten')
                ),
                array(
                    'key'       => $this->id.'_title_color',
                    'type'      => 'color',
                    'title'     => __('Title Color','flatten'),
                    'default'   => '#000000'
                ),
                array(
                    'key'   => $this->id.'_title_animation',
                    'type'  => 'select',
                    'label' => __('Incoming animation', 'pagelines'),
                    'opts'  => array(
                        'no-anim'           => array( 'name' => 'No Animation'),
                        'pla-fade'          => array( 'name' => 'Fade'),
                        'pla-scale'         => array( 'name' => 'Scale'),
                        'pla-from-left'     => array( 'name' => 'From Left'),
                        'pla-from-right'    => array( 'name' => 'From Right'),
                        'pla-from-bottom'   => array( 'name' => 'From Bottom'),
                        'pla-from-top'      => array( 'name' => 'From Top')
                    )
                )
            )
        );

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Subtitle Options','flatten'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_sub_title',
                    'type'      => 'text',
                    'title'     => __('Sub Title','flatten')
                ),
                array(
                    'key'       => $this->id.'_sub_color',
                    'type'      => 'color',
                    'title'     => __('Sub Title Color','flatten'),
                    'default'   => '#000000'
                ),
                array(
                    'key'   => $this->id.'_subtitle_animation',
                    'type'  => 'select',
                    'label' => __('Incoming animation', 'pagelines'),
                    'opts'  => array(
                        'no-anim'           => array( 'name' => 'No Animation'),
                        'pla-fade'          => array( 'name' => 'Fade'),
                        'pla-scale'         => array( 'name' => 'Scale'),
                        'pla-from-left'     => array( 'name' => 'From Left'),
                        'pla-from-right'    => array( 'name' => 'From Right'),
                        'pla-from-bottom'   => array( 'name' => 'From Bottom'),
                        'pla-from-top'      => array( 'name' => 'From Top')
                    )
                )
            )
        );

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Description Options','flatten'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_description',
                    'type'      => 'textarea',
                    'title'     => __('Extra Content','flatten'),
                    'help'      => __('You can add any other content in this place, please note this content accept shorcodes as well.','flatten')
                ),
                array(
                    'key'       => $this->id.'_desc_color',
                    'type'      => 'color',
                    'title'     => __('Content Text Color','flatten'),
                    'default'   => '#000000'
                ),
                array(
                    'key'   => $this->id.'_desc_animation',
                    'type'  => 'select',
                    'label' => __('Incoming animation', 'pagelines'),
                    'opts'  => array(
                        'no-anim'           => array( 'name' => 'No Animation'),
                        'pla-fade'          => array( 'name' => 'Fade'),
                        'pla-scale'         => array( 'name' => 'Scale'),
                        'pla-from-left'     => array( 'name' => 'From Left'),
                        'pla-from-right'    => array( 'name' => 'From Right'),
                        'pla-from-bottom'   => array( 'name' => 'From Bottom'),
                        'pla-from-top'      => array( 'name' => 'From Top')
                    )
                )
            )
        );


        return $options;

    }

}



