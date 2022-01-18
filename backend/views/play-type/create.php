<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PlayType */

$this->title = 'Create Play Type';
$this->params['breadcrumbs'][] = ['label' => 'Play Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'titlePlayTypeGroups' => $titlePlayTypeGroups,
        'titleGames' => $titleGames,
    ]) ?>

</div>