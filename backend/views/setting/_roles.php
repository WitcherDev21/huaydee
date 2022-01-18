<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
?>
    
    <p>
        
        <div>
            <button class="btn btn-success pull-right" id="create-roles"><span class="glyphicon glyphicon-plus"></span> Create</button>
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
                    'dataProvider' => $dataAuthRoles,
                    'tableOptions' => ['style' => 'font-size: 12px;','class' => 'table table-bordered table-striped table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['class' => 'text-center','width' => '40'],
                            'contentOptions' => ['style' => 'text-align: center;']
                        ],
                        [
                            'attribute' => 'name',
                            'headerOptions' => ['class' => 'text-center'],
                            'attribute' => 'name',
                        ],
                        [
                            'header' => 'View',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url,$model,$key) {
                                    return '<span class="glyphicon glyphicon-list-alt roles_view" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                        [
                            'header' => 'Edit',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url,$model,$key) {
                                    return '<span class="glyphicon glyphicon-pencil roles_update" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                        [
                            'header' => 'Delete',
                            'headerOptions' => ['width' => '60', 'class' => 'text-center'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url,$model,$key) {
                                    return '<span class="glyphicon glyphicon-trash roles_delete" id="'.$model->id.'" style="cursor: pointer;"></span>';
                                }
                            ],
                        ],
                    ],
                ]); ?>
            </div>
            
            

<?php
$urlRoles = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/rolesgetdate']);
$urlRolesDelete = Yii::$app->urlManager->createUrl([Yii::$app->controller->id.'/rolesdelete']);
$script = <<<SCRIPT
$(document).ready(function(){
        
    $('span.roles_view').on('click',function(){
        var ViewId = this.id;
        $('#spinner').show();
        $.ajax({
            url: '$urlRoles',
            dataType: 'json',
            type: 'POST',
            data: {id:ViewId},
            success: function (data) {
                $('#spinner').hide();
                var tmp = '';
                if(data['roles_name'] !== ''){
                    $('#modal-roles-detail').modal('show');
                    $('div.roles_name').html(data['roles_name']);
                    if(data['permission'] !== ''){
                        if(data['permission'].length > 0){
                            $.each(data['permission'], function( index, value ) {
                                tmp += '<div><label>' + value['name'] + '</label></div>';
    //                            if(value['auth_permission_child'].length > 0){
    //                                $.each(value['auth_permission_child'], function( index2, value2 ) {
    //                                    tmp += '<div style="padding-left:20px;"><label>' + value2['auth_items']['name'] + '</label></div>';
    //                                });
    //                            }
                            });
                        }
                    }
                }
                $('div.roles_detail').html(tmp);
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
        
    $('span.roles_update').on('click',function(){
        var UpdateId = this.id;
        $('#spinner').show();
        $.ajax({
            url: '$urlRoles',
            dataType: 'json',
            type: 'POST',
            data: {id:UpdateId},
            success: function (data) {
                $('#spinner').hide();
                if(data['roles_name'] !== ''){
                    $('#modal-roles h4.modal-title').html('Update role.');
                    $('#form-roles')[0].reset();
                    $('#modal-roles').modal('show');
                    $('#authroles-id').val(data['roles_id']);
                    $('#authroles-name').val(data['roles_name']);
                    if(data['permission'] !== ''){
                        if(data['permission'].length > 0){
                            $.each(data['permission'], function( index, value ) {
                                $('input[name*="main_per[' + value['id'] + ']"]').prop( 'checked', true );
    //                            if(value['auth_permission_child'].length > 0){
    //                                $.each(value['auth_permission_child'], function( index2, value2 ) {
    //                                    $('input[name*="items_per[' + value['auth_permission_id'] + '][' + value2['auth_items_id'] + ']"]').prop( 'checked', true );
    //                                });
    //                            }
                            });
                        }
                    }
                }
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
    var deleteId = '';
    $('span.roles_delete').click(function(){
        deleteId = this.id;
        if(deleteId !== ''){
            $('div.modal_confirm_bank #modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('div.modal_confirm_bank #modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('div.modal_confirm_bank #modalConfirm h4#myModalLabel').html('ยืนยันการลบข้อมูล');
            $('div.modal_confirm_bank #modalConfirm div.modal-body').html('คุณต้องการลบข้อมูลใช่หรือไม่?');
            $('div.modal_confirm_bank #modalConfirm').modal('show');
        
            $('div.modal_confirm_bank button#btnConfirm').on('click',function(){
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
    <div class="modal_confirm_bank">
        <?php  
        echo $this->render('../template/modal_confirm');                      
        ?>
    </div>
<?php Modal::begin([
    'size' => 'modal-lg',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-roles-detail',
    'header' => '<h4 class="modal-title">รายละเอียด</h4>',
    'footer' => '<button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>',
]); ?>
    <div class="modal-detail">
        <div style="padding: 20px;">
            <div class="row">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-6 col-md-6">
                        <div class="roles_name">
                            
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="roles_detail">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php Modal::end(); ?>

<?php Modal::begin([
    'size' => 'modal-lg',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'id' => 'modal-roles',
    'header' => '<h4 class="modal-title"></h4>',
    'footer' => '<button class="btn btn-primary" id="btnRolesSave"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button> <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>',
]); ?>
    <?php
    $form = ActiveForm::begin([
        'id' => 'form-roles',
        'action' => Yii::$app->urlManager->createUrl(['setting/role']),
    ]); 
    ?>
    <div class="modal-detail">
        <div style="padding: 20px;">
            <div class="row">
                <div class="col-lg-12 col-md-12" style="padding: 0;">
                    <div class="col-lg-6 col-md-6">
                        <?php echo $form->field($modelAuthRoles, 'name')->textInput(['maxlength'=>'30', 'class'=>'form-control']); ?>
                        <?php echo $form->field($modelAuthRoles, 'id')->hiddenInput()->label(false); ?>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <b>ตั้งค่า</b>
                        <?php
                        if(!empty($listAuthPermission)){
                            foreach($listAuthPermission as $var){
                            ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="main_per main_per_<?php echo $var->id; ?>" id="main_per_<?php echo $var->id; ?>_<?php echo (!empty($var->auth_permission_items) ? count($var->auth_permission_items) : 0); ?>" name="main_per[<?php echo $var->id; ?>]" value="1"> <?php echo $var->name; ?>
                                    </label>
                                </div>
                                <?php
//                                if(!empty($var)){
//                                    foreach($var->auth_permission_items as $items){
                                    ?>
<!--                                        <div class="checkbox" style="padding-left:20px;">
                                            <label>
                                                <input type="checkbox" class="items_per items_per_<?php // echo $var->id; ?>" id="items_per_<?php // echo $var->id; ?>" name="items_per[<?php // echo $var->id; ?>][<?php // echo $items->auth_items->id; ?>]" value="1"> <?php // echo $items->auth_items->name; ?>
                                            </label>
                                        </div>-->
                                    <?php
//                                    }
//                                }
                                ?>
                            <?php  
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php Modal::end(); ?>
    
<?php
$script = <<<SCRIPT
$(document).ready(function(){
        
    $('button#create-roles').on('click', function(){
        $('#modal-roles h4.modal-title').html('Create role.');
        $('#form-roles')[0].reset();
        $('#modal-roles').modal('show');
    });
        
    $('button#btnRolesSave').on('click', function(){
        $('#form-roles').submit();
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