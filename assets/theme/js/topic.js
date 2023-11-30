'use strict';

var Topic = function() {

  var handleTopicForm = function() {
    var formTopicForm = $('#topic-form');
    var errorTopicForm = $('.alert-danger', formTopicForm);
    var successTopicForm = $('.alert-success', formTopicForm);
    var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    var activeText = 'Submit';

    formTopicForm.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      focusInvalid: false,
      rules: {
        title: {
          required: true
        },
        description: {
          required: true
        }
      },
      invalidHandler: function(event, validator) { //display error alert on form submit   
        successTopicForm.addClass('d-none');
        errorTopicForm.removeClass('d-none');
      },
      highlight: function(element) { // hightlight error inputs
        $(element).removeClass('is-valid');
        $(element).addClass('is-invalid');
      },
      success: function(label) {

      },
      errorPlacement: function(error, element) {
        
      },
      submitHandler: function(form) {
        successTopicForm.addClass('d-none');
        errorTopicForm.addClass('d-none');

        $.ajax({
          url: app_path+'forum/topic/request',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#topic-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#topic-form-save-button').prop('disabled', true);
            $('#topic-form-save-button').html(loadingText);
          },
          complete: function() {
          },
          success: function(json) {
            if (json['success']) {           
              location = json['redirect'];
            }

            if (json['error']) {
              errorTopicForm.removeClass('d-none');
              $('.alert-danger > span').html(json['message']);
              $('.alert-danger').removeClass('d-none');
              $('#topic-form-save-button').prop('disabled', false);
              $('#topic-form-save-button').html(activeText);
            }

            setTimeout(function(){
              $('.alert-danger').addClass('d-none');
              $('.alert-success').addClass('d-none');
            }, 5000);
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#topic-form-save-button').prop('disabled', false);
            $('#topic-form-save-button').html(activeText);
          }
        });

        return false;
      }
    });
  }

  var handleConversationForm = function() {
    var formConversationForm = $('#conversation-form');
    var errorConversationForm = $('.alert-danger', formConversationForm);
    var successConversationForm = $('.alert-success', formConversationForm);
    var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    var activeText = 'Post';

    formConversationForm.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      focusInvalid: false,
      rules: {
        title: {
          required: true
        },
        description: {
          required: true
        }
      },
      invalidHandler: function(event, validator) { //display error alert on form submit   
        successConversationForm.addClass('d-none');
        errorConversationForm.removeClass('d-none');
      },
      highlight: function(element) { // hightlight error inputs
        $(element).removeClass('is-valid');
        $(element).addClass('is-invalid');
      },
      success: function(label) {

      },
      errorPlacement: function(error, element) {
        
      },
      submitHandler: function(form) {
        successConversationForm.addClass('d-none');
        errorConversationForm.addClass('d-none');

        $.ajax({
          url: app_path+'forum/topic/conversation',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#conversation-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#conversation-form-save-button').prop('disabled', true);
            $('#conversation-form-save-button').html(loadingText);
          },
          complete: function() {
          },
          success: function(json) {
            if (json['success']) {
              $('#input-conversation-comment').val('');
              $('.alert-success > span').html(json['message']);
              errorConversationForm.addClass('d-none');
              successConversationForm.removeClass('d-none');
              $('#topic-conversation').prepend(json['conversation']);
              $('.count-conversation').text(json['count_conversations']);
              $('#conversation-form-save-button').prop('disabled', false);
              $('#conversation-form-save-button').html(activeText);
              setTimeout(function(){
                successConversationForm.addClass('d-none');
              }, 3000);
            }

            if (json['redirect']) {
              location = json['redirect'];
            }

            if (json['error']) {
              errorConversationForm.removeClass('d-none');
              $('.alert-danger > span').html(json['message']);
              $('.alert-danger').removeClass('d-none');
              $('#conversation-form-save-button').prop('disabled', false);
              $('#conversation-form-save-button').html(activeText);
            }

            setTimeout(function(){
              $('.alert-danger').addClass('d-none');
              $('.alert-success').addClass('d-none');
            }, 5000);
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#conversation-form-save-button').prop('disabled', false);
            $('#conversation-form-save-button').html(activeText);
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
      handleConversationForm();
    }
  };
}();

jQuery(document).ready(function() {
  Topic.init();
});

