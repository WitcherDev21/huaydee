<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\Constants;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-sm-6 col-md-6 col-xs-12 col-sm-offset-6 col-md-offset-6">
		<div class="post-credit-transection-search">
		
		    <br>
			   <?php ActiveForm::begin(['class'=>'form-horizontal'])?>
			   <div class="input-group date">
                        <span class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </span>
                        <input type="date" class="form-control" name="date" value="<?= $date?>">
                        <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">ตกลง</button>
                        </span>
               	</div>
               	<?= Html::hiddenInput('tab','date')?>
			    <?php ActiveForm::end(); ?>
		
		</div>
	</div>
</div>
