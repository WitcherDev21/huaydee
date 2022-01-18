<?php
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
?>

<div class="row">
	<div class="col-sm-6 col-sm-offset-6">
		<br>
	    <?php $form = ActiveForm::begin([
	        'method' => 'get',
	        'options' => ['data-pjax' => true ,'class'=>'form-horizontal']
	    ]); 

	    ?>
	    <div class="row">
		    <div class="col-md-9">
                <?php if($active_tab === 'list-history') { ?>
                <div class="form-group">
                    <label class="col-md-3 col-sm-3 control-label">วันที่</label>
                    <div class="col-md-9  col-sm-9">
                        <?= Html::activeInput('date', $searchModel, 'date_at', ['class' => 'form-control', 'placeholder' => date('Y-m-d')]) ?>
                    </div>
                </div>
                <?php } ?>
				<div class="form-group">
				    <label class="col-md-3 col-sm-3 control-label">รอบที่</label>
				    <div class="col-md-9  col-sm-9">
				      <?= Html::activeInput('text', $searchModel, 'round', ['class'=>'form-control']) ?>
				    </div>
				</div>
			</div>
			<div class="col-md-3">
				<button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
			</div>
		</div>
	    <?php ActiveForm::end(); ?>
	</div>
</div>