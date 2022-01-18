<?php
use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
<div class="widget-box">
	<div class="widget-title">
		<span class="icon"> <i class="icon-briefcase"></i>
		</span>
		<h5>รายการเอเยนต์</h5>
		<span class="label label-info">SSL Secure</span>
	</div>
    
    <?php if(count(Yii::$app->session->getAllFlashes()) > 0){ ?>
            <div style="padding: 20px 20px 0 20px;">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message){
                echo yii\bootstrap\Alert::widget([
                    'options' => [
                        'class' => 'alert-'.$message['type'],
                    ],
                    'body' => $message['message'],
                ]);
            } ?>
            </div>
    <?php } ?>
    
    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

	<div class="widget-content ">
            <div class="row">
                    <div class="col-sm-6 col-sm-offset-6">
                            <br>
                        <?php $form = ActiveForm::begin([
                            'id' => 'form-agent',
                            'action' => Yii::$app->urlManager->createUrl(['agent/list']),
                            'options' => ['data-pjax' => true ],
                            'method' => 'GET'
                        ]); 

                        $list_user_status[] = ['id'=>'','name'=>'-- สถานะ --'];
                        $list_user_status[] = ['id'=>Constants::user_status_active,'name'=>Constants::$user_status[Constants::user_status_active]];
                        $list_user_status[] = ['id'=>Constants::user_status_withhold,'name'=>Constants::$user_status[Constants::user_status_withhold]];
                        ?>
                        <div class="row">
                                <div class="col-md-9">
                                            <div class="form-group">
                                                <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">Username</label>
                                                <div class="col-md-9  col-sm-9">
                                                  <?= Html::activeInput('text',$searchModel, 'username',['class'=>'form-control']) ?>
                                                </div>
                                            </div>
                                            <br><br>
                                            <div class="form-group">
                                                <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">สถานะ</label>
                                                <div class="col-md-9  col-sm-9">
                                                  <?= Html::activeDropDownList($searchModel, 'user_status', ArrayHelper::map($list_user_status, 'id', 'name'),['class'=>'form-control']) ?>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="col-md-3">
                                            <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
                                    </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
            </div>

            <br><br>
                    
            <div class="table-responsive">
                <?php 
		echo GridView::widget ( [ 
                    'dataProvider' => $dataProvider,
				// 'filterModel' => $searchModel,
                    'columns' => [
                                    // ['class' => 'yii\grid\SerialColumn'],
                        [ 
                            'label' => 'ID',
                            'attribute' => 'id'
                        ],
                        [ 
                            'label' => 'Username',
                            'attribute' => 'username'
                        ],
                        [ 
                            'label' => 'ชื่อบัญชี',
                            'value' => function ($model, $key, $index, $column) {
                                return (!empty($model['bank_account_name']) ? $model['bank_account_name'] : '');
                            } 
                        ],
                        [ 
                            'label' => 'เลขที่บัญชี',
                            'value' => function ($model, $key, $index, $column) {
                                return (!empty($model['bank_account_no']) ? $model['bank_account_no'] : '');
                            } 
                        ],
                        [ 
                            'label' => 'เครดิต',
                            'value' => function ($model, $key, $index, $column) {
                                return (!empty($model['balance']) ? $model['balance'] : '');
                            } 
                        ],
                        [ 
                            'label' => 'ยอดแทง',
                            'value' => function ($model, $key, $index, $column) {
                                return (!empty($model['total_amount']) ? $model['total_amount'] : '');
                            } 
                        ],
                        [ 
                            'label' => 'เบอร์โทรศัพท์',
                            'attribute' => 'tel'
                        ],
                        [ 
                            'label' => 'วันที่สมัคร',
                            'value' => function ($model, $key, $index, $column) {
                                return (!empty($model['created_at']) ? date('Y-m-d',$model['created_at']) : '');
                            } 
                        ],
                        [ 
                            'label' => 'สถานะ',
                            'format'=>'html',
                            'value' => function ($model, $key, $index, $column) {
                                $result = '';
                                if(!empty($model['user_status'])){
                                    $result = '<a href="javascript:;" class="btn btn-xs btn-'.($model['user_status']==1 ? 'success' : 'danger').'">'.Constants::$user_status[$model['user_status']].'</a>';
                                }
                                return $result;
                            } 
                        ],
                        [ 
                            'label' => 'กระทำ',
                            'format' => 'raw',
                            'options' => ['style' => 'width:50px;'],
                            'value' => function ($model, $key, $index, $column) {
                                $url = Yii::$app->urlManager->createUrl(['agent/update/'.$model['id']]);
                                $tmp = '';
                                $tmp .= '<div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" style="left: -125px;">
                                        <li><a href="'.$url.'">แก้ไข</a></li>
                                        <li role="separator" class="divider"></li>';
                                $tmp .= ($model['user_status']=='1' ? '<li class="assistant_inactive" id="'.$model['id'].'"><a href="#">ระงับ</a></li>' : '<li class="assistant_active" id="'.$model['id'].'"><a href="#">ปลดระงับ</a></li>');
                                $tmp .= '</ul></div>';

                                return $tmp;
                            }
                        ],
                    ],
		] );
                ?>
            </div>
            
	</div>
    
    
<?php
$url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/changestatus']);
$script = <<<SCRIPT
$(document).ready(function(){
        
    var eventId = '';
    var eventType = '';
        
    $('li.assistant_inactive').click(function(){
        eventId = this.id;
        eventType = 'inactive';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการเปลี่ยนสถานะข้อมูล');
            $('#modalConfirm div.modal-body').html('คุณต้องการเปลี่ยนสถานะข้อมูลใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('li.assistant_active').click(function(){
        eventId = this.id;
        eventType = 'active';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการเปลี่ยนสถานะข้อมูล');
            $('#modalConfirm div.modal-body').html('คุณต้องการเปลี่ยนสถานะข้อมูลใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('button#btnConfirm').on('click',function(){
        $('#spinner').show();
        window.location.replace('$url' + '/' + eventType + '/' + eventId);
    });  
});
SCRIPT;
$this->registerJs($script);
?>
    
<?php  
echo $this->render('../template/modal_confirm');                      
?>
    <?php Pjax::end() ?>
</div>
