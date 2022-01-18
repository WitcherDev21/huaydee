var credit_global = 0;

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function loadcredit() {
    $.ajax({
        url: '/member/credit_balance',
        cache: false,
        type: 'get',
        success: function (data) {
            $('[data-id=credit_balance]').html(numberWithCommas(data) + " ฿");
            credit_global = data;
            //console.info($('[data-toggle="tooltip"]'));
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('[data-id=credit_balance]').html('0.00' + " ฿");
        }
    });
}

$(window).on('load', function(){
    setTimeout(function(){
        // odometer.innerHTML = 99999;
        $('#odometer2').html(total_jackpot)
        $('#odometer3').html(total_jackpot);
    }, 500);

    $(function () {
        loadcredit();
    });

})

$(document).ready(function () {
    // odoo.default({ el:'.js-odoo', from: '99999', to: '99999', animationDelay: 1000 });
    //!--Defer
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#dismiss, .overlay').on('click', function () {
        // hide sidebar
        $('#sidebar').removeClass('active');
        // hide overlay
        $('.overlay').removeClass('active');
    });

    $('#sidebarCollapse, #sidebarCollapsePC').on('click', function () {
        // open sidebar
        $('#sidebar').addClass('active');
        // fade in the overlay
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    // poy 23-01-2561
    $('.btn-poy').click(function() {
        var poytab = $(this).data("id");
        $('.btn-poy').removeClass('active');
        $(this).addClass('active');
        $('.poy-content').removeClass('active');
        $('#'+poytab).addClass('active');
    });

    $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
});

    $('#re-credit').on('click', function (event) {
        $('#re-credit').tooltip("show");
        $('.refresh-icon').addClass('fa-spin');
        setTimeout(function(){
            $('.refresh-icon').removeClass('fa-spin');
        }, 1000);

        $('#re-credit').addClass('active');
        setTimeout(function(){
            loadcredit();
            $('#re-credit').tooltip('hide');
            $('#re-credit').removeClass('active');
        }, 800);
    });

    $('body').on('mouseover click', '#menu-profile.dropdown-toggle', function(e) {
        $(e.target).dropdown('toggle');
    });
    $('body').on('mouseout', '#menu-profile .dropdown-menu', function(e) {
        $(e.target).dropdown('dispose');
    });



$(function(){
    var pathArray = window.location.pathname.split(/[^A-Za-z]/);
    var check = pathArray.slice(-1);
    var current;
    if(check[0])
        current = pathArray[pathArray.length-1];
    else
        current = pathArray[pathArray.length-2];

    $('#menu-mobile a, #menu-pc a').each(function(){
        var $this = $(this);
        // if the current path is like this link, make it active
        if($this.attr('href').indexOf(current) !== -1){

            $this.addClass('active');

            // if($('[data-id="award"]').hasClass('active') OR $('[data-id="refill"]').hasClass('active') OR $('[data-id="alert"]').hasClass('active') OR $('[data-id="support"]').hasClass('active')){
            //     $('[data-id="lotto"]').removeClass('active');
            // }

        }else{
            $this.removeClass('active');
        }
    })
});

// $(document).ready(function () {
//     var formatter = new Intl.NumberFormat('th', {
//       style: 'currency',
//       currency: 'THB',
//       minimumFractionDigits: 0
//     });


//     var elements = document.getElementsByClassName('thb');
//     //var amount = elements[0].innerHTML;
//     for(var i=0;i<elements.length;i++){

//         var amount = elements[i].innerHTML;
//         var baht = formatter.format(amount);
//         var thb = baht.replace(/\b(\w*THB\w*)\b/, '฿');
//         elements[i].innerHTML = thb;
//     }
// });

$(document).ready(function () {

    var elements = document.getElementsByClassName('thb');
    for(var i=0;i<elements.length;i++){
        var amount = elements[i].innerHTML;
        var baht = numeral(amount).format('$ 0,0[.]00');
        var thb = baht.replace('$', '฿');
        elements[i].innerHTML = thb;
    }

});

function fullhight(){
    var grid = $('.row-menu-grid');
    if (grid.length) {
        var scrollTop     = $(window).scrollTop(),
            elementOffset = $('.row-menu-grid').offset().top,
            distance      = (elementOffset - scrollTop);
        $('.row-menu-grid').css('min-height','calc(100vh - '+distance+'px - 59px)');
    }
}
function removehight(){
    var grid = $('.row-menu-grid');
    if (grid.length) {
        var scrollTop = $(window).scrollTop(),
            elementOffset = $('.row-menu-grid').offset().top,
            distance = (elementOffset - scrollTop);
        $('.row-menu-grid').removeCss('min-height', 'calc(100vh - ' + distance + 'px - 59px)');
    }
}

$(document).ready(function () {
    var main = $('.main-content');
    if (main.length) {
        var scrollTop = $(window).scrollTop(),
            elementOffset = $('.main-content').offset().top,
            distance = (elementOffset - scrollTop);
        $('.main-content').css('min-height', 'calc(100vh - ' + distance + 'px - 50px)');
    }
});


(function($) {
    var $window = $(window),
        $html = $('#section-content'),
        $bt1 = $('.btn-credit'),
        $row1 =  $('.row-menu-grid');
    function resize() {
        if ($window.width() < 768) {
            return $html.addClass('container-fluid'),
                $bt1.removeClass('btn-lg'),
                $bt1.addClass('btn-sm'),
                fullhight();
        }
        //removehight();
        $html.removeClass('container-fluid'),
            $bt1.removeClass('btn-sm'),
            $bt1.addClass('btn-lg'),
            $row1.removeAttr('style');
    }
    $window
        .resize(resize)
        .trigger('resize');
})(jQuery);

$('#hide-list-huay').click(function(){
    $('#sidebar-huay').css({"padding":"0px","min-width":"0px", "width":"0px","opacity":"0","overflow":"hidden"});
    $('#show_poy_list').css({"min-width":"0px", "width":"0px","opacity":"0"});
    $('#show-list-huay').removeAttr('style');
    $(this).css({"display":"none"});
});
$('#show-list-huay').click(function(){
    $('#sidebar-huay').removeAttr('style');
    $('#show_poy_list').removeAttr('style');
    $('#hide-list-huay').removeAttr('style');
    $(this).css({"width":"0px"});
});

function notify(msg,type="success") {
    toastr.clear();
    if(type=="success")
        var notify = toastr.success(msg);

    if(type=="error")
        var notify = toastr.error(msg);

    if(type=="warning")
        var notify = toastr.warning(msg);

    var $notifyContainer = jQuery(notify).closest('.toast-top-center');
    if ($notifyContainer) {
        var containerWidth = jQuery(notify).width() + 20;
        $notifyContainer.css("margin-left", -containerWidth / 2);
    }
}

$(document).ready(function() {
    $('.btn-poy').click(function() {
        var poytab = $(this).data("id");
        $('.btn-poy').removeClass('active');
        $(this).addClass('active');
        $('.poy-content').removeClass('active');
        $('#'+poytab).addClass('active');
    });
});

$(function(){
    var path = window.location.href;
    $('a.btn-af').each(function(){
        var $this = $(this);
        // if the current path is like this link, make it active
        if (this.href === path) {
            $('a.btn-af').removeClass('active');
            $this.addClass('active');
        }
    })
});