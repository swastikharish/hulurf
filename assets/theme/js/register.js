'use strict';

var Register = function() {

  var handleUserForm = function() {
    var formSignup = $('#signup-form');
    var errorSignup = $('.alert-danger', formSignup);
    var successSignup = $('.alert-success', formSignup);
    var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    var activeText = 'Create Account';

    formSignup.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      focusInvalid: false,
      rules: {
        name: {
          required: true
        },
        phone: {
          required: true
        },
        email: {
          email: true,
          remote: {
            url: app_path+'email/validate',
            type: "post"
          }
        },
        password: {
          required: true
        }
      },
      invalidHandler: function(event, validator) { //display error alert on form submit   
        successSignup.addClass('d-none');
        errorSignup.removeClass('d-none');

        $([document.documentElement, document.body]).animate({
          scrollTop: $("#signup-start").offset().top - 75
        }, 300);
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
        successSignup.addClass('d-none');
        errorSignup.addClass('d-none');

        $.ajax({
          url: app_path+'register',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#signup-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#signup-form-save-button').prop('disabled', true);
            $('#signup-form-save-button').html(loadingText);
          },
          complete: function() {
          },
          success: function(json) {
            if (json['success']) {
              location = json['redirect'];
            }

            if (json['error']) {
              $('.alert-danger', $('#signup-form')).removeClass('d-none');
              $('.alert-danger > span', $('#signup-form')).html(json['message']);
              $('#signup-form-save-button').prop('disabled', false);
              $('#signup-form-save-button').html(activeText);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#signup-form-save-button').prop('disabled', false);
            $('#signup-form-save-button').html(activeText);
          }
        });

        return false;
      }
    });
  }

  return {
    //main function to initiate the module
    init: function() {
      handleUserForm();
    }
  };
}();

jQuery(document).ready(function() {
  Register.init();
});

