<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = 'Create Post Credit Transection';
$this->params['breadcrumbs'][] = ['label' => 'Post Credit Transections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-credit-transection-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
