<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
?>
    
    <p>
        
        <div>
            <button class="btn btn-success pull-right" id="create-bank">
                <span class="glyphicon glyphicon-plus"></span> Create
            </button>
        </div>
        <br><br>

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

        <?php Pjax::begin(['id' => 'countries', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>
    
            <div class="table-responsive">
                <?php echo GridView::widget([
                    'dataProvider' => $dataBank,
                    'tableOptions' => ['style' => 'font-size: 12px;','class' => 'table table-bordered table-striped table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class' => 'text-center','width' => '40'],
                            'contentOptions' => ['style' => 'text-align: center;']
                        ],
                        [
                            'label' => 'ชื่อธนาคาร',
                            'headerOptions' => ['class' => 'text-center'],
                            'attribute' => 'title',
                        ],
                        [
                            'header' => 'รูป',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center; width:150px;'],
                            'format'=>'html',
                            'value' => function ($model, $key, $index, $column) {
                                $bank = '<img src="'. str_replace('backend', 'frontend', Yii::getAlias('@web')).'/bank/'.$model->icon.'" class="bank_icon" style="background-color: '.$model->color.';width:20px;">';
                                return $bank;
                            }
                        ],
                        
                        [
                            'header' => 'แก้ไข',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url,$model,$key) {
                                    return '<span class="glyphicon glyphicon-pencil bank_update" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                        [
                            'header' => 'ลบ',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url,$model,$key) {
                                    return '<span class="glyphicon glyphicon-trash bank_delete" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                    ],
                ]); ?>
            </div>
            
<?php
$urlRoles = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/bankgetdate']);
$urlRolesDelete = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/bankdelete']);
$imgPath = str_replace('backend', 'frontend', Yii::getAlias('@web'));
$script = <<<SCRIPT
$(document).ready(function(){
        
    $('span.bank_update').on('click',function(){
        var UpdateId = this.id;
        $('#spinner').show();
        $.ajax({
            url: '$urlRoles',
            dataType: 'json',
            type: 'POST',
            data: {id:UpdateId},
            success: function (data) {
                $('#spinner').hide();
                if(data['title'] !== ''){
                    $('#modal-bank-edit h4.modal-title').html('แก้ไขข้อมูล');
                    $('#form-bank-edit')[0].reset();
                    $('#modal-bank-edit').modal('show');
                    $('#form-bank-edit div.show-img').html('<img src="$imgPath/bank/' + data['icon'] + '" style="background-color: ' + data['color'] + '; width:20px;" class="bank_icon">');
                    $('#form-bank-edit #banksearch-id').val(data['id']);
                    $('#form-bank-edit #banksearch-title').val(data['title']);
                }
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
    var deleteId = '';
    $('span.bank_delete').click(function(){
        deleteId = this.id;
        if(deleteId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการลบข้อมูล');
            $('#modalConfirm div.modal-body').html('คุณต้องการลบข้อมูลใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        
            $('button#btnConfirm').on('click',function(){
                $('#spinner').show();
                window.location.replace('$urlRolesDelete' + '/' + deleteId);
            });  
        
        }
    });
});
SCRIPT;
$this->registerJs($script);
?>
            
            
        <?php Pjax::end() ?>
    </p>
<?php  
echo $this->render('../template/modal_confirm');                      
?>

<?php Modal::begin([
    'size' => 'modal-lg',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-bank-add',
    'header' => '<h4 class="modal-title"></h4>',
    'footer' => '<button class="btn btn-primary" id="btnBankAddSave"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>',
]); ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-bank-add',
        'action' => Yii::$app->urlManager->createUrl(['setting/bank']),
        'options' => ['enctype' => 'multipart/form-data'],
        'method' => 'POST',
    ]); 
    ?>
    <div class="modal-detail">
        <div style="padding: 20px;">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <?php echo $form->field($modelBank, 'icon_add')->fileInput(['class'=>'form-control']) ?>
                    <?php echo $form->field($modelBank, 'title')->textInput(['maxlength'=>'200', 'class'=>'form-control']); ?>
                    
                    <?php echo $form->field($modelBank, 'id')->hiddenInput()->label(false); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
    

<?php Modal::begin([
    'size' => 'modal-lg',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-bank-edit',
    'header' => '<h4 class="modal-title"></h4>',
    'footer' => '<button class="btn btn-primary" id="btnBankEditSave"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>',
]); ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-bank-edit',
        'action' => Yii::$app->urlManager->createUrl(['setting/bank']),
        'options' => ['enctype' => 'multipart/form-data'],
        'method' => 'POST',
    ]); 
    ?>
    <div class="modal-detail">
        <div style="padding: 20px;">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="show-img"></div>
                    <br>
                    <?php echo $form->field($modelBank, 'icon_edit')->fileInput(['class'=>'form-control']) ?>
                    <?php echo $form->field($modelBank, 'title')->textInput(['maxlength'=>'200', 'class'=>'form-control']); ?>
                    
                    <?php echo $form->field($modelBank, 'id')->hiddenInput()->label(false); ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
    
<?php
$script = <<<SCRIPT
$(document).ready(function(){
        
    $('button#create-bank').on('click', function(){
        $('#modal-bank-add h4.modal-title').html('เพิ่มข้อมูลธนาคาร');
        $('#form-bank-add')[0].reset();
        $('#modal-bank-add').modal('show');
    });
        
    $('button#btnBankAddSave').on('click', function(){
        $('#form-bank-add').submit();
    });
        
    $('button#btnBankEditSave').on('click', function(){
        $('#form-bank-edit').submit();
    });
        
    $('input.main_per').on('change', function(){
        var id = this.id;
        var arr = id.split('_');
        if (!$('input#' + this.id).is(':checked')) {
            $('input.items_per_' + arr[2]).prop( 'checked', false );
        }
    });
        
    $('input.items_per').on('change', function(){
        var id = this.id;
        var arr = id.split('_');
        if (!$('input.main_per_' + arr[2]).is(':checked')) {
            $('input.main_per_' + arr[2]).prop( 'checked', true );
        }
    });
});
SCRIPT;
$this->registerJs($script);
?>