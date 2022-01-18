<?php
/* @var $user \common\models\User */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile(Yii::getAlias('@web/version6/js/index/withdraw.js?1562925715'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/cleave/cleave.js?1549581359'), ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<?php $form = ActiveForm::begin([
    'id' => 'withdraw',
    'action' => ['post-credit-transection/create-withdraw'],
    'method' => 'post',
]); ?>
    <div class="bar-back">
        <a href="<?= Url::to(['site/home']) ?>">
            <i class="fas fa-chevron-left"></i> หน้าหลัก
        </a>
    </div>
    <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
        <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
            <div class="lotto-title">
                <h4><i class="fas fa-vote-yea"></i> แจ้งถอนเงิน</h4>
            </div>
        </div>
        <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 xtarget col-lotto">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                   value="<?= Yii::$app->request->csrfToken; ?>"/>
            <div class="form-row">
                <div class="col-12">
                    <label><i class="fas fa-money-check"></i> เลือกบัญชีธนาคารของท่าน<br>
                        <small><font color="red">*กรุณากดคลิกบัญชีด้านล่างตามที่ท่านต้องการ</font></small>
                    </label>
                </div>
                <style>
                    .bnk48_014 {
                        background: #4e2e7f
                    } </style>
                <?php foreach ($userBanks as $userBank) { ?>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-2 text-center bank-user" data-id="<?= $userBank->id ?>">
                        <input type="radio" name="bank" id="bank_<?= $userBank->id ?>" value="<?= $userBank->id ?>"
                               class="input-hidden bank" data-acc="<?= $userBank->bank_account_no ?>"
                               data-name="<?= $userBank->bank_account_name ?>"
                               data-bank="<?= $userBank->bank->title ?>">
                        <label for="scb-1" class="bank-radio">
                            <img src="<?= Yii::getAlias('@web/bank/') . $userBank->bank->icon ?>"
                                 alt="<?= $userBank->bank->title ?>" class="icon-bank bnk48_014"
                                 style="background:<?= $userBank->bank->color ?>;">
                            <br>
                            <span><?= $userBank->bank_account_name ?></span>
                            <span class="badge badge-dark"><?= $userBank->bank_account_no ?></span>
                        </label>
                    </div>
                <?php } ?>
                <div class="border-bottom w-100 my-2"></div>
                <div class="col-12 col-sm-12 col-md-6">
                    <label><i class="fas fa-coins"></i> จำนวนเงินที่ถอนได้</label><br>
                    <div class="alert alert-success py-2">
                        <h3 class="thb text-success text-center my-0 w-100" data-id="credit_balance">
                            <?= number_format($user->credit->balance, 2) ?> ฿
                        </h3>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <label>
                        <i class="fas fa-hand-holding-usd"></i> จำนวนเงินที่ต้องการถอน
                    </label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">฿</span>
                        </div>
                        <input type="tel" value="100" class="form-control form-control-lg money-withdraw"
                               placeholder="ระบุเฉพาะตัวเลข" name="wmoney"
                               onkeydown="if(event.key==='.'){event.preventDefault();}">
                        <div class="input-group-append">
                            <button class="btn btn-warning" type="button" id="totalwithdraw">ทั้งหมด</button>
                        </div>
                    </div>
                    <small class="text-secondary2">ถอนเงินขั้นต่ำ 100 หรือ สูงสุด 100,000 บาท</small>
                </div>
                <div class="col-12 col-sm-12 col-md-12">
                    <label class="mt-2"><i class="far fa-star"></i> หมายเหตุ</label>
                    <textarea name="note" cols="30" rows="2" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="bg-white p-2 rounded shadow-sm w-100 mb-5">
            <div class="row">
                <div class="col pr-1">
                    <button class="btn btn-secondary_bkk btn-block" type="reset">ยกเลิก</button>
                </div>
                <div class="col pl-1">
                    <button class="btn btn-success_bkk btn-block" type="submit" onclick="return check_withdraw()">ถอนเงิน
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
?>