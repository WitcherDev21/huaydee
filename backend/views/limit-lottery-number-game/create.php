<?php

/* @var $this yii\web\View */
/* @var $model common\models\LimitLotteryNumberGame */
/* @var $playTypes array */
/* @var $maxLengths array */

$this->title = 'สร้าง Limit ราคาจ่าย';
$this->params['breadcrumbs'][] = ['label' => 'Limit Lottery Number Games', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\ArrayHelper; ?>
<?php if(Yii::$app->session->hasFlash('alert')):?>
    <?= \yii\bootstrap\Alert::widget([
        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
    ])?>
<?php endif; ?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
    <?= $this->render('_form', [
        'model' => $model,
        'playTypes' => $playTypes,
        'maxLengths' => $maxLengths,
        'thaiSharedGame' => $thaiSharedGame,
    ]) ?>
    </div>
</div>
