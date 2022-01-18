<?php
/* @var $yeekeeGameChit \common\models\YeekeeChitSearch */

use common\libs\Constants;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Url;

$this->registerJsFile(Yii::getAlias('@web/version6/js/index/poy.js?1552053594'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsVar('poy', $yeekeeGameChit->id);
$this->registerJsVar('poyCancelUrl', Url::to(['yeekeechit/cancel-yeekeechit', 'id' => $yeekeeGameChit->id]));

?>
    <div class="bar-back">
        <a href="<?= Url::to(['thai-shared/index']) ?>">
            <i class="fas fa-chevron-left"></i> โพยหวย
        </a>
    </div>
    <div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
        <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
            <div class="lotto-title d-flex flex-row align-items-end">
                <h4 class="mr-1">
                    <i class="fas fa-receipt"></i> รายละเอียดโพย
                </h4>
            </div>
        </div>
        <div class="border border-dark bg-white shadow-sm rounded mb-2" style="overflow:hidden;">
            <div class="bg-secondary text-white py-1 px-2 text-center border-bottom border-dark">
                <i class="fas fa-sticky-note"></i> โพยเลขที่ #<?= $yeekeeGameChit->getOrder() ?>
            </div>
            <div class="bg-light py-1 px-2">
                <div class="row m-0 p-0">
                    <div class="col-12 col-sm-12 col-md-6 py-1 border-bottom d-flex flex-row justify-content-between justify-content-sm-between justify-content-md-start align-items-center">
                        <span class="mr-1">จับยี่กี รอบที่ <?= $yeekeeGameChit->yeekee->round ?></span>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 py-1 border-bottom text-secondary text-center text-sm-center text-md-right">
                        <?php
                        if (empty($yeekeeGameChit->update_at)) {
                            $date = date('d/m/Y', strtotime($yeekeeGameChit->create_at));
                            $time = date('H:i', strtotime($yeekeeGameChit->create_at));
                        } else {
                            $date = date('d/m/Y', strtotime($yeekeeGameChit->update_at));
                            $time = date('H:i', strtotime($yeekeeGameChit->update_at));
                        }
                        ?>
                        <small class="mr-1">ทำรายการเมื่อ</small>
                        <span class="badge">
                        <i class="far fa-calendar-alt"></i> <?= $date ?>
                    </span>
                        <span class="badge">
                        <i class="far fa-clock"></i> <?= $time ?>
                    </span>
                    </div>
                    <div class="col px-0">
                        <div class="pl-1 pr-0 text-center w-100">
                            <small>ยอดแทง</small>
                            <h5 class="font-weight-light text-primary thb">
                                <?php
                                $amount = number_format($yeekeeGameChit->total_amount, 2);
                                echo '฿' . $amount;
                                ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col px-0">
                        <div class="pr-1 pl-0 text-center border-left w-100">
                            <small>ผลได้เสีย</small>
                            <?php if ($yeekeeGameChit->getIsWin()) {
                                $classWinLose = 'text-success';
                                $total = '฿ +' . $yeekeeGameChit->getTotalWinCredit();
                            } else if ($yeekeeGameChit->status === Constants::status_playing || $yeekeeGameChit->status === Constants::status_cancel) {
                                $classWinLose = '';
                                $total ='฿ 0';
                            } else {
                                $classWinLose = 'text-danger';
                                $total = '฿ -' . $amount;
                            } ?>
                            <h5 class="font-weight-light <?= $classWinLose ?>">
                                <?= $total ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-12 px-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <?php foreach ($yeekeeGameChit->yeekeeChitDetails as $key => $yeekeeChitDetail) { ?>
                <div class="poy-list">
                    <div class="poy-list-head">
                        <small>ลำดับ</small>
                        <span><?= $key + 1; ?></span>
                        <?php
                        if ($yeekeeGameChit->status === Constants::status_finish_show_result) {
                            if ($yeekeeChitDetail->flag_result === 1) {
                                $messageWinOrLose = 'ถูกรางวัล';
                                $classNameWinOrLose = 'win';
                            } else {
                                $messageWinOrLose = 'ไม่ถูกรางวัล';
                                $classNameWinOrLose = 'lost';
                            }
                        } elseif ($yeekeeGameChit->status === Constants::status_playing) {
                            $messageWinOrLose = 'รอออกผล';
                            $classNameWinOrLose = 'notyet';
                        } else {
                            $messageWinOrLose = 'ยกเลิก';
                            $classNameWinOrLose = 'abort';
                        } ?>
                        <div class="poy-status <?= $classNameWinOrLose ?>"><?= $messageWinOrLose ?></div>
                    </div>
                    <div class="poy-list-content pb-0">
                        <div class="row">
                            <div class="col m-0 pl-2 pr-1 pb-1">
                                <div class="poy-type text-center">
                                    <h3 class="text-primary"><?= $yeekeeChitDetail->number ?></h3>
                                    <small><?= $yeekeeChitDetail->playTypeCode->title ?></small>
                                </div>
                            </div>
                            <div class="col m-0 pl-1 pr-3 pb-1 border-left" style="color: black;">
                                <div class="d-flex justify-content-between border-bottom">
                                    <small>เลขที่ออก</small>
                                    <span>
                                <?php
                                if (!$yeekeeGameChit->yeekee->result) {
                                    echo '-';
                                }else{
                                    $result = $yeekeeGameChit->yeekee->getResults($yeekeeChitDetail->play_type_code);
                                    if(is_array($result)){
                                        $result = implode(',', $result);
                                    }
                                    echo $result == '' ? '-' : $result;
                                }
                                ?>
                                </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>ราคาจ่าย</small>
                                    <span class="thb">฿ <?= $yeekeeChitDetail->playTypeCode->jackpot_per_unit; ?></span>
                                </div>
                            </div>
                            <div class="col-12 bg-light border-top m-0 pt-1 d-flex justify-content-between">
                                <div class="d-flex">
                                    <small class="mr-2">ราคาแทง:</small>
                                    <span class="thb">฿ <?= $yeekeeChitDetail->amount; ?></span>
                                </div>
                                <div class="d-flex">
                                    <small class="mr-2">ผลได้เสีย:</small>
                                    <h5 class="thb ">
                                        ฿ <?= $yeekeeChitDetail->win_credit > 0 ?
                                            number_format($yeekeeChitDetail->win_credit, 2) :
                                            0
                                        ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>