<?php
/* @var $thaiSharedGameChitFinishes \common\models\ThaiSharedGameChit */
/* @var $thaiSharedGameChitHistories \common\models\ThaiSharedGameChit */
/* @var $thaiSharedGameChitWaites \common\models\ThaiSharedGameChit */
/* @var $thaiSharedGameChitTodaies \common\models\ThaiSharedGameChit */
/* @var $thaiSharedGameChitFinishesTodays \common\models\ThaiSharedGameChit */

use common\libs\Constants;
use yii\helpers\Url;

$thaiSharedGameUrl = Url::to(['thai-shared/index']);
$js = <<<EOT
    function select_type(bet_type) {
        window.location.href =  '$thaiSharedGameUrl' + '?type=' + bet_type;
    }
EOT;

$this->registerJs($js);
?>
<div class="bar-back my-2 radius-15 border">
    <a href="<?= Url::to(['site/home']) ?>" class="text-blue-2"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="bgwhitealpha text-secondary shadow-sm rounded my-2 radius-15 border p-2 py-3 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
    <div class="lotto-title d-flex flex-row align-items-end">
        <h4 class="mr-1"><i class="fas fa-receipt"></i> <b>โพยหวย</b></h4>
    </div>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch border radius-15" style="min-height: calc((100vh - 140px) - 50px); margin-bottom: 70px">

    <div class="border border-dark bg-white shadow-sm round<!---->ed mb-2" style="overflow:hidden;">
        <div class="bg-dark text-white py-1 px-2">
            <i class="sn-icon sn-icon--horoscope2"></i> ยอดแทงวันนี้
        </div>
        <div class="bg-white py-1 px-2">
            <div class="row px-3">
                <div class="col-6 col-sm-6 pl-0 pr-1">
                    <div class="mr-1 mt-1 py-1 text-center w-100 rounded bg-green-2 text-white">
                        <small>ยอดรวม</small>
                        <h5 class="font-weight-normal thb">฿
                            <?php
                            $total = 0;
                            if ($thaiSharedGameChitWaites) {
                                foreach ($thaiSharedGameChitWaites as $thaiSharedGameChitWaite) {
                                    if ($thaiSharedGameChitWaite->totalDiscount > 0) {
                                        $total += $thaiSharedGameChitWaite->totalDiscount;
                                    } else {
                                        $total += $thaiSharedGameChitWaite->totalAmount;
                                    }
                                }
                            }
                            if ($thaiSharedGameChitFinishesTodays) {
                                foreach ($thaiSharedGameChitFinishesTodays as $thaiSharedGameChitFinishesToday) {
                                    if ($thaiSharedGameChitFinishesToday->totalDiscount > 0) {
                                        $total += $thaiSharedGameChitFinishesToday->totalDiscount;
                                    } else {
                                        $total += $thaiSharedGameChitFinishesToday->totalDiscount;
                                    }
                                }
                            }
                            if ($yeekeeGameChitWaites) {
                                foreach ($yeekeeGameChitWaites as $yeekeeGameChitWaite) {
                                    $total += $yeekeeGameChitWaite->total_amount;
                                }
                            }
                            if ($yeekeeGameChitFinishesTodays) {
                                foreach ($yeekeeGameChitFinishesTodays as $yeekeeGameChitFinishesToday) {
                                    $total += $yeekeeGameChitFinishesToday->total_amount;
                                }
                            }
                            echo $total;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col-3 col-sm-3 px-0">
                    <div class="m-1 py-1 text-center border-left w-100">
                        <small>ออกผลแล้ว</small>
                        <h5 class="font-weight-light text-success">
                            <?= count((array)$thaiSharedGameChitFinishesTodays) + count((array)$yeekeeGameChitFinishesTodays) ?>
                        </h5>
                    </div>
                </div>
                <div class="col-3 col-sm-3 px-0">
                    <div class="m-1 py-1 text-center border-left w-100">
                        <small>ยังไม่ออกผล</small>
                        <h5 class="font-weight-light text-primary">
                            <?= count((array)$thaiSharedGameChitWaites) + count((array)$yeekeeGameChitWaites) ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border border-secondary bg-white shadow-sm rounded mb-5">
        <div class="row m-0 poy-all">
            <div class="col-12 col-sm-12 col-md-4 p-0 bg-dark">
                <div class="dropdown bootstrap-select form-control">
                    <select class="selectpicker form-control" data-style="btn-dark"
                        onchange="window.location='<?= $thaiSharedGameUrl ?>?type='+this.value" tabindex="-98">
                        <option value="" selected="" >หวยทั้งหมด</option>
                        <option value="GOVERNMENT"
                            <?= Yii::$app->request->get('type') === 'GOVERNMENT' ? 'selected=""' : '' ?>>
                            หวยรัฐบาลไทย
                        </option>
                        <option value="STOCK" <?= Yii::$app->request->get('type') === 'STOCK' ? 'selected=""' : '' ?>>
                            หวยหุ้น
                        </option>
                        <option value="YEEKEE" <?= Yii::$app->request->get('type') === 'YEEKEE' ? 'selected=""' : '' ?>>
                            จับยี่กี่ VIP
                        </option>
                        <option value="GSB" <?= Yii::$app->request->get('type') === 'GSB' ? 'selected=""' : '' ?>>
                            หวยออมสิน
                        </option>
                        <option value="BACC" <?= Yii::$app->request->get('type') === 'BACC' ? 'selected=""' : '' ?>>
                            หวย ธกส
                        </option>
                        <option value="LAOS_CHAMPASAK"
                            <?= Yii::$app->request->get('type') === 'LAOS_CHAMPASAK' ? 'selected=""' : '' ?>>
                            หวยลาว จำปาสัก
                        </option>
                        <option value="VIETNAM_4D"
                            <?= Yii::$app->request->get('type') === 'VIETNAM_4D' ? 'selected=""' : '' ?>>
                            หวยฮานอย 4D
                        </option>
                        <option value="LOTTERY_SERVE"
                            <?= Yii::$app->request->get('type') === 'LOTTERY_SERVE' ? 'selected=""' : '' ?>>
                            หวยลาวทดแทน
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-3 col-sm-3 col-md-2 p-0">
                <a href="#" class="btn btn-primary_bkk btn-sm btn-block h-100 btn-poy text-white active" data-id="poy-today">
                    <i class="fas fa-calendar-week d-none"></i>
                    <div><span>โพย</span><span></span></div>
                </a>
            </div>
            <div class="col-3 col-sm-3 col-md-2 p-0">
                <a href="#" class="btn bg-pink-tab text-dark btn-sm btn-block h-100 btn-poy" data-id="poy-notyet">
                    <i class="fas fa-times-circle d-none"></i>
                    <div><span>โพยหวย</span><span>ที่ยังไม่ออกผล</span></div>
                </a>
            </div>
            <div class="col-3 col-sm-3 col-md-2 p-0">
                <a href="#" class="btn bg-grenn-lan text-white btn-sm btn-block h-100 btn-poy" data-id="poy-success">
                    <i class="fas fa-check-circle d-none"></i>
                    <div><span>โพยหวย</span><span>ออกผลแล้ว</span></div>
                </a>
            </div>
            <div class="col-3 col-sm-3 col-md-2 p-0">
                <a href="#" class="btn btn-secondary_bkk btn-sm btn-block h-100 btn-poy" data-id="poy-history">
                    <i class="fas fa-history d-none"></i>
                    <div><span>โพย</span><span>ก่อนหน้า</span></div>
                </a>
            </div>
        </div>

        <div class="col-12 p-0 table-primary poy-content active" id="poy-today">
            <?php if (!$thaiSharedGameChitTodaies && !$yeekeeGameChitTodaies) { ?>
            <div class="text-center">
                <span class="text-danger">ไม่มีรายการ</span>
            </div>
            <?php }
            foreach ($thaiSharedGameChitTodaies as $thaiSharedGameChitToday) { ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $thaiSharedGameChitToday->getOrder() ?></span>
                    <?php
                        $winCredit = 0;
                        if ($thaiSharedGameChitToday->status === Constants::status_playing) {
                            $poyClassStatus = 'notyet';
                            $textStatus = 'รอออกผล';
                        } elseif ($thaiSharedGameChitToday->status === Constants::status_cancel) {
                            $poyClassStatus = '';
                            $textStatus = 'ยกเลิก';
                        } elseif ($thaiSharedGameChitToday->status === Constants::status_finish_show_result) {
                            if ($thaiSharedGameChitToday->getIsWin()) {
                                $poyClassStatus = 'win';
                                $textStatus = 'ถูกรางวัล';
                                $winCredit = $thaiSharedGameChitToday->getTotalWinCredit();
                            } else {
                                $poyClassStatus = 'lost';
                                $textStatus = 'ไม่ถูกรางวัล';
                            }
                        } else {
                            $poyClassStatus = 'notyet';
                            $textStatus = 'รอออกผล';
                        } ?>
                    <div class="poy-status <?= $poyClassStatus ?>">
                        <?= $textStatus ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span><?= $thaiSharedGameChitToday->thaiSharedGame->title ?></span><br>
                                <?php
                                    if (empty($thaiSharedGameChitToday->updateAt)) {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitToday->createdAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitToday->createdAt));
                                    } else {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitToday->updateAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitToday->createdAt));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= $thaiSharedGameChitToday->totalDiscount > 0 ?
                                            number_format($thaiSharedGameChitToday->totalDiscount, 2) :
                                            number_format($thaiSharedGameChitToday->totalAmount, 2)
                                        ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="thb">฿ <?= $winCredit; ?></span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['thai-shared/detail', 'id' => $thaiSharedGameChitToday->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">
                                รายละเอียด <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            foreach ($yeekeeGameChitTodaies as $yeekeeGameChitToday) { ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $yeekeeGameChitToday->getOrder() ?></span>
                    <?php
                        $winCredit = 0;
                        if ($yeekeeGameChitToday->status === Constants::status_playing) {
                            $poyClassStatus = 'notyet';
                            $textStatus = 'รอออกผล';
                        } elseif ($yeekeeGameChitToday->status === Constants::status_cancel) {
                            $poyClassStatus = '';
                            $textStatus = 'ยกเลิก';
                        } elseif ($yeekeeGameChitToday->status === Constants::status_finish_show_result) {
                            if ($yeekeeGameChitToday->getIsWin()) {
                                $poyClassStatus = 'win';
                                $textStatus = 'ถูกรางวัล';
                                $winCredit = $yeekeeGameChitToday->getTotalWinCredit();
                            } else {
                                $poyClassStatus = 'lost';
                                $textStatus = 'ไม่ถูกรางวัล';
                            }
                        } else {
                            $poyClassStatus = 'notyet';
                            $textStatus = 'รอออกผล';
                        } ?>
                    <div class="poy-status <?= $poyClassStatus ?>">
                        <?= $textStatus ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span>จับยี่กี รอบที่ <?= $yeekeeGameChitToday->yeekee->round ?></span><br>
                                <?php
                                    if (empty($yeekeeGameChitToday->update_at)) {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitToday->create_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitToday->create_at));
                                    } else {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitToday->update_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitToday->update_at));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= number_format($yeekeeGameChitToday->total_amount, 2) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="thb">฿ <?= $winCredit; ?></span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['yeekeechit/detail', 'id' => $yeekeeGameChitToday->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">
                                รายละเอียด <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-12 p-0 table-danger poy-content" id="poy-notyet">
            <?php if (!$thaiSharedGameChitWaites && !$yeekeeGameChitWaites) { ?>
            <div class="text-center">
                <span class="text-danger">ไม่มีรายการ</span>
            </div>
            <?php }
            foreach ($thaiSharedGameChitWaites as $thaiSharedGameChitWaite) { ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $thaiSharedGameChitWaite->getOrder() ?></span>
                    <div class="poy-status notyet">
                        รอออกผล
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span><?= $thaiSharedGameChitWaite->thaiSharedGame->title ?></span><br>
                                <?php
                                    if (empty($thaiSharedGameChitWaite->updateAt)) {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitWaite->createdAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitWaite->createdAt));
                                    } else {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitWaite->updateAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitWaite->createdAt));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= $thaiSharedGameChitWaite->totalDiscount > 0 ?
                                            number_format($thaiSharedGameChitWaite->totalDiscount, 2) :
                                            number_format($thaiSharedGameChitWaite->totalAmount, 2)
                                        ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="thb">฿ 0</span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['thai-shared/detail', 'id' => $thaiSharedGameChitWaite->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            foreach ($yeekeeGameChitWaites as $yeekeeGameChitWaite) { ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $yeekeeGameChitWaite->getOrder() ?></span>
                    <div class="poy-status notyet">
                        รอออกผล
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span>จับยี่กี รอบที่ <?= $yeekeeGameChitWaite->yeekee->round ?></span><br>
                                <?php
                                    if (empty($yeekeeGameChitWaite->update_at)) {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitWaite->create_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitWaite->create_at));
                                    } else {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitWaite->update_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitWaite->update_at));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= number_format($yeekeeGameChitWaite->total_amount, 2) ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="thb">฿ 0</span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['yeekeechit/detail', 'id' => $yeekeeGameChitWaite->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="col-12 p-0 table-success poy-content" id="poy-success">
            <?php if (!$thaiSharedGameChitFinishes && !$yeekeeGameChitFinishes) { ?>
            <div class="text-center">
                <span class="text-danger">ไม่มีรายการ</span>
            </div>
            <?php }
            foreach ($thaiSharedGameChitFinishes as $thaiSharedGameChitFinish) {
                if ($thaiSharedGameChitFinish->getIsWin()) {
                    $className = 'win';
                    $textWinLose = 'ถูกรางวัล';
                } elseif ($thaiSharedGameChitFinish->status === Constants::status_cancel) {
                    $className = '';
                    $textWinLose = 'ยกเลิก';
                } else {
                    $className = 'lost';
                    $textWinLose = 'ไม่ถูกรางวัล';
                }
                ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $thaiSharedGameChitFinish->getOrder() ?></span>
                    <div class="poy-status <?= $className ?>">
                        <?= $textWinLose ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span><?= $thaiSharedGameChitFinish->thaiSharedGame->title ?></span><br>
                                <?php
                                    if (empty($thaiSharedGameChitFinish->updateAt)) {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitFinish->createdAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitFinish->createdAt));
                                    } else {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitFinish->updateAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitFinish->createdAt));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= $thaiSharedGameChitFinish->totalDiscount > 0 ?
                                            number_format($thaiSharedGameChitFinish->totalDiscount, 2) :
                                            number_format($thaiSharedGameChitFinish->totalAmount, 2)
                                        ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span
                                    class="<?= $thaiSharedGameChitFinish->getIsWin() ? 'text-success' : 'text-danger' ?>">
                                    <span class="thb">
                                        ฿
                                        <?= $thaiSharedGameChitFinish->getIsWin() ? $thaiSharedGameChitFinish->getTotalWinCredit() : 0 ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['thai-shared/detail', 'id' => $thaiSharedGameChitFinish->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            foreach ($yeekeeGameChitFinishes as $yeekeeGameChitFinish) {
                if ($yeekeeGameChitFinish->getIsWin()) {
                    $className = 'win';
                    $textWinLose = 'ถูกรางวัล';
                } elseif ($yeekeeGameChitFinish->status === Constants::status_cancel) {
                    $className = '';
                    $textWinLose = 'ยกเลิก';
                } else {
                    $className = 'lost';
                    $textWinLose = 'ไม่ถูกรางวัล';
                }
                ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $yeekeeGameChitFinish->getOrder() ?></span>
                    <div class="poy-status <?= $className ?>">
                        <?= $textWinLose ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span>จับยี่กี รอบที่ <?= $yeekeeGameChitFinish->yeekee->round ?></span><br>
                                <?php
                                    if (empty($yeekeeGameChitFinish->update_at)) {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitFinish->create_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitFinish->create_at));
                                    } else {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitFinish->update_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitFinish->update_at));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= number_format($yeekeeGameChitFinish->total_amount, 2); ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="<?= $yeekeeGameChitFinish->getIsWin() ? 'text-success' : 'text-danger' ?>">
                                    <span class="thb">
                                        ฿
                                        <?= $yeekeeGameChitFinish->getIsWin() ? $yeekeeGameChitFinish->getTotalWinCredit() : 0 ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge">
                                <i class="far fa-calendar-alt"></i> <?= $date ?>
                            </span>
                            <span class="badge">
                                <i class="far fa-clock"></i> <?= $time ?>
                            </span>
                            <a href="<?= Url::to(['yeekeechit/detail', 'id' => $yeekeeGameChitFinish->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <div class="col-12 p-0 table-secondary poy-content" id="poy-history">
            <?php if (!$thaiSharedGameChitHistories && !$yeekeeGameChitHistories) { ?>
            <div class="text-center">
                <span class="text-danger">ไม่มีรายการ</span>
            </div>
            <?php
            }
            foreach ($thaiSharedGameChitHistories as $thaiSharedGameChitHistory) {
                if ($thaiSharedGameChitHistory->getIsWin()) {
                    $className = 'win';
                    $textWinLose = 'ถูกรางวัล';
                } elseif ($thaiSharedGameChitHistory->status === Constants::status_cancel) {
                    $className = '';
                    $textWinLose = 'ยกเลิก';
                } else {
                    $className = 'lost';
                    $textWinLose = 'ไม่ถูกรางวัล';
                }
                ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $thaiSharedGameChitHistory->getOrder() ?></span>
                    <div class="poy-status <?= $className ?>">
                        <?= $textWinLose ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span><?= $thaiSharedGameChitHistory->thaiSharedGame->title ?></span><br>
                                <?php
                                    if (empty($thaiSharedGameChitHistory->updateAt)) {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitHistory->createdAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitHistory->createdAt));
                                    } else {
                                        $date = date('d/m/Y', strtotime($thaiSharedGameChitHistory->updateAt));
                                        $time = date('H:i', strtotime($thaiSharedGameChitHistory->createdAt));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= $thaiSharedGameChitHistory->totalDiscount > 0 ?
                                            number_format($thaiSharedGameChitHistory->totalDiscount, 2) :
                                            number_format($thaiSharedGameChitHistory->totalAmount, 2);
                                        ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="<?= $thaiSharedGameChitHistory->getIsWin() ?
                                        'text-success' :
                                        'text-danger' ?>">
                                    <span class="thb">฿ <?= $thaiSharedGameChitHistory->getIsWin() ?
                                                    $thaiSharedGameChitHistory->getTotalWinCredit() :
                                                    0 ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge"><i class="far fa-calendar-alt"></i> <?= $date ?></span>
                            <span class="badge"><i class="far fa-clock"></i> <?= $time ?></span>
                            <a href="<?= Url::to(['thai-shared/detail', 'id' => $thaiSharedGameChitHistory->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด <i
                                    class="fas fa-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }
            foreach ($yeekeeGameChitHistories as $yeekeeGameChitHistory) {
                if ($yeekeeGameChitHistory->getIsWin()) {
                    $className = 'win';
                    $textWinLose = 'ถูกรางวัล';
                } elseif ($yeekeeGameChitHistory->status === Constants::status_cancel) {
                    $className = '';
                    $textWinLose = 'ยกเลิก';
                } else {
                    $className = 'lost';
                    $textWinLose = 'ไม่ถูกรางวัล';
                }
                ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>โพยเลขที่</small>
                    <span>#<?= $yeekeeGameChitHistory->getOrder() ?></span>
                    <div class="poy-status <?= $className ?>">
                        <?= $textWinLose ?>
                    </div>
                </div>
                <div class="poy-list-content">
                    <div class="row">
                        <div class="col m-0 pl-2 pr-1 pb-1">
                            <div class="poy-type ">
                                <span>จับยี่กี รอบที่ <?= $yeekeeGameChitHistory->yeekee->round ?></span><br>
                                <?php
                                    if (empty($yeekeeGameChitHistory->updateAt)) {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitHistory->create_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitHistory->create_at));
                                    } else {
                                        $date = date('d/m/Y', strtotime($yeekeeGameChitHistory->update_at));
                                        $time = date('H:i', strtotime($yeekeeGameChitHistory->update_at));
                                    }
                                    ?>
                                <small>วันที่ : <?= $date ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content-between text-dark">
                                <small>เงินเดิมพัน</small>
                                <span class="thb text-info">
                                    ฿ <?= number_format($yeekeeGameChitHistory->total_amount, 2); ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between text-dark">
                                <small>ผลแพ้/ชนะ</small>
                                <span class="<?= $yeekeeGameChitHistory->getIsWin() ?
                                        'text-success' :
                                        'text-danger' ?>">
                                    <span class="thb">฿ <?= $yeekeeGameChitHistory->getIsWin() ?
                                                    $yeekeeGameChitHistory->getTotalWinCredit() :
                                                    0 ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 border-top m-0 pt-1 text-dark">
                            <span class="badge"><i class="far fa-calendar-alt"></i> <?= $date ?></span>
                            <span class="badge"><i class="far fa-clock"></i> <?= $time ?></span>
                            <a href="<?= Url::to(['yeekeechit/detail', 'id' => $yeekeeGameChitHistory->id]) ?>"
                                class="btn btn-secondary btn-sm py-0 px-1 float-right">รายละเอียด <i
                                    class="fas fa-search"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>