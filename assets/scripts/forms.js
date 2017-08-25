var FormEvents = (function($) {

  return {
    init: function() {
      $('input').on('change keyup paste', function() {
        var input = $(this);
        var val = input.val();

        if(val.length > 0){
          input.addClass('has-value');
        }
        else {
          input.removeClass('has-value');
        }
      });
    }
  }

})(jQuery);

export default FormEvents;
