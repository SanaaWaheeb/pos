/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);


$(document).ready(function() {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
        } else {
            $("header").removeClass("head-sticky");
        }
    });   
     /********* Wrapper top space ********/
     var header_hright = $('header').outerHeight();
     $('header').next('.wrapper').css({
        'margin-top': header_hright + 'px',
        'margin-bottom': header_hright + 'px'
    });  
    /********* Mobile Menu ********/  
    $('.mobile-menu-button').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            $('.overlay').addClass('menu-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.menu-overlay, .menu-close-icon svg', function(e){
        e.preventDefault(); 
        $('body').removeClass('no-scroll active-menu');
        $(".mobile-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    // $(".mobile-menu").click(function() { 
    //     $(".main-nav").toggleClass("active"); 
    // });
    /********* Cart Popup ********/
    $('.cart-header').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll cartOpen');
            $('.overlay').addClass('cart-overlay');
        }, 50);
    }); 
    $('body').on('click','.overlay.cart-overlay, .closecart', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('cart-overlay');
        $('body').removeClass('no-scroll cartOpen');
    });
    /********* Mobile Filter Popup ********/
    $('.filter-title').on('click',function(e){
        e.preventDefault();
        setTimeout(function(){
            $('body').addClass('no-scroll filter-open');
            $('.overlay').addClass('active');
        }, 50);
    }); 
    $('body').on('click','.overlay.active, .close-filter', function(e){
        e.preventDefault(); 
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    }); 
     /*********  Header Search Popup  ********/ 
     $(".search-header a").click(function() { 
        $(".omnisearch").toggleClass("show"); 
        $("body").toggleClass("no-scroll");
    });
    $(".search-header a").click(function() { 
        $(".mask-body").addClass("active"); 
        // $(".mask-body").removeClass("active");
    });
    $(".mask-body").click(function() { 
        $(".mask-body").removeClass("active"); 
        $(".omnisearch").removeClass("show"); 
    });

     /*********  cart Popup  ********/ 
     $("#checkout-btn").click(function() { 
        $(".checkoutModal").toggleClass("show"); 
        $("body").toggleClass("no-scroll");
        $(".mask-body").addClass("active");
    });

    // $(".fade").click(function() { 
    //     $(".fade").removeClass("show"); 
    //     $(".mask-body").removeClass("active");  
    // });

    $(" button.close").click(function() { 
        $(".mask-body").removeClass("active");  
        $(".fade").removeClass("show"); 
    });
    $('.remove-btn').click(function(){
        $("body").toggleClass("no-scroll");  
    });


    /******* Cookie Js *******/
    $('.cookie-close').click(function () {
        $('.cookie').slideUp();
    });
    /******* Subscribe popup Js *******/
    $('.close-sub-btn').click(function () {
        $('.subscribe-popup').slideUp(); 
        $(".subscribe-overlay").removeClass("open");
    });      
    /********* qty spinner ********/
    var quantity = 0;
    $('.quantity-increment').click(function(){;
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        $(t).val(quantity + 1); 
    }); 
    $('.quantity-decrement').click(function(){
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity > 1){
            $(t).val(quantity - 1);
        }
    });   
    /******  Nice Select  ******/ 
    $('.custom-select').niceSelect(); 
    /*********  Multi-level accordion nav  ********/ 
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        }
        else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    });  
    /****  TAB Js ****/
    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');
        $(this).closest('.tabs-wrapper').find('.tab-link').removeClass('active');
        $(this).addClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content').removeClass('active');
        $(this).closest('.tabs-wrapper').find('.tab-content#' + tab_id).addClass('active');
        $(this).closest('.tabs-wrapper').find('.slick-slider').slick('refresh');
    });



    if ($('.testimonial-slider').length > 0) {
        $('.testimonial-slider').slick({
            autoplay: false,
            slidesToShow: 1,
            speed: 1000,
            slidesToScroll: 1,
            rtl:true,
            prevArrow: '<button class="slide-arrow slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="9" viewBox="0 0 5 9" fill="none"><path d="M1.06694 0.188289L4.81694 4.04543C5.06102 4.29648 5.06102 4.70352 4.81694 4.95457L1.06694 8.81171C0.822864 9.06276 0.427136 9.06276 0.183059 8.81171C-0.0610195 8.56066 -0.0610195 8.15363 0.183058 7.90257L3.49112 4.5L0.183058 1.09743C-0.0610199 0.846375 -0.0610199 0.43934 0.183058 0.188289C0.427136 -0.0627632 0.822864 -0.0627633 1.06694 0.188289Z" fill="#05103B"/></svg></button>',
            nextArrow: '<button class="slide-arrow slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="9" viewBox="0 0 5 9" fill="none"><path d="M1.06694 0.188289L4.81694 4.04543C5.06102 4.29648 5.06102 4.70352 4.81694 4.95457L1.06694 8.81171C0.822864 9.06276 0.427136 9.06276 0.183059 8.81171C-0.0610195 8.56066 -0.0610195 8.15363 0.183058 7.90257L3.49112 4.5L0.183058 1.09743C-0.0610199 0.846375 -0.0610199 0.43934 0.183058 0.188289C0.427136 -0.0627632 0.822864 -0.0627633 1.06694 0.188289Z" fill="#05103B"/></svg></button>',
            dots: true,
            buttons: false,
            arrows:true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }
     /** PDP slider **/
     $('.pdp-main-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        rtl:true,
        dots: false,
        prevArrow: '<button class="slick-prev slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        nextArrow: '<button class="slick-next slick-arrow"><span class="slickbtn"><svg><use xlink:href="#slickarrow"></use></svg></span></button>',
        infinite: true,
        speed: 1000,
        loop: true,
        asNavFor: '.pdp-thumb-slider',
        autoplay: false,
    });
    $('.pdp-thumb-slider').slick({
        slidesToShow: 5,
        arrows: false,
        rtl:true,
        asNavFor: '.pdp-main-slider',
        dots: false,
        touchMove: true,
        speed: 1000,
        slidesToScroll: 1,
        touchMove: true,
        focusOnSelect: true,
        loop: true,
        infinite: true,
        vertical: false,
        verticalSwiping: false,
        prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        responsive: [
            {
            breakpoint: 768,
                settings: {
                    vertical: false,
                }
            }
        ]
    });


    // client-logo

    if ($('.client-logo-slider').length > 0) {
        $('.client-logo-slider').slick({
            autoplay: false,
            slidesToShow: 6,
            speed: 1000,
            slidesToScroll: 1,
            dots: false,
            buttons: false,
            rtl:true,
            arrows:false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
    }

});