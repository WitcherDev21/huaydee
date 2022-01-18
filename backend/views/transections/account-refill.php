<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Html;
use common\libs\Constants;
?>


<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-bar-chart"></i></span>
		<h5>บัญชีฝาก-ถอน</h5>
	</div>
	<div class="widget-content ">
            
            <?php if(count(Yii::$app->session->getAllFlashes()) > 0){ ?>
                <div style="padding-top: 20px;">
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

                <div class="widget-content nopadding">
            
                    <div class="row">
                            <div class="">
                                    <br>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'form-accountrefill',
                                    'action' => Yii::$app->urlManager->createUrl(['transections/accountrefill']),
                                    'options' => ['data-pjax' => true ],
                                    'method' => 'GET'
                                ]); 

                                ?>
                                        <div class="col-md-5 col-sm-5">
                                            <div class="form-group">
                                                <label class="control-label" >User Name / ชื่อบัญชี / เลขบัญชี</label>
                                                <?= Html::activeInput('text',$searchModel, 'filter_text',['class'=>'form-control']) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-5">
                                            <div class="form-group">
                                                <label class="control-label" >สถานะ</label>
                                                <?= Html::activeDropDownList($searchModel, 'filter_bank_id', ArrayHelper::map($bankList, 'id', 'name'),['class'=>'form-control','prompt'=>'-- ค้นหาธนาคาร --']) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2" style="padding-top: 20px;">
                                            <div class="form-group">
                                                <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
                                            </div>
                                        </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                    </div>

                    <br><br>
            
                    <div>
                        <a href="<?php echo Yii::$app->urlManager->createUrl(['transections/accountrefill/create']); ?>" class="btn btn-success pull-right" data-pjax="0"><span class="glyphicon glyphicon-plus"></span> Create</a>
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
                                    'label' => 'ธนาคาร',
                                    'value' => function ($model, $key, $index, $column) {
                                        return (!empty($model['bank']['title']) ? $model['bank']['title'] : '');
                                    } 
                                ],
                                [ 
                                    'label' => 'ชื่อบัญชี',
                                    'value' => function ($model, $key, $index, $column) {
                                        return (!empty($model['bank_account_name']) ? $model['bank_account_name'] : '');
                                    } 
                                ],
                                [ 
                                    'label' => 'เลขบัญชี',
                                    'value' => function ($model, $key, $index, $column) {
                                        return (!empty($model['bank_account_no']) ? $model['bank_account_no'] : '');
                                    } 
                                ],
                                [ 
                                    'label' => 'สถานะ',
                                    'options' => ['style' => 'width:100px;'],
                                    'value' => function ($model, $key, $index, $column) {
                                        return Constants::$status[$model['status']];
                                    } 
                                ],
                                [ 
                                    'label' => 'กระทำ',
                                    'format' => 'raw',
                                    'options' => ['style' => 'width:50px;'],
                                    'value' => function ($model, $key, $index, $column) {
                                        $url = Yii::$app->urlManager->createUrl(['transections/accountrefill/update/'.$model['id']]);
                                        $tmp = '';
                                        $tmp .= '<div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" style="left: -125px;">
                                                <li><a href="'.$url.'">แก้ไข</a></li>
                                                <li role="separator" class="divider"></li>';
                                        $tmp .= ($model['status']=='1' ? '<li class="accountrefill_status_withhold" id="'.$model->id.'"><a href="#">ระงับการใช้งาน</a></li>' : '<li class="accountrefill_status_active" id="'.$model->id.'"><a href="#">ปลดระงับ</a></li>');
                                        $tmp .= '<li class="accountrefill_delete" id="'.$model->id.'"><a href="#">ลบ</a></li>
                                            </ul>
                                        </div>';
                                        
                                        return $tmp;
                                    }
                                ],
                            ],
                        ] );
                        ?>
                    </div>
                </div>

<?php
$urlRolesDelete = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/accountrefill']);
$script = <<<SCRIPT
$(document).ready(function(){
        
    var eventId = '';
    var eventType = '';
        
    $('li.accountrefill_delete').click(function(){
        eventId = this.id;
        eventType = 'delete';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการลบข้อมูล');
            $('#modalConfirm div.modal-body').html('คุณต้องการลบข้อมูลใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('li.accountrefill_status_withhold').click(function(){
        eventId = this.id;
        eventType = 'withhold';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการเปลี่ยนสถานะ');
            $('#modalConfirm div.modal-body').html('คุณต้องการระงับใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('li.accountrefill_status_active').click(function(){
        eventId = this.id;
        eventType = 'active';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการเปลี่ยนสถานะ');
            $('#modalConfirm div.modal-body').html('คุณต้องการปลดระงับใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('button#btnConfirm').on('click',function(){
        $('#spinner').show();
        window.location.replace('$urlRolesDelete' + '/' + eventType + '/' + eventId);
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
</div>
