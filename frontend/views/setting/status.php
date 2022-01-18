<?php
/* @var $userHasBank \common\models\UserHasBank */
use yii\helpers\Url;
?>
<div class="bar-back">
    <a href="<?= Url::to(['setting/bank']) ?>">
        <i class="fas fa-chevron-left"></i> บัญชีธนาคาร
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
        <div class="lotto-title w-100 d-flex justify-content-between">
            <div class="d-inline">
                <h4 class="mr-1 d-inline"><i class="fas fa-tasks"></i>
                    สถานะบัญชีธนาคาร <span id="displayDiv"></span>
                </h4>
            </div>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary shadow-sm rounded xtarget col-lotto d-flex flex-column mb-5 pb-0">
        <?php foreach ($userHasBanks as $userHasBank) { ?>
            <div class="row px-3 addbankstatus">
                <div class="col-12 col-sm-12 px-0 table-dark rounded-top border border-secondary d-flex justify-content-between">
                    <div class="py-1 px-2 d-flex">
                        <div class="d-inline mr-2">
                            <img style="background:<?= $userHasBank->bank->color ?>;padding:2px;border-radius:2px;width:45px;"
                                 src="<?= Yii::getAlias('@web/bank/') . $userHasBank->bank->icon ?>">
                        </div>
                        <div class="d-inline">
                            <span><?= $userHasBank->bank->title ?></span>
                        </div>
                    </div>
                    <?php if($userHasBank->status === \common\libs\Constants::status_waitting) {
                        $shotStatus = 'รออนุมัติ';
                        $status = 'บัญชีของท่านอยู่ระหว่างดำเนินการ';
                        $class = 'wait';
                    } elseif ($userHasBank->status === \common\libs\Constants::status_cancel) {
                        $shotStatus = 'ไม่ผ่าน';
                        $status = 'บัญชีชองท่านไม่ผ่านการตรวจสอบติดต่อ admin';
                        $class = 'cancel';
                    } else {
                        $shotStatus = 'ผ่าน';
                        $status = 'บัญชีของท่านได้รับการอนุมัติ';
                        $class = 'confirm';
                    } ?>
                    <div class="status <?= $class ?>">
                        <small>สถานะ</small>
                        <span><i class="far fa-check-circle"></i>
                             <?= $shotStatus ?>
                        </span>
                    </div>
                </div>
                <div class="col-12 rounded-bottom border border-top-0 border-secondary" style="overflow:hidden">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 py-1 px-2">
                            <span class="text-info">ชื่อบัญชี :</span> <?= $userHasBank->bank_account_name ?>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 py-1 px-2">
                            <span class="text-info">เลขที่บัญชี :</span> <?= $userHasBank->bank_account_no ?>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 py-1 px-2 table-warning">
                            <i class="fas fa-comment-alt"></i>
                            <?= $status ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-12 mb-3 mt-1">
            <a href="<?= Url::to(['post-credit-transection/create-topup']) ?>"
               class="btn btn-success d-flex justify-content-around align-items-center">
                <span><i class="fas fa-plus-square"></i> ฝากเงิน</span>
            </a>
        </div>
    </div>
</div>
