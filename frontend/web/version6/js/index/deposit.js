$(document).ready(function() {
    window.localStorage.removeItem('poston');
    $("#bankselect").change(function() {
        var banknmm = $(this).val();
        var sps = banknmm.split("-");
        if(sps[0]!=="blank"){
            console.log(sps);
            var banknm = $('#' + sps[0]);
            var acc = banknm.data("acc");
            var name = banknm.data("name");
            var bank = banknm.data("bank");
            var color = banknm.data("color");
            var icon = banknm.data("icon");
            var bcolor = 'icon-' + sps[0];
            $('.copyacc').attr('data-clipboard-text', acc);
            $('.copyacc').show();
            $('#bank2').attr("src","//" + window.location.hostname + logoBankUrl + icon);
            $('#bank2').css("background-color", color);
            $('#bank2').removeAttr("class");
            $('#bank2').addClass("detail-bank rounded");
            $('#bank2').addClass(bcolor);
            $('#name2').html(name);
            $('#acc2').html(acc);
            $('#acc2').css("color",color);
            $('#nameacclang').html('ชื่อบัญชี');
            $("#destep2").show();
            var objDate = new Date();
            var hours = objDate.getHours();
            var minutes = objDate.getMinutes();
            var time = hours+""+minutes;
            // if (time>=2255 || time<=5) {
            //     toastr.error(lang.limit_time_deposit, "Error");
            //     $('#btnpayment').prop('disabled', true).html("อยู่นอกเวลาทำการ");
            // }
        } else {
            $('#bank2').removeAttr("class");
            $('#bank2').removeAttr("style");
            $('#bank2').css("color","black");
            $('#bank2').addClass("detail-bank table-secondary rounded");
            $('#nameacclang').html('');
            $('#name2').html('');
            $('#acc2').html('กรุณาเลือก บัญชีธนาคาร ของเว็บ RUAYDEE96');
            $('#bank2').attr("src","//" + window.location.hostname + bankUrl);
            $('#acc2').css("color","black");
            $("#destep2").hide();
            $('.copyacc').hide();
        }

    }).trigger('change');

    $("#btnpayment").click(function() {
        var money = $('input[name=money]', '#deposit').val();
        if (!money) {
            toastr.error("กรุณาใส่จำนวนเงิน", "Error");
            $(".moneyinput").addClass("border-danger");
            $(".moneyinput").focus();
            return false;
        } else {
            $(".money-transfer").hide();
            $("#destep3").show();
            $("#msgstep2").hide();
            $("#onbankselect").css("display", "none");
            $("#labelselectbank").html('<i class="fas fa-university"></i> บัญชีของเว็บที่ท่านโอนเงินเข้า');
            var amount = $("#CurrencyInput").val();
            var decimal = $("#decimal").val();
            if (decimal <= 9){
                var money = amount + '.0' + decimal;
            } else {
                var money = amount + '.' + decimal;
            }
            $("#money-transfer").val(money);
            //countDown(900, 'สิ้นสุดเวลาการโอน', 'timetransferlimit');
            document.getElementsByClassName("timetransferlimit")[0].innerText = "กรุณาโอนเงินเข้าบัญชีด้านบนภายใน 5 นาที";
        }
    });

    $('input#CurrencyInput').on('blur', function () {
        var input = this.value;
        if (!input) {
            return true;
        }
        const value = input.replace(/,/g, '');
        this.value = parseFloat(value).toLocaleString('th', {
            style: 'decimal',
            // maximumFractionDigits: 2,
            // minimumFractionDigits: 2
        });
    });
    $('#datetimepicker4').datetimepicker({
        defaultDate: moment(),
        locale: 'th',
        todayBtn: "linked",
        ignoreReadonly: true,
        allowInputToggle: true,
        language: "th",
        todayHighlight: true,
        format: "DD/MM/YYYY",
        autoclose: true
    });
    $('#datetimepicker3').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true,
        format: "HH:mm",
        autoclose: true
    });
});

function spiderman(s) {
    s.value = s.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
}

