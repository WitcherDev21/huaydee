
$(document).ready(function() {
    new Cleave('.money-withdraw', {
        numericOnly: true,
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });
});
function check_withdraw() {
    $('.btn-success_bkk').prop('disabled', true);
    var bankval = $('input[name=bank]:checked', '#withdraw').val();
    var money = $('input[name=wmoney]', '#withdraw').val();
    //console.info(bankval);
    if(!bankval){
        $('.btn-success_bkk').prop('disabled', false);
        toastr.error("กรุณาเลือกธนาคาร","Error");
        return false;
    }
    if(!money){
        $('.btn-success_bkk').prop('disabled', false);
        toastr.error('กรุณาใส่จำนวนเงิน', "Error");
        return false;
    }
    var moneys = money.replace(/,/g,'');
    if(parseInt(moneys) < 100 || parseInt(moneys) > 100000){
        $('.btn-success_bkk').prop('disabled', false);
        toastr.error('ถอนขั้นต่ำ 300 และไม่เกิน 50,000 บาท ต่อครั้ง', "Error");
        return false;
    }

    var remoney = money.replace(/,/g, "");
    if(parseInt(remoney) > parseInt(credit_global)){
        $('.btn-success_bkk').prop('disabled', false);
        toastr.error('ยอดเงินของคุณไม่เพียงพอ', "Error");
        return false;
    }
    $('#withdraw').submit();
}

$( "#totalwithdraw" ).click(function() {
    console.log(credit_global);
    $('input[name=wmoney]', '#withdraw').val(Math.ceil(credit_global));
});

$(".bank-user").click(function(){
    if ($(this).parent().find("input:radio:checked").length > 0) {
        var oldId = '#'+$(this).parent().find("input:radio:checked").attr('id');
        $(oldId).prop('checked', false);
        var id = '#bank_' + $(this).data('id');
        $(id).prop('checked', true);
    } else {
        var id = '#bank_' + $(this).data('id');
        $(id).prop('checked', true);
    }
});

$(function () {

        intdown = setInterval(function() {
            var credit_private = 0;
            if(parseInt(credit_global) >= 100000){
                credit_private = 100000;
            } else if(parseInt(credit_global) <= 100){
                credit_private = 100;
            } else {
                credit_private = credit_global;
            }
            // console.info(credit_global);
            $('input[name=wmoney]', '#withdraw').val(credit_private);
            clearInterval(intdown);
        }, 500);
});