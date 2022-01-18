<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\Constants;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransectionSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchModel */
?>

<div class="row">
	
	<div class="col-sm-6 col-md-6 col-xs-12 col-sm-offset-6 col-md-offset-6">
		<div class="post-credit-transection-search">
		
		    <br>
			    <?php $form = ActiveForm::begin([
                    'action' => Url::to([Yii::$app->controller->getRoute()]),
			        'method' => 'get',
			        'options' => ['data-pjax' => true ,'class'=>'form-horizontal']
			    ]); 
		
			    ?>
			    <div class="row">
				    <div class="col-md-9">
						<div class="form-group">
							<label class="col-md-6 col-sm-6 control-label">ประเภท</label>
						    <div class="col-md-6  col-sm-6">
						    	<?= Html::activeDropDownList($searchModel, 'reason_action_id', [
						    			''=>'ทั้งหมด',
						    			Constants::action_credit_top_up => Constants::$action_credit[Constants::action_credit_top_up],
						    			Constants::action_credit_withdraw => Constants::$action_credit[Constants::action_credit_withdraw],
						    			Constants::action_credit_top_up_admin => Constants::$action_credit[Constants::action_credit_top_up_admin],
						    			Constants::action_credit_withdraw_admin => Constants::$action_credit[Constants::action_credit_withdraw_admin],
                                        Constants::reason_credit_bet_win => Constants::$reason_credit[Constants::reason_credit_bet_win],
                                        Constants::reason_credit_bet_play => Constants::$reason_credit[Constants::reason_credit_bet_play],
                                        Constants::reason_credit_return_chit => Constants::$reason_credit[Constants::reason_credit_return_chit],
                                        Constants::reason_credit_top_up_promotion => Constants::$reason_credit[Constants::reason_credit_top_up_promotion],
                                        Constants::reason_credit_withdraw_promotion => Constants::$reason_credit[Constants::reason_credit_withdraw_promotion],
                                        Constants::reason_credit_commission_in => Constants::$reason_credit[Constants::reason_credit_commission_in],
                                        Constants::action_commission_withdraw_direct => Constants::$action_commission[Constants::action_commission_withdraw_direct],
						    			
],['class'=>'form-control'])?></br>
						    </div>
							<div class="form-group">
                            <label class="col-md-6 col-sm-5 control-label">หมายเหตุ</label>
                            <div class="col-md-6 col-sm-8">
                                <?= $form->field($searchModel, 'remark')->textInput(['class'=>'form-control'])->label(false) ?>
                            </div>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
					</div>
				</div>
				<?php 
				$uri = Yii::$app->controller->getRoute();
				if($uri == 'post-credit-transection/list-current'){
					echo Html::activeHiddenInput($searchModel, 'create_at');
				}?>
			    <?php ActiveForm::end(); ?>
		
		</div>
	</div>
</div>
