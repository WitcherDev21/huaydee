<?php

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAssetWinWheel;
use common\libs\Constants;

$uri = Yii::getAlias('@web');
AppAssetWinWheel::register($this);

$apiUri = Url::to([
    'blackred/get-blackred-result'
]);
$apiBlackRedUrl = Url::to(['blackred/get-select-blackred']);
$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$status_finish_show_result = Constants::status_finish_show_result;
$swal_width = 50;
$minimum_set_all = Constants::minimum_set_all;
$maximum_set_all = Constants::maximum_set_all;
$jackpotBlack = number_format($allPlayType[0]['jackpot'], 2);
$jackpotRed = number_format($allPlayType[1]['jackpot'], 2);
$js = <<<EOT

//$('select[name="select_blackred_id"]').on('change',function(e){
//	$('#selected_blackred_zone').submit();
//});

var totalPlay = 0;
var minimum_set_all = $minimum_set_all;
var maximum_set_all = $maximum_set_all;
var oldBlackred = '';
var jackpotBlackPoint = $jackpotBlack;
var jackpotRedPoint = $jackpotRed;
var totalCoin = 0;
function getBlackredResult(){
	var blackred_id = $("#select-blackred").find(':selected').val();
	var blackred_status = $('input[name="blackred_status"]').val();
	var blackred_result = $('input[name="blackred_result"]').val();
	var z = setInterval(function(){
	$.ajax({
	    url: '$apiUri',
		type: 'post',
		data: {
		    '$csrf':'$token',
		    'blackred_id':blackred_id
		},
		success: function (data) {
		    if(data.state == 'finish'){
	       	    stopsWing(data.result);
	       		clearInterval(z);
			}else{
		       	continueWing();
			}
		}
	});	       	
},3000);
}
function startCountdown () {   				
//countdown
    var x = setInterval(function() {
        var now = new Date().getTime();
        var blackred = $('#select-blackred').val();
        if (!blackred) {
            return false;
        }
        var selectFinish = selectBlackred(blackred);
        selectFinish.success(function (data) {
            var countDownDate = (data.finish_at - 30) * 1000; //php to java time
            var distance = countDownDate - now;
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
            var h = (hours<10?'0':'') + hours;
            var m = (minutes<10?'0':'') + minutes;
            var s = (seconds<10?'0':'') + seconds;
    
            var text = h+':'+m+':'+s;
            $('#countdown-item').text(text);
            continueWing();
    
            if(distance < 0) {
                getBlackredResult();
                clearInterval(x);
                $('#countdown-item').text('ปิดรับแทง');
            }else{
                continueWing();
            }
        });
        
    }, 1000);
}
$(window).load(function() {
  startCountdown();
});


function selectBlackred(blackred_id) {
     if (!blackred_id) {
        return false;
     }
     var old
     return $.ajax({
	    url: '$apiBlackRedUrl',
		type: 'post',
		data: {
		    '$csrf':'$token',
		    'blackred_id':blackred_id
		},
		success: function (data) {
		   data.finish_at;
		}
	});	       	
}

$('.btn-play-black').on('click',function(){
	var tapContentMoney = document.getElementById('tap-content-money');
	var conditionBlack = document.getElementById('condition-black');
	var conditionRed = document.getElementById('condition-red');
	conditionRed.style.display = 'none';
	var buyBlack = document.getElementById('buy-black');
	var buyRed = document.getElementById('buy-red');
	buyRed.style.display = 'none';
	var buttonBuy = document.getElementById('button-buy');
	var setAllPrice = document.getElementById('set-all-price');
	var playBlack = $('#play-black').val();
    if(conditionBlack.style.display == 'block'){
       conditionBlack.style.display = 'none';
       buyBlack.style.display = 'none';
       if(buyRed.style.display == 'none' && buyBlack.style.display == 'none') {
           setAllPrice.style.display = 'none';
           buttonBuy.style.display = 'none';
       }
       calc();
    }else{
       conditionBlack.style.display = 'block';
       buyBlack.style.display = 'block';
       setAllPrice.style.display = 'block';
       buttonBuy.style.display = 'block';
       calc();
    }
});

