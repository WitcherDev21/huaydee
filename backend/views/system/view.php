<?php

use common\libs\Constants;
use common\models\PlayType;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LotteryGameChitAnswer */
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= Yii::t('app', 'Yeekee round') ?><?= $model->round?></h5>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'เกม',
                'value' => function ($model) {
                    return Yii::t('app', 'Yeekee round').' '.$model->round;
                }
            ],
            [
                'label' => 'ผลสามตัวบน',
                'value' => function () use ($threeTop) {
                    return $threeTop ? $threeTop : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสามตัวโต๊ด',
                'format' => 'html',
                'value' => function () use ($threeTod) {
                    $isArray = is_array($threeTod);
                    $textValue = '';
                    if ($isArray) {
                        foreach ($threeTod as $value) {
                            if ($value  === '') {
                                return 'ยังไม่ออกผล';
                            }
                            $textValue .= $value.'<br>';
                        }
                    }
                    return $textValue;
                }
            ],
            [
                'label' => 'ผลสองตัวบน',
                'value' => function () use ($twoTop) {
                    return $twoTop ? $twoTop : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลสองตัวล่าง',
                'value' => function () use ($twoUnder) {
                    return $twoUnder ? $twoUnder : 'ยังไม่ออกผล';
                }
            ],
            [
                'label' => 'ผลวิ่งบน',
                'format' => 'html',
                'value' => function () use ($runTop) {
                    $isArray = is_array($runTop);
                    $textValue = '';
                    if ($isArray) {
                        foreach ($runTop as $value) {
                            if ($value  === '') {
                                return 'ยังไม่ออกผล';
                            }
                            $textValue .= $value.'<br>';
                        }
                    }
                    return $textValue;
                }
            ],
            [
                'label' => 'ผลวิ่งล่าง',
                'format' => 'html',
                'value' => function () use ($runUnder) {
                    $textValue = '';
                    $isArray = is_array($runUnder);
                    if ($isArray) {
                        foreach ($runUnder as $value) {
                            if ($value === '') {
                                return 'ยังไม่ออกผล';
                            }
                            $textValue .= $value.'<br>';
                        }
                    }
                    return $textValue;
                }
            ],
        ],
    ]) ?>

</div>
