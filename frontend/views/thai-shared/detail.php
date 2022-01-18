<?php
/* @var $thaiSharedGameChit \common\models\ThaiSharedGameChit */

use common\libs\Constants;
use common\models\ThaiSharedAnswerGame;
use yii\helpers\Url;

$this->registerJsFile(Yii::getAlias('@web/version6/js/index/poy.js?1552053594'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsVar('poy', $thaiSharedGameChit->id);
$this->registerJsVar('poyCancelUrl', Url::to(['thai-shared-chit/cancel', 'id' => $thaiSharedGameChit->id]));

?>
<div class="bar-back">
    <a href="<?= Url::to(['thai-shared/index']) ?>">
        <i class="fas fa-chevron-left"></i> โพยหวย
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
        <div class="lotto-title d-flex flex-row align-items-end">
            <h4 class="mr-1">
                <i class="fas fa-receipt"></i> รายละเอียดโพย
            </h4>
        </div>
    </div>
    <div class="border border-dark bg-white shadow-sm rounded mb-2" style="overflow:hidden;">
        <div class="bg-secondary text-white py-1 px-2 text-center border-bottom border-dark">
            <i class="fas fa-sticky-note"></i> โพยเลขที่ #<?= $thaiSharedGameChit->getOrder() ?>
        </div>
        <div class="bg-light3 py-1 px-2">
            <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-md-6 py-1 border-bottom d-flex flex-row justify-content-between justify-content-sm-between justify-content-md-start align-items-center">
                    <span class="mr-1"><?= $thaiSharedGameChit->thaiSharedGame->title ?></span>
                </div>
                <div class="col-12 col-sm-12 col-md-6 py-1 border-bottom text-secondary text-center text-sm-center text-md-right">
                    <?php
                    if (empty($thaiSharedGameChit->updateAt)) {
                        $date = date('d/m/Y', strtotime($thaiSharedGameChit->createdAt));
                        $time = date('H:i', strtotime($thaiSharedGameChit->createdAt));
                    } else {
                        $date = date('d/m/Y', strtotime($thaiSharedGameChit->updateAt));
                        $time = date('H:i', strtotime($thaiSharedGameChit->createdAt));
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
                            if ($thaiSharedGameChit->totalDiscount > 0) {
                                $amount = number_format($thaiSharedGameChit->totalDiscount, 2);
                            } else {
                                $amount = number_format($thaiSharedGameChit->totalAmount, 2);
                            }
                            echo '฿' . $amount;
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="col px-0">
                    <div class="pr-1 pl-0 text-center border-left w-100">
                        <small>ผลได้เสีย</small>
                        <?php if ($thaiSharedGameChit->getIsWin()) {
                            $classWinLose = 'text-success';
                            $total = '฿ +' . $thaiSharedGameChit->getTotalWinCredit();
                        } else if ($thaiSharedGameChit->status === Constants::status_playing || $thaiSharedGameChit->status === Constants::status_cancel) {
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
        <?php foreach ($thaiSharedGameChit->thaiSharedGameChitDetails as $key => $thaiSharedGameChitDetail) { ?>
            <div class="poy-list">
                <div class="poy-list-head">
                    <small>ลำดับ</small>
                    <span><?= $key + 1; ?></span>
                    <?php
                    if ($thaiSharedGameChit->status === Constants::status_finish_show_result) {
                        if ($thaiSharedGameChitDetail->flag_result === 1) {
                            $messageWinOrLose = 'ถูกรางวัล';
                            $classNameWinOrLose = 'win';
                        } else {
                            $messageWinOrLose = 'ไม่ถูกรางวัล';
                            $classNameWinOrLose = 'lost';
                        }
                    } elseif ($thaiSharedGameChit->status === Constants::status_playing) {
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
                                <h3 class="text-primary"><?= $thaiSharedGameChitDetail->number ?></h3>
                                <small><?= $thaiSharedGameChitDetail->playType->title ?></small>
                            </div>
                        </div>
                        <div class="col m-0 pl-1 pr-3 pb-1 border-left">
                            <div class="d-flex justify-content2-between border-bottom">
                                <font color="#000000"> <small>เลขที่ออก</small>
                                <span>
                                <?php
                                $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where([
                                    'thaiSharedGameId' => $thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGameId,
                                    'playTypeId' => $thaiSharedGameChitDetail->playTypeId
                                ])->all();
                                if (!$thaiSharedAnswerGames) {
                                    echo '-';
                                }
                                foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                                    echo $thaiSharedAnswerGame->number . '<br>';
                                }
                                ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                               <small>ราคาจ่าย</small>
                                <?php
                                if (($thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGame->gameId === Constants::LOTTERYLAOGAME ||
                                        $thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGame->gameId === Constants::LOTTERYLAODISCOUNTGAME) &&
                                    $thaiSharedGameChitDetail->thaiSharedGameChit->status !== Constants::status_finish_show_result) {
                                    $amountPaid = 0;
                                }
                                $amountPaid = $thaiSharedGameChitDetail->jackPotPerUnit;
                                ?>
                                <span class="thb">฿ <?= $amountPaid ?></span>
									</div></font>
                        </div>
                        <div class="col-12 bg-light border-top m-0 pt-1 d-flex justify-content-between">
                            <div class="d-flex">
                                <small class="mr-2">ราคาแทง:</small>
                                <?php
                                if ($thaiSharedGameChitDetail->discountGameId) {
                                    $pay = $thaiSharedGameChitDetail->discount;
                                } else {
                                    $pay = $thaiSharedGameChitDetail->amount;
                                }
                                ?>
                                <span class="thb">฿ <?= $pay ?></span>
                            </div>
                            <div class="d-flex">
                                <small class="mr-2">ผลได้เสีย:</small>
                                <h5 class="thb ">
                                    ฿ <?= $thaiSharedGameChitDetail->win_credit > 0 ?
                                        number_format($thaiSharedGameChitDetail->win_credit, 2) :
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