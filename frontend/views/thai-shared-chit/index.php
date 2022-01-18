<?php
/* @var $lotteryGames array */

/* @var $thaiSharedGames \common\models\ThaiSharedGame */

use yii\helpers\Url;
use common\models\ThaiSharedGame;
use yii\helpers\ArrayHelper;
use common\libs\Constants;

$today = date('Y-m-d H:i:s');
$timestampToday = strtotime($today) * 1000;
$this->registerJsVar('timex', $timestampToday, \yii\web\View::POS_HEAD);
?>
<div class="bar-back d-flex justify-content-between align-items-center">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 150px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-3 xtarget col-lotto">
        <h3>หวย</h3>
        <div class="row">
            <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                <a href="<?= Url::to(['yeekee/index']) ?>">
                    <div class="lotto-card">
                        <div class="ribbon-lotto"><i class="fas fa-trophy"></i></div>
                        <div class="lotto-head lotto-yeekee">
                            <h5>จับยี่กี VIP</h5>
                            <span class="badge badge-dark">เปิดแทง 88 รอบ</span>
                        </div>
                        <div class="lotto-time">
                            <div class="txt-24">
                                <i class="far fa-check-circle"></i> <span id="offset">24 ชม.</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php foreach ($lotteryGames as $lotteryGame) {
                if ($lotteryGame->status === Constants::status_inactive || $lotteryGame->status === Constants::status_withhold ||
                    strtotime($lotteryGame->startDate) >= strtotime($today) ||
                    strtotime($lotteryGame->endDate) <= strtotime($today)) {
                    ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['thai-shared-chit/play', 'id' => $lotteryGame->id]) ?>">
                            <div class="lotto-card lotto-close">
                                <div class="ribbon-lotto">
                                    <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($lotteryGame->title),'icon') ?>"></span>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($lotteryGame->title),'classHead') ?>">
                                    <h5><?= str_replace('แบบ', '', $lotteryGame->title) ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $lotteryGame->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success time-government">
                                    <i class="fas fa-ban"></i><span></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['thai-shared-chit/play', 'id' => $lotteryGame->id]) ?>">
                            <div class="lotto-card">
                                <div class="ribbon-lotto">
                                    <i class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($lotteryGame->title), 'icon') ?>"></i>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($lotteryGame->title),'classHead') ?>">
                                    <h5><?= str_replace('แบบ', '', $lotteryGame->title) ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $lotteryGame->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success time-government">
                                    <i class="sn-icon sn-icon--daily2"></i>
                                    <?php if($lotteryGame->status !== Constants::status_active){
                                        echo 'ปิดรับแทง';
                                    }else{  ?>
                                    <span class="countdown"
                                          data-finaldate="<?= strtotime($lotteryGame->endDate) * 1000 ?>">
                                    <?= date("H:i:s", strtotime($lotteryGame->endDate)) ?>
                                    </span>
                                    <?php } ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-3 xtarget col-lotto mb-5">
        <h3>หวยหุ้น</h3>
        <div class="row">
            <?php foreach ($thaiSharedGames as $thaiSharedGame) {
                if ($thaiSharedGame->status === Constants::status_inactive || $thaiSharedGame->status === Constants::status_withhold ||
                    strtotime($thaiSharedGame->startDate) >= strtotime($today) ||
                    strtotime($thaiSharedGame->endDate) <= strtotime($today)) {
                    ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['thai-shared-chit/play', 'id' => $thaiSharedGame->id]) ?>">
                            <div class="lotto-card lotto-close">
                                <div class="ribbon-lotto">
                                    <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiSharedGame->title), 'icon') ?>"></span>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiSharedGame->title),'classHead') ?>">
                                    <h5><?= $thaiSharedGame->title ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $thaiSharedGame->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success">
                                    <i class="fas fa-ban"></i><span>ปิดรับแทง</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['thai-shared-chit/play', 'id' => $thaiSharedGame->id]) ?>">
                            <div class="lotto-card ">
                                <div class="ribbon-lotto">
                                    <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiSharedGame->title), 'icon') ?>"></span>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiSharedGame->title),'classHead') ?>">
                                    <h5><?= $thaiSharedGame->title ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $thaiSharedGame->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success time-government">
                                    <i class="sn-icon sn-icon--daily2"></i>
                                    <?php if($thaiSharedGame->status !== Constants::status_active){
                                        echo 'ปิดรับแทง';
                                    }else{?>
                                    <span class="countdown"
                                          data-finaldate="<?= strtotime($thaiSharedGame->endDate) * 1000 ?>">
                                    <?= date("H:i:s", strtotime($thaiSharedGame->endDate)) ?>
                                    </span>
                                    <?php } ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>