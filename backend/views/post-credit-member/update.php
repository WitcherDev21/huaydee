<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = 'Update Post Credit Transection: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Post Credit Transections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-credit-transection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
