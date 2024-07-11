(($) => {
  $(() => {
    // $('*').on('focus', (e)=> console.log('focused', e.target)); //debug
    $("#skip-to-content>a").on("keydown", function (e) {
      var code = e.keyCode || e.which;
      if (code == 13)
        //Enter
        $("#main-content a:visible, #main-content button:visible")
        .not('.dsm_breadcrumbs_wrap a')
        .not('.dsm_breadcrumbs_wrap button')
        .first().focus();
    });
  });
})(jQuery);
