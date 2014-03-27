<?php
/*
    Section: PortfolioFlat
    Author: Enrique Chavez
    Author URI: http://enriquechavez.co
    Description: Show your work in a beautiful way with this section, PortfolioFlat can be use for show a photographer's work, a designer's work or just to show personal images using great transitions and effects driven by CSS3 and jQuery, also, all happens in just one site thanks to ajax.
    Class Name: TMPortfolioFlat
    Demo: http://dms.tmeister.net/flatten
    Version: 1.0
    Filter: misc
*/


if( ! function_exists('cmb_init') ){
    require_once( 'cmb/custom-meta-boxes.php' );
}


class TMPortfolioFlat extends PageLinesSection{

    var $tax_id           = 'project_flat_sets';
    var $custom_post_type = 'project_flat_post';
    var $icon;

    function section_persistent(){
    	$this->icon = $this->base_url .'/icon.png';
        add_action('wp_ajax_load_project', array($this, 'get_project'));
        add_action('wp_ajax_nopriv_load_project', array($this, 'get_project'));
        add_image_size( 'project-thum', 420 , 300, true );

        $this->post_type_setup();
        add_filter( 'cmb_meta_boxes', array(&$this, 'meta_boxes') );
    }

    function section_styles(){
        wp_enqueue_script( 'isotope', $this->base_url . '/js/jquery.isotope.min.js', array( 'jquery' ), '1.5.25', true );
        wp_enqueue_script( 'project-flat', $this->base_url . '/js/project.flat.js', array( 'isotope' ), '1.0', true );
    }

    function section_head(){
    ?>
        <script type="text/javascript">var adminUrl = '<?php echo get_site_url()?>/';</script>
    <?php
    }

    function section_template(){
        $limit = ( $this->opt($this->id.'_items') ) ? $this->opt($this->id.'_items')    : 12;
        $sets  = ( $this->opt($this->id.'_tax'))    ? $this->opt($this->id.'_tax')      : array();

        if( count($sets) ){
            $filter = implode(',',$sets);
        }else{
            $filter = null;
            foreach (get_terms($this->tax_id) as $term) {
                $sets[] = $term->slug;
            }
        }

        $posts = $this->custom_get_posts($this->custom_post_type, $this->tax_id, $filter, $limit);

        if( !count( $posts ) ){
            echo setup_section_notify($this, __('Sorry,there are no post to display.', 'flatten'), get_admin_url().'edit.php?post_type='.$this->custom_post_type, __('Please create some posts', 'flatten'));
            return;
        }

        $showlabel   = ( $this->opt($this->id.'_show') ) ? $this->opt($this->id.'_show') : 'Showing';
        $alllabel    = ( $this->opt($this->id.'_all') ) ? $this->opt($this->id.'_all') : 'All';
        $buttonlabel = ( $this->opt($this->id.'_button') ) ? $this->opt($this->id.'_button') : 'Launch Project';
        $buttonclass = ( $this->opt($this->id.'_btnClass') ) ? $this->opt($this->id.'_btnClass') : 'btn-primary';

    ?>

        <div class="project-flat-wrapper">
            <div class="filters" id="filters-anchor">
                <span class="line showing"></span>
                <span class="title" data-sync="<?php echo $this->id.'_show' ?>"><?php echo $showlabel ?></span>
                <div class="tags-wrapper">
                    <ul class="tags-filter">
                        <li data-filter="project" class="active" data-sync="<?php echo $this->id.'_all'?>" ><?php echo $alllabel ?></li>
                        <?php foreach ($sets as $slug): $term = get_term_by( 'slug', $slug, $this->tax_id ) ?>
                            <li data-filter="<?php echo $slug ?>"><?php echo $term->name ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>

            <div class="pf-content">
                <div class="row">
                    <div class="span8 media">
                    </div>
                    <div class="span4">
                        <div class="project-nav">
                            <div class="nav-wrapper">
                                <a href="#" class="arrowleft">
                                    <i class="icon icon-chevron-left"></i>
                                </a>
                                <a href="#" class="arrowright">
                                    <i class="icon icon-chevron-right"></i>
                                </a>
                                <a href="#" class="navclose">
                                    <i class="icon icon-remove"></i>
                                </a>
                            </div>
                        </div>
                        <div class="text-wrapper">
                            <h3 class="clear"></h3>
                            <div>
                                <div class="description"></div>
                                <ul class="ptags"></ul>
                                <div class="go-project">
                                    <a class="btn btn-large <?php echo $buttonclass ?>" href="#" data-sync="<?php echo $this->id.'_button' ?>"><?php echo $buttonlabel ?></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="boxes-wrapper clear">
                <?php foreach ($posts as $post):
                    $cats = wp_get_post_terms($post->ID, $this->tax_id);
                    $class = '';
                    $cout = '';
                    foreach ($cats as $cat) {
                        $class .= $cat->slug.' ';
                        $cout .= $cat->name.' ';
                    }
                ?>
                    <div class="project <?php echo $class ?>" data-postid="<?php echo $post->ID ?>">
                        <div class="pf-image">
                            <?php echo get_the_post_thumbnail($post->ID, 'project-thum'); ?>
                            <div class="mask"></div>
                            <div class="plus"><i class="icon icon-link"></i></div>
                        </div>
                        <div class="pf-info">
                            <h4 class="zmb"><?php echo $post->post_title;?></h4>
                            <h5><?php echo $cout;?></h5>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php
    }


