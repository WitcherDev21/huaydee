<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ThaiSharedValueAddedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thai Shared Value Addeds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-lottery-lao-set-index">
    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5>มูลค่าเพิ่มรายการหวยหุ้น</h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label' => 'Thai Shared Game',
                            'value' => function ($model) {
                                return $model->thaiSharedGame->title;
                            }
                        ],
                        [
                            'label' => 'play Type',
                            'value' => function ($model) {
                                return $model->playType->title;
                            }
                        ],
                        'number',
                        'amount',
                        'createdAt',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttonOptions' => ['class' => 'btn btn-default'],
                            'template' => '<div class="btn-group btn-group-sm text-center" role="group">{delete}</div>',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>