<?php

use common\libs\Constants;
use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\LotteryGameChitAnswer */

$this->title = $model->game->title . ' ' . $model->round;
$this->params['breadcrumbs'][] = ['label' => 'Thai Shared Answer Game', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if ($model->gameId === Constants::LOTTERYLAOGAME || $model->gameId === Constants::LOTTERYLAODISCOUNTGAME || $model->gameId === Constants::LOTTERY_VIETNAM_SET) {
    $url = Url::to(['tricker-lottery-lao-set', 'id' => $model->id]);
} else {
    $url = Url::to(['tricker', 'id' => $model->id]);
}
$urlBlock = Url::to(['block', 'id' => $model->id]);
$js = <<<EOT
var url = '$url';
var urlBlock = '$urlBlock';
    $('#confirm').click(function(e){
	    swal({
          width: '50%',
          title: '',
          html: "<div style='font-size:18px;'>คุณต้องการออกผล <br>ใช่ หรือ ไม่ใช่ ?</div>",
          type: 'info',
          showCancelButton: true,
          confirmButtonText: 'ใช่',
          cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
          if (result.value) {
            $('#confirm').prop('disabled', true);
			$.ajax({
		        url: url,
		        type: 'post',
		        success: function (result) {
                    swal({
                        width: '50%',
                        html: "<div style='font-size:18px;'>"+result.message+"</div>",
                    }).then(function() {
                        location.reload();
                    });
                    $('#confirm').prop('disabled', false);
		        },
		        error: function () {
		        }
		      }); 
          }
        });
    });
   $('#confirm-block').click(function(e){
	    swal({
          width: '50%',
          title: '',
          html: "<div style='font-size:18px;'>คุณต้องการ block user <br>ใช่ หรือ ไม่ใช่ ?</div>",
          type: 'info',
          showCancelButton: true,
          confirmButtonText: 'ใช่',
          cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
          if (result.value) {
            $('#confirm-block').prop('disabled', true);
			$.ajax({
		        url: urlBlock,
		        type: 'post',
		        success: function (result) {
                    swal({
                        width: '50%',
                        html: "<div style='font-size:18px;'>"+result.message+"</div>",
                    }).then(function() {
                        location.reload();
                    });
                    $('#confirm').prop('disabled', false);
		        },
		        error: function () {
		        }
		      }); 
          }
        });
    });
EOT;
$this->registerJs($js);
?>
<p>
    <?php
    if (in_array('update-answer-shared-game', $arrRoles)) {
        echo Html::a('แก้ไขการออกผล', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
    }
    if (in_array('thai-shared-game-answer', $arrRoles)) {
        echo Html::button('ยืนยันการออกผล', ['id' => 'confirm', 'class' => 'btn btn-danger']);
        echo Html::button('block การออกผล', ['id' => 'confirm-block', 'class' => 'btn btn-danger']);
    }
    echo Html::a('กลับสู่หน้าหวยหุ้น', ['thai-shared/index'], ['class' => 'btn btn-warning'])
    ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $model->title ?> / <?= date('Y-m-d', strtotime($model->startDate)) ?></h5>
    </div>

    <?php
    if ($model->gameId === Constants::LOTTERYLAOGAME || $model->gameId === Constants::LOTTERYLAODISCOUNTGAME || $model->gameId === Constants::LOTTERY_VIETNAM_SET) {
        $attributes = [
            [
                'label' => 'เกม',
                'value' => function ($model) {
                    return $model->game->title . ' ' . $model->round;
                }
            ],
            [
                'label' => 'ผลสี่ตัวตรง',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'four_dt', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสี่ตัวโต๊ด',
                'format' => 'html',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'four_tod', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    if (!$lotteryGameChitAnswers) {
                        return 'ยังไม่ออกผล';
                    }
                    $text = '';
                    foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                        $text .= $lotteryGameChitAnswer->number . '<br>';
                    }
                    return $text;
                }
            ],
            [
                'label' => 'ผลสามตัวบน',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'three_top', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสามตัวโต๊ด',
                'format' => 'html',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'three_tod', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    if (!$lotteryGameChitAnswers) {
                        return 'ยังไม่ออกผล';
                    }
                    $text = '';
                    foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                        $text .= $lotteryGameChitAnswer->number . '<br>';
                    }
                    return $text;
                }
            ],
            [
                'label' => 'ผลสองตัวหน้า',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'two_ft', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสองตัวหลัง',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'two_bk', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสามตัวหน้า',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'three_ft', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
        ];
    } else {
        $attributes = [
            [
                'label' => 'เกม',
                'value' => function ($model) {
                    return $model->game->title . ' ' . $model->round;
                }
            ],
            [
                'label' => 'ผลสามตัวบน',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'three_top', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสามตัวโต๊ด',
                'format' => 'html',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'three_tod', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    if (!$lotteryGameChitAnswers) {
                        return 'ยังไม่ออกผล';
                    }
                    $text = '';
                    foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                        $text .= $lotteryGameChitAnswer->number . '<br>';
                    }
                    return $text;
                }
            ],
            [
                'label' => 'ผลสองตัวบน',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'two_top', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสองตัวล่าง',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'two_under', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswer = ThaiSharedAnswerGame::findOne(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    return isset($lotteryGameChitAnswer->number) ? $lotteryGameChitAnswer->number : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลวิ่งบน',
                'format' => 'html',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'run_top', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    if (!$lotteryGameChitAnswers) {
                        return 'ยังไม่ออกผล';
                    }
                    $text = '';
                    foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                        $text .= $lotteryGameChitAnswer->number . '<br>';
                    }
                    return $text;
                }
            ],
            [
                'label' => 'ผลวิ่งล่าง',
                'format' => 'html',
                'value' => function ($model) {
                    $playTypeObj = PlayType::findOne(['code' => 'run_under', 'game_id' => $model->gameId]);
                    $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                    if (!$lotteryGameChitAnswers) {
                        return 'ยังไม่ออกผล';
                    }
                    $text = '';
                    foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                        $text .= $lotteryGameChitAnswer->number . '<br>';
                    }
                    return $text;
                }
            ],
        ];
        if ($model->gameId === Constants::LOTTERYGAME || $model->gameId === Constants::LOTTERYGAMEDISCOUNT) {
            $lotteryGameAttributes = [
                [
                    'label' => '3 ตัวหน้าหมุน 2 ครั้ง',
                    'format' => 'html',
                    'value' => function ($model) {
                        $playTypeObj = PlayType::findOne(['code' => 'three_top2', 'game_id' => $model->gameId]);
                        $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                        if (!$lotteryGameChitAnswers) {
                            return 'ยังไม่ออกผล';
                        }
                        $text = '';
                        foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                            $text .= $lotteryGameChitAnswer->number . '<br>';
                        }
                        return $text;
                    }
                ],
                [
                    'label' => '3 ตัวล่างหมุน 2 ครั้ง',
                    'format' => 'html',
                    'value' => function ($model) {
                        $playTypeObj = PlayType::findOne(['code' => 'three_und2', 'game_id' => $model->gameId]);
                        $lotteryGameChitAnswers = ThaiSharedAnswerGame::findAll(['playTypeId' => $playTypeObj->id, 'thaiSharedGameId' => $model->id]);
                        if (!$lotteryGameChitAnswers) {
                            return 'ยังไม่ออกผล';
                        }
                        $text = '';
                        foreach ($lotteryGameChitAnswers as $lotteryGameChitAnswer) {
                            $text .= $lotteryGameChitAnswer->number . '<br>';
                        }
                        return $text;
                    }
                ],
            ];
            $attributes = array_merge($attributes, $lotteryGameAttributes);
        }
        if ($model->gameId === Constants::GSB_THAISHARD_GAME || $model->gameId === Constants::BACC_THAISHARD_GAME) {
            $resultAttribute = [
                    [
                        'label' => 'เลข 6 ตัว',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->result;
                        }
                    ]
            ];
            $attributes = array_merge($attributes, $resultAttribute);
        }
    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
