var Page = function() {
  var handlePageForm = function() {
    var formPage = $('#page-form');
    var errorPage = $('.alert-danger', formPage);
    var successPage = $('.alert-success', formPage);
    var blockContent = $('.app-content-body');

    formPage.validate({
      errorElement: 'span',
      errorClass: 'help-block help-block-error',
      focusInvalid: false,
      ignore: "",
      invalidHandler: function (event, validator) {
        successPage.addClass('hide');
        errorPage.removeClass('hide');
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

        var pageFormData = new FormData($('#page-form')[0]);
        pageFormData.append('description', $('#input-page-description').html());

        $.ajax({
          url: app_path+'admin/ajax/page/save',
          type: 'post',
          dataType: 'json',
          data: pageFormData,
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            ajaxRequest = false;
            $('.btn-page-save').button('loading');
          },
          complete: function() {
            ajaxRequest = true;
            $('.btn-page-save').button('reset');
          },
          success: function(json) {
            unblockUI(blockContent);

            if (json['success']) {
              $('.alert-success > span').html(json['message']);
              errorPage.addClass('hide');
              successPage.removeClass('hide');
            }
            
            if (json['redirect']) {
              location = json['redirect'];
            }

            if (Object.keys(json['error']).length > 0) {
              for (var i in json['error']) {
                var element = $('#input-page-' + i.replace('_', '-'));
                $(element)
                  .closest('.form-group').removeClass('has-success').addClass('has-error');
              }
              errorPage.removeClass('hide');
              $('.alert-danger > span').html(json['message']);
              scrollToUpper(errorPage, -120);
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            ajaxRequest = true;
            $('.btn-page-save').button('reset');
            unblockUI(blockContent);
          }
        });
        return false;
      }
    });
  }

  var handlePageList = function() {

    $(document).delegate('.delete-page-btn', 'click', function(el) {
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
                url: app_path+'admin/ajax/delete-page',
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
  }

  return {
    //main function to initiate the module
    init: function() {
      handlePageForm();
      handlePageList();
    }
  };
}();

jQuery(document).ready(function() {
  Page.init();
});

