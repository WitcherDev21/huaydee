<?php
/* @var $thaiSharedGameChit \common\models\ThaiSharedGameChit */

/* @var $playTypes \common\models\PlayType */

use common\models\ThaiSharedGameChitDetail;
use yii\helpers\Url;

?>
<div id="printpoy" class="pagemodal-wrapper">
    <div class="container">
        <div class="pagemodal">
            <div class="head bg-success_bkk">
                <i class="fas fa-check-double"></i> ส่งโพยสำเร็จ
                <a class="btn-close"
                   href=""></a>
            </div>
            <div class="content">
                <div class="content-scroll p-2">
                    <div class="bg-light_bkk border border-0 pb-1 mb-1" id="capture">
                        <div class="row p-0 m-0">
                            <div class="row p-0 m-0 col-12 bg-dark">
                                <div class="col-7 pl-2 py-1 d-flex justify-content-start align-items-center">
                                    <img src="<?= Yii::getAlias('@web/version6/images/logo-demolotto.png') ?>"
                                         alt="fifalotto" width="150" class="align-middle">
                                </div>
                                <div class="col-5 pr-2 py-1 pl-0 text-white text-right" style="line-height:1;">
                                    <small class="text-secondary">โพยเลขที่</small>
                                    <br>
                                    <h4 class="hbill">
                                        <small>#</small>
                                        <span id="poy_id"></span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-8 pl-2 pt-2">
                                <table class="table table-sm table-responsive mb-1">
                                    <tbody>
                                    <tr>
                                        <td colspan="2" class="border-top-0">
                                            <h5 class="hhuay bet_name"></h5>
                                        </td>
                                    </tr>
                                    <tr class="border">
                                        <td class="table-secondary"><i class="far fa-clock"></i></td>
                                        <td><b class="hhuay_round bet_round"></b></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <span class="text-success"><i class="fas fa-vote-yea"></i> ส่งโพยสำเร็จ</span>
                            </div>
                            <div class="col-4 pl-0 pt-2 text-right">
                                <small>ทำรายการเมื่อ</small>
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                    <tr>
                                        <td class="text-left table-secondary">วันที่</td>
                                        <td class="text-left" id="date"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left table-secondary">เวลา</td>
                                        <td class="text-left" id="time"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 p-2 mb-0 text-center table-success border border-success border-right-0 border-left-0"
                                 style="transition: none;">
                                <span>ยอดรวม :</span>
                                <span class="font-weight-bold text-success thb total_price"></span>
                            </div>
                            <div class="col-12 pt-3 pb-0 mb-0" id="pl_success">
                            </div>
                            <div class="col-12 mt-0 mb-2">
                                <div class="alert alert-success p-1 px-2 mb-1 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-check"></i> <?= Yii::$app->user->identity->username ?></span>
                                    <span class="badge badge-secondary">© RUAYDEE96.COM</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fixbot p-2">
                    <div class="row px-3 mt-3">
                        <div class="col-4 p-0">
                            <button class="btn btn-dark_bkk btn-block rounded-0" id="capturePoy">
                                <i class="fas fa-save"></i> บันทึกโพย </button>
                        </div>
                        <div class="col-4 p-0">
                            <button class="btn btn-light btn-block border rounded-0 doo_poy"
                                    onclick="location.href='<?= Url::to(['thai-shared/index']) ?>';">
                                <i class="fas fa-history doo_poy"></i> ประวัติโพย
                            </button>
                        </div>
                        <div class="col-4 p-0">
                            <button class="btn btn-primary_bkk btn-block rounded-0" onclick="location.href='<?= Url::to(['thai-shared-chit/play', 'id' => $_GET['id']]) ?>';">
                                <i class="fas fa-plus"></i> แทงต่อ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>