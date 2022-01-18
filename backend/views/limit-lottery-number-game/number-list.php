<?php

use common\models\PlayType;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\libs\Constants;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LimitLotteryNumberGameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'limit ราคาแทง / เลข';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'สร้าง limit ราคาแทง / เลข'), ['create-number-list', 'thaiSharedGameId' => $thaiSharedGame->id], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>limit ตัวเลขเกม: <?= $thaiSharedGame->title ?> : <?= date('Y-m-d', strtotime($thaiSharedGame->endDate))?></h5>
    </div>
    <div class="widget-content">
        <div class="panel">
            <?= $this->render('_tab', ['thaiSharedGameId' => $thaiSharedGame->id]) ?>
            <div class="table-responsive">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'Play Type',
                        'attribute' => 'playTypeId',
                        'value' => 'playType.title',
                        'filter' => ArrayHelper::map(PlayType::find()->where(['game_id' => Constants::THAISHARED])->asArray()->all(), 'id', 'title'),
                    ],
                    'number',
                    [
                        'attribute' => 'jackPotPerUnit',
                        'visible' => $thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' ? false : true,
                    ],
                    [
                        'attribute' => 'totalSetNumber',
                        'visible' => $thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' ? true : false,
                    ],
                    [
                        'attribute' => 'isLimitByUser',
                        'value' => function ($model) {
                            return $model->isLimitByUser === 1 ? 'เปิด' : 'ปิด';
                        },
                        'visible' => $thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' ? false : true,
                    ],
                    [
                        'attribute' => 'amountLimit',
                        'visible' => $thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' ? false : true,
                    ],
                    'createdAt',
                ],
            ]); ?>

        </div>
        </div>
    </div>
</div>