$('.btn-play-red').on('click',function(){
	var tapContentMoney = document.getElementById('tap-content-money');
	var conditionRed = document.getElementById('condition-red');
	var buyBlack = document.getElementById('buy-black');
	var conditionBlack = document.getElementById('condition-black');
	conditionBlack.style.display = 'none';
	buyBlack.style.display = 'none';
	var buyRed = document.getElementById('buy-red');
	var setAllPrice = document.getElementById('set-all-price');
	var buttonBuy = document.getElementById('button-buy');
	var setAllPrice = document.getElementById('set-all-price');
	var playRed = $('#play-red').val();
    if(conditionRed.style.display == 'block'){
        conditionRed.style.display = 'none';
        buyRed.style.display = 'none';
        if(buyRed.style.display == 'none' && buyBlack.style.display == 'none') {
           setAllPrice.style.display = 'none';
           buttonBuy.style.display = 'none';
       }
       calc();
    }else{
        conditionRed.style.display = 'block';
        buyRed.style.display = 'block';
        setAllPrice.style.display = 'block';
        buttonBuy.style.display = 'block';
        calc();
    }
});

$(".play-number").bind({
    'input':function(){
        calc();
    }
});

$("#set-all-price").bind({
    'input':function(){
        setAllPrice();
    }
});

$('#btn-set-all-price').on('click',function(e){
    swal({
      width: '$swal_width%',
      title: 'Confirm?',
      html: '<div style="font-size:18px;">'+'ต้องการปรับราคาแทงทุกรายการเท่ากัน?'+'</div>',
      type: 'info',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน'
    }).then((result) => {
      if (result.value) {
        setAllPrice();
      }
    })

});

$('.coin').on('click',function(e){
    
    if (this.id == 'coin-1'){
        totalCoin += 1;
    } else if (this.id == 'coin-10'){
        totalCoin += 10;
    } else if (this.id == 'coin-50'){
        totalCoin += 50;
    } else if (this.id == 'coin-100'){
        totalCoin += 100;
    } else if (this.id == 'coin-500'){
        totalCoin += 500;
    }
    var priceRed = $('#play-red').val(totalCoin);
    var priceBlack = $('#play-black').val(totalCoin);
    calc();
});

function setAllPrice(){
	var all_price = $('input[name="set_all_price"]').val();	
    if(all_price != ''){
    	var validate = validateInputPrice(all_price);
    }
    if (validate) {
        totalPlay = 0;
        var buyBlack = document.getElementById('buy-black');
        var buyRed = document.getElementById('buy-red');
        var priceRed = $('#play-red').val(all_price);
        var priceBlack = $('#play-black').val(all_price);
        if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
            totalPlay = parseInt(priceRed.val()) + parseInt(priceBlack.val());
        }  else {
            totalPlay = priceRed == 'block' ? parseInt(priceRed.val()) : parseInt(priceBlack.val());
        }
        var jackpotTextBlack = all_price * jackpotBlackPoint;
        var jackpotTextRed = all_price * jackpotRedPoint;
        $('#jackpotRed').text(jackpotTextRed.toFixed(2));
        $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));
        if (totalPlay) {
            $('.total_play_number').text(totalPlay);
        } else {
            $('.total_play_number').text(0);
        }
    }
}

