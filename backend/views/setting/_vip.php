<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Vip */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vip-form">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'action' => Url::to(['vip/create']),
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'point')->textInput() ?>

    <?= $form->field($model, 'commissionThreeTop')->textInput() ?>

    <?= $form->field($model, 'commissionThreeTod')->textInput() ?>

    <?= $form->field($model, 'commissionTwoTop')->textInput() ?>

    <?= $form->field($model, 'commissionTwoTod')->textInput() ?>

    <?= $form->field($model, 'commissionRunOn')->textInput() ?>

    <?= $form->field($model, 'commissionRunUnder')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'point',
            'commissionThreeTop',
            'commissionThreeTod',
            'commissionTwoTop',
            'commissionTwoTod',
            'commissionRunUnder',
            'commissionRunOn',
            'createdAt',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'contentOptions' => ['style' => 'width:130px;'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        $url = ['vip/update', 'id' => $model->id];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'class' => 'btn btn-default',
                            'data' => ['method' => 'post']]);
                    },
                    'delete' => function ($url, $model) {
                        $url = ['vip/delete', 'id' => $model->id];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-default',
                            'data' => [
                                'method' => 'post',
                                'confirm' => 'คุณต้องการลบข้อมูลใช่หรือไม่ ?',
                            ]
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>


