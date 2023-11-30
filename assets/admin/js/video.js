'use strict';

var Video = function() {
  var handleVideoForm = function() {
    var formVideo = $('#video-form');
    var errorVideo = $('.alert-danger', formVideo);
    var successVideo = $('.alert-success', formVideo);
    var blockContent = $('.app-content-body');

    formVideo.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      rules: {
        url: {
          url: true
        }
      },
      invalidHandler: function (event, validator) {
        successVideo.addClass('hide');
        errorVideo.removeClass('hide');
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

        var videoFormData = new FormData($('#video-form')[0]);

        $.ajax({
          url: app_path+'admin/ajax/video/save',
          type: 'post',
          dataType: 'json',
          data: videoFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.button-video-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.button-video-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorVideo.addClass('hide');
              successVideo.removeClass('hide');
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-video-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorVideo.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorVideo, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.button-video-save').button('reset');
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
      handleVideoForm();
    }
  };
}();

jQuery(document).ready(function() {
  Video.init();

  $("#input-video-image").change(function() {
    var fileName = $(this).val();
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if(fileNameExt == "heic" || fileNameExt == "heif") {
      convertHeicToJpg(this, $('#preview-video-image'));
    } else {
      readImageURL(this, $('#preview-video-image'));
    }
  });

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
                $('#preview-video-image').attr('src', app_path+'assets/admin/img/no-preview-available.png');
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
});