function calc() 
{
  totalPlay = 0;
  var str_confirm_black = '';
  var str_confirm_red = '';
  var str_confirm = '';
  var priceRed = $('#play-red').val() ? $('#play-red').val() : 0;
  var priceBlack = $('#play-black').val() ? $('#play-black').val() : 0;
  var buyBlack = document.getElementById('buy-black');
  var buyRed = document.getElementById('buy-red');
  var jackpotTextBlack = priceBlack * jackpotBlackPoint;
  var jackpotTextRed = priceRed * jackpotRedPoint;
  $('#jackpotRed').text(jackpotTextRed.toFixed(2));
  $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));
  var validatePriceRed = validateInputPriceByType($('#play-red'));
  var validatePriceBlack = validateInputPriceByType($('#play-black'));    
  if (validatePriceRed && validatePriceBlack) {
      if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
        totalPlay = parseInt(priceRed) + parseInt(priceBlack);
      } else {
        totalPlay = priceRed == 'block' ? parseInt(priceRed) : parseInt(priceBlack);
      } 
      if (!isNaN(totalPlay)) {
         $('.total_play_number').text(totalPlay);
      } else {
        $('.total_play_number').text(0);
      }
      if (buyBlack.style.display == 'block') {
        str_confirm_black += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข ดำ</div>
             <div class="col-xs-6 text-right">`+totalPlay+`</div><hr>`;
      }
      if (buyRed.style.display == 'block') {
        str_confirm_red += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข แดง</div>
             <div class="col-xs-6 text-right">`+totalPlay+`</div><hr>`;
      }
      str_confirm = str_confirm_black + str_confirm_red;   
      $('#confirm-black-red').html(str_confirm);
  }else {
      if (buyBlack.style.display == 'block' && priceBlack > maximum_set_all) {
        str_confirm_black += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข ดำ</div>
             <div class="col-xs-6 text-right">`+maximum_set_all+`</div><hr>`;
      }
      else if (buyRed.style.display == 'block' && priceRed > maximum_set_all) {
        str_confirm_red += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข แดง</div>
             <div class="col-xs-6 text-right">`+maximum_set_all+`</div><hr>`;
      }
      else if (buyBlack.style.display == 'block' && priceBlack < minimum_set_all) {
        str_confirm_red += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข ดำ</div>
             <div class="col-xs-6 text-right">`+minimum_set_all+`</div><hr>`;
      }
      else if (buyRed.style.display == 'block' && priceRed < minimum_set_all) {
         str_confirm_red += 
            `<div class="col-xs-6 m-t-none m-b-xs">แทงเลข แดง</div>
             <div class="col-xs-6 text-right">`+minimum_set_all+`</div><hr>`;
      }
      str_confirm = str_confirm_black + str_confirm_red;   
      $('#confirm-black-red').html(str_confirm);
  }
}

function validateInputPriceByType(id)
{
    var buyBlack = document.getElementById('buy-black');
    var buyRed = document.getElementById('buy-red');
    if(id.val() < minimum_set_all){
        var jackpotTextBlack = minimum_set_all * jackpotBlackPoint;
        var jackpotTextRed = minimum_set_all * jackpotRedPoint;  
        $('#jackpotRed').text(jackpotTextRed.toFixed(2));
        $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));  
        swal({
            width: '$swal_width%',
            title: 'Warning!',
            html: '<div style="font-size:18px;">'+'กรุณาระบุเงินขั้นต่ำอีกครั้ง '+minimum_set_all+' ขึ้นไป</div>',
            type: 'warning',
            confirmButtonText: 'close'
        });
        $(id).val(minimum_set_all);
        if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
            $('.total_play_number').text(minimum_set_all * 2);
        }else{
            $('.total_play_number').text(minimum_set_all);
        }
        return false;
    }else if(id.val() > maximum_set_all){
        var jackpotTextBlack = maximum_set_all * jackpotBlackPoint;
        var jackpotTextRed = maximum_set_all * jackpotRedPoint;  
        $('#jackpotRed').text(jackpotTextRed.toFixed(2));
        $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));  
        swal({
            width: '$swal_width%',
            title: 'Warning!',
            html: '<div style="font-size:18px;">'+'ใส่ราคาได้ไม่เกิน '+maximum_set_all+' </div>', 
            type: 'warning',
            confirmButtonText: 'close'
        });
        $(id).val(maximum_set_all);
        if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
            $('.total_play_number').text(maximum_set_all * 2);
        }else{
            $('.total_play_number').text(maximum_set_all);
        }
        return false;
    }
    return true;
}

function validateInputPrice(all_price)
{
    var buyBlack = document.getElementById('buy-black');
    var buyRed = document.getElementById('buy-red');
    if(all_price < minimum_set_all){
        swal({
            width: '$swal_width%',
            title: 'Warning!',
            html: '<div style="font-size:18px;">'+'กรุณาระบุเงินขั้นต่ำอีกครั้ง '+minimum_set_all+' ขึ้นไป</div>',
            type: 'warning',
            confirmButtonText: 'close'
        });
        $('input[name="set_all_price"]').val(minimum_set_all);
        $('.play-number').val(minimum_set_all);
        var jackpotTextBlack = minimum_set_all * jackpotBlackPoint;
        var jackpotTextRed = minimum_set_all * jackpotRedPoint;  
        $('#jackpotRed').text(jackpotTextRed.toFixed(2));
        $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));  
        if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
            $('.total_play_number').text(minimum_set_all * 2);
        }else{
            $('.total_play_number').text(minimum_set_all);
        }
        return false;
    }else if(all_price > maximum_set_all){
        swal({
            width: '$swal_width%',
            title: 'Warning!',
            html: '<div style="font-size:18px;">'+'ใส่ราคาได้ไม่เกิน '+maximum_set_all+' </div>', 
            type: 'warning',
            confirmButtonText: 'close'
        });
        var t = $('input[name="set_all_price"]').val(maximum_set_all);
        var jackpotTextBlack = maximum_set_all * jackpotBlackPoint;
        var jackpotTextRed = maximum_set_all * jackpotRedPoint;  
        $('#jackpotRed').text(jackpotTextRed.toFixed(2));
        $('#jackpotBlack').text(jackpotTextBlack.toFixed(2));  
        $('.play-number').val(maximum_set_all);
        if (buyBlack.style.display == 'block' && buyRed.style.display == 'block') {
            $('.total_play_number').text(maximum_set_all * 2);
        }else{
            $('.total_play_number').text(maximum_set_all);
        }
        return false;
    }
    return true;
}

$('.clear_number').on('click',function(){
    swal({
      width: '$swal_width%',
      title: 'ยืนยันการล้างข้อมูล',
      html: "<div style='font-size:18px;'>ลบชุดดำแดงที่คุณได้เลือกไว้ทั้งหมด</div>",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ใช่',
      cancelButtonText: 'ไม่ใช่'
    }).then((result) => {
      if (result.value) {
	    document.getElementById('buy-black').style.display = 'none';
        document.getElementById('buy-red').style.display = 'none';
        document.getElementById('condition-black').style.display = 'none';
        document.getElementById('condition-red').style.display = 'none';
        document.getElementById('set-all-price').style.display = 'none';
        $('#play-black').val(minimum_set_all);
        $('#play-red').val(minimum_set_all);
        $('.total_play_number').text(minimum_set_all);
        $('#confirm-black-red').html('');
        totalCoin = 0;
      }
    });
//
});
var textSelected = $(this).find(':selected').text();
$('#round').text(textSelected);
$("#select-blackred").change(function (e) {
    var textSelected = $(this).find(':selected').text();
    $('#round').text(textSelected);
    startCountdown();
});

$('#refresh').on('click',function(){
     location.reload();
});

/*	       				
$('.play-btn').on('click',function(e){	
    console.log('2222');	       				
	form_id = $(this).data('form');
	amount = $('#'+form_id).find('input[name="amount"]').val();
	if(amount>0){
       	if(confirm('ยืนยันการซื้อ')){
		    $('#'+form_id).submit();
		}
	}else{
		alert('กรุณาระบุจำนวนเงิน');	
		e.preventDefault();
		return false;
	}
e.preventDefault();
return false;	
});
*/
EOT;
$this->registerJs($js);
$imgUrlPointer =
$css = <<<EOT
#canvasContainer {
    position: relative;

}
 
#canvas {
    z-index: 1;
}
 
#prizePointer {
    position: absolute;
    display: block;
    margin-left: 45%;
    margin-right: auto;
    top: 0px;
    z-index: 999;
}

.btn-play-black:hover{
	background-color:#000000 !important;
	color: #ffffff;
}

.btn-play-red:hover{
	background-color:#ff0000 !important;
	color: #ffffff;
	
}
.btn-play-red:link, .btn-play-red:visited, .btn-play-black:link, .btn-play-black:visited{
	color: #ffffff;
}
EOT;
$this->registerCss($css);
$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
?>
<form id="blackred_form" action="<?= Url::toRoute(['buy-blackred']) ?>" method="post">
    <?= Html::hiddenInput('selectBlackRed', '', ['id' => 'selectBlackRed']) ?>
    <?= Html::hiddenInput('playBlack', '', ['id' => 'playBlack']) ?>
    <?= Html::hiddenInput('playRed', '', ['id' => 'playRed']) ?>
    <?= Html::hiddenInput($csrf, $token) ?>
</form>

<div class="col-md-4 col-sm-12">
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body" style="height:100%; overflow-y: scroll;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col" colspan="2">สถิติ 20 ตาล่าสุด <span id="refresh"
                                                                                class="glyphicon glyphicon-refresh"
                                                                                style="cursor: pointer;"></span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>รอบ</th>
                            <th>ผล</th>
                        </tr>
                        <?php foreach ($arrBlackredTopTen as $model) { ?>
                            <tr>
                                <td><?= $model->round ?></td>
                                <td><?= Constants::$blackred_result[$model->result] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">

                    <!-- <canvas id="canvas"  width="400" height="400">Canvas not supported</canvas> -->
                    <?php ActiveForm::begin([
                        'id' => 'selected_blackred_zone',
                        'enableClientScript' => false,
                    ]) ?>
                    <?= Html::hiddenInput('blackred_id', $blackred->id) ?>
                    <?= Html::hiddenInput('blackred_status', $blackred->status) ?>
                    <?= Html::hiddenInput('blackred_result', $blackred->result) ?>
                    <?= Html::dropDownList(
                        'select_blackred_id',
                        $blackred->id,
                        $arrBlackredRound,
                        [
                            'id' => 'select-blackred',
                            'class' => 'selectpicker form-control',
                            'data-live-search' => 'true',
                        ]
                    ) ?>

                    <h4 class="text-center" style="color: #ed5565;">ปิดรับการทายผลดำแดง</h4>
                    <h4 class="text-center"><span style="font-size: 32px; color: #ed5565;" id="countdown-item"
                                                  data-finish_at="<?= strtotime($blackred->finish_at) ?>"><?php echo date("H:i:s", (strtotime(date('Y-m-d 00:00:00')) + strtotime($blackred->finish_at) - time())) ?></span>
                    </h4>

                    <?php if ($blackred->status == Constants::status_active) { ?>
                        <div id="canvasContainer" align="center">
                            <canvas id="canvas" width="auto" height="380">
                                Canvas not supported, please user another browser.
                            </canvas>
                            <img id="prizePointer"
                                 src="<?= Yii::getAlias('@web/wheel/examples/basic_pointer.png') ?>"
                                 alt="V"/>
                        </div>
                    <?php } else { ?>
                        <h4 class="text-center">
					<span style="font-size: 32px; color: #808080;">ผลลัพธ์ :
					<span style="font-size: 32px; color: #ffffff;" class="label label-red">
					<?= isset(Constants::$blackred_result[$blackred->result]) ? Constants::$blackred_result[$blackred->result] : '' ?>
					</span>
					</span>
                        </h4>
                    <?php } ?>

                    <?php ActiveForm::end() ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12">
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body" style="margin-bottom: 10px;">
                    <h3 class="text-center">เล่นเกมส์</h3>
                    <div class="col-xs-12 col-md-6 col-sm-6">
                        <button type="button" class="btn btn-dark btn-lg btn-block btn-play-black"
                                data-toggle="modal"
                                data-target="#exampleModalCenter-black" style="color: white">ดำ
                        </button>
                    </div>
                    <div class="col-xs-12 col-md-6 col-sm-6">
                        <button type="button" class="btn btn-dark btn-lg btn-block btn-play-red" data-toggle="modal"
                                style="background-color: red; color: white" data-target="#exampleModalCenter-red">แดง
                        </button>
                    </div>
                </div>
                <h4 class="card-header bg-transparent">รายการแทง</h4>
                <div class="card-body">
                    <form class="from-control" id="form-play">
                        <div id="buy-black" class="panel panel-default" style="display: none;">
                            <div class="panel-heading">
                                <h3 class="panel-title">ดำ</h3>
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <input type="number" value="1" class="form-control play-number"
                                           id="play-black" min="1" max="500">
                                    <span id="jackpotBlack" class="input-group-addon"
                                          style="padding:5px;"> ชนะ <?= number_format($allPlayType[0]['jackpot'], 2) ?></span>
                                    <span class="input-group-btn">
				                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="buy-red" class="panel panel-default" style="display: none;">
                            <div class="panel-heading">
                                <h3 class="panel-title">แดง</h3>
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <input type="number" value="1" class="form-control play-number"
                                           id="play-red" min="1" max="500">
                                    <span id="jackpotRed" class="input-group-addon"
                                          style="padding:5px;"> ชนะ <?= number_format($allPlayType[1]['jackpot'], 2) ?></span>
                                    <span class="input-group-btn">
				                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="panel panel-default" style="display: none;" id="set-all-price">
                        <div class="panel-body">
                            <div class='row'>
                                <div class="col-offset-1 col-md-2 offset-1 col-2">
                                    <?= Html::img(['img/1.png'], ['width' => '100px;', 'class' => 'img-fluid img-center coin', 'id' => 'coin-1', 'style' => 'cursor: pointer;']); ?>
                                </div>
                                <div class="col-md-2 col-2">
                                    <?= Html::img(['img/10.png'], ['width' => '100px;', 'class' => 'img-fluid img-center coin', 'id' => 'coin-10', 'style' => 'cursor: pointer;']); ?>
                                </div>
                                <div class="col-md-2 col-2">
                                    <?= Html::img(['img/50.png'], ['width' => '100px;', 'class' => 'img-fluid img-center coin', 'id' => 'coin-50', 'style' => 'cursor: pointer;']); ?>
                                </div>
                                <div class="col-md-2 col-2">
                                    <?= Html::img(['img/100.png'], ['width' => '100px;', 'class' => 'img-fluid img-center coin', 'id' => 'coin-100', 'style' => 'cursor: pointer;']); ?>
                                </div>
                                <div class="col-md-2 col-2">
                                    <?= Html::img(['img/500.png'], ['width' => '100px;', 'class' => 'img-fluid img-center coin', 'id' => 'coin-500', 'style' => 'cursor: pointer;']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="panel panel-default" style="display: none;" id="set-all-price">
                        <div class="panel-heading">
                            <h3 class="panel-title">ปรับเท่ากันหมด</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <input name="set_all_price" type="number" value="1" class="form-control" min="1"
                                       max="500">
                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="button" id="btn-set-all-price">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                            </span>
                            </div>
                        </div>
                    </div> -->
                    <hr>
                    <div id="button-buy" style="display: none;">
                        <div class="col-xs-12">
                            <strong class="total_play" style="">จำนวนเงินทั้งหมด : <span
                                        class="total_play_number">6</span></strong>
                            <input type="hidden" name="total_play_number" value="6">
                            <p></p>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <button type="button" class="btn btn-primary btn-block post_number-btn" style=""
                                    data-toggle="modal" data-target="#play-confirm">แทงพนัน
                            </button>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <button type="button" class="btn btn-danger btn-block clear_number" style="">
                                ล้างข้อมูล
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="card">
                <h4 class="card-header bg-transparent">เงื่อนไขการแทง</h4>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($allPlayType as $playType) { ?>
                            <div class="col-xs-12 condition-play-type" style="display: none;"
                                 id="condition-<?= $playType['code'] ?>">
                                <h4 class="no-margins"><?= $playType['title'] ?></h4>
                                แทงขั้นต่ำ : <?= number_format($playType['min']) ?> <br>
                                แทงสูงสุดต่อครั้ง : <?= number_format($playType['max']) ?> <br>
                                <hr>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<div class="col-xs-12" style="padding-top: 15px;">
    <div class="card">
        <h4 class="card-header bg-transparent">กติกาการเล่น ดำ-<font size="3" color="red">แดง</font></h4>
        <div class="card-body">
            <?= Html::decode($game->rule); ?>
        </div>
    </div>
</div>

<!-- <div class="row tab-pane col-md-12 col-sm-12" id="tap-content-money" style="padding-top: 20px; display: none">
        <div class="col-md-12 ab-pane animated fadeIn" style="background-color: white; margin-left: 20px;">
            <div class="col-md-2 col-6">
                <?= Html::img(['img/1.png'], ['width' => '100px;', 'class' => 'img-center coin', 'id' => 'coin-1', 'style' => 'cursor: pointer;']); ?>
            </div>
            <div class="col-md-2 col-6">
                <?= Html::img(['img/10.png'], ['width' => '100px;', 'class' => 'img-center coin', 'id' => 'coin-10', 'style' => 'cursor: pointer;']); ?>
            </div>
            <div class="col-md-2 col-6">
                <?= Html::img(['img/50.png'], ['width' => '100px;', 'class' => 'img-center coin', 'id' => 'coin-50', 'style' => 'cursor: pointer;']); ?>
            </div>
            <div class="col-md-2 col-6">
                <?= Html::img(['img/100.png'], ['width' => '100px;', 'class' => 'img-center coin', 'id' => 'coin-100', 'style' => 'cursor: pointer;']); ?>
            </div>
            <div class="col-md-2 col-6">
                <?= Html::img(['img/500.png'], ['width' => '100px;', 'class' => 'img-center coin', 'id' => 'coin-500', 'style' => 'cursor: pointer;']); ?>
            </div>
        </div>
    </div> -->

<!-- Modal -->
<?php if (false) {//ยังไม่เปิดให้บริการ?>
    <div class="modal fade" id="exampleModalCenter-black" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="color: #000000;">แทงดำ</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php //ActiveForm::begin(['action'=>['blackred/play-post'],'id'=>'play-black-form'])?>
                    <?= Html::hiddenInput('play_type', Constants::blackred_black) ?>
                    <label>จำนวน</label>
                    <?= Html::input('number', 'amount', '', ['class' => 'form-control']) ?>

                    <?php //ActiveForm::end()?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary play-btn" data-form="play-black-form">แทง</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter-red" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="color: #ff0000;">แทงแดง</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php //ActiveForm::begin(['action'=>['blackred/play-post'],'id'=>'play-red-form'])?>
                    <?= Html::hiddenInput('play_type', Constants::blackred_red) ?>
                    <label>จำนวน</label>
                    <?= Html::input('number', 'amount', '', ['class' => 'form-control']) ?>

                    <?php //ActiveForm::end()?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-primary play-btn" data-form="play-red-form">แทง</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?= $this->render('_modal-confirm'); ?>

