var Forum = function() {
  var handleForumForm = function() {
    var formForum = $('#forum-form');
    var errorForum = $('.alert-danger', formForum);
    var successForum = $('.alert-success', formForum);
    var blockContent = $('.app-content-body');

    formForum.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successForum.addClass('hide');
        errorForum.removeClass('hide');
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

        var forumFormData = new FormData($('#forum-form')[0]);
        forumFormData.append('description', $('#input-forum-description').html());

        $.ajax({
          url: app_path+'admin/ajax/forum/save',
          type: 'post',
          dataType: 'json',
          data: forumFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.btn-forum-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.btn-forum-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorForum.addClass('hide');
              successForum.removeClass('hide');
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-forum-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorForum.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorForum, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.btn-forum-save').button('reset');
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
      handleForumForm();
    }
  };
}();

jQuery(document).ready(function() {
  Forum.init();

  var categoryName = '';
  var forumCounter = 0;

  // $('select.allow-custom-input').on('chosen:no_results', function(event, parameters) {
  //   categoryName = parameters.chosen.search_results[0].textContent.match(/Click here to add new entry: "(.+)"/)[1];
  // });

  // $('.container-chosen').delegate('li.no-results', 'click', function(el) {
  //   $('#input-forum-category').attr('required', false);
  //   $('#input-forum-category').val('');
  //   $('#input-forum-category-name').val(categoryName);
  //   $('#input-forum-category-name').attr('required', true);
  //   $('.container-chosen').addClass('hide');
  //   $('.container-category').removeClass('hide');
  // });

  $('.container-category').delegate('.btn-chosen-category', 'click', function(el) {
    $('#input-forum-category-name').val('');
    $('#input-forum-category-name').attr('required', false);
    $('#input-forum-category').attr('required', true);
    $('.container-category').addClass('hide');
    $('.container-chosen').removeClass('hide');
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
                $('#preview-forum-image').attr('src', app_path+'assets/admin/img/no-preview-available.png');
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

  $(document).on('click', '.confirm-remove-video', function(e){
    e.preventDefault();
    var confirmLink = $(this).data('href');
    var confirmText = $(this).data('confirm');
    var confirmIndex = $(this).data('video-index');
    bootbox.confirm(confirmText, function(result){
      if (result) {
        $.ajax({
          url: confirmLink,
          type: 'DELETE',
          success: function(json) {
            if (json['success']) {
              $('#video-index-'+confirmIndex).remove();
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

  $("#input-forum-image").change(function() {
    var fileName = $(this).val();
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if(fileNameExt == "heic" || fileNameExt == "heif") {
      convertHeicToJpg(this, $('#preview-forum-image'));
    } else {
      readImageURL(this, $('#preview-forum-image'));
    }
  });
});

