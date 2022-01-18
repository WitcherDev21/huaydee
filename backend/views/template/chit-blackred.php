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
                        'value' => function ($model) {
                            return $model->blackred->getOrderId();
                        }
                    ],
                    [
                        'header' => '<div class="text-center">งวดที่</div>',
                        'format'=>'html',
                        'value' => function ($model) {
                            return '<div class="text-center">'.$model->blackred->round.'</div>';
                        }
                    ],
                    [
                        'label' => 'วันที่ / เวลา',
                        'attribute' => 'create_at',
                        'value' => function ($model) {
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
                        'value' => function ($model) {
                            return number_format ( $model->total_amount, 2 );
                        }
                    ],
                    [
                         'label' => 'รายการที่แทง',
                         'value' => function ($model) {
                            return $model->play_type_code === Constants::blackred_black ? 'ดำ' : 'แดง';
                         }
                    ],
                    [
                        'label' => 'ผลชนะ',
                        'format'=>'html',
                        'value' => function ($model) {
                            $result = 'รอผล';
                            if($model->status == Constants::status_finish_show_result){
                                if($model->flag_result){
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
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column){
                            return '<a href="javascript:;" class="btn btn-xs btn-'.Constants::$statusIcon[$model->status].'">'
                                .Constants::$status[$model->status].'</a>';
                        }
                    ],
                ],
            ] );
            ?>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>
