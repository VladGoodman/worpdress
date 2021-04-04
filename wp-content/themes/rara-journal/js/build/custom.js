
jQuery(document).ready(function($){
    var slider_auto, slider_loop, slider_control;
    /* removes class hidden from the banner */
    $("#banner-slider").removeClass("hidden");
   
   /** Variables from Customizer for Slider settings */
    if( rarajournal_data.auto == '1' ){
        slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( rarajournal_data.loop == '1' ){
        slider_loop = true;
    }else{
        slider_loop = false;
    }
    
    if( rarajournal_data.pager == '1' ){
        slider_control = true;
    }else{
        slider_control = false;
    }
    
    /** Home Page Slider */
    $('#lightSlider').lightSlider({

        slideMargin: 0,
        mode: rarajournal_data.animation,        
        speed: rarajournal_data.a_speed, //ms'
        auto: slider_auto,
        loop: slider_loop,
        pager: slider_control,
        keyPress: true,
        
        responsive : [
            {
                breakpoint:767.5,
                settings: {
                    item: 1,
                }
            }
        ],

    });

    var winWidth = $(window).width();
    if(winWidth < 992){

        //secondary menu in mobile
        $('.btn-secondary-menu-button').click(function(){
            $('body').addClass('open-secondary-menu');
        });

        // $('.top-menu').prepend('<button type="button" class="btn-close-secondary-menu"></button>');

        // $('.top-menu ul .menu-item-has-children').append('<button type="button" class="btn-submenu"></button>');
        $('<button type="button" class="btn-submenu"></button>').insertAfter($('.mobile-menu-wrapper .mobile-menu ul .menu-item-has-children > a'));
        $('<button type="button" class="btn-submenu"></button>').insertAfter($('.mobile-secondary-menu-wrapper .mobile-menu ul .menu-item-has-children > a'));

        $('.top-menu ul li .btn-submenu').click(function(){
               $(this).next().slideToggle();
               $(this).toggleClass('active');
        });

        $('.btn-close-secondary-menu').click(function(){
            $('body').removeClass('open-secondary-menu');
        });

        // $('.overlay').click(function(){
        //     $('body').removeClass('open-secondary-menu');
        //     $('body').removeClass('open-primary-menu');
        // });

        //primary menu in mobile
        $('.btn-primary-menu-button').click(function(){
            $('body').addClass('open-primary-menu');
        });

        // $('.main-navigation').prepend('<button type="button" class="btn-close-primary-menu"></button>');

        // $('.main-navigation ul .menu-item-has-children').append('<button type="button" class="btn-submenu"></button>');

        $('.main-navigation ul li .btn-submenu').click(function(){
               $(this).next().slideToggle();
               $(this).toggleClass('active');
        });

        $('.btn-close-primary-menu').click(function(){
            $('body').removeClass('open-primary-menu');
        });
    }
    //responsive menu toggle
    $('.header-top .btn-primary-menu-button').on('click', function(){
        $('.mobile-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    $('.mobile-menu-wrapper .close').on('click', function () {
        $('body').removeClass('open-primary-menu');
        $('.mobile-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    // $('.overlay').on('click', function () {
    //     $('.mobile-menu-wrapper').animate({
    //         width: 'toggle',
    //     });
    // });

     //responsive secondary menu toggle
    $('.header-top .btn-secondary-menu-button').on('click', function(){
        $('body').addClass('open-secondary-menu');
        $('.mobile-secondary-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    $('.mobile-secondary-menu-wrapper .top-menu .close-nav-toggle').on('click', function () {
        $('body').removeClass('open-secondary-menu');
        $('.mobile-secondary-menu-wrapper').animate({
            width: 'toggle',
        });
    });

    // $('.overlay').on('click', function () {
    //     $('.mobile-secondary-menu-wrapper').animate({
    //         width: 'toggle',
    //     });
    // });


    //dropdown menu for edge
    if(winWidth > 991){
        $("#site-navigation ul li a").focus(function() {
            $(this).parents("li").addClass("focus");
        }).blur(function() {
            $(this).parents("li").removeClass("focus");
        });

        $("#secondary-menu ul li a").focus(function() {
            $(this).parents("li").addClass("focus");
        }).blur(function() {
            $(this).parents("li").removeClass("focus");
        });
    }

});
