<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SettingLotteryLaoSet */

$this->title = 'Create Setting Lottery Lao Set';
$this->params['breadcrumbs'][] = ['label' => 'Setting Lottery Lao Sets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-lottery-lao-set-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'gameObjs' => $gameObjs,
    ]) ?>

</div>
