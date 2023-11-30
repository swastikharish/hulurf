/*------------ Show / Hide Text ------------*/
function showHideText(sSelector, options) {
    // Def. options
    var defaults = {
        charQty     : 100,
        ellipseText : "...",
        moreText    : "Show more",
        lessText    : "Show less"
    };

    var settings = $.extend( {}, defaults, options );

    var s = this;

        s.container = $(sSelector);
        s.containerH = s.container.height();

        s.container.each(function() {
            var content = $(this).html();

            if(content.length > settings.charQty) {

                var visibleText = content.substr(0, settings.charQty);
                var hiddenText  = content.substr(settings.charQty, content.length - settings.charQty);

                var html = visibleText
                         + '<span class="moreellipses">' +
                           settings.ellipseText
                         + '</span><span class="morecontent"><span>' +
                           hiddenText
                         + '</span><a href="" class="morelink">' +
                           settings.moreText
                         + '</a></span>';

                $(this).html(html);
            }

        });

        s.showHide = function(event) {
            event.preventDefault();
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(settings.moreText);

                $(this).prev().fadeToggle('fast', function() {
                    $(this).parent().prev().fadeIn();
                });
            } else {
                $(this).addClass("less");
                $(this).html(settings.lessText);

                $(this).parent().prev().hide();
                $(this).prev().fadeToggle('fast');
            }
        }

        $(".morelink").bind('click', s.showHide);
}
/*------------------------------------------*/


$(document).ready(function () {
  // $(window).load(function () {
  //   $('.loader').fadeOut();
  // });

  $('#hero-banner').owlCarousel({
    nav: false,
    center: true,
    loop: true,
    items: 1,
    autoplay: true,
    dots: false,
    margin: 0,
    autoplayTimeout: 5000,
    autoplaySpeed: 1500
  });

  $('.testimonial-slider').owlCarousel({
    nav: true,
    center: true,
    loop: true,
    items: 1,
    autoplay: true,
    dots: false,
    margin: 0,

    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
  });

  $('.testimonial-slider01').owlCarousel({
    nav: true,
    center: true,
    loop: true,
    items: 1,
    autoplay: true,
    dots: true,
    margin: 0,

    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
  });

  $('li.child-menu').click(function (e) {
    $(this).children('.dropdown_list').addClass('open');
  }, function () {
    $(this).children('.dropdown_list').addClass('open');
  });

  $(".dropdown_list").click(function (e) {
    e.stopPropagation();
  });

  $('li.child-menu').click(function () {
    $(this).children('.dropdown_list').slideToggle(200);
  })

  $('.menu-toggler').click(function (event) {
    $(this).toggleClass('open');
    $('.user-logined').toggleClass('open');
    $('.header .header-second-menu').slideToggle();
  });

  $('.related-slider').owlCarousel({
    nav: false,
    center: false,
    loop: true,
    items: 4,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplaySpeed: 1000,
    dots: false,
    margin: 30,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 2
      },
      1000: {
        items: 3
      },
      1200: {
        items: 4
      }
    }
  });

  $(".shop-parts").click(function () {
    $('.shoppart-menu').slideToggle();
  });

  $(".shoppart-menu,.shop-parts").click(function (e) {
    e.stopPropagation();
  });

  $(document).click(function () {
    $('.shoppart-menu').hide();
  });

  $(".shoppart-menu ul li").click(function () {
    var val = $(this).text();
    $('.shop-parts span').html(val);
    $('.shoppart-menu').hide();
  });

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
  $(".mask-currency").inputmask('currency', {
    rightAlign: false,
    prefix: ''
  });

  $(".mask-limit-hundred").inputmask('Regex', {
     regex: "^[1-9][0-9]?$|^100$",
  });

  $('.mask-zip').inputmask('99999', { 
    regex: "^[0-9]{0,5}$",
  });

  $(".mask-url").inputmask('url', {
    autoUnmask: true,
    clearMaskOnLostFocus: true,
  });

  $(".mask-three-decimal-two").inputmask('Regex', {
    regex: "^[0-9]{1,3}(\\.\\d{1,2})?$"
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
});