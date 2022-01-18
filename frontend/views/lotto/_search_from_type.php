<?php

/* @var $select_index */
/* @var $arrType */
/* @var $game_type */
/* @var $searchFromType */
/* @var $thaiSharedGamesAlls */

use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\libs\Constants;

$js = <<<EOT
$( document ).ready(function() {
	
	$('select[name="select_index"]').on('change',function(){
		$('#search-from-type').submit();
	});
});
EOT;
$this->registerJs($js);
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="post-credit-transection-search">
            <br>
            <?php ActiveForm::begin(['class' => 'form-inline', 'id' => 'search-from-type']) ?>

            <div class="form-group">
                <?= Html::dropDownList('select_index', $select_index, $arrType, ['class' => 'selectpicker form-control', 'data-live-search' => 'true']) ?>
            </div>
            <?= Html::hiddenInput('tab', 'type') ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="col-xs-12" style="margin:10px">
        <?php
        if ($game_type == Constants::YEEKEE) {
            foreach ($searchFromType as $model) { ?>
                <h4 class="m-b-xs text-danger">จับยี่กี
                    รอบที่ <?= $model->round ?>  <?= date('d/m/Y', strtotime($model->date_at)) ?></h4>
                <table class="table table-bordered lotto-result">
                    <thead>
                    <tr>
                        <th>สามตัวบน</th>
                        <th>สองตัวล่าง</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $display_result = isset(Constants::$status[$model->status]) ? Constants::$status[$model->status] : '';
                    if ($model->status == Constants::status_active) {
                        $display_result = 'รอผล';
                    }
                    ?>
                    <tr>
                        <td><?= empty($model->getResults('three_top')) ? $display_result : $model->getResults('three_top') ?></td>
                        <td><?= empty($model->getResults('two_under')) ? $display_result : $model->getResults('two_under') ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php } ?>
        <?php } else if ($game_type == Constants::BLACKRED) {
            foreach ($searchFromType as $model) { ?>
                <h4 class="m-b-xs text-danger">จับยี่กี
                    รอบที่ <?= $model->round ?>  <?= date('d/m/Y', strtotime($model->date_at)) ?></h4>
                <table class="table table-bordered lotto-result">
                    <thead>
                    <tr>
                        <th>สามตัวบน</th>
                        <th>สองตัวล่าง</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $display_result = isset(Constants::$status[$model->status]) ? Constants::$status[$model->status] : '';
                    if ($model->status == Constants::status_active) {
                        $display_result = 'รอผล';
                    }
                    ?>
                    <tr>
                        <td><?= empty($model->getResults('three_top')) ? $display_result : $model->getResults('three_top') ?></td>
                        <td><?= empty($model->getResults('two_under')) ? $display_result : $model->getResults('two_under') ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php }
        } else if (in_array($game_type, $thaiSharedGamesAlls)) {
            foreach ($searchFromType as $model) { ?>
                <h4 class="m-b-xs text-danger">
                   <?= $model->title ?>  <?= date('d/m/Y', strtotime($model->endDate)) ?></h4>
                <table class="table table-bordered lotto-result">
                    <thead>
                    <tr>
                        <th>สามตัวบน</th>
                        <th>สองตัวล่าง</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $playTypeThreeTop = PlayType::find()->where(['code' => 'three_top', 'game_id' => Constants::THAISHARED])->one();
                    $playTypeTwoUnder = PlayType::find()->where(['code' => 'two_under', 'game_id' => Constants::THAISHARED])->one();
                    $thaiSharedAnswerGameThreeTop = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playTypeThreeTop->id, 'thaiSharedGameId' => $model->id])->one();
                    $thaiSharedAnswerGameTwoUnder = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playTypeTwoUnder->id, 'thaiSharedGameId' => $model->id])->one();
                    ?>
                    <tr>
                        <td><?= $thaiSharedAnswerGameThreeTop ? $thaiSharedAnswerGameThreeTop->number : 'ยังไม่ออกผล'; ?></td>
                        <td><?= $thaiSharedAnswerGameTwoUnder ? $thaiSharedAnswerGameTwoUnder->number : 'ยังไม่ออกผล'; ?></td>
                    </tr>
                    </tbody>
                </table>
            <?php }
        } ?>
    </div>
</div>
