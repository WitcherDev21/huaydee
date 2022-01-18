<?php
use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;
?>

<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><span class="glyphicon glyphicon-file"></span></span>
		<h5>รายการโพย</h5>
	</div>
    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>
	<div class="widget-content ">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">ข้อมูลสมาชิก</h3>
                </div>
                <div class="panel-body">
                    <b>Username ::</b> <?php echo $dataUser->username; ?><br><br>
                    <b>อีเมล์ ::</b> <?php echo $dataUser->email; ?><br><br>
                    <b>เบอร์โทร ::</b> <?php echo $dataUser->tel; ?>
                </div>
            </div>
            
            <div class="table-responsive">
                <?php 
		echo GridView::widget ( [ 
                    'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
                    'columns' => [
                        [ 
                            'label' => 'เลขที่รายการ',
                            'value' => function ($model, $key, $index, $column) {
                                return $model->getOrder();
                            } 
                        ],
                        [ 
                            'header' => '<div class="text-center">งวดที่</div>',
                            'format'=>'html',
                            'value' => function ($model, $key, $index, $column) {
                                    return '<div class="text-center">'.$model->yeekee->round.'</div>';
                            } 
                        ],
                        [
                            'label' => 'วันที่ / เวลา',
                            'attribute' => 'create_at',
                            'value' => function ($model, $key, $index, $column) {
                                    $date = '';
                                    if(empty($model->update_at)){
                                            $date = date('d/m/Y H:i:s',strtotime($model->create_at));
                                    }else{
                                            $date = date('d/m/Y H:i:s',strtotime($model->update_at));
                                    }
                                    return $date;
                            } 
                        ],
                        [ 
                            'label' => 'เงินเดิมพัน',
                            'attribute' => 'total_amount',
                            'footer' => '',
                            'value' => function ($model, $key, $index, $column) {
                                    return number_format ( $model->total_amount, 2 );
                            } 
                        ],
                        [
                            'label' => 'ผลชนะ',
                            'format'=>'html',
                            'value' => function ($model, $key, $index, $column) {
                                $result = 'รอผล';
                                if($model->status == Constants::status_finish_show_result){
                                        if($model->getIsWin()){
                                                $result = '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>';
                                        }else{
                                                $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>';
                                        }										
                                }else if($model->status == Constants::status_cancel){
                                        $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'ยกเลิก'.'</a>';
                                }
                                return $result;

                            } 
                        ],
                        [ 
                            'label' => 'สถานะ',
//                            'value' => function ($model, $key, $index, $column){
//                                    return Constants::$status[$model->status];
//                            }
                            'format' => 'html',
                            'value' => function ($model, $key, $index, $column){
                                    return '<a href="javascript:;" class="btn btn-xs btn-'.Constants::$statusIcon[$model->status].'">'
                                            .Constants::$status[$model->status].'</a>';
                            }
                        ],
                        [
                            'header' => 'ดูโพย',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url,$model,$key) {
                                    $urlChit = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/chit_detail/'.$model->id]);
                                    return '<a href="'.$urlChit.'" target="_blank" data-pjax="0"><span class="glyphicon glyphicon-list-alt"></span></a>';
                                }
                            ],
                        ],    
                    ],
		] );
                ?>
            </div>
	</div>
    <?php Pjax::end() ?>
</div>
