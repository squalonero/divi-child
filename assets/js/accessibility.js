(($) => {
  $(() => {
    // $('*').on('focus', (e)=> console.log('focused', e.target)); //debug
    $("#skip-to-content>a").on("keydown", function (e) {
      var code = e.keyCode || e.which;
      if (code == 13)
        //Enter
        $("#main-content a, #main-content button").first().focus();
    });

    /**
     * @WORKAROUND
     * remove bad accessibility attributes from Divi head tags
     * width=device-width, initial-scale=1.0, maximum-scale=1.0
     */
    $('meta[name="viewport"]').attr({content: 'width=device-width, initial-scale=1.0, maximum-scale=1.0'});
  });
})(jQuery);
