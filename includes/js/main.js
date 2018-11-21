(function($) {
        


//Settings for lightbox
    var cbSettings = {
      rel: 'cboxElement',
      width: '95%',
      height: 'auto',
      fixed: true,
      maxWidth: '980',
      maxHeight: '800',
      title: function() {
      return $(this).find('img').attr('alt');
      },
      current: themeslug_script_vars.current,
      previous: themeslug_script_vars.previous,
      next: themeslug_script_vars.next,
      close: themeslug_script_vars.close,
      xhrError: themeslug_script_vars.xhrError,
      imgError: themeslug_script_vars.imgError
    }

    //Initialize jQuery Colorbox   
    $('.gallery a[href$=".jpg"], .gallery a[href$=".jpeg"], .gallery a[href$=".png"], .gallery a[href$=".gif"]').colorbox(cbSettings);
      
    //Keep lightbox responsive on screen resize
    $(window).on('resize', function() {
        $.colorbox.resize({
        width: window.innerWidth > parseInt(cbSettings.maxWidth) ? cbSettings.maxWidth : cbSettings.width
      }); 
    });

})(jQuery);