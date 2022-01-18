<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PlayTypeGourp */

$this->title = 'Create Play Type Gourp';
$this->params['breadcrumbs'][] = ['label' => 'Play Type Gourps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-type-gourp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