function countDown(i = 10, str = null, cla = null) {
    var int = setInterval(function () {
        //console.info(i);
        document.getElementsByClassName(cla)[0].innerText = "กรุณาโอนเงินเข้าบัญชีด้านบนภายใน "+i+" วินาที";
        if (i < 1) {
            document.getElementsByClassName(cla)[0].innerText = str;
            clearInterval(int);
        }
        i--;
    }, 1000);
}

function autoDash(s) {
    s.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
    adddash = s.replace(/\D[^\.]/g, "");
    s = adddash.slice(0, 3) + "-" + adddash.slice(3, 4) + "-" + adddash.slice(4, 9) + "-" + adddash.slice(9);
    return s;
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

$('#deposit').on("submit",function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);
    var next = checkvaildpost();
    if(next===true){
        $.ajax({
            url: sendDepositUrl,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function (data) {
                if (data.result !== 'success') {
                    toastr.error("เนื่องจากมีปัญหาด้านเทคนิค กรุณาติดต่อแอดมิน หรือ ลองใหม่อีกครั้ง", "Error");
                    $('.step4text').html("การแจ้งโอนไม่สำเร็จ");
                    $('#step4badge').removeClass("badge-success");
                    $('#step4badge').addClass("badge-danger");
                } else if (data.result === 'show_message') {
                    toastr.error(data.message, "Error");
                    $('.step4text').html("การแจ้งโอนไม่สำเร็จ");
                    $('#step4badge').removeClass("badge-success");
                    $('#step4badge').addClass("badge-danger");
                } else {
                    if (data.result === "success") {
                        toastr.success("ส่งข้อมูลสำเร็จ", "Success");
                        setTimeout(function() {
                            window.location.replace(successUrl+'?id='+data.id);
                        }, 1000);
                    } else {
                        if (data.error === 0) {
                            toastr.error("การแจ้งโอนครั้งล่าสุดคุณ รอคิวตรวจสอบ", "Error");
                        } else if (data.error === 1) {
                            toastr.error("การแจ้งโอนครั้งล่าสุดคุณ รอยอดจากธนาคาร", "Error");
                        } else if (data.error === 2) {
                            toastr.error("การแจ้งโอนครั้งล่าสุดคุณ รอเพิ่มเคดิต", "Error");
                        } else if (data.error === 95) {
                            toastr.error("ถอนขั้นต่ำ 100 และไม่เกิน 50,000 บาท ต่อครั้ง", "Error");
                        } else if (data.error === 75) {
                            toastr.error("ธนาคารปิดปรับปรุงประจำวัน 23.00 - 0.10 โปรดรอทำรายการฝากใหม่นอกเวลาดังกล่าว", "Error");
                        } else {
                            toastr.error("เนื่องจากมีปัญหาด้านเทคนิค กรุณาติดต่อแอดมิน หรือ ลองใหม่อีกครั้ง", "Error");
                        }
                        $('.confirmdeposit').prop('disabled', false).html("ยืนยันการแจ้งโอนเงิน");
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

$("#bordermybank").change(function() {
    if($("#bordermybank").hasClass("border-danger")){
        $("#bordermybank").removeClass("border-danger");
    }
});
$("#borderbankway").change(function() {
    if($("#borderbankway").hasClass("border-danger")){
        $("#borderbankway").removeClass("border-danger");
    }
});
$(".moneyinput").change(function() {
    if($(".moneyinput").hasClass("border-danger")){
        $(".moneyinput").removeClass("border-danger");
    }
});
$("#date").change(function() {
    if($("#date").hasClass("border-danger")){
        $("#date").removeClass("border-danger");
    }
});
var timeint = null;
var timehightlight = null;
function checkvaildpost(){
    var bank = $('select[name=mybank] option:selected', '#deposit').val();
    var svbank = $('select[name=svbank] option:selected', '#deposit').val();
    var channel = $('select[name=channel] option:selected', '#deposit').val();
    var money = $('input[name=money]', '#deposit').val();
    var mydate = $('input[name=date]', '#deposit').val();
    var mytime = $('input[name=time]', '#deposit').val();
    var patterndate = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    var patterntime = /^([0-1][0-9]|2[0-3]):([0-5][0-9])$/;
    var moneys = money.replace(/,/g, '');
    var svex = svbank.split("-");
    // console.log(svex.length);
    // var myex = mybank.split("-");
    // console.log(channel);

    if (svex.length !== 3) {
        toastr.error("Loading Error", "Error");
        return false;
    }  else if (bank === '') {
        toastr.error("กรุณาเลือกบัญชีธนาคารของลูกค้า", "Error");
        return false;
    }else if (!channel) {
        toastr.error("กรุณาเลือกช่องทางการโอนเงิน", "Error");
        return false;
    } else if (svbank==="blank") {
        toastr.error("กรุณาเลือกธนาคาร", "Error");
        return false;
    }  else if (!money) {
        toastr.error("กรุณาใส่จำนวนเงิน", "Error");
        $(".moneyinput").addClass("border-danger");
        $(".moneyinput").focus();
        return false;
    } else if (parseInt(moneys) < 5 || parseInt(moneys) > 1000000) {
        toastr.error("ฝากขั้นต่ำ 5 และไม่เกิน 100,000 บาท ต่อครั้ง", "Error");
        $(".moneyinput").addClass("border-danger");
        $(".moneyinput").focus();
        return false;
    } else if (patterndate.test(mydate) === false) {
        toastr.error("กรุณาระบุวันที่", "Error");
        return false;
    } else if (patterntime.test(mytime) === false) {
        toastr.error("กรุณาระบุเวลา", "Error");
        return false;
    }

    var dt = moment().tz('Asia/Bangkok');
    var d = mydate.split('/');
    var t = mytime.split(':');
    var mydt = new Date(d[2], d[1]-1, d[0], t[0], t[1], 0);
    // if (dt.valueOf()-mydt.getTime() < 180*1000) {
    //     //console.log('too fast : '+(dt.valueOf()-mydt.getTime()));
    //     //lang.deposit_too_fast
    //     // ((dt.getTime()-mydt.getTime())/1000).toFixed(0)
    //     //console.info(180-((dt.getTime()-mydt.getTime())/1000).toFixed(0));
    //     var secdeposit = (180-((dt.valueOf()-mydt.getTime())/1000).toFixed(0));
    //     toastr.warning("กรุณารอ "+secdeposit+" วินาที จึงจะสามารถแจ้งโอนได้", "Warning");
    //     clearInterval(timeint);
    //     countDown_deposit(secdeposit,"ยืนยันการแจ้งโอนเงิน","confirmdeposit");
    //     return false;
    // }

    postbdata = ({
        'accname': svex[1],
        'accno': svex[2],
        'totalmoney': moneys,
        'transferdate': mydate,
        'transfertime': mytime,
        'bankId': svex[0],
    });
    window.localStorage.setItem('poston', JSON.stringify(postbdata));
    $('.confirmdeposit').prop('disabled', true).html("กำลังส่งข้อมูล");
    return true;
}

function countDown_deposit(i = 10, str = null) {
    timeint = setInterval(function () {
        //console.info(i);
        if(!timehightlight){
            blinking($(".confirmdeposit"));
        }
        document.getElementsByClassName("confirmdeposit")[0].innerText = "ท่านสามารถแจ้งโอนได้ใน "+i+" วินาที";
        if($(".confirmdeposit").hasClass("btn-success")){
            $(".confirmdeposit").removeClass("btn-success");
            $(".confirmdeposit").addClass("btn-warning");
        }
        if (i < 1) {
            document.getElementsByClassName("confirmdeposit")[0].innerText = str;
            clearInterval(timeint);
            clearInterval(timehightlight);
            if($(".confirmdeposit").hasClass("btn-warning")){
                $(".confirmdeposit").removeClass("btn-warning");
                $(".confirmdeposit").addClass("btn-success");
                $(".confirmdeposit").stop(true).fadeTo("slow",1.0);
            }
        }
        i--;
    }, 1000);
}


function blinking(elm) {
    timehightlight = setInterval(blink, 200);
    function blink() {
        elm.fadeTo("slow", 0.4).fadeTo("slow", 1.0);
    }
}
