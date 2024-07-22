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
    $("#skip-to-language>a").on("keydown", function (e) {
      var code = e.keyCode || e.which;
      var link = $("#mega-menu-item-wpml-ls-9-it  a:visible");
      if (code == 13){
        if(link.length < 2){
          $("#mega-menu-item-wpml-ls-9-en  a:visible").first().focus();
        }else{
          $("#mega-menu-item-wpml-ls-9-it a:visible").first().focus();
        }
      }
    });
      
        
    // Accessibilty on social icon
    $("#skh_social_button > li > a").attr("role", "button");
  });
})(jQuery);
