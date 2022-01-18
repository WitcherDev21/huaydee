<?php
/* @var $creditTransections \common\models\CreditTransection */

/* @var $creditTransectionHistorys \common\models\CreditTransection */

use common\libs\Constants;
use yii\helpers\Url;

?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-file-invoice-dollar"></i> รายงานการเงิน</h4>
        </div>
    </div>
    <ul class="nav nav-tabs nav-justified" id="menucredit" role="tablist">
        <li class="nav-item">
            <a class="nav-link active show" href="#tab1content" data-toggle="tab" id="tab1contentt"
               role="tab" aria-controls="home" aria-selected="true">
                <i class="fas fa-calendar-week"></i> วันนี้
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab2content" role="tab" aria-controls="home"
               aria-selected="false" id="tab2contentt">
                <i class="fas fa-history"></i> ก่อนหน้า
            </a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div id="tab1content" class="tab-pane fade active show" role="tabpanel" aria-labelledby="tab1contentt">
            <div class="mb-5 py-1 bg-light rounded col-lotto">
                <small class="text-secondary">*คลิกที่รายการเพื่อดูรายละเอียดเพิ่มเติม</small>
                <div class="accordion bg-transparent" id="accordionCredit">
                    <?php foreach ($creditTransections as $key => $creditTransection) {
                        if (in_array($creditTransection->action_id, [
                            Constants::action_credit_top_up_admin,
                            Constants::action_credit_withdraw_admin,
                        ])) {
                            $class = Constants::$reason_credit_class[$creditTransection->reason_action_id];
                            $text = 'โปรโมชั่น';
                        } elseif (in_array($creditTransection->action_id, [
                            Constants::action_commission_withdraw_direct,
                        ])){
                            $class = Constants::$reason_credit_class[$creditTransection->reason_action_id];
                            $text = Constants::$action_commission[$creditTransection->reason_action_id];
                        }elseif (in_array($creditTransection->action_id, [
                            Constants::action_credit_withdraw_admin_special,
                            Constants::action_credit_top_up_admin_special,
                        ])) {
                            $class = Constants::$reason_credit_class[$creditTransection->reason_action_id];
                            $text = Constants::$action_credit[$creditTransection->action_id];
                        } else {
                            $class = Constants::$reason_credit_class[$creditTransection->reason_action_id];
                            $text = Constants::$reason_credit[$creditTransection->reason_action_id];
                        }
                        ?>
                        <div class="card border-<?= $key ?>">
                            <div class="card-header collapsed" id="list<?= $creditTransection->id ?>"
                                 data-toggle="collapse"
                                 data-target="#current-list<?= $creditTransection->id ?>" aria-expanded="false"
                                 aria-controls="current-list1">
                                <div class="bg-white border border-bottom-0 rounded-top p-2 d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start text-secondary">
                                        <div class="align-middle">
                                            <span class="<?= $class ?>">
                                                <?= $text ?>
                                            </span>
                                        </div>
                                        <small><?= $creditTransection->operator->username ?> <i
                                                    class="fas fa-angle-right"></i> <?= $creditTransection->reciver->username ?>
                                        </small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <?php
                                        if (in_array($creditTransection->action_id, [
                                            Constants::action_credit_withdraw,
                                            Constants::action_credit_withdraw_admin,
                                            Constants::action_credit_withdraw_admin_special,
                                            Constants::action_commission_withdraw_direct,
                                        ])) {
                                            echo '<h4 class="text-danger_bkk mb-0 d-inline">- <span class="thb">฿' . number_format($creditTransection->amount, 2) . '</span></h4>';
                                        } else if (in_array($creditTransection->action_id, [
                                            Constants::action_credit_top_up,
                                            Constants::action_credit_top_up_admin,
                                            Constants::action_credit_top_up_admin_special,
                                        ])) {
                                            echo '<h4 class="text-success mb-0 d-inline">+ <span class="thb">฿' . number_format($creditTransection->amount, 2) . '</span></h4>';
                                        }
                                        ?>
                                        <small class="text-secondary"><i
                                                    class="far fa-clock"></i> <?= date('d/m/Y H:i:s', strtotime($creditTransection->create_at)) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div id="current-list<?= $creditTransection->id ?>" class="collapse"
                                 aria-labelledby="list<?= $creditTransection->id ?>" data-parent="#accordionCredit"
                                 style="">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="align-middle">
                                            <span class="badge badge-secondary font-weight-light">เลขที่รายการ <?= $creditTransection->getOrderId() ?></span>
                                        </div>
                                        <div class="align-middle">
                                            <small>คงเหลือ</small>
                                            <span class="thb text-info">฿ <?= number_format($creditTransection->balance, 2) ?></span>
                                        </div>
                                    </div>
                                    <small class="text-secondary">หมายเหตุ:</small>
                                    <small><?= $creditTransection->remark ?></small>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div id="tab2content" class="tab-pane fade" role="tabpanel" aria-labelledby="tab2contentt">
            <div class="mb-5 py-1 bg-light rounded col-lotto">
                <small class="text-secondary">*คลิกที่รายการเพื่อดูรายละเอียดเพิ่มเติม - รายการย้อนหลังไม่เกิน 3 วัน
                </small>
                <div class="accordion bg-transparent" id="accordionCredithistory">
                    <?php foreach ($creditTransectionHistorys as $key => $creditTransectionHistory) {
                        if (in_array($creditTransectionHistory->action_id, [
                            Constants::action_credit_top_up_admin,
                            Constants::action_credit_withdraw_admin,
                        ])) {
                            $class = Constants::$reason_credit_class[$creditTransectionHistory->reason_action_id];
                            $text = 'โปรโมชั่น';
                        } elseif (in_array($creditTransectionHistory->action_id, [
                            Constants::action_credit_withdraw_admin_special,
                            Constants::action_credit_top_up_admin_special,
                        ])) {
                            $class = Constants::$reason_credit_class[$creditTransectionHistory->reason_action_id];
                            $text = Constants::$action_credit[$creditTransectionHistory->action_id];
                        } else {
                            $class = Constants::$reason_credit_class[$creditTransectionHistory->reason_action_id];
                            $text = Constants::$reason_credit[$creditTransectionHistory->reason_action_id];
                        }
                        ?>
                        <div class="card border-<?= $key ?>">
                            <div class="card-header collapsed" id="hlist<?= $creditTransectionHistory->id ?>"
                                 data-toggle="collapse"
                                 data-target="#history-list<?= $creditTransectionHistory->id ?>" aria-expanded="false"
                                 aria-controls="history-list<?= $creditTransectionHistory->id ?>">
                                <div class="bg-white border border-bottom-0 rounded-top p-2 d-flex justify-content-between align-items-center">
                                    <div class="d-flex flex-column align-items-start text-secondary">
                                        <div class="align-middle">
                                            <span class="<?= $class ?>">
                                                <?= $text ?>
                                            </span>
                                        </div>
                                        <small><?= $creditTransectionHistory->operator->username ?> <i
                                                    class="fas fa-angle-right"></i> <?= $creditTransectionHistory->reciver->username ?>
                                        </small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <?php
                                        if (in_array($creditTransectionHistory->action_id, [
                                            Constants::action_credit_withdraw,
                                            Constants::action_credit_withdraw_admin,
                                            Constants::action_credit_withdraw_admin_special,
                                        ])) {
                                            echo '<h4 class="text-danger_bkk mb-0 d-inline">- <span class="thb">฿' . number_format($creditTransectionHistory->amount, 2) . '</span></h4>';
                                        } else if (in_array($creditTransectionHistory->action_id, [
                                            Constants::action_credit_top_up,
                                            Constants::action_credit_top_up_admin,
                                            Constants::action_credit_top_up_admin_special,
                                        ])) {
                                            echo '<h4 class="text-success mb-0 d-inline">+ <span class="thb">฿' . number_format($creditTransectionHistory->amount, 2) . '</span></h4>';
                                        }
                                        ?>
                                        <small class="text-secondary"><i
                                                    class="far fa-clock"></i> <?= date('d/m/Y H:i:s', strtotime($creditTransectionHistory->create_at)) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div id="history-list<?= $creditTransectionHistory->id ?>" class="collapse"
                                 aria-labelledby="hlist<?= $creditTransectionHistory->id ?>"
                                 data-parent="#accordionCredit"
                                 style="">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="align-middle">
                                            <span class="badge badge-secondary font-weight-light">เลขที่รายการ <?= $creditTransectionHistory->getOrderId() ?></span>
                                        </div>
                                        <div class="align-middle">
                                            <small>คงเหลือ</small>
                                            <span class="thb text-info">฿ <?= number_format($creditTransectionHistory->balance, 2) ?></span>
                                        </div>
                                    </div>
                                    <small class="text-secondary">หมายเหตุ:</small>
                                    <small><?= $creditTransectionHistory->remark ?></small>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>