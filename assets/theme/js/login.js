var Login = function() {
  var handleLogin = function() {
  	var formLogin = $('#login-form');
	  var errorLogin = $('.alert-danger', formLogin);
	  var successLogin = $('.alert-success', formLogin);
    var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    var activeText = 'Login';

    formLogin.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      focusInvalid: false,
      rules: {
	      email: {
	        required: true
	      },
	      password: {
	        required: true
	      }
      },
      invalidHandler: function(event, validator) { //display error alert on form submit   
      	successLogin.addClass('d-none');
        errorLogin.removeClass('d-none');
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
        successLogin.addClass('d-none');
        errorLogin.addClass('d-none');

	      $.ajax({
	        url: app_path+'ajax/login',
	        type: 'post',
	        dataType: 'json',
	        data: new FormData($('#login-form')[0]),
	        cache: false,
	        contentType: false,
	        processData: false,
	        beforeSend: function() {
	        	$('#login-button').prop('disabled', true);
            $('#login-button').html(loadingText);
	        },
	        complete: function() {
	        },
	        success: function(json) {
		        if (json['success']) {
		          location = json['redirect'];
		        }

		        if (json['error']) {
              errorLogin.removeClass('d-none');
              $('.alert-danger > span', $('#login-form')).html(json['message']);
              $('#login-button').prop('disabled', false);
              $('#login-button').html(activeText);
            }
	        },
	        error: function(xhr, ajaxOptions, thrownError) {
	        	alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#login-button').prop('disabled', false);
            $('#login-button').html(activeText);
	        }
	      });

	      return false;
      }
    });
  }

  return {
    //main function to initiate the module
    init: function() {
      handleLogin();
    }
  };
}();

jQuery(document).ready(function() {
  Login.init();
});