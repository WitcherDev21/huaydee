<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\PlayType;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LimitAutoLotteryNumberGameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Limit Auto Lottery Number Games';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'กลับ'), ['thai-shared/index'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a(Yii::t('app', 'สร้าง Limit Auto ราคาจ่าย'), ['create', 'thaiSharedGameId' => $thaiSharedGame->id], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>limit Auto ตัวเลขเกม: <?= $thaiSharedGame->title ?>
            : <?= date('Y-m-d', strtotime($thaiSharedGame->endDate)) ?></h5>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'minimumRate',
            'maximumRate',
            [
                'label' => 'Play Type',
                'attribute' => 'playTypeId',
                'value' => 'playType.title',
                'filter' => ArrayHelper::map(PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->asArray()->all(), 'id', 'title'),
            ],
            'jackPotPerUnit',
            'createdAt',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttonOptions' => ['class' => 'btn btn-default'],
                'template' => '<div class="btn-group btn-group-sm text-center" role="group">{update}{delete}</div>',
            ],
        ],
    ]); ?>
</div>
