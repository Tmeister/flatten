(function($) {
$.fn.smartFlatMenu = function(options) {

  if (!this.length) { return this; }

  var opts = $.extend(true, {}, $.fn.smartFlatMenu.defaults, options);

  this.each(function() {
    var $this = $(this);
    var $menu = $this.find('.flat-menu');
    var lastItems = $menu.find('>:nth-last-child(-n+3)');
    var lastItem =  $menu.find('>:nth-last-child(-n+1)');
    var maxLeft =  $(lastItem).position().left +  $(lastItem).width();
    var windowHeight;
    var currentMobileItem = false;
    var currentMobileSubItem = false;

    /* Scroll handle to change the menu size*/

    $(window).scroll(function(event) {

            if(window.loadingproject){return}

            var newHeight = 100 - ($(window).scrollTop());
            var newPadding = ( 40 - ($(window).scrollTop()) / 2);
            newPadding = ( newPadding < 20 ) ? 20 : newPadding;
            var newAlpha = (newPadding + 65) / 100;
            var color = (newAlpha < 1) ? '240, 242, 244' : '255, 255, 255';
            var border = (newAlpha < 1) ? '#ddd' : '#fff';
            $('.flat-logo img').height(newHeight);
            $('.flat-menu > li > a').css({'padding': newPadding+'px 15px'});
            $('.mobile-icon').css({'margin': newPadding+'px 0px'});
            $('.section-flat-nav').css({'background': 'rgba('+color+', '+newAlpha+')', 'border-bottom': '1px solid '+border} );
    });

    /* Align the sub-menus */

    $.each(lastItems, function(index, val) {
        $el = $(this);
        $submenu = $el.find('>.sub-menu');

        $submenu.find('li').each(function(index, el) {
            $li = $(el);
            if( $li.find('ul').length ){
                $li.addClass('grandchild');
            }
        });

        if( ( $el.position().left + $submenu.width() ) > maxLeft ){
            $submenu.css({'left': ($submenu.width()*-1) + $el.width()});
            $submenu.find('>li>a').css({'text-align': 'right'});
            $submenu.find('li').each(function(index, el) {
                $li = $(el);
                if( $li.find('ul').length ){
                    $li.addClass('grandchild toleft');
                }
            });
        }
    });

    /* Clone the Main menu*/
    $this.windowHeight = $(window.top).height();
    $('<div/>').attr('id', 'flat-mobile-menu-holder')
        .css({'min-height': $this.windowHeight})
        .prependTo('body');

    $menu.clone()
        .removeClass('flat-menu')
        .attr('id', 'main-mobile-menu')
        .appendTo('#flat-mobile-menu-holder');

    $('#mobile-menu-template').clone().attr('id', 'mobile-menu-template').removeClass('remove-it').prependTo('#flat-mobile-menu-holder');
    $('.remove-it').remove();

    /*Icons*/

    $('#flat-mobile-menu-holder > ul > li').each(function(index, el) {
        $el = $(el)
        var matches = $el.attr('class').match("icon-(.*?) ");
        $el.find('a').contents().wrap('<span/>')
        if( matches ){
            $el.removeClass(matches[0]);
            $('<i/>').addClass(matches[0]).prependTo( $el.find('>a') );
        }
    });

    $('li', $menu).removeClass (function (index, css) {
        return (css.match (/\bicon-\S+/g) || []).join(' ');
    });

    $('.section-simple_nav li').removeClass (function (index, css) {
        return (css.match (/\bicon-\S+/g) || []).join(' ');
    });


    /*Handler Mobile Menu*/

    $('.mobile-icon').toggle(function() {
        $('#flat-mobile-menu-holder').animate({'left': '0px'}, 250);
    }, function() {
        $('#flat-mobile-menu-holder').animate({'left': '-300px'}, 250);
    });

    $('#flat-mobile-menu-holder ul ul, #flat-mobile-menu-holder ul ul ul').hide().slideUp();

    /*
    * Level One Sub Menu
    */

    $('#flat-mobile-menu-holder > ul > li:has(ul)' ).on('click', function(event) {

        var item = $( this );
        if( item[ 0 ] != currentMobileItem[ 0 ] )
        {
            event.preventDefault();
            if( currentMobileItem ){
                currentMobileItem.find('>ul').slideUp('slow')
            }
            currentMobileItem = item;
            $(this).find('>ul').slideDown('slow');
        }
    });

    /*
    * Level Two Submenu
    */
    $('#flat-mobile-menu-holder ul ul > li:has(ul)' ).on('click', function(event) {
        var item = $( this );
        if( item[ 0 ] != currentMobileSubItem[ 0 ] )
        {
            event.preventDefault();
            if( currentMobileSubItem ){
                currentMobileSubItem.find('>ul').slideUp('slow')
            }
            currentMobileSubItem = item;
            $(this).find('>ul').slideDown('slow');
        }
    });

    $('#page-main').css({'margin-top': $('.section-flat-nav').height()+'px'});

  });

    return this;
};
$.fn.smartFlatMenu.defaults = {
    'default': '1'
};

})(jQuery, window, document);

