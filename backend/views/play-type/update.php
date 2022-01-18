<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayType */

$this->title = 'Update Play Type: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Play Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="play-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'titlePlayTypeGroups' => $titlePlayTypeGroups,
        'titleGames' => $titleGames,
    ]) ?>

</div>