jQuery(document).ready(function($) {

    var current
    ,   fTimeOut;

    $currentProject = null;
    window.loadingproject = false;
    noScroll = false;

    jQuery('.boxes-wrapper').isotope();

    /**************************************************************************
    * Filters tags
    **************************************************************************/

    current = jQuery('.tags-filter li.active');

    $filter = jQuery('.tags-filter');

    jQuery('.tags-filter li').on('click', function(event) {
        event.preventDefault();

        if( current.data('filter') == $(this).data('filter') ){ return }

        current.removeClass('active');
        $(this).addClass('active');

        current = $(this);

        filter = '.'+$(this).data('filter');
        jQuery('.boxes-wrapper').isotope({filter:filter});

    });

    $filter.find('li:not(.active)').hide();

    $filter.hover(
        function(){
            clearTimeout(fTimeOut);
            fTimeOut=setTimeout(
                function(){$filter.find('li:not(.active)').stop(true, true).animate({width: 'show' }, 100, 'linear'); }, 250);
        },function(){

            clearTimeout(fTimeOut);

            fTimeOut=setTimeout(function(){ $filter.find('li:not(.active)').stop(true, true).animate({width: 'hide' }, 100, 'linear'); }, 100);
    });

    /**************************************************************************
    * Ajax Projects
    **************************************************************************/

    $projectDetails = jQuery('.pf-content');

    /**************************************************************************
    * Navigation
    **************************************************************************/

    $projectDetails.find('.navclose').on('click', function(event) {
        event.preventDefault();
        $projectDetails.slideUp('slow');
    });

    $projectDetails.find('.arrowleft').on('click', function(event) {
        event.preventDefault();
        if( $currentProject.prev().length ){
            noScroll = true;
            $currentProject.prev().trigger('click');
        }
    });

    $projectDetails.find('.arrowright').on('click', function(event) {
        event.preventDefault();
        if( $currentProject.next().length ){
            noScroll = true;
            $currentProject.next().trigger('click');
        }

    });

    /**************************************************************************
    * Project handler
    **************************************************************************/

    jQuery('.boxes-wrapper .project').on('click', function(event) {
        event.preventDefault();
        $project = $(this);
        id = $project.data('postid');
        if( $currentProject != null ){
            if( $currentProject.data('postid') ==  id){
                return;
            }
        }
        $currentProject = $project;
        if(!noScroll){
            $('html,body').animate({scrollTop: ( $("#filters-anchor").offset().top) - 93},'slow', function(){
                $projectDetails.slideUp('slow', function(){
                    loadNewItem( id );
                });
            })
            noScroll = false;
        }else{
            $projectDetails.slideUp('slow', function(){
                loadNewItem( id );
            });
            noScroll = false;
        }

    });

    function createNewItem(data){
        $projectDetails.find('.media').html(data.image);
        $projectDetails.find('.text-wrapper h3').html(data.title);
        $projectDetails.find('.text-wrapper .description').html(data.content);
        if(data.link.length){
            $projectDetails.find('.text-wrapper .go-project a').show().attr('href', data.link);
        }else{
            $projectDetails.find('.text-wrapper .go-project a').hide()
        }
        $projectDetails.find('.text-wrapper .go-project a').attr('href', data.link);

        $tags = $projectDetails.find('.ptags');
        $tags.html('');

        jQuery.each(data.tags, function(index, el) {
            $tags.append(jQuery('<li/>').append(jQuery('<i/>').addClass('icon-long-arrow-right')).append(' '+el));
        });
        $projectDetails.slideDown('slow', function(){
            window.loadingproject = false;
        });



    }

    function loadNewItem(id){
        if(!window.loadingproject){
            window.loadingproject = true;
            jQuery.ajax({
                url: adminUrl+'wp-admin/admin-ajax.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    'action' : 'load_project',
                    'project' : id
                }
            })
            .done(function(data) {
                createNewItem(data);
            })
            .fail(function(data) {
                console.log("error");
                console.log(data)
                window.loadingproject = false;
            })
        }
    }

});