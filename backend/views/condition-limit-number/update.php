<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConditionLimitNumber */

$this->title = 'Update Condition Limit Number: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Condition Limit Numbers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="condition-limit-number-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'playTypes' => $playTypes,
    ]) ?>

</div>