    function section_opts(){

        $cats = get_terms($this->tax_id);
        $sets = array();
        foreach ($cats as $cat) {
            $sets[$cat->slug] = array('name' => $cat->name);
        }

        $options = array();

        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('title','domain'),
            'opts'      => array(
                array(
                    'key'          => $this->id.'_items',
                    'type'         => 'count_select',
                    'count_start'  => 1,
                    'count_number' => 50,
                    'label'        => __('The amount of post to show', 'flatten')
                ),
                array(
                    'key'   => $this->id.'_tax',
                    'type'  => 'select_multi',
                    'label' => 'Select the sets include',
                    'opts'  => $sets
                ),
                array(
                    'key'   => $this->id.'_btnClass',
                    'type'  => 'select_button',
                    'label' => 'Select the buttton style',
                    'opts'  => $sets
                ),

            )
        );


        // Multi Select
        $options[] = array(
            'type'      => 'multi', // Here you can nest multiple options
            'title'     => __('Labels','flatten'),
            'opts'      => array(
                array(
                    'key'       => $this->id.'_show',
                    'type'      => 'text',
                    'title'     => __('Filter Label','flatten'),
                    'help'      => __('Default: Showing:', 'flatten')
                ),
                array(
                    'key'       => $this->id.'_all',
                    'type'      => 'text',
                    'title'     => __('"All" Label','flatten'),
                    'help'      => __('Default: All:', 'flatten')
                ),
                array(
                    'key'       => $this->id.'_button',
                    'type'      => 'text',
                    'title'     => __('Button Label','flatten'),
                    'help'      => __('Default: Launch Project', 'flatten')
                ),

            )
        );

        return $options;
    }

    function post_type_setup(){
        $args = array(
            'label'          => __('Projects', 'flatten'),
            'singular_label' => __('Project', 'flatten'),
            'description'    => __('', 'flatten'),
            'taxonomies'     => array( $this->tax_id ),
            'menu_icon'			 => $this->icon,
            'supports'       => array( 'title', 'editor', 'thumbnail')
        );
        $taxonomies = array(
            $this->tax_id => array(
                'label'          => __('Project Sets', 'flatten'),
                'singular_label' => __('project Set', 'flatten'),
            )
        );
        $columns = array(
            "cb"              => "<input type=\"checkbox\" />",
            "title"           => "Title",
            $this->tax_id     => "Project Set"
        );
        $this->post_type = new PageLinesPostType( $this->custom_post_type, $args, $taxonomies, $columns, array(&$this, 'column_display') );
    }

    function column_display($column){
        global $post;
        switch ($column){
            case $this->tax_id:
                echo get_the_term_list($post->ID, $this->tax_id, '', ', ','');
                break;
        }
    }

    function meta_boxes( $meta_boxes ){
        $meta_boxes[] = array(
            'title' => __('Project Details','flatten'),
            'pages' => $this->custom_post_type,
            'fields' => array(
                array(
                    'id'   => $this->id.'_url',
                    'name' => __('Project URL','flatten'),
                    'type' => 'text_url',
                    'desc' => __( 'If the project is public add the URL.<br/>Please use full path http://', 'flatten'),
                    'cols' => 6
                ),
                array(
                    'id'   => $this->id.'_tags',
                    'name' =>  __('Project tags','flatten'),
                    'type' => 'text',
                    'desc' => __( 'Please add tags for the project, for multiple tags please use a comma separate', 'flatten'),
                    'cols' => 6
                ),
            )
        );
        return $meta_boxes;
    }

    function custom_get_posts( $custom_post, $tax_id, $set = null, $limit = null){
        $query              = array();
        $query['orderby']   = 'ID';
        $query['post_type'] = $custom_post;
        $query[ $tax_id ]   = $set;

        if(isset($limit)){
            $query['showposts'] = $limit;
        }

        $q = new WP_Query($query);

        if(is_array($q->posts))
            return $q->posts;
        else
            return array();
    }

    /**************************************************************************
    * Ajax Stuff
    **************************************************************************/
    function get_project()
    {
        $action  = $_POST['action'];
        $project = $_POST['project'];
        $oset  = array('post_id' => $project);
        if( $action == 'load_project' ){
            $out = array();
            $post = get_post($project);
            $out['title'] = $post->post_title;
            $out['content'] = apply_filters( 'the_content',$post->post_content);
            $out['image'] = get_the_post_thumbnail($project);
            $out['link'] = plmeta($this->id.'_url', $oset);
            $out['tags'] = explode(',', plmeta($this->id.'_tags', $oset));
            echo json_encode( $out );

        }
        die();
    }


}