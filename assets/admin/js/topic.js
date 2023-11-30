var Topic = function() {
  var handleTopicForm = function() {
    var formTopic = $('#topic-form');
    var errorTopic = $('.alert-danger', formTopic);
    var successTopic = $('.alert-success', formTopic);
    var blockContent = $('.app-content-body');

    formTopic.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successTopic.addClass('hide');
        errorTopic.removeClass('hide');
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

        var topicFormData = new FormData($('#topic-form')[0]);
        topicFormData.append('description', $('#input-topic-description').html());

        $.ajax({
          url: app_path+'admin/ajax/topic/save',
          type: 'post',
          dataType: 'json',
          data: topicFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.btn-topic-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.btn-topic-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorTopic.addClass('hide');
              successTopic.removeClass('hide');
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-topic-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorTopic.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorTopic, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.btn-topic-save').button('reset');
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
      handleTopicForm();
    }
  };
}();

var Conversation = function() {
  var handleConversationForm = function() {
    var formConversation = $('#conversation-form');
    var errorConversation = $('.alert-danger', formConversation);
    var successConversation = $('.alert-success', formConversation);
    var blockContent = $('.app-content-body');

    formConversation.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successConversation.addClass('hide');
        errorConversation.removeClass('hide');
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

        var conversationFormData = new FormData($('#conversation-form')[0]);

        $.ajax({
          url: app_path+'admin/ajax/conversation/save',
          type: 'post',
          dataType: 'json',
          data: conversationFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.btn-conversation-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.btn-conversation-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('#input-conversation-comment').val('');
              $('.alert-success > span').html(json['message']);
              errorConversation.addClass('hide');
              successConversation.removeClass('hide');
              $('#topic-conversation').prepend(json['conversation']);
              $('.count-conversation').text(json['count_conversations']);
              setTimeout(function(){
                successConversation.addClass('hide');
              }, 3000);
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-conversation-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorConversation.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.btn-conversation-save').button('reset');
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
      handleConversationForm();
    }
  };
}();

jQuery(document).ready(function() {
  Topic.init();
  Conversation.init();

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
                $('#preview-topic-image').attr('src', app_path+'assets/admin/img/no-preview-available.png');
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

  $(document).on('click', '.confirm-remove-pdf', function(e){
    e.preventDefault();
    var confirmElm = $(this);
    var confirmLink = $(this).data('href');
    var confirmText = $(this).data('confirm');
    bootbox.confirm(confirmText, function(result){
      if (result) {
        $.ajax({
          url: confirmLink,
          type: 'DELETE',
          success: function(json) {
            if (json['success']) {
              confirmElm.parents('.pdf-row').remove();
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

  $("#input-topic-image").change(function() {
    var fileName = $(this).val();
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if(fileNameExt == "heic" || fileNameExt == "heif") {
      convertHeicToJpg(this, $('#preview-topic-image'));
    } else {
      readImageURL(this, $('#preview-topic-image'));
    }
  });
});

