<?php

use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;


$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12" style="overflow-y:auto; height:100%;">
    <h4>หวยหุ้นไทย  - <?= date('d/m/Y',strtotime($date))?></h4>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider3,
        'columns' => [
            [
                'label' => 'รอบ',
                'value' => function ($model) {
                    return $model->title;
                }
            ],
            [
                'label' => 'สามตัวบน',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return '<span style="color: red;">ยกเลิก</span>';
                    }
                    $playType = PlayType::find()->where(['code' => 'three_top', 'game_id' => Constants::THAISHARED])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สองตัวล่าง',
                'format' => 'html',
                'attribute' => 'action_id',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return '<span style="color: red;">ยกเลิก</span>';
                    }
                    $playType = PlayType::find()->where(['code' => 'two_under', 'game_id' => Constants::THAISHARED])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<div class="col-md-12" style="overflow-y:auto; height:100%;">
    <h4>หวยหุ้นต่างประเทศ  - <?= date('d/m/Y',strtotime($date))?></h4>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider4,
        'columns' => [
            [
                'label' => 'รอบ',
                'value' => function ($model) {
                    return $model->title;
                }
            ],
            [
                'label' => 'สามตัวบน',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return '<span style="color: red;">ยกเลิก</span>';
                    }
                    $playType = PlayType::find()->where(['code' => 'three_top', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สองตัวล่าง',
                'format' => 'html',
                'attribute' => 'action_id',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return '<span style="color: red;">ยกเลิก</span>';
                    }
                    $playType = PlayType::find()->where(['code' => 'two_under', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
