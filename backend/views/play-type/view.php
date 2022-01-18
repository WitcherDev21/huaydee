<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PlayType */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'ประเภทการเล่น', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a('กลับสู่หน้า play-type', ['setting/index/setting_play_type'], ['class' => 'btn btn-success']) ?>
</p>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>ปรเภทการเล่น</h5>
    </div>
    <div class="widget-content">
        <div class="table-responsive">
            <h1><?= Html::encode($this->title) ?></h1>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'code',
                    'title',
                    'description',
                    'jackpot_per_unit',
                    'minimum_play',
                    'maximum_play',
                    'limitByUser',
                    [
                        'attribute' => 'group_id',
                        'value' => function ($model) {
                            return $model->group->title;
                        }
                    ],
                    [
                        'attribute' => 'game_id',
                        'value' => function ($model) {
                            return $model->game->title;
                        }
                    ],
                    [
                        'attribute' => 'create_by',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    'create_at',
                    'update_at',
                ],
            ]) ?>
        </div>
    </div>

</div>