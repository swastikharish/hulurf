jQuery(document).ready(function() {
  
  $(".mask-year").inputmask("9999", {
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });        
  $('.mask-month').inputmask('Regex', { 
    regex: "^(0?[1-9]|1[012])$",
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });
  $(".mask-date").inputmask("d/m/y", {
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });        
  $(".mask-phone").inputmask("9999999999", {
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });        
  $(".mask-number").inputmask({
    "mask": "9",
    "repeat": 10,
    "greedy": false
  });

  $(".mask-zero-positive-number").inputmask('Regex', {
    regex: "^[0-9][0-9]?$|^100$",
  });

  $(".mask-non-zero-positive-number").inputmask('Regex', {
    regex: "^[1-9][0-9]?$|^100$",
  });

  $(".mask-decimal").inputmask('decimal', {
    rightAlign: false,
    autoUnmask: true,
  });

  $(".mask-decimal-two").inputmask('decimal', {
    rightAlign: false,
    autoUnmask: true,
    digits : 2
  });

  $(".mask-tin").inputmask("99-9999999", {
    clearMaskOnLostFocus: true
  });        
  $(".mask-ssn").inputmask("999-99-9999", {
    clearMaskOnLostFocus: true,
    autoUnmask: true
  });
  $(".mask-currency").inputmask('currency', {
    rightAlign: false,
    prefix: ''
  });

  $(".mask-limit-hundred").inputmask('Regex', {
     regex: "^[1-9][0-9]?$|^100$",
  });

  $('.mask-npi').inputmask('Regex', { 
    regex: "^[a-zA-Z0-9]{0,10}$",
  });

  $('.mask-pin').inputmask('Regex', { 
    regex: "^[0-9]{0,4}$",
  });

  $('.mask-zip').inputmask('Regex', { 
    regex: "^[0-9]{0,5}$",
  });

  $(".mask-url").inputmask('url', {
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });

  $(".mask-three-decimal-two").inputmask('Regex', {
    regex: "^[0-9]{1,3}(\\.\\d{1,2})?$"
  });
   

  $(document).on('click', '.bootbox-confirm-box', function(e){
    e.preventDefault();
    var confirmLink = $(this).data('href');
    var confirmText = $(this).data('confirm');
    bootbox.confirm(confirmText, function(result){
      if (result) {
        window.location = confirmLink;
      }
    });   
  });

  $('[data-toggle="tooltip"]').tooltip();

  $('[data-toggle="popover"]').popover();

  $(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
    if(!$this.hasClass('panel-collapsed')) {
      $this.parents('.panel').find('.panel-body').slideUp();
      $this.addClass('panel-collapsed');
      $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    } else {
      $this.parents('.panel').find('.panel-body').slideDown();
      $this.removeClass('panel-collapsed');
      $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }
  })
});


function scrollToUpper(el, offeset) {
  var pos = (el && el.size() > 0) ? el.offset().top : 0;
  if (el) {
    if ($('body').hasClass('page-header-fixed')) {
      pos = pos - $('.page-header').height();
    } else if ($('body').hasClass('page-header-top-fixed')) {
      pos = pos - $('.page-header-top').height();
    } else if ($('body').hasClass('page-header-menu-fixed')) {
      pos = pos - $('.page-header-menu').height();
    }
    pos = pos + (offeset ? offeset : -1 * el.height());
  }

  $('html,body').animate({
    scrollTop: pos
  }, 'slow');
}


