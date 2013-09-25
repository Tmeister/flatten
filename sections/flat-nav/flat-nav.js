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

    $('#page-main').css({'margin-top': $('.section-flat-nav').height()+'px'});

    $(window).scroll(function(event) {

            var newHeight = 100 - ($(window).scrollTop());
            var newPadding = ( 40 - ($(window).scrollTop()) / 2);
            newPadding = ( newPadding < 20 ) ? 20 : newPadding;
            var newAlpha = (newPadding + 60) / 100;
            var color = (newAlpha < 1) ? '240, 242, 244' : '255, 255, 255';
            var border = (newAlpha < 1) ? '#ddd' : '#fff';
            $('.flat-logo img').height(newHeight);
            $('.flat-menu > li > a').css({'padding': newPadding+'px 15px'});
            $('.section-flat-nav').css({'background': 'rgba('+color+', '+newAlpha+')', 'border-bottom': '1px solid '+border} );
    });


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
            $submenu.css({'left': (maxLeft - $el.position().left) - $submenu.width()});
            $submenu.find('>li>a').css({'text-align': 'right'});
            $submenu.find('li').each(function(index, el) {
                $li = $(el);
                if( $li.find('ul').length ){
                    $li.addClass('grandchild toleft');
                }
            });
        }
    });
  });

    return this;
};
$.fn.smartFlatMenu.defaults = {
    'default': '1'
};

})(jQuery);
