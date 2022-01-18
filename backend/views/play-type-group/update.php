<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayTypeGourp */

$this->title = 'Update Play Type Gourp: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Play Type Gourps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="play-type-gourp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
