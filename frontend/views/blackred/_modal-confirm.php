<?php
use yii\helpers\Url;
use common\libs\Constants;

$min = Constants::minimum_set_all;
$max = Constants::maximum_set_all;

$js = <<<EOT
    //กดแทง
var max = $max;
var min = $min;
$('.post_number').on('click',function(e){
    var buyBlack = document.getElementById('buy-black');
	var buyRed = document.getElementById('buy-red');
	var playBlack = $('#play-black').val();
	var playRed = $('#play-red').val();
	if(buyBlack.style.display == 'none') {
	    playBlack = 0;
	}
	if(buyRed.style.display == 'none') {
	    playRed = 0;
	}
	var selectBlackRed = $("#select-blackred").find(':selected').val();
	var messageError = '';
	if (!selectBlackRed){
	    messageError = 'คุณไม่ได้เลือกรอบที่แทงดำแดง';
	} else if (playBlack > max || playRed > max) {
	    messageError = 'คุณเล่นเกินจำนวนที่กำหนด '+max;
	} else if (playBlack <= 0 && playRed <= 0) {
	    messageError = 'คุณเล่นต่ำกำหนด '+min;
	}
			

	if(messageError != ''){
        swal({
          title: 'Warning!',
          text: messageError,
          type: 'warning',
          confirmButtonText: 'OK'
        });	
	}else{
	    $('#selectBlackRed').val(selectBlackRed);
	    $('#playBlack').val(playBlack);
	    $('#playRed').val(playRed);
        $('#blackred_form').submit();
    }
	e.preventDefault();
}); 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date ( 'Y-m-d H:i:s' );
?>


<!-- Modal -->
<div class="modal fade" id="play-confirm" tabindex="-1" role="dialog"
	aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">ดูโพยอีกครั้งก่อนยืนยันการแทงพนัน <br><small class="font-bold" id="round"></small></h4>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-5">
						<button type="button" class="btn btn-primary btn-block btn-lg post_number">
							<i class="fa fa-check"></i> ส่งโพย
						</button>
					</div>
					<div class="col-xs-5">
						<button type="button" data-dismiss="modal"
							class="btn btn-danger btn-block btn-lg">ยกเลิก</button>
					</div>
				</div>
				<div class="row">
    				<div class="col-xs-12">
    					<div class="row modal-confirm-body">
                            <div class="col-xs-12">
                                <h4 class="pull-left">ประเภท</h4>
                                <h4 class="pull-right">ราคา</h4>
                                <div class="clearfix"></div>
                                <div class="row" id="confirm-black-red">
                                </div>
                                <hr>
                            </div>
    					</div>
    				</div>
        			<div class="col-xs-12 text-right">
        				<strong>รวม :</strong> <strong class="total_play_number"></strong>
        			</div>
        		</div>
    			<div class="row">
					<div class="col-xs-5">
						<button type="button" class="btn btn-primary btn-block btn-lg post_number">
							<i class="fa fa-check"></i> ส่งโพย
						</button>
					</div>
					<div class="col-xs-5">
						<button type="button" data-dismiss="modal"
							class="btn btn-danger btn-block btn-lg">ยกเลิก</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>