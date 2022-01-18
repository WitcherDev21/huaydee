<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
?>
    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

            <div>

                <?php
                $form = ActiveForm::begin([
                    'id' => 'form-chit1',
                    'method' => 'post',
                    'options' => ['class'=>'form-horizontal'],
                    'action' => Yii::$app->urlManager->createUrl(['chit/list/tab1'])
                ]); 
                ?>

<!--                <div class="row">
                    <div class="col-md-9 col-md-offset-3">
                        <div class="form-group">
                            <label class="col-md-2 col-sm-2 control-label" style="text-align: right;">งวดที่</label>
                            <div class="col-md-7  col-sm-7">
                              <?= Html::activeInput('text',$searchModel, 'round',['class'=>'form-control','placeholder'=>'1 - 88']) ?>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>-->
                <?php ActiveForm::end(); ?>
            </div>
            <div class="table-responsive">
                <?php 
		echo GridView::widget ( [ 
                    'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
                    'columns' => [
                                    // ['class' => 'yii\grid\SerialColumn'],
//                        [ 
//                            'label' => 'เลขที่รายการ',
//                            //'attribute' => 'id',
//                            'value' => function ($model, $key, $index, $column) {
//                                //return str_pad ( $model->id, 8, '0', STR_PAD_LEFT );
//                                return $model->getOrderId();
//                            } 
//                        ],
                        [ 
                            'label' => 'สมาชิก',
                            //'attribute' => 'id',
                            'value' => function ($model, $key, $index, $column) {
                                //return str_pad ( $model->id, 8, '0', STR_PAD_LEFT );
                                return $model->user->username;
                            } 
                        ],
                        [ 
                            'label' => 'เอเย็นต์',
                            //'attribute' => 'id',
                            'value' => function ($model, $key, $index, $column) {
                                //return str_pad ( $model->id, 8, '0', STR_PAD_LEFT );
                                $username = $model->getAgentProfile($model->user->agent);
                                return (!empty($username) ? $username->username : '');
                            } 
                        ],
//                        [ 
//                            'header' => '<div class="text-center">งวดที่</div>',
//                            'format'=>'html',
//                            'value' => function ($model, $key, $index, $column) {
//                                    return '<div class="text-center">'.'งวดที่ '.$model->yeekee->round.' วันที่ '.date('d/m/Y',strtotime($model->yeekee->date_at)).'</div>';
//                            } 
//                        ],
//                        [
//                            'label' => 'วันที่ / เวลา',
//                            'attribute' => 'create_at',
//                            'value' => function ($model, $key, $index, $column) {
//                                    $date = '';
//                                    if(empty($model->update_at)){
//                                            $date = date('d/m/Y H:i:s',strtotime($model->create_at));
//                                    }else{
//                                            $date = date('d/m/Y H:i:s',strtotime($model->update_at));
//                                    }
//                                    return $date;
//                            } 
//                        ],
//                        [ 
//                            'label' => 'เงินเดิมพัน',
//                            'attribute' => 'total_amount',
//                            'footer' => '',
//                            'value' => function ($model, $key, $index, $column) {
//                                    return number_format ( $model->total_amount, 2 );
//                            } 
//                        ],
//                        [
//                            'label' => 'ผลชนะ',
//                            'format'=>'html',
//                            'value' => function ($model, $key, $index, $column) {
//                                $result = 'รอผล';
//                                if($model->status == Constants::status_finish_show_result){
//                                        if($model->getIsWin()){
//                                                $result = '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>';
//                                        }else{
//                                                $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'0'.'</a>';
//                                        }										
//                                }else if($model->status == Constants::status_cancel){
//                                        $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'ยกเลิก'.'</a>';
//                                }
//                                return $result;
//
//                            } 
//                        ],
//                        [ 
//                            'label' => 'สถานะ',
//                            'format' => 'html',
//                            'value' => function ($model, $key, $index, $column){
//                                    return '<a href="javascript:;" class="btn btn-xs btn-'.Constants::$statusIcon[$model->status].'">'
//                                            .Constants::$status[$model->status].'</a>';
//                            }
//                        ],
                        [
                            'header' => 'ดูโพย',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url,$model,$key) {
                                    $url = Yii::$app->urlManager->createUrl(['chit']);
                                    return '<a href="'.$url.'/chit/'.$model->user_id.'" data-pjax="0"><span class="btn btn-xs btn-info">view</span></a>';
                                }
                            ],
                        ],
                    ],
		] );
                ?>
            </div>

    <?php Pjax::end() ?>