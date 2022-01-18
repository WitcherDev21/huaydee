<?php
/* @var $userBank array */

/* @var $userHasBanks array */

use yii\helpers\Url;

$this->registerJsVar('bankUrl', Yii::getAlias('@web/version6/images/bank-icon.png'), \yii\web\View::POS_HEAD);
$this->registerJsVar('logoBankUrl', Yii::getAlias('@web/bank/'), \yii\web\View::POS_HEAD);
$this->registerJsVar('successUrl', Url::to(['post-credit-transection/success']), \yii\web\View::POS_HEAD);
$this->registerJsVar('sendDepositUrl', Url::to(['post-credit-transection/send-deposit']), \yii\web\View::POS_HEAD);


$this->registerJsFile(Yii::getAlias('@web/version6/js/moment.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/moment-timezone.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/moment-timezone-with-data-2012-2022.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/index/deposit.js?1563021630'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/tempusdominus-bootstrap-4.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/clipboard.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web/version6/css/spacing.css'));
$this->registerCssFile(Yii::getAlias('@web/version6/css/tempusdominus-bootstrap-4.min.css'));


$js = <<<EOT
var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function (e) {
        toastr.success('คัดลอกเรียบร้อยแล้ว', 'Success', {timeOut: 1500, preventDuplicates: true});
        e.clearSelection();
    });
EOT;
$decimal = rand(00, 99);
$this->registerJs($js);
?>
<form method="post" enctype="application/x-www-form-urlencoded" id="deposit" action="javascript:void(0);">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>"/>
    <div id="section-content" class="container">
        <div class="bar-back">
            <a href="<?= Url::to(['site/home']) ?>">
                <i class="fas fa-chevron-left"></i> หน้าหลัก
            </a>
        </div>
        <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch"
             style="min-height: calc((100vh - 140px) - 50px);">
            <div class="bgwhitealpha_bkk text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
                <div class="lotto-title">
                    <h4><i class="fas fa-donate"></i> แจ้งเติมเครดิต</h4>
                </div>
            </div>
            <div class="bgwhitealpha_bkk text-secondary2 shadow-sm rounded p-1 mb-5 xtarget col-lotto">
                <div class="row w-100 p-0 m-0 d-flex justify-content-center">
                    <div id="destep1" class="row col-12 col-sm-12 col-md-6 py-2 px-0 my-1 border rounded">
                        <div class="col-12">
                            <h6 class="font-weight-normal">
                                <span class="badge badge-pill badge-success font-weight-normal">STEP 1</span>
                                บัญชีธนาคาร RUAYDEE96
                            </h6>
                        </div>
                        <div class="col-12">
                            <label id="labelselectbank">
                                <i class="fas fa-university"></i>เลือกบัญชีธนาคารของเว็บ
                            </label>
                            <div class="border rounded mb-2" id="onbankselect">
                                <div class="dropdown bootstrap-select form-control">
                                    <select class="selectpicker form-control" data-container="body" data-size="5"
                                            id="bankselect" name="svbank" tabindex="-98">
                                        <option value="blank">กรุณาเลือกธนาคาร</option>
                                        <?php
                                        foreach ($userHasBanks as $key => $userHasBank) { ?>
                                            <option data-content="<img style='background:<?= $userHasBank['color'] ?>;padding:2px;border-radius:2px;width:22px;' src='<?= Yii::getAlias('@web/bank/') . $userHasBank['icon'] ?>'>
                                            <span><?= $userHasBank['title'] ?></span>"
                                                    data-acc="<?= $userHasBank['bank_account_no'] ?>"
                                                    data-icon="<?= $userHasBank['icon'] ?>"
                                                    data-name="<?= $userHasBank['bank_account_name'] ?>"
                                                    data-bank="<?= $userHasBank['title'] ?>"
                                                    value="<?= $userHasBank['user_has_bank_id'] . '-' . $userHasBank['bank_account_name'] . '-' . $userHasBank['bank_account_no'] ?>"
                                                    id="<?= $userHasBank['user_has_bank_id'] ?>"
                                                    data-color="<?= $userHasBank['color'] ?>">
                                                <?= $userHasBank['title'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rounded mb-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row py-2 px-3 justify-content-center">
                                                <div class="col-3 col-sm-3 col-md-4 col-lg-3 pt-2">
                                                    <img src="<?= Yii::getAlias('@web/version6/images/bank-icon.png') ?>"
                                                         alt="" width="100%" id="bank2"
                                                         class="detail-bank table-secondary rounded">
                                                </div>
                                                <div class="col-9 col-sm-9 col-md-8 col-lg-5 p-2 d-flex flex-column justify-content-center align-items-start">
                                                    <h6 class="numacc" id="acc2" style="color: black;">กรุณาเลือก
                                                        บัญชีธนาคาร ของเว็บ RUAYDEE96</h6>
                                                    <span class="badge badge-pill badge-secondary font-weight-normal"
                                                          id="nameacclang"></span>

                                                    <span id="name2"></span>
                                                    <input type="hidden" value="xxxxxxxxxx" id="accdeposit">
                                                </div>
                                                <button class="btn btn-light btn-sm border border-secondary copyacc mr-3"
                                                        type="button" data-clipboard-text="4130409445"
                                                        style="display: none;">
                                                    <i class="fas fa-copy"></i> COPY
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row w-100 p-0 m-0 d-flex justify-content-center">
                        <div id="destep2" class="row col-12 col-sm-12 col-md-6 py-2 px-0 my-1 border rounded" >
                            <div id="destep2" class="row py-2 px-3 justify-content-center">
                                <div class="col-12">
                                    <h6 class="font-weight-normal"><span
                                                class="badge badge-pill badge-success font-weight-normal">STEP 2</span>
                                        โอนเงินเพื่อเติมเครดิต</h6>
                                </div>
                                <div class="col-12">
                                    <div class="row money-transfer">
                                        <div class="col-7">
                                            <label><i class="fas fa-money-bill"></i> จำนวนเงินที่โอน</label>
                                            <input type="tel" class="form-control moneyinput" id="CurrencyInput"
                                                   name="money" autocomplete="off" oninput="spiderman(this);"
                                                   maxlength="10">
                                        </div>
                                        <div class="col-5">
                                            <label><i class="fas fa-money-bill"></i> ทศนิยม</label>
                                            <input type="hidden" name="decimal" value="<?= $decimal ?>" id="decimal" />
                                            <input type="tel" class="form-control moneyinput"
                                                   value="<?= $decimal <= 9 ? '0'.$decimal : $decimal ?>"
                                                   maxlength="10" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="msgstep2"><br>
                                    <div class="alert alert-danger border border-danger">
                                        โอนขั้นต่ำ "ครั้งละ 5 บาท" ถ้าโอนต่ำกว่า 5 บาทเงินของท่านจะไม่เข้าระบบ
                                        และไม่สามารถคืนได้
                                    </div>
                                    <div class="alert alert-warning">คำเตือน! กรุณาใช้บัญชีที่ท่านผูกกับ RUAYDEE96
                                        ในการโอนเงินเท่านั้น
                                    </div>
                                    <div class="alert alert-info">
                                        เมื่อท่านทำการโอนเงินไปยังบัญชีข้างต้นเรียบร้อยแล้ว
                                        <small>(เก็บสลิปการโอนไว้ทุกครั้ง)</small>
                                        <br><b>"คลิกปุ่มด้านล่าง"</b> <u>เพื่อแจ้งการโอนเงิน</u></div>
                                    <button type="button" class="btn btn-primary_bkk btn-block btn-larg"
                                            id="btnpayment">เมื่อโอนเงินแล้ว คลิกที่นี่
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100"></div>

                    <div class="row w-100 p-0 m-0 d-flex justify-content-center">
                        <div id="destep3" class="row col-12 col-sm-12 col-md-6 py-2 px-0 my-1 border rounded" style="display:none;">

                            <div class="col-12">
                                <h6 class="font-weight-normal">
                                    <span class="badge badge-pill badge-success font-weight-normal">STEP 3</span>
                                    แจ้งรายละเอียดการโอนเงิน
                                </h6>
                            </div>
                            <div class="col-12 text-center">
                                <h5><span class="text-danger timetransferlimit"></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <label><i class="fas fa-university"></i> เลือกบัญชีธนาคารของลูกค้า</label>
                                        <div class="border rounded mb-2" id="bordermybank">
                                            <div class="dropdown bootstrap-select form-control">
                                                <select class="selectpicker form-control" data-container="body" data-size="5" id="mybankselect" name="mybank" tabindex="-98">
                                                    <option value="">กรุณาเลือกธนาคาร</option>
                                                    <?php foreach ($userBanks as $userBank) { ?>
                                                        <option data-content="<img style='background:<?= $userBank->bank->color ?>;padding:2px;border-radius:2px;width:22px;' src='<?= Yii::getAlias('@web') . '/bank/' . $userBank->bank->icon ?>'><small class='badge badge-light text-dark text-uppercase'><?= ucwords($userBank->bank->title) ?></small> <span><?= $userBank->bank_account_no ?></span>"
                                                                data-acc="<?= $userBank->bank_account_no ?>"
                                                                data-name="<?= $userBank->bank_account_name ?>"
                                                                data-bank="<?= ucwords($userBank->bank->title) ?>"
                                                                data-color="<?= $userBank->bank->color ?>"
                                                                value="<?= $userBank->id ?>"
                                                                id="<?= $userBank->id ?>"><?= ucwords($userBank->bank->title) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label><i class="fas fa-money-check-alt"></i> เลือกช่องทางการโอนเงิน
                                        </label>
                                        <div class="border rounded mb-2" id="borderbankway">
                                            <div class="dropdown bootstrap-select form-control">
                                                <select id="channel-bank" class="selectpicker form-control"
                                                        data-container="body" data-size="6" name="channel"
                                                        tabindex="-98">
                                                    <option value="">กรุณาเลือกช่องทางธุรกรรม</option>
                                                    <option data-subtext="โอนจากตู้กดเงินสด"
                                                            value="โอนจากตู้กดเงินสด">
                                                        ATM
                                                    </option>
                                                    <option data-subtext="ฝากผ่านตู้ฝากเงินสด"
                                                            value="ฝากผ่านตู้ฝากเงินสด">
                                                        CDM
                                                    </option>
                                                    <option data-subtext="ฝากเงินผ่านเคาท์เตอร์ธนาคาร"
                                                            value="ฝากเงินผ่านเคาท์เตอร์ธนาคาร">
                                                        Counter Cashier
                                                    </option>
                                                    <option data-subtext="โอนเงินผ่านหน้าเว็บไซต์"
                                                            value="โอนเงินผ่านหน้าเว็บไซต์">
                                                        Internet Banking
                                                    </option>
                                                    <option data-subtext="โอนเงินผ่านแอพมือถือ"
                                                            value="โอนเงินผ่านแอพมือถือ">
                                                        Mobile Banking
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label><i class="fas fa-money-bill"></i> จำนวนเงินที่โอน</label>
                                                <input id="money-transfer" type="tel" class="form-control" autocomplete="off"
                                                       maxlength="10" disabled>
                                            </div>
                                        </div>
                                        <div class="col-12"><br>
                                            <label><i class="fas fa-calendar-alt"></i> วันที่โอน </label>
                                            <div class="input-group date" id="datetimepicker4"
                                                 data-target-input="nearest"
                                                 data-toggle="datetimepicker">
                                                <input type="text" name="date"
                                                       class="form-control datetimepicker-input mb-0"
                                                       data-target="#datetimepicker4" data-container="body"
                                                       style="background-color : #ffffff;" readonly="">
                                                <div class="input-group-append" data-target="#datetimepicker4"
                                                     data-toggle="datetimepicker">
                                                    <button class="btn btn-outline-secondary" type="button"><i
                                                                class="fas fa-calendar my-0"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label><i class="far fa-clock"></i> เวลาที่โอน </label>
                                            <div class="input-group date" id="datetimepicker3"
                                                 data-target-input="nearest"
                                                 data-toggle="datetimepicker">
                                                <input type="text" name="time" id="time"
                                                       class="form-control datetimepicker-input mb-0"
                                                       data-target="#datetimepicker3" data-container="body"
                                                       style="background-color : #ffffff;" readonly="">
                                                <div class="input-group-append" data-target="#datetimepicker3"
                                                     data-toggle="datetimepicker">
                                                    <button class="btn btn-outline-secondary" type="button"><i
                                                                class="fas fa-clock"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-danger mt-2"
                                                   style="display: inline-block;line-height: 1.2;">
                                                * กรุณากรอก วัน-เวลา ในการโอนให้ตรงกับ Slip
                                                ระบบจะเติมเครดิตให้คุณอัตโนมัติ
                                            </small>
                                            <br>
                                            <label class="mt-2"><i class="far fa-star"></i> หมายเหตุ</label>
                                            <textarea name="note" id="note" cols="30" rows="2"
                                                      class="form-control"></textarea>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <small>กรุณาตรวจสอบข้อมูลของท่านให้ถูกต้อง และกดปุ่ม <span
                                                        class="text-success">ยืนยันการแจ้งโอนเงิน</span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-success_bkk btn-block btn-larg confirmdeposit">
                                            ยืนยันการแจ้งโอนเงิน
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

</form>