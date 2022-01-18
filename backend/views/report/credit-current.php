<?php
/* @var $searchModel */
/* @var $dataProvider */
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\libs\Constants;
use yii\bootstrap\Modal;
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

                <div class="row">
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
                <br><br>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="table-responsive">
                <?php 
		echo GridView::widget ( [ 
                    'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
                    'columns' => [
                                    // ['class' => 'yii\grid\SerialColumn'],
                        
                        
//								<th>เลขที่ทำรายการ</th>
//								<th>รายละเอียด</th>
//								<th>ผู้กระทำ</th>
//								<th>ลูกค้า</th>
//								<th>เครดิต</th>
//								<th>วันที่ - เวลา</th>
//								<th>หมายเหตุ</th>
                        
                        
                        
                        
                        [ 
                            'label' => 'เลขที่รายการ',
                            //'attribute' => 'id',
                            'value' => function ($model, $key, $index, $column) {
                                //return str_pad ( $model->id, 8, '0', STR_PAD_LEFT );
                                return $model->getOrderId();
                            } 
                        ],
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
                            'value' => function ($model, $key, $index, $column){
                                    return Constants::$status[$model->status];
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
                                    return '<span class="glyphicon glyphicon-list-alt chit_view" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                    ],
		] );
                ?>
            </div>

<?php
$urlChit = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/chitgetdate']);
$script = <<<SCRIPT
$(document).ready(function(){
        
    $('span.chit_view').on('click',function(){
        var ViewId = this.id;
        $('#spinner').show();
        $.ajax({
            url: '$urlChit',
            dataType: 'json',
            type: 'POST',
            data: {id:ViewId},
            success: function (data) {
        
                $('#modal-chit-detail').modal('show');
                var tmp = '';
                var tmp_foot = '';
                if(data !== ''){
                    $.each(data, function( index, value ) {
                        if(index !== 'amount_total' && index !== 'win_credit'){
                            tmp += '<tr>';
                            tmp += '<td>' + (parseInt(index) + 1) + '</td>';
                            tmp += '<td>' + value['title'] + '</td>';
                            tmp += '<td>' + value['number'] + '</td>';
                            tmp += '<td>' + value['amount'] + '</td>';
                            tmp += '<td>' + value['jackpot_per_unit'] + '</td>';
                            tmp += '<td>' + value['play_type_code'] + '</td>';
                            tmp += '<td>' + value['win_credit'] + '</td>';
                            tmp += '<td>' + value['status'] + '</td>';
                            tmp += '</tr>';
                        }
                    });
                    
                    tmp_foot += '<tr>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '<td>' + data['amount_total'] + '</td>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '<td>' + data['win_credit'] + '</td>';
                    tmp_foot += '<td></td>';
                    tmp_foot += '</tr>';
        
                }
        
                $('#modal-chit-detail table tbody').html(tmp);
                $('#modal-chit-detail table tfoot').html(tmp_foot);
        
        
                $('#spinner').hide();
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
});
SCRIPT;
$this->registerJs($script);
?>






    <?php Pjax::end() ?>


<?php Modal::begin([
    'size' => 'modal-lg',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-chit-detail',
    'header' => '<h4 class="modal-title">รายการโพย</h4>',
    'footer' => '<button type="reset" class="btn btn-default" data-dismiss="modal">ปิด</button>',
]); ?>
    <div class="modal-detail">
        <div style="padding: 20px;">
            <div class="row">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ประเภทหวย</th>
                            <th>เลขที่แทง</th>
                            <th>ราคาที่แทง</th>
                            <th>ราคาจ่าย</th>
                            <th>เลขที่ออก</th>
                            <th>ผลได้เสีย</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                    <tfoot>
                        
                    </tfoot>
                </table>
                
            </div>
        </div>
    </div>
<?php Modal::end(); ?>