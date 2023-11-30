'use strict';

var Setting = function() {

  var handleGlobalForm = function() {
    
    var formGlobal = $('#global-form');
    var errorGlobal = $('.alert-danger', formGlobal);
    var successGlobal = $('.alert-success', formGlobal);
    var blockContent = $('.app-content-body');

    formGlobal.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successGlobal.addClass('hide');
        errorGlobal.removeClass('hide');
        scrollToUpper(errorGlobal, -120);
      },
      errorPlacement: function (error, element) {
        var cont = $(element).parent('.input');
        if (cont) {
          cont.after(error);
        } else {
          element.after(error);
        }
      },
      highlight: function (element) {

        $(element)
          .closest('.form-group').addClass('has-error');
      },
      unhighlight: function (element) {
        $(element)
          .closest('.form-group').removeClass('has-error');
      },
      success: function (label) {
        label
          .closest('.form-group').removeClass('has-error');
      },
      submitHandler: function (form) {
        //errorGlobal.addClass('hide');
        blockUI({target: blockContent, circle: true});

        var ajaxRequest = true;
        $.ajax({
          url: app_path+'admin/ajax/setting/global/save',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#global-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.button-user-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.button-user-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);
            
            if (json['success']) {
              errorGlobal.addClass('hide');
              successGlobal.removeClass('hide');
              scrollToUpper(successGlobal, -120);
            }
            if (json['redirect']) {
              location = json['redirect'];
            }
            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorGlobal.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorGlobal, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
        return false;
      }
    });
  }

  return {
    //main function to initiate the module
    init: function() {
      handleGlobalForm();
    }
  };
}();

jQuery(document).ready(function() {
  Setting.init();
});