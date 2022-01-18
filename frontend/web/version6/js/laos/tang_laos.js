var max_num = 4;
var cur_num = 0;
var last_add_num = 0;
var total_price = 0;
var lottery_list_detail = '';
var triggercflaoslotto = 1;
$(document).ready(function (e) {
    $(".btn-cancle-last-add-num").on("click", function () {
        var lottery = JSON.parse(window.localStorage.getItem('lottery'));
        if (lottery.length == 0 || last_add_num == 0) return;
        delete_last_add_num();
    });

    $(".btn-reset-lottery").on("click", function () {
        var lottery = JSON.parse(window.localStorage.getItem('lottery'));
        if (lottery.length == 0) return;
        reset_lottery();
    });

    $(".triggerSendLottery").on("click", function () {
        triggercflaoslotto = 1;
        var lottery = JSON.parse(window.localStorage.getItem('lottery'));
        if (lottery.length == 0 || last_add_num == 0) return;
        $.each(lottery, function (index, value) {
            if (value.amount === "" || value.amount === "0") {
                triggercflaoslotto = 0;
                notify("กรุณาใส่เลขจำนวนชุดเท่ากับ 1 หรือ มากกว่า", "error");
                return false;
            }
        });
        if (triggercflaoslotto === 1) {
            cflaoslotto();
        }
    });
    if ($('#lottery_list').length) {
        lottery_list_detail = $('#lottery_list').prop('innerHTML').replace('d-none', 'd-flex');
        reset_lottery();
    }
});

function define_lottery() {
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    if (!lottery) {
        reset_lottery();
        return;
    }
}

function reset_lottery() {
    var lottery = [];
    window.localStorage.setItem('lottery', JSON.stringify(lottery));
    last_add_num = 0;
    $('#lottery_list').html('');
    $('.total_price').html('0');
    show_bet_num();
}

function delete_last_add_num() {
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    if (lottery.length == 0) return;
    var i = [];
    $.each(lottery, function (index, value) {
        if (lottery[index].last_add_num == last_add_num) {
            i.push(index);
        }
    });
    i.sort(function (a, b) {
        return b - a
    });
    $.each(i, function (index, value) {
        lottery.splice(value, 1);
    });
    last_add_num = last_add_num - 1;
    if (last_add_num <= 0) {
        $('#lottery_list').html('');
        $('.total_price').html('0');
    }
    window.localStorage.setItem('lottery', JSON.stringify(lottery));
    show_bet_num();
}

function show_bet_num() {
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    //console.log('show_bet_num', lottery);
    if (lottery.length <= 0) {
        return;
    }
    var num = [];
    total_price = 0;
    $('#lottery_list').html('');
    $.each(lottery, function (index, value) {
        var add_lottery_list_detail = lottery_list_detail;
        add_lottery_list_detail = add_lottery_list_detail.replace('{lottery-number}', value.number);
        add_lottery_list_detail = add_lottery_list_detail.replace('{lottery-amount}', value.amount);
        add_lottery_list_detail = add_lottery_list_detail.replace(/{lottery-id}/g, index);
        $('#lottery_list').append(add_lottery_list_detail);
        total_price += value.amount * lottery_config.price;
        $('.total_price').html(total_price);
    });
}

function set_lottery_list(add_lottery_list) {
    last_add_num += 1;
    var lottery_add = {'number': add_lottery_list, 'amount': 1, 'last_add_num': last_add_num};
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    var chk_already = false;
    $.each(lottery, function (index, value) {
        if (value.number == add_lottery_list) {
            chk_already = true;
        }
    });
    if (!chk_already) {
        lottery.push(lottery_add);
        window.localStorage.setItem('lottery', JSON.stringify(lottery));
    }
    show_bet_num();
}

function check_max_set(id) {
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    if ($('#lottery_amount_' + id).val() >= lottery_config.max_set) {
        $('#lottery_amount_' + id).val(lottery_config.max_set);
    } else {
        $('#lottery_amount_' + id).val($('#lottery_amount_' + id).val());
    }
    lottery[id].amount = $('#lottery_amount_' + id).val();

    window.localStorage.setItem('lottery', JSON.stringify(lottery));
    total_price = 0;
    $.each(lottery, function (index, value) {
        total_price += value.amount * lottery_config.price;
        $('.total_price').html(total_price);
    });
}

