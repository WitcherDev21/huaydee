$(document).ready(function () {

    var prefixZero = function (num, size) {
        var s = num + "";
        while (s.length < size) s = "0" + s;
        return s;
    };


    var setTimer = function (element, finalDate, args) {

        if (finalDate) {
            console.log(finalDate);
            if(timex){
                var serDateTime = timex;
            } else {
                var serDateTime = 0000000000000;
            }
            intervalTimer(element, finalDate, serDateTime);
            var yeeKeeInterval = setInterval(function () {
                serDateTime = serDateTime + 1000;
                intervalTimer(element, finalDate, serDateTime, args);
            }, 1000);
            console.log(yeeKeeInterval);
            args.yeeKeeInterval = yeeKeeInterval;
        }
    };

    function intervalTimer(element, finalDate, serDateTime, args) {

        var now = new Date().getTime();
        var offset = serDateTime - Date.now();
        var now = offset + Date.now();
        var distance = finalDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if(days > 0){
            element.html(days + '<small> วัน</small>' +hours + '<small>:</small>' + prefixZero(minutes, 2) + '<small>:</small>' + prefixZero(seconds, 2));
        } else {
            element.html(hours + '<small>:</small>' + prefixZero(minutes, 2) + '<small>:</small>' + prefixZero(seconds, 2));
        }

        if (distance < 0) {
            window.location.href = url_thai_shared+'?id='+playId;
            clearInterval(yeeKeeInterval);
        }
    }

    $('.countdown').each(function (index, el) {
        var args = {
            id: index,
            counter: $('.countdown').length
        };
        setTimer($(this), $(this).data('finaldate'), args);
    });
});