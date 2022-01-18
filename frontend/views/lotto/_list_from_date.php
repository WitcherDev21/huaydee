<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;


$this->title = '';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12" style="overflow-y:auto; height:100%;">
	<h4>จับยี่กี  - <?= date('d/m/Y',strtotime($date))?></h4>	
    <?php Pjax::begin(); ?>

    <?= GridView::widget([    	
        'dataProvider' => $dataProvider,
        'columns' => [
            [ 
				'label' => 'รอบ',
				'value' => function ($model, $key, $index, $column) {
					return 'จับยี่กี รอบที่ '. $model->round;
				} 
			],
			[
				'label' => 'สามตัวบน',
				'format' => 'html',
				'attribute' => 'poster_id',
				'value' => function ($model, $key, $index, $column) {
					if($model->status == Constants::status_cancel){
						return '<div class="text-danger">'.Constants::$status[$model->status].'</div>';
					}else{
						return empty($model->getResults('two_under'))?'รอผล': '<strong>'.$model->getResults('three_top').'</strong>';
					}
				}
			],
			[
				'label' => 'สองตัวล่าง',
				'format' => 'html',
				'attribute' => 'action_id',
				'value' => function ($model, $key, $index, $column) {	
					if($model->status == Constants::status_cancel){
						return '<div class="text-danger">'.Constants::$status[$model->status].'</div>';
					}else{
						return empty($model->getResults('two_under'))?'รอผล':'<strong>'.$model->getResults('two_under').'</strong>';
					}
				}
			],
          
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