// wrApper function to  block element(indicate loading)
function blockUI(options) {
  options = $.extend(true, {}, options);
  var html = '';
  if (options.barlines) {
      html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="loader"></div>' + '</div>';
  } else if (options.dice) {
    html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="dice"><div class="face first-face"><div class="dot"></div></div><div class="face second-face"><div class="dot"></div><div class="dot"></div></div><div class="face third-face"><div class="dot"></div><div class="dot"></div><div class="dot"></div> </div><div class="face fourth-face"><div class="column"><div class="dot"></div><div class="dot"></div> </div><div class="column"><div class="dot"></div><div class="dot"></div> </div></div><div class="face fifth-face"><div class="column"><div class="dot"></div><div class="dot"></div></div><div class="column"><div class="dot"></div></div><div class="column"><div class="dot"></div><div class="dot"></div></div></div><div class="face sixth-face"><div class="column"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div><div class="column"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div></div></div>';
  } else if (options.circle) {
    html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="loader-circle"></div><div class="loader-line-mask one"><div class="loader-line"></div></div><div class="loader-line-mask two"><div class="loader-line"></div></div><div class="loader-line-mask three"><div class="loader-line"></div></div><div class="loader-line-mask four"><div class="loader-line"></div></div>' + '</div>';
  } else {
    html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<i class="fa fa-spinner fa-pulse fa-spin fa-4x"></i>' + '</div>';
  }

  if (options.target) { // element blocking
    var el = $(options.target);
    if (el.height() <= ($(window).height())) {
      options.cenrerY = true;
    }
    el.block({
      message: html,
      baseZ: options.zIndex ? options.zIndex : 1000,
      centerY: options.cenrerY !== undefined ? options.cenrerY : false,
      css: {
        top: '10%',
        border: '0',
        padding: '0',
        backgroundColor: 'none'
      },
      overlayCSS: {
        backgroundColor: 'black',
        opacity: options.boxed ? 0.05 : 0.1,
        cursor: 'wait'
      }
    });
  } else { // page blocking
    $.blockUI({
      message: html,
      baseZ: options.zIndex ? options.zIndex : 1000,
      css: {
        border: '0',
        padding: '0',
        backgroundColor: 'none'
      },
      overlayCSS: {
        backgroundColor: options.overlayColor ? options.overlayColor : '#555',
        opacity: options.boxed ? 0.05 : 0.1,
        cursor: 'wait'
      }
    });
  }
}

// wrApper function to  un-block element(finish loading)
function unblockUI(target) {
  if (target) {
    $(target).unblock({
      onUnblock: function() {
        $(target).css('position', '');
        $(target).css('zoom', '');
      }
    });
  } else {
    $.unblockUI();
  }
}


$(document).delegate('.c-update-status-check', 'click', function(e) {
  var elmBtn = $(this);
  var value = (elmBtn.is(':checked') == false) ? '0' : '1';
  var tableName = elmBtn.data('table-name');
  var fieldName = elmBtn.data('field-name');
  var fieldValue = value;
  var primaryName = elmBtn.data('primary-name');
  var primaryValue = elmBtn.data('primary-value');
  var confirm = elmBtn.data('confirm');

  var blockContent = $('.app-content-body');
  if((tableName != '') && (fieldName != '') && (fieldValue != '') && (primaryName != '') && (primaryValue != '')) {
    bootbox.confirm(confirm, function(result){
      if (result) {
        var ajaxRequest = true;
        if(ajaxRequest) {
          blockUI({target: blockContent, circle: true});
          $.ajax({
            url: app_path+'admin/ajax/update/table',
            type: 'post',
            dataType: 'json',
            data: {table_name : tableName, field_value : fieldValue, field_name : fieldName, primary_value : primaryValue, primary_name : primaryName},
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
      } else {
        if(fieldValue == '1'){
          elmBtn.removeAttr('checked');
        } else {
          elmBtn.prop('checked', true);
        }
      }
    });
  }
});

function tooltipInitDynamic()
{
  $('[data-toggle="tooltip"]').tooltip();
}

function popOverModalDynamic()
{
  $('[data-toggle="popover"]').popover({
    container: '.modal-body'
  });
}

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


jQuery(document).ready(function() {

  function limitCharacter(el) {
    var text_max        = el.data('limit-char');
    var text_length     = el.val().length;
    var text_remaining  = text_max - text_length;
    var charHtml = '<span class="char-count">'+text_remaining+' characters remaining</span>';
    el.next('.char-count').remove();
    el.after(charHtml);
  }

  $('.character-counter').each(function(i) {
    limitCharacter($(this));
  });

  $(document).delegate('.character-counter', 'keydown keyup', function(e) {
    limitCharacter($(this));
  });

  $('.numeric').inputmask({
    alias: 'numeric', 
    allowMinus: false,  
    digits: 2
  });

  $('.numeric-left').inputmask({
    alias: 'numeric', 
    allowMinus: false,  
    digits: 2,
    rightAlign: false
  });

  $('.numeric-three').inputmask({
    alias: 'numeric', 
    allowMinus: false,  
    digits: 3
  });

  $('.number').inputmask({
    "mask": "9",
    "repeat": 10,
    "greedy": false
  });

  $(document).delegate('.btn-copy-text', 'click', function(e) {
    var textStr = $(this).data('copy-text');
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(textStr).select();
    document.execCommand("copy");
    $temp.remove();
    alert('Copied to clipboard!');
  });

  $('#LinkInput').on('click', function(e) {
    e.stopPropagation();
  });
});
