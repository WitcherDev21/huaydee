<?php

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

use yii\bootstrap\ActiveForm;
use common\libs\Constants;

$uri = Yii::getAlias('@web');
$minimum = Constants::minimum_commission_withdraw;
$maximum = Constants::maximum_commission_withdraw;
$js = <<<EOT
$('input[name="amount"]').keydown(function(e){
    console.log(e.key);
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
      text: "คอมมิชชั่น จะถูกถอนไปที่ วงเงิน เครดิต อัติโนมัติ?",
      type: 'info',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน'
    }).then((result) => {
      if (result.value) {
        if($('input[name="amount"]').val() > $maximum || $('input[name="amount"]').val() < $minimum){
            swal({
              title: 'Warning!',
              text: 'การถอนต้องอยู่ในช่วง '+$minimum+' - '+$maximum,
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

EOT;
$this->registerJs($js);
$css = <<<EOT

EOT;
$this->registerCss($css);
?>


<div class="col-xs-12">
    <div class="panel">
        <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
        <div class="tab-content">
            <div class="tab-pane fade in active">
                <br>

                <div class="alert alert-warning text-center" role="alert">รายได้ ระบบแนะนำ จะถอนเข้าเป็นเงินเครดิต
                    หากสงสัยโปรดติดต่อเอเย่นต์ที่ท่านสมัครสมาชิก
                </div>
                <?php ActiveForm::begin(['id' => 'withdraw-form']) ?>
                <h4 class="text-center">รายได้ปัจจุบัน <span style="color: red;"><?= $currentIncome ?></span></h4>
                <div class="input-group col-md-4 col-md-offset-4">
                    <span class="input-group-addon">ต้องการถอน</span>
                    <input type="text" name="amount" class="form-control" placeholder="ระบุจำนวนเงิน ฿" id="amount"
                           data-fv-field="amount">
                </div>
                <button type="button" class="btn btn-primary col-md-4 col-md-offset-4 btn-withdraw"
                        style="margin-top: 9px;">แจ้งถอนรายได้
                </button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'รายละเอียด',
                        'value' => 'remark',
                    ],
                    [
                        'label' => 'จำนวนเงิน',
                        'format' => 'raw',
                        'attribute' => 'poster_id',
                        'value' => function ($model, $key, $index, $column) {
                            if (in_array($model->action_id, [Constants::action_credit_top_up_admin, Constants::action_credit_withdraw_admin])) {
                                $color = Constants::$reason_credit_color[$model->reason_action_id];
                                $text = 'โปรโมชั่น';
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
                        'value' => function ($model, $key, $index, $column) {
                            if ($model->action_id == Constants::reason_commission_top_up) {
                                $stingText = '<div style="color:' . Constants::color_credit_in . '">+' . $model->amount . '</div>';
                            } else if ($model->action_id == Constants::reason_commission_withdraw) {
                                $stingText = '<div style="color:' . Constants::color_credit_out . '">-' . $model->amount . '</div>';
                            }
                            return $stingText;
                        }
                    ],
                    [
                        'label' => 'วันที่ทำรายการ',
                        'value' => function ($model, $key, $index, $column) {
                            return date('d/m/Y H:i:s', strtotime($model->create_at));
                        }
                    ],

                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>


