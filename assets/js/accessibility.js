(($) => {
  $(() => {
    // $('*').on('focus', (e)=> console.log('focused', e.target)); //debug
    $("#skip-to-content>a").on("keydown", function (e) {
      var code = e.keyCode || e.which;
      if (code == 13) //Enter
        $("#main-content a, #main-content button").first().focus();
    });
  });
})(jQuery);
