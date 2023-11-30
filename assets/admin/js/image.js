'use strict';

var Banner = function() {
  var handleBannerForm = function() {
    var formBanner = $('#banner-form');
    var errorBanner = $('.alert-danger', formBanner);
    var successBanner = $('.alert-success', formBanner);
    var blockContent = $('.app-content-body');

    formBanner.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successBanner.addClass('hide');
        errorBanner.removeClass('hide');
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

        var bannerFormData = new FormData($('#banner-form')[0]);
        //bannerFormData.append('description', $('#input-banner-description').html());

        $.ajax({
          url: app_path+'admin/ajax/banner/save',
          type: 'post',
          dataType: 'json',
          data: bannerFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.button-banner-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.button-banner-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorBanner.addClass('hide');
              successBanner.removeClass('hide');
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-banner-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorBanner.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorBanner, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.button-banner-save').button('reset');
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
      handleBannerForm();
    }
  };
}();

jQuery(document).ready(function() {
  Banner.init();

  $(document).on('click', '.confirm-remove-image', function(e){
    e.preventDefault();
    var confirmLink = $(this).data('href');
    var confirmText = $(this).data('confirm');
    var confirmImageType = $(this).data('image-type');
    bootbox.confirm(confirmText, function(result){
      if (result) {
        $.ajax({
          url: confirmLink,
          type: 'DELETE',
          success: function(json) {
            if (json['success']) {
              if (confirmImageType == 'image') {
                $('#preview-banner-image').attr('src', app_path+'assets/admin/img/no-preview-available.png');
                $('*[data-image-type="image"]').remove();
              }
              alert(json['message']);
            }

            if (Object.keys(json['error']).length > 0) {
              alert(json['message']);
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }
          }
        });
      }
    });   
  });

  $("#input-banner-image").change(function() {
    var fileName = $(this).val();
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if(fileNameExt == "heic" || fileNameExt == "heif") {
      convertHeicToJpg(this, $('#preview-banner-image'));
    } else {
      readImageURL(this, $('#preview-banner-image'));
    }
  });
});

