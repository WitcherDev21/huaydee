<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingLotteryLaoSet */

$this->title = 'Update Setting Lottery Lao Set: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setting Lottery Lao Sets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-lottery-lao-set-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'gameObjs' => $gameObjs,
    ]) ?>

</div>
