<?php
/* @var $dataProvider */

/* @var $currentIncome */

use common\libs\Constants;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$minimum = Constants::minimum_commission_withdraw;
$maximum = Constants::maximum_commission_withdraw;
$js = <<<EOT
$('input[name="amount"]').keydown(function(e){
    var num = ['0','1','2','3','4','5','6','7','8','9','Delete','Backspace'];
    var a = num.indexOf(e.key);

    if(e.key == 'Enter'){
        $('.btn-withdraw').click();
    }
    if(a < 0){
        return false;
    }
});
$('.btn-withdraw').on('click',function(e){
     //e.preventDefault();
    //return false; 
    swal({
      title: 'Confirm?',
      html: "คอมมิชชั่น จะถูกถอนไปที่วงเงินที่คุณเลือกอัติโนมัติ?<br>กรุณาเลือกบัญชีธนาคารของท่านที่ต้องการรับโอนเงินให้ถูกต้อง",
      type: 'info',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน'
    }).then((result) => {
      if (result) {
         if($('input[name="amount"]').val() > $maximum || $('input[name="amount"]').val() < $minimum){
            swal({
              title: 'Warning!',
              text: 'การถอนต้องมียอดขั้นต่ำ '+$minimum+' บาทขึ้นไป',
              type: 'warning',
              confirmButtonText: 'close'
            });    
    		$('input[name="amount"]').focus();
    		e.preventDefault();
    		return false;
    	}else{
            $('#withdraw-form').submit();
        }
      }
    });  
});

$('.transfer').on('change', function() {
    var wallet = $('input[name=checkWallet]:checked').val();
    if (wallet === 'direct') {
        $('#bank').show();
    }else{
        $('#bank').hide();
    }
});

EOT;
$this->registerJs($js);
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <?= $this->render('_tab') ?>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-1 xtarget col-lotto">
        <h4><i class="fas fa-file-invoice-dollar"></i> แจ้งถอนรายได้</h4>
        <hr>
        <p class="alert alert-primary_bkk text-center">รายได้ ระบบแนะนำ จะถอนเข้าเป็นเงินเครดิต
            หากสงสัยโปรดติดต่อเอเย่นต์ที่ท่านสมัครสมาชิก</p>
        <div class="text-center">
            <b>รายได้ปัจจุบัน - คงเหลือ</b><br>
            <h4 class="thb text-primary_bkk">฿ <?= $currentIncome ?></h4>
        </div>
        <div class="text-center my-3">
            <?php ActiveForm::begin([
                'id' => 'withdraw-form',
            ]) ?>
            <input type="hidden" name="csrf_token" value="edfc94847790fda329dca480dbf51594">
            <input type="hidden" name="afwithdraw" value="1">
            <div class="input-group mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">ต้องการถอน</span>
                </div>
                <input type="number" class="form-control" name="amount" placeholder="ระบุจำนวนเงิน"
                       aria-describedby="basic-addon1">
            </div>
           
            <div class="col-md col-md-offset-6 transfer">
                <input type="radio" name="checkWallet" value="wallet" checked> ถอนเข้ากระเป๋าเงิน
                <input type="radio" name="checkWallet" value="direct"> ถอนตรง
            </div>
			 <div id="bank" class="col-md col-md-offset-6" style="display: none;">กรุณาระบุธนาคารรับโอนเงิน : <br>
                <?php foreach ($userHasBanks as $key => $userHasBank) { ?>
                <input type="radio" name="bank" value="<?= $userHasBank->id ?>" <?= $key === 0 ? 'checked' : ''?>> <?= $userHasBank->bank->title ?>
                <?php } ?>
			</div><br>
            <button type="button" class="btn btn-primary_bkk2 btn-block btn-withdraw"><i class="fas fa-hand-holding-usd"></i>
                แจ้งถอนรายได้
            </button>
            <?php ActiveForm::end() ?>
        </div>
    </div>
    <div class="bg-white text-secondary2 shadow-sm rounded pt-2 pb-1 px-2 mb-1">
        <h5><i class="fas fa-history"></i> แนะนำเพื่อนประวัติการถอนล่าสุด</h5>
    </div>
    <div class="bg-white text-secondary2 shadow-sm rounded py-2 px-1 mb-5">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'รายละเอียด',
                    'value' => 'remark',
                ],
                [
                    'label' => 'จำนวนเงิน',
                    'format' => 'raw',
                    'attribute' => 'poster_id',
                    'value' => function ($model) {
                        if (in_array($model->action_id, [Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin])) {
                            $color = Constants::$reason_credit_color[$model->reason_action_id];
                            $text = 'โปรโมชั่น';
                        } elseif ($model->action_id === Constants::reason_commission_withdraw_direct) {
                            $color = Constants::$reason_credit_color[$model->reason_action_id];
                            $text = 'ถอนตรงค่าคอมมิชชั่น';
                        } else {
                            $color = Constants::$reason_credit_color[$model->reason_action_id];
                            $text = Constants::$reason_credit[$model->reason_action_id];
                        }
                        return '<label" style="padding:4px; color:#ffffff; background:' . $color . ';">' . $text . '</label>';
                    }
                ],
                [
                    'label' => 'รายได้คงเหลือ',
                    'value' => 'balance',
                ],
                [
                    'label' => 'เครดิต',
                    'format' => 'html',
                    'value' => function ($model) {
                        if ($model->action_id == Constants::reason_commission_top_up) {
                            $stingText = '<div style="color:' . Constants::color_credit_in . '">+' . $model->amount . '</div>';
                        } else if ($model->action_id == Constants::reason_commission_withdraw) {
                            $stingText = '<div style="color:' . Constants::color_credit_out . '">-' . $model->amount . '</div>';
                        } else if ($model->action_id == Constants::reason_commission_withdraw_direct) {
                            $stingText = '<div style="color::' . Constants::color_credit_out . '">-' . $model->amount . '</div>';
                        }
                        return $stingText;
                    }
                ],
                [
                    'label' => 'วันที่ทำรายการ',
                    'value' => function ($model) {
                        return date('d/m/Y H:i:s', strtotime($model->create_at));
                    }
                ],
            ],
        ]); ?>

    </div>
</div>