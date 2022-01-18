<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Bank;
use common\models\UserHasBankSearch;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = 'แจ้งเติมเครดิต';
$this->params['breadcrumbs'][] = ['label' => 'แจ้งเติมเครดิต', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
	<div class="card">
		<h4 class="card-header bg-transparent"><?= Html::encode($this->title)?></h4>
		<div class="card-body">
			<div class="col-md-12">
				<div class="post-credit-transection-form">
					<div class="alert alert-info">
						<div>ขั้นตอนการแจ้งฝากเงิน ระบบใหม่</div>
						<div>1. กด แจ้งเติมเครดิต</div>
						<div>- กด เลือกธนาคารที่ต้องการโอน</div>
						<div>- กด ระบุจำนวนเงินที่ต้องการโอน แล้วกด ตกลง</div>
						<div>2. ระบบจะแจ้งให้โอนเงินตามจำนวนที่ระบุ (โดยระบบจะสุ่มตัวเลข เพิ่มจุดทศนิยม 2 ตำแหน่ง ต่อท้ายยอดเต็มที่สมาชิกระบุ)</div>
						<div>3. ให้สมาชิกโอนยอดเงินตามที่ระบบกำหนดให้ โดยต้องโอนยอดมีเลขทศนิยม 2 ตำแหน่ง ที่ระบบระบุต่อท้ายด้วยทุกครั้ง (เน้นย้ำ สำคัญมาก หากไม่โอนยอดที่มีจุดทศนิยม จะไม่สามารถปรับยอดใช้งานได้)</div>
						<div>- ระบบจะมีระยะเวลาในการทำรายการ 10 นาที โดยนับถอยหลัง เริ่มตั้งแต่ที่สมาชิกกดระบุยอดเงิน แล้วกด ตกลง</div>
						<div>- ไม่สามารถกด ยกเลิกรายการได้ จนกว่ารายการจะผ่านไป 5 นาที นับตั้งแต่ที่สมาชิก กด ระบุยอดเงิน แล้วกด ตกลง</div> 
						<div>- หากโอนเงินแล้ว ไม่สามารถทำรายการได้ทัน 10 นาทีตามที่ระบบกำหนด หรือมีการทำรายการผิดใดๆ ให้ติดต่อเอเย่นต์ทันที</div>
						<div>4. กดแจ้ง เวลาฝาก โดยระบุ ชั่วโมง นาที ให้ตรงตามข้อมูลโมบายแบงค์กิ้ง หรือในใบสลิป</div>
						<div>5. กด ระบุหมายเหตุ หากต้องการส่งข้อความใดๆถึงเอเย่นต์ (หากไม่ต้องการ สามารถข้ามการใส่ข้อมูลในช่องนี้ได้)</div>
						<div>6. กด บันทึกข้อมูลการแจ้งฝาก</div>
					</div>
				    <?php $form = ActiveForm::begin([
				    		'id' => 'topup-form',
				    		'enableAjaxValidation' => true,
				    ]); ?>
					<div class="form-group">
						
							<label class="col-xs-12 col-lg-1 control-label" style="text-align:left">เลือกธนาคาร</label>
							<div class="col-lg-11">
								<div class="radio">
								<?= $form->field($model, 'user_has_bank_id')
			                        ->radioList(
			                            UserHasBankSearch::getBankAccountSystem(),
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
			                                    $return .= '<stong> ' . ucwords($title) . '</stong>';
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
				    <?php ActiveForm::end(); ?>
				
				</div>
			</div>
		</div>
	</div>
 </div>


