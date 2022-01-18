<?php

use common\libs\Constants;
use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LotteryGameChitAnswer */
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= Yii::t('app', 'Yeekee Fix Number round') ?><?= $model->round?></h5>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'เกม',
                'value' => function ($model) {
                    return Yii::t('app', 'Yeekee round').' '.$model->round;
                }
            ],
            [
                'label' => 'Fix Number',
                'value' => function () use ($fixNumberYeekee) {
                    return $fixNumberYeekee->number;
                }
            ],
            [
                'label' => 'Created By',
                'value' => function () use ($fixNumberYeekee) {
                    return isset($fixNumberYeekee->createBy->username) ? $fixNumberYeekee->createBy->username : '';
                }
            ],
            [
                'label' => 'Updated Up',
                'value' => function () use ($fixNumberYeekee) {
                    return isset($fixNumberYeekee->updatedBy->username) ? $fixNumberYeekee->updatedBy->username : '';
                }
            ],
            [
                'label' => 'Created At',
                'value' => function () use ($fixNumberYeekee) {
                    return $fixNumberYeekee->createdAt;
                }
            ],
            [
                'label' => 'Updated At',
                'value' => function () use ($fixNumberYeekee) {
                    return $fixNumberYeekee->updatedAt;
                }
            ],
        ],
    ]) ?>

</div>
