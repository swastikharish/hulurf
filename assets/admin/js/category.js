'use strict';

var Category = function() {
  var handleCategoryForm = function() {
    var formCategory = $('#category-form');
    var errorCategory = $('.alert-danger', formCategory);
    var successCategory = $('.alert-success', formCategory);
    var blockContent = $('.app-content-body');

    formCategory.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successCategory.addClass('hide');
        errorCategory.removeClass('hide');
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
        blockUI({target: blockContent, circle: true});
        var ajaxRequest = true;
        $.ajax({
          url: app_path+'admin/ajax/category/save',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#category-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.button-category-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.button-category-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorCategory.addClass('hide');
              successCategory.removeClass('hide');
            }

            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-category-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorCategory.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorCategory, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.button-category-save').button('reset');
            unblockUI(blockContent);
          }
        });
        return false;
      }
    });
  }
  return {
    //main function to initiate the module
    init: function() {
      handleCategoryForm();
    }
  };
}();

jQuery(document).ready(function() {
  Category.init();
});

