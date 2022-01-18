<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PostCreditTransection */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Post Credit Transections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-credit-transection-view" style="padding:6px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php //= Html::a('Delete', ['delete', 'id' => $model->id], [
//             'class' => 'btn btn-danger',
//             'data' => [
//                 'confirm' => 'Are you sure you want to delete this item?',
//                 'method' => 'post',
//             ],
//         ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'poster_id',
            'action_id',
            'user_has_bank_id',
            'user_has_bank_version',
            'status',
            'amount',
            'remark',
            'create_at',
            'create_by',
            'update_at',
            'update_by',
        ],
    ]) ?>

</div>
