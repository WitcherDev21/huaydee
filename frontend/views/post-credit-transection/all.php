<?php
/* @var $postCreditTransections \common\models\PostCreditTransection */

/* @var $pages */

use common\libs\Constants;
use yii\helpers\Url;

?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch mb-5" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title d-flex flex-row align-items-end">
            <h4 class="mr-1"><i class="fas fa-tasks"></i> สถานะ ฝากเงิน</h4>
        </div>
    </div>
    <?= $this->render('_tab') ?>
    <div class="mb-5">
        <?php
        if (!$postCreditTransections) { ?>
            <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 col-lotto text-center">
                <span class="text-danger">ไม่มีรายการ</span>
            </div>
        <?php } else {
            foreach ($postCreditTransections as $postCreditTransection) {
                $icon = isset($postCreditTransection->userHasBankUser->bank->icon) ?
                    $postCreditTransection->userHasBankUser->bank->icon :
                    $postCreditTransection->createBy->userHasBank->bank->icon;
                $color = isset($postCreditTransection->userHasBankUser->bank->color) ?
                    $postCreditTransection->userHasBankUser->bank->color :
                    $postCreditTransection->createBy->userHasBank->bank->color;
                ?>
                <div class="row px-1 addbankstatus">
                    <div class="col-12 col-sm-12 px-0 table-dark rounded-top border border-secondary d-flex justify-content-between">
                        <div class="py-1 px-2 d-flex">
                            <div class="d-inline mr-2">
                                <img style="background:<?= $color ?>; padding:2px;border-radius:2px;width:45px;"
                                     src="<?= Yii::getAlias('@web/bank/') . $icon ?>">
                            </div>
                            <div class="d-inline">
                                <br>
                                <small>
                                    <?= isset($postCreditTransection->userHasBankUser->bank->title) ?
                                        $postCreditTransection->userHasBankUser->bank->title :
                                        $postCreditTransection->createBy->userHasBank->bank->title ?>
                                </small>
                            </div>
                        </div>
                        <?php if ($postCreditTransection->status === Constants::status_cancel) {
                            $bankIcon = 'fa fa-times-circle';
                            $bankClass = 'cancel';
                        } elseif ($postCreditTransection->status === Constants::status_approve) {
                            $bankIcon = 'fa-check-circle';
                            $bankClass = 'confirm';
                        } else {
                            $bankIcon = 'fa fa-clock-o';
                            $bankClass = 'wait';
                        } ?>
                        <div class="status <?= $bankClass ?>">
                            <small>สถานะ</small>
                            <span>
                                <i class="far <?= $bankIcon ?>"></i>
                                <?= Constants::$status[$postCreditTransection->status]; ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 py-1 px-2 text-dark table-secondary border border-top-0 border-bottom-0 border-secondary d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <?php
                            if ($postCreditTransection->action_id == Constants::action_credit_top_up ||
                                $postCreditTransection->action_id == Constants::action_credit_top_up_admin) {
                                $textStyle = 'text-success';
                            } else if ($postCreditTransection->action_id == Constants::action_credit_withdraw ||
                                $postCreditTransection->action_id == Constants::action_credit_withdraw_admin ||
                                $postCreditTransection->action_id == Constants::action_commission_withdraw_direct) {
                                $textStyle = 'text-danger';
                            }
                            if ($postCreditTransection->action_id == Constants::action_commission_withdraw_direct) {
                                if (isset(Constants::$action_commission[$postCreditTransection->action_id])) {
                                    $actionText = Constants::$action_commission[$postCreditTransection->action_id];
                                }
                            } else {
                                if (isset(Constants::$action_credit[$postCreditTransection->action_id])) {
                                    $actionText = Constants::$action_credit[$postCreditTransection->action_id];
                                }
                            }
                            ?>
                            <span class="badge badge-light <?= $textStyle ?> mr-1">
                                <i class="fas fa-folder-plus"></i>
                                <?= $actionText ?>
                            </span>
                            <h5 class="mb-0 d-inline thb"><?= number_format($postCreditTransection->amount, 2) ?></h5>
                        </div>
                        <small>
                            <i class="fas fa-calendar-week"></i>
                            <?php
                            if (empty($postCreditTransection->update_at)) {
                                $date = date('d/m/Y H:i:s', strtotime($postCreditTransection->create_at));
                            } else {
                                $date = date('d/m/Y H:i:s', strtotime($postCreditTransection->update_at));
                            }
                            echo $date;
                            ?>
                        </small>
                    </div>
                    <div class="col-12 rounded-bottom border border-top-0 border-secondary" style="overflow:hidden">
                        <div class="row">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-4 py-1 px-2 d-flex flex-column flex-sm-column flex-md-row align-items-md-center">
                                <small class="\&quot;text-info\&quot;">ชื่อบัญชี :</small>
                                <small>
                                    <?= isset($postCreditTransection->userHasBankUser->bank_account_name) ?
                                        $postCreditTransection->userHasBankUser->bank_account_name :
                                        $postCreditTransection->createBy->userHasBank->bank_account_name ?>
                                </small>
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 col-lg-4 py-1 px-2 d-flex flex-column flex-sm-column flex-md-row align-items-end align-items-sm-end align-items-md-center">
                                <small class="text-info">เลขที่บัญชี :</small>
                                <small>
                                    <?= isset($postCreditTransection->userHasBankUser->bank_account_no) ?
                                        $postCreditTransection->userHasBankUser->bank_account_no :
                                        $postCreditTransection->createBy->userHasBank->bank_account_no
                                    ?>
                                </small>
                                <?php if ($postCreditTransection->action_id == Constants::action_credit_top_up) { ?>
                                    <small class="text-info">&nbsp;ช่องทางฝาก :</small>
                                    <small>&nbsp;<?= $postCreditTransection->channel ?></small>
                                <?php } ?>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 py-1 px-2 bg-light_bkk border border-bottom-0 text-dark">
                                <i class="fas fa-comment-alt"></i>
                                <?= $actionText ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
</div>