<?php
/* @var $user User*/
use common\models\User;

$user = User::find()->where(['id' => Yii::$app->user->id])->one();
$prices = [5, 10, 20, 50, 100];
?>
<div id="price" class="pagemodal-wrapper">
    <div class="container">
        <div class="pagemodal">
            <div class="head">
                <i class="fas fa-edit"></i> ใส่ราคา
                <span class="badge badge-pill badge-dark" id="total_poy_list"></span>
                <a class="btn-close triggerPrice" href="javascript:;"></a>
            </div>
            <table class="table table-striped table-hover table-sm d-none" id="model_poy_option">
                <tbody>
                <tr>
                    <th class="align-middle">#</th>
                    <th colspan="2" class="text-danger" style="font-size:16px;">{bet-option-name}</th>
                    <th class="text-center text-primary" style="font-size:16px;">อัตราคูณ</th>
                    <th colspan="2" class="text-dark" style="font-size:16px;">เรทชนะ</th>
                </tr>
                <tr id="model_poy_list" class="{is-duplicate-class}">
                    <td class="align-middle text-center table-secondary">{bet_cnt}.</td>
                    <td class="align-middle text-center bg-success_bkk text-white font-weight-bold {is-duplicate-class}" style="font-size:16px;">
                        {pl_number}
                    </td>
                    <td class="align-middle text-center">
                        <input type="tel" id="pl_price_{poy-list-id}" value="{pl-price}" placeholder="ระบุจำนวนเงิน"
                               minlength="1" maxlength="6" pattern="[0-9]*"
                               class="form-control bg-black text-gold border-right-gold pl_price"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    </td>
                    <td class="align-middle text-center" id="model_poy_list_multiply">
                        {bet_multiply}
                    </td>
                    <td><input type="text" class="form-control" id="model_poy_list_win" value="{pl-win}฿" readonly="">
                    </td>
                    <td>
                        <div class="btn btn-danger_bkk" id="del_pl_{poy-list-id}"><i class="fas fa-trash-alt"></i></div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="content" style="min-height:100vh;">
                <div class="content-scroll">
                    <span id="poy_list"></span>
                </div>
                <div class="fixbot">
                    <div class="row px-3">
                        <div class="col-6 ml-0">
                            <button class="btn btn-secondary btn-block" id="view_duplicate">
                                <i class="fas fa-search"></i> ดูเลขซ้ำ
                            </button>
                        </div>
                        <div class="col-6 mr-0">
                            <button class="btn btn-dark btn-block" id="delete_duplicate">
                                <i class="fas fa-eraser"></i> ตัดเลขซ้ำ
                            </button>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="row px-3">
                        <div class="col-6 align-middle text-center text-success pl-4 pt-2 pr-0">
                            ราคา<b>"เท่ากัน"</b>ทั้งหมด<br>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control" placeholder="ใส่ราคา" aria-label="ใส่ราคา"
                                       aria-describedby="button-addon2" id="set_all_price"
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-success" type="button" id="button-addon2">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 align-middle text-center pr-1 pl-0">
                            <div class="box__chip-lists d-flex justify-content-around align-items-center flex-wrap p-2">
                                <?php foreach ($prices as $price) { ?>
                                    <div class="price" data-id="<?= $price ?>"><?= $price ?> ฿</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="row px-3 pb-1 mb-1">
                        <div class="col-6 bg-primary_bkk text-white text-center p-1 mb-3"><span class="badge badge-light">
                                ยอดเครดิตคงเหลือ
                            </span>
                            <br>
                            <span class="thb" data-id="credit_balance"><?= $user->creditBalance ?> ฿</span>
                        </div>
                        <div class="col-6 bg-danger_bkk text-white text-center p-1 mb-3">
                            <span class="badge badge-light">รวมยอดแทง</span>
                            <br>
                            <span class="thb total_price">฿ 0</span>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-light btn-block" id="reset_poy">
                                <i class="fas fa-ban"></i> ยกเลิกทั้งหมด
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success_bkk btn-block triggerSendpoy">
                                <i class="fas fa-check"></i> ส่งโพย
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