function cflaoslotto() {
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    //console.log('show_bet_num',lottery);
    if (lottery.length <= 0) {
        return;
    }
    var num = [];
    total_price = 0;
    var html_swal = '';
    $.each(lottery, function (index, value) {
        html_swal += '<strong>' + value.number + '</strong> จำนวน ' + value.amount + ' ชุด<BR>';
        total_price += value.amount * lottery_config.price;
    });
    html_swal += '<strong>รวมเป็นเงิน</strong> ' + total_price + ' บาท';
    swal({
        title: "คุณต้องการแทง",
        html: html_swal,
        type: "warning",
        confirmButtonText: "ยืนยันการแทง",
        showCancelButton: true,
        cancelButtonText: "ปิด",
    }).then((confirm_tang) => {
        //console.log(confirm_tang);
        send_lottery();

    });
}

function send_lottery() {
    //ส่งโพยไปที่
    //show('loading',true);
    var lottery = JSON.parse(window.localStorage.getItem('lottery'));
    if (lottery.length == 0) return;
    if (!testTab()) {
        notify('กรุณาใช้งานเพียง tap เดียวเท่านั้น', "error");
        return false;
    }
    $.ajax({
        url: lotteryLaoGameBuyUrl,
        cache: false,
        type: 'post',
        data: {
            lottery_type: 'laos',
            thaiSharedGameId: thaiSharedGameId,
            lottery: JSON.stringify(lottery),
            _csrf: yii.getCsrfToken()
        },
        success: function (data) {
            //console.log('send_lottery', data);
            //show('loading',false);
            // var d = JSON.parse(data);
            var d = data;

            if (d.result == 'SUCCESS') {
                notify('ส่งหวยชุดสำเร็จ', "success");
                reset_lottery();
                // swal({
                //     title:"แทงเรียบร้อย",
                //     type: "success",
                //     showConfirmButton: true,
                //     confirmButtonText: "บันทึกฉลาก หวยลาว ที่ท่านซื้อ",
                //     showCancelButton: true,
                //     cancelButtonText: "ปิด",
                // })
                //     .then((value) => {
                //         if (value) {
                //             window.open("print/"+numjackpot+"/"+money+"/"+sets,"_blank");
                //         }
                //     });
                setTimeout(function () {
                    window.location.replace(lotteryLaoPoyUrl);
                }, 1000);
            } else if (d.result == 'ERROR') {
                //console.log(d.lottery_limit);
                if (d.msg === "NOTOPEN") {
                    notify(gameTitle+'ยังไม่เปิดในตอนนี้', "error");
                } else if (d.msg === "NOMONEY") {
                    notify('จำนวนเงินของคุณไม่พอ', "error");
                } else if (d.msg === "LOTTERY_LIMIT") {
                    notify('เลขชุดนี้เต็มแล้ว กรุณาเปลี่ยนเลขชุดใหม่', "error");
                } else {
                    messages = d.msg;
                    // console.log(messages);
                    notify(messages.join('<br>'), "error");
                }
                hashcsrf = d.token;
                if (undefined !== d.lottery_limit && d.lottery_limit.length > 0) {
                    $.each(d.lottery_limit, function (i, v) {

                        $.each(lottery, function (index, value) {
                            console.log('change limit', value.number, v.number);
                            if (value.number = v.number) {
                                lottery[index].amount = v.lottery_left;
                            }
                        });
                    });
                    window.localStorage.setItem('lottery', JSON.stringify(lottery));
                    show_bet_num();
                }

            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

$(document).keyup(async function (e) {
    if ($('.lottery_amount').is(':focus')) return false;
    console.log('keyup', $('.lottery_amount').is(':focus'));
    if ($('#price').hasClass('pagemodal-wrapper open')) return true;
    if ($('#printpoy').hasClass('pagemodal-wrapper open')) return true;
    if ($('#sendpoy').hasClass('pagemodal-wrapper open')) return true;
    let chr;
    if (e.keyCode >= 48 && e.keyCode <= 57) {
        let keyCode = e.keyCode;
        let chrCode = keyCode - 48 * Math.floor(keyCode / 48);
        chr = String.fromCharCode((96 <= keyCode) ? chrCode : keyCode);
    }
    if ((e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode === 8)) {
        chr = e.key;
    }
    //console.info(chr);
    if (chr) {
        if (cur_num <= 0) {
            cur_num = 0;
        }
        if (chr === "Backspace" /*&& cur_num <= (max_num-1)*/) {
            $('.box__show-number .lists .number').eq(cur_num).html('');
            if (cur_num <= 0) {
                $('.box__show-number .lists .number').eq(0).html('<span></span>');
            } else {
                $('.box__show-number .lists .number').eq(cur_num - 1).html('<span></span>');
            }
            cur_num--
        } else {
            var reg = new RegExp('^\\d+$');
            if (!reg.test(chr)) return true;
            $('.box__show-number .lists .number').eq(cur_num).html(chr);
            $('.box__show-number .lists .number').eq(cur_num + 1).html('<span></span>');
            cur_num++
        }
        if (cur_num == max_num && max_num > 0) {
            await sleep(200);
            cur_num = 0;
            var num = $('.box__show-number .lists .number').eq(0).html();
            var num2 = '';
            var num3 = '';
            var num4 = '';
            if (max_num > 1) num2 = $('.box__show-number .lists .number').eq(1).html();
            if (max_num > 2) num3 = $('.box__show-number .lists .number').eq(2).html();
            if (max_num > 3) num4 = $('.box__show-number .lists .number').eq(3).html();
            //รับค่าส่งตามใจชอบ
            console.info(num + num2 + num3 + num4);
            set_lottery_list(num + num2 + num3 + num4);
            $('.box__show-number .lists .number').html('');
        } else {
            return;
        }
    }
});

$('.box__show-number .box__keyboard button').click(async function () {
    //console.info($(this).data('id'));
    if (cur_num <= 0) {
        cur_num = 0;
    }
    if ($(this).data('id') == "delete" /*&& cur_num <= (max_num-1)*/) {
        $('.box__show-number .lists .number').eq(cur_num).html('');
        if (cur_num <= 0) {
            $('.box__show-number .lists .number').eq(0).html('<span></span>');
        } else {
            $('.box__show-number .lists .number').eq(cur_num - 1).html('<span></span>');
        }
        cur_num--
    } else {
        var reg = new RegExp('^\\d+$');
        if (!reg.test($(this).data('id'))) return true;
        $('.box__show-number .lists .number').eq(cur_num).html($(this).data('id'));
        $('.box__show-number .lists .number').eq(cur_num + 1).html('<span></span>');
        cur_num++
    }
    if (cur_num == max_num && max_num > 0) {
        await sleep(10);
        cur_num = 0;
        var num = $('.box__show-number .lists .number').eq(0).html();
        var num2 = '';
        var num3 = '';
        var num4 = '';
        if (max_num > 1) num2 = $('.box__show-number .lists .number').eq(1).html();
        if (max_num > 2) num3 = $('.box__show-number .lists .number').eq(2).html();
        if (max_num > 3) num4 = $('.box__show-number .lists .number').eq(3).html();
        //รับค่าส่งตามใจชอบ
        console.info(num + num2 + num3 + num4);
        set_lottery_list(num + num2 + num3 + num4);
        $('.box__show-number .lists .number').html('');
    } else {
        return;
    }
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$('#laosPoy').on('hidden.bs.modal', function () {
    var $modal = $(this);
    $modal.find('.edit-content').attr("src", loadingImage);
})

$('#laosPoy').on('show.bs.modal', function (e) {

    var $modal = $(this),
        dataid = e.relatedTarget.id;
    $.ajax({
        cache: false,
        type: 'POST',
        url: printPoyUrl,
        data: {
            pic: dataid,
            _csrf: yii.getCsrfToken()
        },
        success: function (data) {
            //console.info(data);
            // var s = JSON.parse(data);
            var s = data;
            $modal.find('.edit-content').attr("src", s.img);
            hashcsrf = s.token;
        }
    });

});

$('.saveimages').on('click', function () {
    window.open($('.edit-content').attr('src').replace('image/png', 'image/octet-stream'), '_blank');
});