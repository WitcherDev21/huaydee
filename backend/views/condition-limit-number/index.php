<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConditionLimitNumberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="condition-limit-number">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Condition Limit Number'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5><?= Yii::t('app', 'Condition Limit Number')?></h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'playTypeId',
                            'value' => 'playType.title',
                            'filter' => $playTypes
                        ],
                        'limit',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttonOptions' => ['class' => 'btn btn-default'],
                            'contentOptions' => ['style' => 'width:100px;'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}{delete}</div>',
                        ]
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
