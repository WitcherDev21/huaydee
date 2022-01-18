<?php
/* @var $userHasBank \common\models\UserHasBank */

use yii\helpers\Url;


$js = <<<EOT
$(".bank-user").click(function(){
    if ($(this).parent().find("input:radio:checked").length > 0) {
        var oldId = '#'+$(this).parent().find("input:radio:checked").attr('id');
        $(oldId).prop('checked', false);
        var id = '#bank_' + $(this).data('id');
        $(id).prop('checked', true);
    } else {
        var id = '#bank_' + $(this).data('id');
        $(id).prop('checked', true);
    }
});
$(".not_add_bank").click(function(){
    swal({
        title: 'Warning!',
        text: 'ท่านสามารถเพิ่มบัญชีได้เพียง 2 บัญชีเท่านั้น',
        type: 'warning',
        confirmButtonText: 'OK'
    });	
});
EOT;

$this->registerJs($js)
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-university"></i>บัญชีธนาคาร</h4>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 mb-5 xtarget col-lotto">
        <div id="secondtime" style="display:block;">
            <h3>
                <span class="badge badge-pill badge-secondary font-weight-light">ชื่อบัญชี</span><?= $userHasBanks[0]->bank_account_name ?>
            </h3>
            <div class="form-row">
                <div class="col-12">
                    <label><i class="fas fa-money-check"></i> บัญชีธนาคารที่สามารถใช้ได้</label>
                </div>
                <?php foreach ($userHasBanks as $key => $userHasBank) { ?>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-2 text-center bank-user" data-id="<?= $userHasBank->id ?>">
                        <input type="radio" name="bank2" id="bank_<?= $userHasBank->id ?>"
                               value="<?= $userHasBank->bank->title . '-' . $userHasBank->bank_account_name . '-' . $userHasBank->bank_account_no ?>"
                               class="input-hidden"
                               data-acc="<?= $userHasBank->bank_account_no ?>"
                               data-name="<?= $userHasBank->bank_account_name ?>"
                               data-bank="<?= $userHasBank->bank->title ?>">
                        <label for="scb" class="bank-radio">
                            <img src="<?= Yii::getAlias('@web/bank/') . $userHasBank->bank->icon ?>"
                                 alt="<?= $userHasBank->bank->title ?>" class="icon-bank"
                                 style="background: <?= $userHasBank->bank->color ?>;"><br><span><?= $userHasBank->bank->title ?></span><span
                                    class="badge badge-dark"><?= $userHasBank->bank_account_no ?></span></label>
                    </div>
                <?php } ?>
                <div class="border-bottom w-100 my-2"></div>
                <div class="col-6">
                    <a href="<?= Url::to(['setting/bank-status']) ?>" class="btn btn-primary btn-block text-white">
                        <i class="fas fa-tasks"></i> เช็คสถานะบัญชี</a>
                </div>
                <div class="col-6">
                    <a href="<?= intval($userHasBankCount) >= 2 ? '#' : Url::to(['setting/bank-add']) ?>" class="btn btn-success btn-block text-white <?= intval($userHasBankCount) >= 2 ? 'not_add_bank' : '' ?>">
                        <i class="fas fa-plus"></i> เพิ่มบัญชี
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>