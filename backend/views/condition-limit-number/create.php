<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConditionLimitNumber */

$this->title = 'Create Condition Limit Number';
$this->params['breadcrumbs'][] = ['label' => 'Condition Limit Numbers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="condition-limit-number-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'playTypes' => $playTypes,
    ]) ?>

</div>
