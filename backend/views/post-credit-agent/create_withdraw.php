<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Bank;
use common\models\UserHasBankSearch;


/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = 'แจ้งถอนเครดิต';
$this->params['breadcrumbs'][] = ['label' => 'แจ้งถอนเครดิต', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
	<div class="card">
		<h4 class="card-header bg-transparent"><?= Html::encode($this->title)?></h4>
		<div class="card-body">
			<div class="col-md-12">
				<div class="post-credit-transection-form">
					<div class="alert alert-warning">
						<div>คุณสามารถใช้สิทธิการถอนได้ 9 ครั้งต่อวัน, ขณะนี้คุณใช้สิทธิถอนไปแล้ว 0 ครั้ง</div>
					</div>
				    <?php $form = ActiveForm::begin([
				    		'id' => 'withdraw-form',
				    		'enableAjaxValidation' => true,
				    ]); ?>
					<div class="form-group">
						
							<label class="col-xs-12 col-lg-1 control-label" style="text-align:left">เลือกธนาคาร</label>
							<div class="col-lg-11">
								<div class="radio">
								<?= $form->field($model, 'user_has_bank_id')
			                        ->radioList(
			                            UserHasBankSearch::getBankAccountUser(Yii::$app->user->identity->username),
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
			                                    $return .= '<img src="'.Yii::getAlias('@web').'/bank/'.$icon.'" class="bank_icon" style="background-color: '.$color.';width:20px;">';
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
					<div class="input-group ">
			            <div class="col-md-5">
			           	<?= $form->field($model, 'amount')->input('number',[
			           			'class' => 'form-control input-lg',
			           			'placeholder'=>'ระบุจำนวนเงินที่ต้องการโอน',
			           	])->label(false)?>
			           	</div>
			           	<div class="col-md-5">
				         <?= Html::submitButton('ตกลง', ['class' => 'btn btn-primary btn-lg','style'=>'margin-top:10px;']) ?>
				         </div>
			        </div>	
			        <div class="input-group">
			        	<div class="col-md-5">
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
	</div>
 </div>


