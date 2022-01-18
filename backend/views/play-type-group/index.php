<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PlayTypeGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Play Type Gourps';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="play-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('สร้างกลุ่มประเภทการเล่น', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="widget-box">
        <div class="widget-title bg_lg">
            <span class="icon"><i class="icon-star"></i></span>
            <h5>กลุ่มประเภทการเล่น</h5>
        </div>
        <div class="widget-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'title',
                        'number_length',
                        'number_range',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>