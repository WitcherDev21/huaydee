<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PusherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'การแจ้งเตือน';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-type-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างการแจ้งเตือน', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5>การแจ้งเตือน</h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'title',
                        'message',
                        [
                            'label' => 'Image',
                            'format' => 'html',
                            'value' => function($model) {
                                return Html::img(Yii::getAlias('@pusher/').$model->image, ['width'=>100]);
                            },
                        ],
                        'url',
                        'time',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttonOptions' => ['class' => 'btn btn-default'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group">{delete}</div>',
                        ]


                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
