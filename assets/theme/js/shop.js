'use strict';

var Shop = function() {

  var handleShopSellForm = function() {
    var formShopSellForm = $('#shop-sell-form');
    var errorShopSellForm = $('.alert-danger', formShopSellForm);
    var successShopSellForm = $('.alert-success', formShopSellForm);
    var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
    var activeText = 'Submit';

    formShopSellForm.validate({
      errorClass: 'is-invalid',
      validClass: 'is-valid',
      focusInvalid: false,
      rules: {
        title: {
          required: true
        },
        description: {
          required: true
        },
        shop_image: {
          required: true
        },
        location: {
          required: true
        },
        address: {
          required: true
        },
        contact_email: {
          required: true
        },
        contact_phone: {
          required: true
        },
        asking_price: {
          required: true
        },
        cash_flow: {
          required: true
        },
        gross_revenue: {
          required: true
        },
        inventory: {
          required: true
        },
        profit: {
          required: true
        },
        bays: {
          required: true
        },
        ffe: {
          required: true
        },
        debt: {
          required: true
        },
        real_estate: {
          required: true
        },
        year_establish: {
          required: true
        },
        employe: {
          required: true
        },
        content: {
          required: true
        }
      },
      invalidHandler: function(event, validator) { //display error alert on form submit   
        successShopSellForm.addClass('d-none');
        errorShopSellForm.removeClass('d-none');

        $([document.documentElement, document.body]).animate({
          scrollTop: $("#shop-sell-form").offset().top - 75
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
        successShopSellForm.addClass('d-none');
        errorShopSellForm.addClass('d-none');

        $.ajax({
          url: app_path+'sell-shop/request',
          type: 'post',
          dataType: 'json',
          data: new FormData($('#shop-sell-form')[0]),
          cache: false,
          contentType: false,
          processData: false,
          beforeSend: function() {
            $('#shop-sell-form-save-button').prop('disabled', true);
            $('#shop-sell-form-save-button').html(loadingText);
          },
          complete: function() {
          },
          success: function(json) {
            if (json['success']) {           
              location = json['redirect'];
            }

            if (json['error']) {
              errorShopSellForm.removeClass('d-none');
              $('.alert-danger > span').html(json['message']);
              $('.alert-danger').removeClass('d-none');
              $('#shop-sell-form-save-button').prop('disabled', false);
              $('#shop-sell-form-save-button').html(activeText);

              $([document.documentElement, document.body]).animate({
                scrollTop: $("#shop-sell-form").offset().top - 75
              }, 300);  
            }

            setTimeout(function(){
              $('.alert-danger').addClass('d-none');
              $('.alert-success').addClass('d-none');
            }, 5000);
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#shop-sell-form-save-button').prop('disabled', false);
            $('#shop-sell-form-save-button').html(activeText);
          }
        });

        return false;
      }
    });
  }

  return {
    //main function to initiate the module
    init: function() {
      handleShopSellForm();
    }
  };
}();

jQuery(document).ready(function() {
  Shop.init();

  var th = new showHideText('.shop-short-description', {
      charQty     : 210,
      ellipseText : "...",
      moreText    : "",
      lessText    : "Hide"
  });

  var shoptitle = new showHideText('.shop-title', {
      charQty     : 24,
      ellipseText : "...",
      moreText    : "",
      lessText    : "Hide"
  });

  /*$(document).delegate('.add-more-shop-image', 'click', function(e) {
    var elebtn    = $(this);
    var Key       = parseInt(elebtn.data('key'));
    var nextKey   = Key+1;
    var nextLabel = parseInt(nextKey)+1;
    var dHtml     = '';

    dHtml += '      <div class="mb-3 shop-image">';
    dHtml += '        <label for="shop-image-'+nextKey+'" class="form-label"></label>';
    dHtml += '        <div class="input-group">';
    dHtml += '         <input type="file" name="shop_image[]" id="shop-image-'+nextKey+'" class="form-control"> ';
    dHtml += '         <span class="input-group-text">';
    dHtml += '         <buttton type="button" class="btn-default delete-shop-image" data-key='+nextKey+'><i class="fa fa-trash"></i> Remove</buttton>';
    dHtml += '         </span>';
    dHtml += '        </div>';
    dHtml += '      </div>';

    $('#add-shop-image').append(dHtml);
    elebtn.data('key', nextKey);
  });

  $(document).delegate('.delete-shop-image', 'click', function(e) {
    $(this).parents('.shop-image').remove();
  });*/

  var shopImages = [];
  $(document).delegate('.attach-image', 'click', function() {

    if(shopImages.length > 4)
    {
      alert("You can upload only 5 images");
      return false;
    }
    else
    { 
      var skey    = parseInt($('#shop-image').data('key'));
      var nextKey = skey+1;
      var dHtml = '';
      dHtml += '<input type="file" class="d-none shop-image" accept="image/*" id="input-file-'+nextKey+'" name="shop_image[]" data-skey="'+nextKey+'">';
      $('#add-more-images-cst').append(dHtml);

      setTimeout(function(){
        $('#input-file-'+nextKey+'').click();
      },300);
    }
  });

  $(document).delegate('.shop-image', 'change', function() {

    var elebtn    = $(this);
    var nextKey   = parseInt(elebtn.data('skey'));
    var nextLabel = parseInt(nextKey)+1;

    var dHtml   = '';
    dHtml += ' <div class="col-md-4 sv-product-img shop-preview-image mb-1">';
    dHtml += '  <img src="" id="shop-preview-image-'+nextKey+'" class="img-thumbnail">';
    dHtml += '  <buttton type="button" class="btn btn-sm btn-danger delete-shop-preview-image" data-pkey='+nextKey+'><i class="fa fa-trash"></i></buttton>';
    dHtml += ' </div>';

    $('#add-more-images-cst').append(dHtml);

    var fileName = elebtn.val();
    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if(fileNameExt == "heic" || fileNameExt == "heif") {
      convertHeicToJpg(this, $('#shop-preview-image-'+nextKey+''));
    } else {
      readImageURL(this, $('#shop-preview-image-'+nextKey+''));
    }

    shopImages.push(nextKey);
    $('#shop-image').data('key',nextKey)
  });

  $(document).delegate('.delete-shop-preview-image', 'click', function(e) {

    var key    = $(this).data('pkey');
    var findex = shopImages.indexOf(key);

    shopImages.splice(findex,1);

    $('#input-file-'+key+'').remove();
    $(this).parents('.shop-preview-image').remove();
  });

  function readImageURL(input, previewContainer) {    
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        previewContainer.attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    } else {
      alert('Select a file to see preview!');
      previewContainer.attr('src', '');
    }
  }

  function convertHeicToJpg(input, previewContainer)
  {  
    var blob = $(input)[0].files[0]; //ev.target.files[0];
    heic2any({
        blob: blob,
        toType: "image/jpeg",
    })
    .then(function (resultBlob) {
      var url = URL.createObjectURL(resultBlob);
      previewContainer.attr('src', url); //previewing the uploaded picture
      //adding converted picture to the original <input type="file">
      let fileInputElement = $(input)[0];
      let container = new DataTransfer();
      let file = new File([resultBlob], "heic"+".jpg",{type:"image/jpeg", lastModified:new Date().getTime()});
      container.items.add(file);

      fileInputElement.files = container.files;
    })
    .catch(function (x) {
      alert('Select a file to see preview!');
      previewContainer.attr('src', '');
      console.log(x.code);
      console.log(x.message);
    });
  }
});

