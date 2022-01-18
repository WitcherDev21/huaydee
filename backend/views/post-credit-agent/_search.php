<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\Constants;
use yii\helpers\Url;
use common\models\BankSearch;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-xs-12">
		<div class="post-credit-transection-search">
		
		    <br>
			    <?php $form = ActiveForm::begin([
			        'method' => 'get',
			        'options' => ['data-pjax' => true ,'class'=>'form-horizontal']
			    ]); 
		   ?>
			    <div class="row">
			    	<div class="col-md-5">
					    <div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 control-label">ค้นหา</label>
							    <div class="col-md-5  col-sm-5">
							    	<?= Html::activeInput('text',$searchModel, 'q',['class'=>'form-control input-sm'])?>
							    </div>
							    <div class="col-md-4 col-sm-4">
							    	<?= Html::activeDropDownList($searchModel, 'fillter_q', [
							    			''=>'ทั้งหมด',
							    			'1'=>'ผู้แจ้ง',
							    			'2'=>'ชื่อบัญชี',
							    			'3'=>'เลขที่บัญชี',
							    			'4'=>'จำนวน'
							    	],['class'=>'form-control input-sm'])?>
							    </div>
							</div>
						</div>
					</div>
					<div class="col-md-5">
					    <div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 control-label">ธนาคาร</label>
							    <div class="col-md-9  col-sm-9">
							    	<?= Html::activeDropDownList($searchModel, 'bank_id', 
							    		[''=>'ทั้งหมด']+BankSearch::getBanks2()
							    	,['class'=>'form-control input-sm'])?>
							    </div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
			    	<div class="col-md-5">
					    <div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 control-label">วันที่</label>
							    <div class="col-md-5  col-sm-5">
							    	<?= Html::activeInput('date',$searchModel, 'date',['class'=>'form-control input-sm'])?>
							    </div>
							    <div class="col-md-4 col-sm-4">
							    	<?= Html::activeDropDownList($searchModel, 'fillter_date', [
							    			''=>'ทั้งหมด',
							    			'1'=>'วันที่สร้าง',
							    			'2'=>'วันที่อัพเดท'
							    	],['class'=>'form-control input-sm'])?>
							    </div>
							</div>
						</div>
					</div>
					<div class="col-md-5">
					    <div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 control-label">อื่นๆ</label>
							    <div class="col-md-9  col-sm-9">
							    	<?= Html::activeDropDownList($searchModel, 'bank_id', [
							    			''=>'ทั้งหมด',
							    			'1'=>'ธนาคารฝากแจ้ง',
							    			'2'=>'ข้อความฝากแจ้ง'
							    	],['class'=>'form-control input-sm'])?>
							    </div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
					    <div class="col-md-12">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 control-label">ประเภท</label>
							    <div class="col-md-9  col-sm-9">
							    	<?= Html::activeDropDownList($searchModel, 'action_id', [
							    			''=>'ทั้งหมด',
							    			Constants::action_credit_top_up => Constants::$action_credit[Constants::action_credit_top_up],
							    			Constants::action_credit_withdraw => Constants::$action_credit[Constants::action_credit_withdraw],
							    			Constants::action_credit_top_up_admin => Constants::$action_credit[Constants::action_credit_top_up_admin],
							    			Constants::action_credit_withdraw_admin => Constants::$action_credit[Constants::action_credit_withdraw_admin]
							    			
							    	],['class'=>'form-control input-sm'])?>
							    </div>
							</div>
						</div>
					</div>
					
					<div class="col-md-5">
						<button class="btn btn-info pull-right" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
					</div>
				</div>
				<?php 
				$uri = Yii::$app->controller->getRoute ();
				if($uri == 'post-credit-agent/list-current'){
					echo Html::activeHiddenInput($searchModel, 'create_at');
				}?>
			    <?php ActiveForm::end(); ?>
		
		</div>
	</div>
</div>
