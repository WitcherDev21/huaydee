<?php
use common\libs\Constants;

$max = Constants::maximum_play_per_chit;
$js = <<<EOT
    //กดแทง
var max = $max;
$('.post_number').on('click',function(e){
	data = $('input[name="play_data"]').val();
	
    play = $('input[name="total_play_number"]').val();
	is_pass = 0;
    title = '';

	if(data != ''){
		obj = JSON.parse(data);
		data_draw = {};
		$.each(obj,function(i,item){
			if(item != '{}'){
				is_pass = 1;
                title = 'คุณยังไม่ได้เลิอกเลขในการแทง';			
			}
		});
	}			

    if(play > max){
        is_pass = 0;
        is_pass = 1;
        title = 'เล่นเกินจำนวนสูงสุดต่อโพย : '+max;
    }
	if(is_pass != 1){
        swal({
          title: 'Warning!',
          text: title,
          type: 'warning',
          confirmButtonText: 'OK'
        });	
	}else{
	    $('.post_number').prop("disabled", true);
        $('#post_form').submit();
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
                <h4 class="modal-title">ดูโพยอีกครั้งก่อนยืนยันการแทงพนัน <br><small class="font-bold"><?= $thaiSharedGame->title.' '.date('Y-m-d', strtotime($thaiSharedGame->startDate)) ?></small></h4>
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
                                class="btn btn-danger_bkk btn-block btn-lg">ยกเลิก</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row modal-confirm-body">
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
                                class="btn btn-danger_bkk btn-block btn-lg">ยกเลิก</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
