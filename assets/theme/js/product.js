'use strict';

var Product = function() {

	var handleProductSellForm = function() {
		var formPart    = $('#part-sell-form');
		var errorPart   = $('.alert-danger', formPart);
		var successPart = $('.alert-success', formPart);
		var loadingText = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
		var activeText  = 'Submit';

		formPart.validate({
		  errorClass: 'is-invalid',
		  validClass: 'is-valid',
		  focusInvalid: false,
		  rules: {
		    category_id: {
		      required: true
		    },
		    title: {
		      required: true
		    },
		    short_description: {
		      required: true
		    },
		    description: {
		      required: true
		    },
		    price: {
		      required: true
		    },
		    contact_email: {
		      required: true
		    },
		    contact_phone: {
		      required: true
		    },
		    location: {
		      required: true
		    },
		    specifications_label: {
		      required: true
		    },
		    specifications_text: {
		      required: true
		    },
		    features: {
		      required: true
		    }
		  },
		  invalidHandler: function(event, validator) { //display error alert on form submit   
		    successPart.addClass('d-none');
		    errorPart.removeClass('d-none');

		    $([document.documentElement, document.body]).animate({
		      scrollTop: $("#part-sell-form").offset().top - 75
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
		    successPart.addClass('d-none');
		    errorPart.addClass('d-none');

		    $.ajax({
		      url: app_path+'sell-part/request',
		      type: 'post',
		      dataType: 'json',
		      data: new FormData($('#part-sell-form')[0]),
		      cache: false,
		      contentType: false,
		      processData: false,
		      beforeSend: function() {
		        $('#part-sell-form-save-button').prop('disabled', true);
		        $('#part-sell-form-save-button').html(loadingText);
		      },
		      complete: function() {
		      },
		      success: function(json) {
		        if (json['success']) {           
		          location = json['redirect'];
		        }

		        if (json['error']) {
		          errorPart.removeClass('d-none');
		          $('.alert-danger > span').html(json['message']);
		          $('.alert-danger').removeClass('d-none');
		          $('#part-sell-form-save-button').prop('disabled', false);
		          $('#part-sell-form-save-button').html(activeText);

		          $([document.documentElement, document.body]).animate({
		            scrollTop: $("#part-sell-form").offset().top - 75
		          }, 300);  
		        }

		        setTimeout(function(){
		          $('.alert-danger').addClass('d-none');
		          $('.alert-success').addClass('d-none');
		        }, 5000);
		      },
		      error: function(xhr, ajaxOptions, thrownError) {
		        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		        $('#part-sell-form-save-button').prop('disabled', false);
		        $('#part-sell-form-save-button').html(activeText);
		      }
		    });
		  }
		});
	}

  	return {
    //main function to initiate the module
        init: function() {
    		handleProductSellForm();
    	}
    };
}();

jQuery(document).ready(function() {
  Product.init();

  var th = new showHideText('.product-short-description', {
      charQty     : 45,
      ellipseText : "...",
      moreText    : "",
      lessText    : "Hide"
  });

  var th = new showHideText('.product-title-name', {
      charQty     : 40,
      ellipseText : "...",
      moreText    : "",
      lessText    : "Hide"
  });

  $('.table-feature').delegate('.btn-remove-feature', 'click', function(el) {
    $(this).closest('tr').remove();
  });

  $('.table-feature').delegate('.btn-add-feature', 'click', function(el) {
    $('.table-feature tbody').append('<tr><td><input type="text" name="features[]" class="form-control" value="" placeholder="Feature*" required></td><td class="p-l-xs p-r-xs p-t-xs p-b-xs" width="5%"><button type="button" class="btn btn-danger btn-sm btn-remove-feature"><i class="fa fa-trash"></i></button></td></tr>');
  });

  $('.table-specification').delegate('.btn-remove-specification', 'click', function(el) {
    $(this).closest('tr').remove();
  });

  $('.table-specification').delegate('.btn-add-specification', 'click', function(el) {
    $('.table-specification tbody').append('<tr><td width="30%"><input type="text" name="specifications_label[]" class="form-control" value="" placeholder="Specification Label*" required></td><td class="p-l-xs p-r-xs p-t-xs p-b-xs" width="65%"><input type="text" name="specifications_text[]" class="form-control" value="" placeholder="Specification Value*" required></td><td class="p-l-xs p-r-xs p-t-xs p-b-xs" width="5%"><button type="button" class="btn btn-danger btn-sm btn-remove-specification"><i class="fa fa-trash"></i></button></td></tr>');
  });


  	var partImages = [];
	$(document).delegate('.attach-image', 'click', function() {

	    if(partImages.length > 4)
	    {
	      alert("You can upload only 5 images");
	      return false;
	    }
	    else
	    { 
	      var skey    = parseInt($('#part-image').data('key'));
	      var nextKey = skey+1;
	      var dHtml = '';
	      dHtml += '<input type="file" class="d-none part-image" accept="image/*" id="input-file-'+nextKey+'" name="part_image[]" data-skey="'+nextKey+'">';
	      $('#add-more-images-cst').append(dHtml);

	      setTimeout(function(){
	        $('#input-file-'+nextKey+'').click();
	      },300);
	    }
	});

	$(document).delegate('.part-image', 'change', function() {

	    var elebtn    = $(this);
	    var nextKey   = parseInt(elebtn.data('skey'));
	    var nextLabel = parseInt(nextKey)+1;

	    var dHtml   = '';
	    dHtml += ' <div class="col-md-4 sv-product-img part-preview-image mb-1">';
	    dHtml += '  <img src="" id="part-preview-image-'+nextKey+'" class="img-thumbnail">';
	    dHtml += '  <buttton type="button" class="btn btn-sm btn-danger delete-part-preview-image" data-pkey='+nextKey+'><i class="fa fa-trash"></i></buttton>';
	    dHtml += ' </div>';

	    $('#add-more-images-cst').append(dHtml);

	    var fileName = elebtn.val();
	    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1);
	    if(fileNameExt == "heic" || fileNameExt == "heif") {
	      convertHeicToJpg(this, $('#part-preview-image-'+nextKey+''));
	    } else {
	      readImageURL(this, $('#part-preview-image-'+nextKey+''));
	    }

	    partImages.push(nextKey);
	    $('#part-image').data('key',nextKey)
	});

	$(document).delegate('.delete-part-preview-image', 'click', function(e) {

	    var key    = $(this).data('pkey');
	    var findex = partImages.indexOf(key);

	    partImages.splice(findex,1);

	    $('#input-file-'+key+'').remove();
	    $(this).parents('.part-preview-image').remove();
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

  var totalSlides = $('#total-slide-image').val();
  totalSlides = parseInt(totalSlides)-1;

  $('.slider-for').slick({
  	slidesToShow: 1,
  	arrows: false,
  	asNavFor: '.slider-nav',
  	vertical: true,
  	autoplay: true,
  	verticalSwiping: false,
  	infinite: true

  });

  $('.slider-nav').slick({
  	asNavFor: '.slider-for',
  	vertical: true,
  	focusOnSelect: true,
  	autoplay: false,
  	verticalSwiping: true,
  	slidesToShow: totalSlides,
  	slidesToScroll: 1
  });

  // Show more row

  var rowsToShow = 5;
  var currentRow = rowsToShow;

  $('#myTable .desc-row:gt(' + (rowsToShow - 1) + ')').hide();


  $('#showMore').click(function (e) {
  	currentRow += rowsToShow;
  	e.preventDefault();

  	$('#myTable .desc-row:lt(' + currentRow + '):gt(' + (currentRow - rowsToShow - 1) + ')').show();

  	if ($('#myTable .desc-row:hidden').length === 0) {
  		$('#showMore').hide();
  	}
  });

});
