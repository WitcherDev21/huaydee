<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Bank;
use common\models\UserHasBankSearch;
use common\libs\Constants;


/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */
$minimum = Constants::minimum_create_withdraw;
$js = <<<EOT
    $('#withdraw-form').on('submit', function(e){
		if($('#postcredittransection-amount').val()< $minimum){
            swal({
              title: 'Warning!',
              text: 'ขั้นต่ำการถอน $minimum',
              type: 'warning',
              confirmButtonText: 'close'
            });
            
			e.preventDefault();
			return false;
		}
    });
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$this->title = 'แจ้งถอนเครดิต';
$this->params['breadcrumbs'][] = ['label' => 'แจ้งถอนเครดิต', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="widget-box">
    <div class="widget-title bg_lg">
            <span class="icon"><span class="glyphicon glyphicon-credit-card"></span></span>
            <h5>เติมเครดิตตรง</h5>
    </div>
    <div class="widget-content ">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลสมาชิก</h3>
            </div>
            <div class="panel-body">
                <b>Username ::</b> <?php echo $dataUser->username; ?><br><br>
                <b>อีเมล์ ::</b> <?php echo $dataUser->email; ?><br><br>
                <b>เบอร์โทร ::</b> <?php echo $dataUser->tel; ?>
            </div>
        </div>
        <div class="post-credit-transection-form">
            <?php $form = ActiveForm::begin([
                        'id' => 'withdraw-form',
                        //'enableAjaxValidation' => true,
            ]); ?>
                <?php 
                if($model->hasErrors()){
                ?>
                    <div class="alert alert-danger">
                        <?php echo $form->errorSummary($model)?>
                    </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <div class="row">
                        <label class="col-xs-12 col-lg-1 control-label" style="text-align:left">เลือกธนาคาร</label>
                        <div class="col-lg-11">
                            <div class="radio">
                                    <?= $form->field($model, 'user_has_bank_id')
                                        ->radioList(
                                            UserHasBankSearch::getBankAccountUser($dataUser->username),
                                            [
                                                'item' => function($index, $label, $name, $checked, $value) {
                                                        $id = $label['user_has_bank_id'];
                                                        $title = $label['title'];
                                                        $icon = $label['icon'];
                                                        $color = $label['color'];
                                                        $version = $label['version'];
                                                        $bank_account_name = $label['bank_account_name'];
                                                        $bank_account_no = $label['bank_account_no'];

                                                                $return = '';
                                                    $return .= '<label style="margin-bottom:5px;">';
                                                    $return .= '<input type="radio" name="' . $name . '" value="' . $id . '" tabindex="3">';
                                                    //$return .= '<i></i>';
                                                    $return .= '<img src="'. str_replace('backend', 'frontend', Yii::getAlias('@web')).'/bank/'.$icon.'" class="bank_icon" style="background-color: '.$color.';width:20px;">';
                                                    $return .= '<stong> ' . ucwords($title) .' : '.substr_replace($bank_account_no,'***',3,6).' ('.$bank_account_name.')'. '</stong>';
                                                    $return .= '</label>';

                                                    return $return;
                                                }
                                            ]
                                        )
                                    ->label(false);?>	
                            </div>		
                        </div>
                    </div>
                </div>
                <div class="input-group ">
                    <div class="col-md-8">
                        <?= $form->field($model, 'amount')->input('number',[
                                        'class' => 'form-control input-lg',
                                        'placeholder'=>'ระบุจำนวนเงินที่ต้องการโอน',
                        ])->label(false)?>
                    </div>
                    <div class="col-md-4">
                        <?= Html::submitButton('ตกลง', ['class' => 'btn btn-primary btn-lg','style'=>'margin-top:10px;']) ?>
                    </div>
                </div>	
                <div class="input-group">
                    <div class="col-md-12">
                        <?= $form->field($model, 'remark')->textarea([
                                        'class' => 'form-control',
                                        'placeholder'=>'ระบุหมายเหตุ',
                                        'rows'=>5
                        ])->label(false)?>
                    </div>
                </div>	         
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>






