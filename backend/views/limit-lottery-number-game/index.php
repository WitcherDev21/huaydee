<?php

use common\libs\Constants;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\PlayType;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LimitLotteryNumberGameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Limit ราคาจ่าย';
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'กลับ'), ['thai-shared/index'], ['class' => 'btn btn-warning']) ?>
    <?= Html::a(Yii::t('app', 'สร้าง Limit ราคาจ่าย'), ['create', 'thaiSharedGameId' => $thaiSharedGame->id], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>limit ตัวเลขเกม: <?= $thaiSharedGame->title ?> : <?= date('Y-m-d', strtotime($thaiSharedGame->endDate))?></h5>
    </div>
    <div class="widget-content">
        <?php if ($thaiSharedGame->title === 'หวยลาวชุด 120' || $thaiSharedGame->title === 'หวยลาวชุด 90' || $thaiSharedGame->title === 'หวยเวียดนามชุด') { ?>
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'number',
                [
                    'attribute' => 'totalSetNumber',
                ],
                'createdAt',
            ],
        ]);
        ?>
        <?php }else{ ?>
        <div class="panel">
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
                                'filter' => ArrayHelper::map(PlayType::find()->where(['game_id' => $thaiSharedGame->gameId])->asArray()->all(), 'id', 'title'),
                            ],
                            'number',
                            [
                                'attribute' => 'jackPotPerUnit',
                            ],
                            'createdAt',
                        ],
                    ]);
                }?>
            </div>
        </div>
    </div>
</div>
