<?php
/* @var $searchModel
 * @var $dataProvider
 */
/**/
use common\libs\Constants;
use common\models\ThaiSharedGameChit;
use common\models\User;
use common\models\YeekeeChit;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\UserHasBank;

?>

<div class="widget-box">
    <div class="widget-title">
        <ul class="nav nav-tabs">
            <li class="<?= Yii::$app->controller->action->id === 'list' ? 'active' : '' ?>">
                <a class="tab1" href="<?= Url::to(['members/list'])?>">รายการสมาชิก</a>
            </li>
            <li class="<?= Yii::$app->controller->action->id === 'waiting-approve' ? 'active' : '' ?>">
                <a class="tab1" href="<?= Url::to(['members/waiting-approve'])?>">รายการบัญชีรออนุมัติ</a>
            </li>
        </ul>
    </div>

    <?php if (count(Yii::$app->session->getAllFlashes()) > 0) { ?>
        <div style="padding: 20px 20px 0 20px;">
            <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                if (isset($message['type']) && isset($message['message'])) {
                    echo yii\bootstrap\Alert::widget([
                        'options' => [
                            'class' => 'alert-' . $message['type'],
                        ],
                        'body' => $message['message'],
                    ]);
                }
            } ?>
        </div>
    <?php } ?>

    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

    <div class="widget-content ">

        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
                <br>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-members',
                    'action' => Yii::$app->urlManager->createUrl(['members/list']),
                    'options' => ['data-pjax' => true],
                    'method' => 'GET'
                ]);

                $list_user_status[] = ['id' => '', 'name' => '-- สถานะ --'];
                $list_user_status[] = ['id' => Constants::user_status_active, 'name' => Constants::$user_status[Constants::user_status_active]];
                $list_user_status[] = ['id' => Constants::user_status_withhold, 'name' => Constants::$user_status[Constants::user_status_withhold]];
                $list_user_status[] = ['id' => Constants::user_status_un_active, 'name' => Constants::$user_status[Constants::user_status_un_active]];
                ?>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-md-4 col-sm-4 control-label"
                                   style="text-align: right;">ค้นหาทุกคอลัมน์</label>
                            <div class="col-md-8 col-sm-8">
                                <?= Html::activeInput('text', $searchModel, 'text_filter', ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-4 col-sm-4 control-label" style="text-align: right;">สถานะ</label>
                            <div class="col-md-8 col-sm-8">
                                <?= Html::activeDropDownList($searchModel, 'user_status', ArrayHelper::map($list_user_status, 'id', 'name'), ['class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา
                        </button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <br><br>

        <div class="table-responsive">
            <?php
            echo GridView::widget([
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
                        'label' => 'เอเย็นต์',
                        'value' => function ($model) {
                            if ($model['agent']) {
                                $user = User::find()->where(['id' => $model['agent']])->one();
                                return $user->username;
                            }
                            return 'ไม่มี agent';
                        }
                    ],
                    [
                        'label' => 'ชื่อบัญชี',
                        'value' => function ($model) {
                            $message = '<ul>';
                            $userHasBanks = UserHasBank::find()->where(['user_id' => $model['id'], 'status' => Constants::status_active])->all();
                            foreach ($userHasBanks as $userHasBank) {
                                $message .= '<li>'.$userHasBank->bank_account_name.'</li>';
                            }
                            $message .= '</ul>';
                            return $message;
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => 'ชื่อธนาคาร',
                        'value' => function ($model) {
                            $message = '<ul>';
                            $userHasBanks = UserHasBank::find()->where(['user_id' => $model['id'], 'status' => Constants::status_active])->all();
                            foreach ($userHasBanks as $userHasBank) {
                                $message .= '<li>'.$userHasBank->bank->title.'</li>';
                            }
                            $message .= '</ul>';
                            return $message;
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => 'เลขที่บัญชี',
                        'value' => function ($model) {
                            $message = '<ul>';
                            $userHasBanks = UserHasBank::find()->where(['user_id' => $model['id'], 'status' => Constants::status_active])->all();
                            foreach ($userHasBanks as $userHasBank) {
                                $message .= '<li>' . $userHasBank->bank_account_no . '</li>';
                            }
                            $message .= '</ul>';
                            return $message;
                        },
                        'format' => 'html',
                    ],
                    [
                        'label' => 'เครดิต',
                        'value' => function ($model) {
                            return (!empty($model['balance']) ? number_format($model['balance'], 2) : '');
                        }
                    ],
                    [
                        'label' => 'ยอดแทงยี่กี้',
                        'value' => function ($model) {
                            $yeekeeTotalAmount = $model['total_amount'];
                            return number_format($yeekeeTotalAmount, 2);
                        }
                    ],
                    [
                        'label' => 'ยอดแทงหวย',
                        'value' => function ($model) {
                            $thaiSharedGameTotal = $model['total_amount_thaishared'];
                            return number_format($thaiSharedGameTotal, 2);
                        }
                    ],
                    [
                        'label' => 'เบอร์โทรศัพท์',
                        'value' => function ($model) {
                            return (!empty($model['tel']) ? $model['tel'] : '-');
                        }
                    ],
                    [
                        'label' => 'วันที่สมัคร',
                        'value' => function ($model) {
                            return (!empty($model['created_at']) ? date('d/m/Y H:i:s', $model['created_at']) : '');
                        }
                    ],
                    [
                        'label' => 'สถานะ',
                        'format' => 'html',
                        'value' => function ($model) {
                            $result = '';
                            if ($model['user_status'] !== '') {
                                $result = '<a href="javascript:;" class="btn btn-xs btn-' . ($model['user_status'] == 1 ? 'success' : 'danger') . '">' . Constants::$user_status[$model['user_status']] . '</a>';
                            }
                            return $result;
                        }
                    ],
                    [
                        'label' => 'กระทำ',
                        'format' => 'raw',
                        'options' => ['style' => 'width:50px;'],
                        'value' => function ($model) {
                            $url = Yii::$app->urlManager->createUrl(['members']);

                            $tmp = '';
                            $tmp .= '<div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" style="left: -125px;">
                                        <li><a href="#" class="createtopup" id="' . $model['id'] . '"> <span class="glyphicon glyphicon-credit-card"></span> ฝาก</a></li>
                                        <li><a href="#" class="createwithdraw" id="' . $model['id'] . '"> <span class="glyphicon glyphicon-credit-card"></span> ถอน</a></li>
                                        <li><a href="' . $url . '/update/' . $model['id'] . '" data-pjax="0"> <span class="glyphicon glyphicon-user"></span> แก้ไข</a></li>
                                        <li><a href="' . $url . '/credit/' . $model['id'] . '" data-pjax="0"> <span class="glyphicon glyphicon-list-alt"></span> รายงานเครดิต</a></li>
                                        <li><a href="' . $url . '/chit/' . $model['id'] . '" data-pjax="0"> <span class="glyphicon glyphicon-file"></span> รายการโพยยี่กี่</a></li>
                                        <li><a href="' . Url::to(['members/chit-shared', 'id' => $model['id']]) . '" data-pjax="0"> <span class="glyphicon glyphicon-file"></span> รายการโพยหวยหุ้น</a></li>
                                        ';
                            if ($model['user_status'] == 1) {
                                $tmp .= '<li class="status_withhold" id="' . $model['id'] . '"><a href="#">ระงับ</a></li>';
                            } elseif ($model['user_status'] == 0) {
                                $tmp .= '<li class="status_un_active" id="' . $model['id'] . '"><a href="#">ยืนยัน</a></li>';
                                $tmp .= '<li>' . Html::a('ลบ', ['members/delete', 'id' => $model['id']], [
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]) . '</li>';
                            } else {
                                $tmp .= '<li class="status_active" id="' . $model['id'] . '"><a href="#">ปลดระงับ</a></li>';
                            }
                            $tmp .= '</ul>
                                    </div>';
                            return $tmp;//'.$url.'/createwithdraw/'.$model['id'].'
                        }
                    ],
                ],
            ]);
            ?><br><br><br><br><br><br><br><br><br><br>
        </div><br><br><br>
    </div>


    <?php
    $url = Yii::$app->urlManager->createUrl([Yii::$app->controller->id]);
    $urlGettopup = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/gettopup']);
    $urlGetwithdraw = Yii::$app->urlManager->createUrl([Yii::$app->controller->id . '/getwithdraw']);
    $script = <<<SCRIPT
$(document).ready(function(){
        
    $('a.createtopup').click(function(){
        var id = this.id;
        $('#modal-topup').modal('show');
        $.ajax({
            url: '$urlGettopup',
            dataType: 'json',
            type: 'POST',
            data: {id:id},
            success: function (data) {
                $('div#modal-topup-content').html(data);
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
        
        
    $('a.createwithdraw').click(function(){
        var id = this.id;
        $('#modal-withdraw').modal('show');
        $.ajax({
            url: '$urlGetwithdraw',
            dataType: 'json',
            type: 'POST',
            data: {id:id},
            success: function (data) {
                $('div#modal-withdraw-content').html(data);
            },
            error: function() {
                console.log('Error');
                $('#spinner').hide();
            }
        });
    });
        
    $('#btnSaveTopup').click(function(){
        var amount = $('#postcredittransectiontopup-amount').val();
        if(amount === ''){
            $('div.field-postcredittransectiontopup-amount').addClass('has-error');
            $('div.field-postcredittransectiontopup-amount p').html('ระบุจำนวนเงินที่ต้องการฝาก');
            return false;
        }
        if(!$.isNumeric(amount)) {
            $('div.field-postcredittransectiontopup-amount').addClass('has-error');
            $('div.field-postcredittransectiontopup-amount p').html('ระบุจำนวนเงินที่ต้องการถอนเป็นตัวเลข');
            return false;
        }
        $('#modal-topup').modal('hide');
        $('#btnSaveTopup').attr('disabled', true);
        swal({
            title: 'กรุณายืนยันการทำรายการ',
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: true,
            allowOutsideClick: true
        }).then((action) => {
            if(action.value){
                $('#form-topup').submit();
            }
        });
        return false;
    });
        
    $('#btnSaveWithdraw').click(function(){
        var amount = $('#postcredittransection-amount').val();
        if(amount === ''){
            $('div.field-postcredittransection-amount').addClass('has-error');
            $('div.field-postcredittransection-amount p').html('ระบุจำนวนเงินที่ต้องการถอน');
            return false;
        }
        if(!$.isNumeric(amount)) {
            $('div.field-postcredittransection-amount').addClass('has-error');
            $('div.field-postcredittransection-amount p').html('ระบุจำนวนเงินที่ต้องการถอนเป็นตัวเลข');
            return false;
        }
        $('#modal-withdraw').modal('hide');
        $('#btnSaveWithdraw').attr('disabled', true);
        swal({
            title: 'กรุณายืนยันการทำรายการ',
            type: 'warning',
            showCancelButton: true,
            closeOnConfirm: true,
            allowOutsideClick: true
        }).then((action) => {
            if(action.value){
                $('#form-withdraw').submit();
            }
        });
        return false;
    });
        
        
        
        
        
        
    var eventId = '';
    var eventType = '';
        
    $('li.status_withhold').click(function(){
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
        
    $('li.status_active').click(function(){
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
    
    $('li.status_un_active').click(function(){
        eventId = this.id;
        eventType = 'active';
        if(eventId !== ''){
            $('#modalConfirm .modal-header').addClass('modal-header-danger').removeClass('modal-header-primary');
            $('#modalConfirm .modal-footer #btnConfirm').addClass('btn-danger').removeClass('btn-primary');
            $('#modalConfirm h4#myModalLabel').html('ยืนยันการเปลี่ยนสถานะ');
            $('#modalConfirm div.modal-body').html('คุณต้องการยืนยันใช่หรือไม่?');
            $('#modalConfirm').modal('show');
        }
    });
        
    $('button#btnConfirm').on('click',function(){
        $('#spinner').show();
        window.location.replace('$url' + '/changstatus/' + eventType + '/' + eventId);
    });
});
SCRIPT;
    $this->registerJs($script);
    ?>
    <?php
    echo $this->render('../template/modal_confirm');
    ?>

    <?php Modal::begin([
        'headerOptions' => ['class' => 'modal-header-primary'],
        'id' => 'modal-topup',
        'header' => '<h4 class="modal-title">เติมเครดิตตรง</h4>',
        'footer' => '<button type="submit" class="btn btn-primary" id="btnSaveTopup">ตกลง</button><button type="reset" class="btn btn-default" data-dismiss="modal">ปิด</button>',
    ]); ?>
    <div class="modal-detail" id="modal-topup-content">


    </div>
    <?php Modal::end(); ?>





    <?php Modal::begin([
        'headerOptions' => ['class' => 'modal-header-primary'],
        'id' => 'modal-withdraw',
        'header' => '<h4 class="modal-title">รายการถอนเงินสมาชิก</h4>',
        'footer' => '<button type="submit" class="btn btn-primary" id="btnSaveWithdraw">ตกลง</button><button type="reset" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>',
    ]); ?>
    <div class="modal-detail" id="modal-withdraw-content">

    </div>
    <?php Modal::end(); ?>


    <?php Pjax::end() ?>

</div>