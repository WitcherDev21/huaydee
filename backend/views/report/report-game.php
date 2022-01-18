<?php
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 3/5/2019
 * Time: 10:37 PM
 */

use kartik\grid\GridView;

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>รายงานเกมราย user </h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <?= $this->render('_search_report', ['searchModel' => $searchModel]) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'panel'=>[
                    'before'=>' '
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'ประเภทหวย',
                        'value' => function ($model) {
                            return $model->playType->title;
                        }
                    ],
                    [
                        'label' => 'เลขที่แทง',
                        'value' => 'number',
                    ],
                    [
                        'label' => 'ราคาที่แทง',
                        'value' => 'amount'
                    ],
                    [
                        'label' => 'ราคาที่จ่าย',
                        'value' => function ($model) {
                            return $model->amount * $model->playType->jackpot_per_unit;
                        }
                    ],
                    [
                        'label' => 'รหัสผู้ใช้',
                        'value' => 'userId',
                    ],
                    [
                        'label' => 'ชื่อผู้ใช้',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'วันที่',
                        'value' => function ($model) {
                            return Yii::$app->formatter->asDatetime($model->createdAt, "php:Y-m-d");
                        }
                    ],
                    [
                        'label' => 'เกม',
                        'value' => function ($model) {
                            return $model->thaiSharedGameChit->thaiSharedGame->game->title;
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
