'use strict';

var User = function() {
  var handleUserForm = function() {
    var formUser = $('#user-form');
    var errorUser = $('.alert-danger', formUser);
    var successUser = $('.alert-success', formUser);
    var blockContent = $('.app-content-body');

    formUser.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successUser.addClass('hide');
        errorUser.removeClass('hide');
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
          url: app_path+'admin/ajax/user/save',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#user-form')[0]),
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
              $('.alert-success > span').html(json['message']);
              errorUser.addClass('hide');
              successUser.removeClass('hide');
            }
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-user-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorUser.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorUser, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.button-user-save').button('reset');
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
      handleUserForm();
    }
  };
}();

jQuery(document).ready(function() {
  User.init();

  $(document).delegate('.btn-approve-user', 'click', function(el) {
    var elmBtn = $(this);
    var primaryValue = elmBtn.data('primary-value');
    var confirm = elmBtn.data('confirm');

    var blockContent = $('.app-content-body');
    if(primaryValue != '') {
      bootbox.confirm(confirm, function(result){
        if (result) {
          var ajaxRequest = true;
          if(ajaxRequest) {
            blockUI({target: blockContent, circle: true});
            $.ajax({
              url: app_path+'admin/ajax/set-user-approved',
              type: 'post',
              dataType: 'json',
              data: {primary_value : primaryValue},
              cache: false,
              beforeSend: function() {
                ajaxRequest = false;
                elmBtn.button('loading');
              },
              complete: function() {
                ajaxRequest = true;
                elmBtn.button('reset');
              },
              success: function(json) {
                unblockUI(blockContent);
                if(json['error']) {
                  $('.alert-danger').removeClass('hide');
                  $('.alert-danger > span').html(json['message']);
                }

                if(json['success']) {
                  location.reload();
                }

                if (json['redirect']) {
                  location.reload();
                }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });
          }
        }
      });
    }
  });

  $(document).delegate('.view-user-btn','click', function(e) {
    var elmBtn = $(this);
    var userId      = elmBtn.data('registration-value');
    var blockContent = $('.app-content-body');
    var ajaxRequest = true;
    if(ajaxRequest) {
      blockUI({target: blockContent, circle: true});
      $.ajax({
        url: app_path+'admin/ajax/get-registration-info',
        type: 'post',
        dataType: 'json',
        data: {user_id : userId},
        cache: false,
        beforeSend: function() {
          ajaxRequest = false;
        },
        complete: function() {
          ajaxRequest = true;
        },
        success: function(json) {
          unblockUI(blockContent);
          
          if(json['success']) {

            if(json['user_info']['access_code'] == 'V') {

              $('#approve-vendor-request-btn').data('primary-value',userId);
              $('#approve-vendor-request-btn').data('confirm','Are you sure for approve '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#delete-vendor-request-btn').data('primary-value',userId);
              $('#delete-vendor-request-btn').data('confirm','Are you sure for delete '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#view-vendor-name').html(json['user_info']['firstname'] +' '+ json['user_info']['lastname']);
              $('#view-vendor-email').html(json['user_info']['email']);
              $('#view-vendor-phone').html(json['user_info']['phone']);
              $('#view-vendor-position').html(json['user_info']['access_code_str']);
              $('#view-vendor-address').html(json['user_info']['address']);
              $('#view-vendor-city').html(json['user_info']['city']);
              $('#view-vendor-state').html(json['user_info']['state_name']);
              $('#view-vendor-zip-code').html(json['user_info']['zip_code']);

              $('#view-vendor-company-name').html(json['user_info']['company_name']);
              $('#view-vendor-selling-products').html(json['user_info']['selling_products']);
              $('#view-vendor-is-promotion').html(json['user_info']['is_promotion']);

              if(json['user_info']['is_promotion'] == 'yes'){
                $('#view-vendor-promotion-file').attr('src',json['user_info']['promotion_file']);
                $('.promotion-file-tr').removeClass('hide');
              }else {
                $('.promotion-file-tr').addClass('hide');
              }
              

              $('#view-vendor-data-modal').modal({show: true, keyboard: false, backdrop: 'static'});
            }

            if(json['user_info']['access_code'] == 'T') {

              $('#approve-technician-request-btn').data('primary-value',userId);
              $('#approve-technician-request-btn').data('confirm','Are you sure for approve '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#delete-technician-request-btn').data('primary-value',userId);
              $('#delete-technician-request-btn').data('confirm','Are you sure for delete '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#view-technician-name').html(json['user_info']['firstname'] +' '+ json['user_info']['lastname']);
              $('#view-technician-email').html(json['user_info']['email']);
              $('#view-technician-phone').html(json['user_info']['phone']);
              $('#view-technician-position').html(json['user_info']['access_code_str']);
              $('#view-technician-address').html(json['user_info']['address']);
              $('#view-technician-city').html(json['user_info']['city']);
              $('#view-technician-state').html(json['user_info']['state_name']);
              $('#view-technician-zip-code').html(json['user_info']['zip_code']);

              $('#view-technician-previous-company').html(json['user_info']['previous_company']);
              $('#view-technician-experience').html(json['user_info']['experience']);
              $('#view-technician-consider-yourself').html(json['user_info']['consider_yourself']);
              $('#view-technician-moderator').html(json['user_info']['moderator']);
              $('#view-technician-certificate').html(json['user_info']['certificate']);

              $('#view-technician-data-modal').modal({show: true, keyboard: false, backdrop: 'static'});
            }

            if(json['user_info']['access_code'] == 'O') {

              $('#approve-owner-request-btn').data('primary-value',userId);
              $('#approve-owner-request-btn').data('confirm','Are you sure for approve '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#delete-owner-request-btn').data('primary-value',userId);
              $('#delete-owner-request-btn').data('confirm','Are you sure for delete '+ json['user_info']['firstname'] +' '+ json['user_info']['lastname']+'?');

              $('#view-owner-name').html(json['user_info']['firstname'] +' '+ json['user_info']['lastname']);
              $('#view-owner-email').html(json['user_info']['email']);
              $('#view-owner-phone').html(json['user_info']['phone']);
              $('#view-owner-position').html(json['user_info']['access_code_str']);
              $('#view-owner-address').html(json['user_info']['address']);
              $('#view-owner-city').html(json['user_info']['city']);
              $('#view-owner-state').html(json['user_info']['state_name']);
              $('#view-owner-zip-code').html(json['user_info']['zip_code']);

              $('#view-owner-company-name').html(json['user_info']['company_name']);
              $('#view-owner-fax').html(json['user_info']['fax']);
              $('#view-owner-website').html(json['user_info']['website']);
              $('#view-owner-total-boys').html(json['user_info']['total_boys']);
              $('#view-owner-road-tech').html(json['user_info']['road_tech']);
              $('#view-owner-total-tech').html(json['user_info']['total_tech']);

              $('#view-owner-total-payroll').html('$'+json['user_info']['total_payroll']);
              $('#view-owner-m-gross-profit').html('$'+json['user_info']['m_gross_profit']);
              $('#view-owner-m-present-profit').html('$'+json['user_info']['m_present_profit']);
              $('#view-owner-shop-picture').attr('src',json['user_info']['shop_picture']);


              $('#view-owner-data-modal').modal({show: true, keyboard: false, backdrop: 'static'});
            }
          }

          if(json['error']){
            $('.alert-danger').removeClass('hide');
            $('.alert-danger > span').html(json['message']);
          }

          if (json['redirect']) {
            location.reload();
          }

          setTimeout(function(){
            $('.alert-danger').addClass('hide');
          }, 3000)
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    }
  });

  $(document).delegate('.btn-delete-user', 'click', function(el) {
    var elmBtn = $(this);
    var primaryValue = elmBtn.data('primary-value');
    var confirm = elmBtn.data('confirm');

    var blockContent = $('.app-content-body');
    if(primaryValue != '') {
      bootbox.confirm(confirm, function(result){
        if (result) {
          var ajaxRequest = true;
          if(ajaxRequest) {
            blockUI({target: blockContent, circle: true});
            $.ajax({
              url: app_path+'admin/ajax/delete-registration-request',
              type: 'post',
              dataType: 'json',
              data: {primary_value : primaryValue},
              cache: false,
              beforeSend: function() {
                ajaxRequest = false;
                elmBtn.button('loading');
              },
              complete: function() {
                ajaxRequest = true;
                elmBtn.button('reset');
              },
              success: function(json) {
                unblockUI(blockContent);
                if(json['error']) {
                  $('.alert-danger').removeClass('hide');
                  $('.alert-danger > span').html(json['message']);
                }

                if(json['success']) {
                  location.reload();
                }

                if (json['redirect']) {
                  location.reload();
                }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
            });
          }
        }
      });
    }
  });

  $(document).delegate('#input-user-salary-type', 'change', function(el) {
    var salaryType = $(this).val();

    if (salaryType == 'hourly') {
      $('#salary-per-hour').removeClass('hide');
      $('#annual-salary').addClass('hide');
    } else {
      $('#annual-salary').removeClass('hide');
      $('#salary-per-hour').addClass('hide');
    }
  });

  $(document).delegate('#input-user-status', 'change', function(el) {
    var userStatus = $(this).val();

    if (userStatus == '0') {
      $('input:checkbox').prop('checked', false).prop('disabled', true);
    } else {
      $('input:checkbox').prop('disabled', false);
    }
  });

  $(document).delegate('#check-all', 'click', function(el) {
    $('input:checkbox').prop('checked', true);
  });
});

