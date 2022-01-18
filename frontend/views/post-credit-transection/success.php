<?php
/* @var $postCreditTransection \common\models\PostCreditTransection */
use yii\helpers\Url;
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>">
        <i class="fas fa-chevron-left"></i> หน้าหลัก
    </a>
</div>
<div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-donate"></i> แจ้งเติมเครดิต</h4>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-1 mb-5 xtarget col-lotto">
        <h6 class="font-weight-normal"><span class="badge badge-pill badge-success font-weight-normal">STEP 4</span>
            ยืนยันการเติมเงิน</h6>
        <div class="form-row h-auto">
            <div class="col-12 col-sm-12 col-md-12 text-center">
                <h5 class="font-weight-light text-success mb-0">แจ้งฝากเงิน</h5>
                <h1 class="font-weight-light text-success totalmoney"><?= number_format($postCreditTransection->amount,2) ?></h1>
                <span class="badge badge-secondary font-weight-light">เวลาแจ้งโอน</span>
                <span>
                    <i class="far fa-calendar-check"></i>
                    <span class="transferdate"><?= date('Y-m-d', strtotime($postCreditTransection->post_requir_time)); ?></span>
                    <i class="far fa-clock"></i>
                    <span class="transfertime"><?= date('H:i', strtotime($postCreditTransection->post_requir_time)); ?></span>
                </span>
            </div>
            <div class="col-12 col-sm-12 col-md-6 py-1 fromacc">
                <div class="alert alert-primary" role="alert">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="text-success w-100 font-weight-light">โอนจากบัญชี</h5>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="col-3 col-sm-3 col-md-4 col-lg-3 pt-2">
                                    <img src="<?= Yii::getAlias('@web/bank/') .$postCreditTransection->userHasBankUser->bank->icon ?>"
                                         style="background-color: <?= $postCreditTransection->createBy->userHasBank->bank->color ?>"
                                         alt="<?= $postCreditTransection->userHasBankUser->bank->title ?>" width="100%" class="detail-bank rounded mybanklogo">
                                </div>
                                <div class="col-9 col-sm-9 col-md-8 col-lg-5 pt-2">
                                    <h6 class="numacc myaccdeposit"><?= $postCreditTransection->userHasBankUser->bank_account_no ?></h6>
                                    <span class="badge badge-pill badge-secondary font-weight-normal">ชื่อบัญชี</span><br>
                                    <span class="myname">
                                        <?= $postCreditTransection->userHasBankUser->bank_account_name ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 py-1 toacc">
                <div class="alert alert-success" role="alert">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="text-success w-100 font-weight-light">โอนให้บัญชี</h5>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="col-3 col-sm-3 col-md-4 col-lg-3 pt-2">
                                    <img src="<?= Yii::getAlias('@web/bank/') .$postCreditTransection->userHasBank->bank->icon ?>"
                                         alt="<?= $postCreditTransection->userHasBank->bank->title ?>"
                                         style="background-color: <?= $postCreditTransection->userHasBank->bank->color ?>" width="100%" class="detail-bank rounded svbanklogo">
                                </div>
                                <div class="col-9 col-sm-9 col-md-8 col-lg-5 pt-2">
                                    <h6 class="numacc svaccdeposit"><?= $postCreditTransection->userHasBank->bank_account_no ?></h6>
                                    <span class="badge badge-pill badge-secondary font-weight-normal">ชื่อบัญชี</span><br>
                                    <span class="svname">
                                         <?= $postCreditTransection->userHasBank->bank_account_name ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-2 text-center">
                <small>รายการของท่านถูกส่งไปยังระบบเรียบร้อยแล้ว กรุณารอไม่เกิน 3 นาที</small>
                <small class="text-primary"><a href="http://nav.cx/elFpxpL">หากมีปัญหากรุณาติดต่อฝ่าย
                        Support</a></small>
            </div>
            <button class="btn btn-primary btn-block btn-larg"
                    onclick="location.href='<?=Url::to(['post-credit-transection/deposit']) ?>';">สถานะการเติมเงิน
            </button>
        </div>
    </div>
</div>