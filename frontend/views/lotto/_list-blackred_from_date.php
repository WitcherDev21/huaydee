<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;
use common\models\UserHasBankLog;
use common\models\UserHasBankSearch;
use common\models\PostCreditTransectionSearch;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostCreditTransectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $date */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12" style="overflow-y:auto; height:100px;">
	<h4>ดำแดง  - <?= date('d/m/Y',strtotime($date))?></h4>
	
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
	       	['class' => 'yii\grid\SerialColumn','header'=>'รอบ'],
			[
				'label' => 'ผลดำแดง',
				'format' => 'html',
				'attribute' => 'poster_id',
				'value' => function ($model, $key, $index, $column) {
                    if ($model->result === 1) {
                        $result = 'ดำ';
                        $color = '#000000';
                    } elseif ($model->result === 2) {
                        $result = 'แดง';
                        $color = '#ff0000';
                    } else {
                        $result = 'ยังไม่ออกผล';
                        $color = '#0216FF';
                    }
					return '<a class="btn btn-xs" style="color:#ffffff; width: 50%; background-color:'.$color.'">'.$result.'</a>';
				}
			],
          
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
