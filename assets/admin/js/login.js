var Login = function() {

  var handleLogin = function() {
    $('.login-form').validate({
      errorElement: 'span', //default input error message container
      errorClass: 'help-block', // default input error message class
      focusInvalid: false, // do not focus the last invalid input
      rules: {
	      a_email: {
	        required: true
	      },
	      a_password: {
	        required: true
	      }
      },

      invalidHandler: function(event, validator) { //display error alert on form submit   

      },
      highlight: function(element) { // hightlight error inputs
	      $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
      },
      success: function(label) {
	      label.closest('.form-group').removeClass('has-error');
	      label.remove();
      },

      errorPlacement: function(error, element) {
      	//error.insertAfter(element.closest('.input-icon'));
      },

      submitHandler: function(form) {

	      $.ajax({
	        url: app_path+'admin/ajax/login',
	        type: 'post',
	        dataType: 'json',
	        data: new FormData($('#login-form')[0]),
	        cache: false,
	        contentType: false,
	        processData: false,
	        beforeSend: function() {
	        	$('#button-login').button('loading');
	        },
	        complete: function() {
	        	$('#button-login').button('reset');
	        },
	        success: function(json) {
		        if (json['error']) {
		          $('.form-error-area span').html(json['message']);
		          $('.form-error-area').removeClass('hide');
		          $('.form-group-cptcha').removeClass('hide');
		        }

		        if (json['success']) {
		          location = json['redirect'];
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
      handleLogin();
    }
  };
}();

jQuery(document).ready(function() {
  Login.init();
});

