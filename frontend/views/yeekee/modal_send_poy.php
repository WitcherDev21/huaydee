<?php
use common\models\YeekeeSearch;

$yeekee = YeekeeSearch::find()->where(['id' => Yii::$app->request->get('id')])->one();
?>
<div id="sendpoy" class="pagemodal-wrapper">
    <div class="container">
        <div class="pagemodal">
            <div class="head bg-success">
                <i class="fas fa-check-circle"></i> ยืนยันคำสั่งซื้อ
                <a class="btn-close triggerSendpoy" href="javascript:;"></a>
            </div>
            <div class="content">
                <div class="content-scroll p-2">
                    <div class="border pb-2 mb-2">
                        <div class="row p-0 m-0">
                            <div class="col-7 py-1 bg-dark d-flex align-items-center">
                                <img src="<?= Yii::getAlias('@web/version6/images/logo-demolotto.png') ?>"
                                     alt="RUAYDEE96" width="150" class="align-middle">
                            </div>
                            <div class="col-5 py-1 pl-0 bg-dark text-white d-flex justify-content-end align-items-center">
                                <h4 class="hbill mb-0">ใบสั่งซื้อ</h4>
                            </div>
                            <div class="col-8 pl-2 pt-2">
                                <table class="table table-sm table-responsive">
                                    <tbody>
                                    <tr>
                                        <td colspan="2" class="border-top-0">
                                            <h5 class="hhuay bet_name" id="pre_confirm_name">จับยี่กี่ รอบที่ <?= $yeekee->round ?></h5>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-4 pl-0 pt-2">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                    <tr>
                                        <td class="text-left table-secondary">วันที่</td>
                                        <td class="text-left"><?= date('d/m/Y') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left table-secondary">เวลา</td>
                                        <td class="text-left"><?= date('H:i:s') ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 py-2 text-center table-success border border-success border-right-0 border-left-0">
                                <span>ยอดเดิมพันทั้งหมด :</span>
                                <span class="font-weight-bold text-success thb total_price">฿ 0</span>
                            </div>
                            <div class="card card-huay d-none" id="model_pre_confirm">
                                <div class="card-header card-header-sm">
                                    <div class="row">
                                        <div class="col-7 font-weight-bold text-success">{bet-name}</div>
                                        <div class="col-5 text-right">ราคา</div>
                                    </div>
                                </div>
                                <div class="card-body card-body-sm">
                                    <div class="row" id="{pl_confirm_op}">
                                        <span class="d-none" id="model_pre_confirm_list">
                                            <div class="col-3">{bet-cnt}.</div>
                                            <div class="col-4">{bet-number}</div>
                                            <div class="col-5 text-right thb">฿ 0</div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 py-3" id="pl_confirm"></div>
                </div>
                <div class="fixbot p-2">
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-light btn-block triggerSendpoy">
                                <i class="fas fa-chevron-left"></i> กลับไปแก้ไข
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success btn-block successbet triggerPrintpoy">
                                <i class="fas fa-check"></i> ยืนยันการส่งโพย
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>