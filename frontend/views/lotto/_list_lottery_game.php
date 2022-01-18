<?php
/**
 * @var $tab
 * @var $date
 * @var $dataProvider2
 * @var $dateLottery
 * @var $dataProvider6
 * @var $dataProviderLotteryLaoGame
 * @var $dataProvider3
 * @var $dataProvider4
 * @var $dateLotteryLao
 *
 */

use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;


$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12" style="overflow-y:auto; height:100%;">
    <h4>หวยรัฐบาลไทย  - <?= date('d/m/Y',strtotime($date))?></h4>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider6,
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
                        return 'ยกเลิก';
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
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'two_under', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สามตัวหน้า',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'three_top2', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->all();
                    $thaiSharedAnswerGameNumber = '';
                    foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                        $thaiSharedAnswerGameNumber .= $thaiSharedAnswerGame->number.'<br>';
                    }
                    return $thaiSharedAnswerGameNumber;
                }
            ],
            [
                'label' => 'สามตัวหลัง',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'three_und2', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->all();
                    $thaiSharedAnswerGameNumber = '';
                    foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                        $thaiSharedAnswerGameNumber .= $thaiSharedAnswerGame->number.'<br>';
                    }
                    return $thaiSharedAnswerGameNumber;
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<div class="col-md-12" style="overflow-y:auto; height:100%;">
    <h4>หวยลาวชุด  - <?= date('d/m/Y',strtotime($dateLotteryLao))?></h4>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderLotteryLaoGame,
        'columns' => [
            [
                'label' => 'หวยลาว',
                'value' => function ($model) {
                    return $model->title;
                }
            ],
            [
                'label' => 'สีตัวตรง',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'four_dt', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สามตัวบน',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'three_top', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สองตัวหน้า',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'two_ft', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
            [
                'label' => 'สองตัวหลัง',
                'format' => 'html',
                'attribute' => 'action_id',
                'value' => function ($model) {
                    if ($model->status === 5) {
                        return 'ยกเลิก';
                    }
                    $playType = PlayType::find()->where(['code' => 'two_bk', 'game_id' => $model->gameId])->one();
                    $thaiSharedAnswerGame = ThaiSharedAnswerGame::find()->where(['playTypeId' => $playType->id, 'thaiSharedGameId' => $model->id])->one();
                    return $thaiSharedAnswerGame ? $thaiSharedAnswerGame->number : 'รอผล';
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
