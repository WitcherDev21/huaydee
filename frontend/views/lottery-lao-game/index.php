<?php
/* @var $laoLists ThaiSharedGame */

use common\libs\Constants;
use common\models\ThaiSharedGame;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$today = date('Y-m-d H:i:s');
$timestampToday = strtotime($today) * 1000;
$this->registerJsVar('timex', $timestampToday, \yii\web\View::POS_HEAD);
?>
<div class="bar-back d-flex justify-content-between align-items-center">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 150px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-3 xtarget col-lotto">
        <h3>หวยชุด</h3>
        <div class="row">
            <?php foreach ($laoLists as $laoList) {
                if ($laoList->status === 2 ||
                    strtotime($laoList->startDate) >= strtotime($today) ||
                    strtotime($laoList->endDate) <= strtotime($today)) {
                    ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['play', 'id' => $laoList->id]) ?>">
                            <div class="lotto-card lotto-close">
                                <div class="ribbon-lotto">
                                    <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($laoList->title),'icon') ?>"></span>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($laoList->title),'classHead') ?>">
                                    <h5><?= $laoList->title ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $laoList->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success time-government">
                                    <i class="fas fa-ban"></i><span>ปิดรับแทง</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                        <a href="<?= Url::to(['play', 'id' => $laoList->id]) ?>">
                            <div class="lotto-card">
                                <div class="ribbon-lotto">
                                    <i class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($laoList->title), 'icon') ?>"></i>
                                </div>
                                <div class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($laoList->title),'classHead') ?>">
                                    <h5><?= $laoList->title ?></h5>
                                    <span class="badge badge-dark">ปิดรับ <?= $laoList->endDate ?></span>
                                </div>
                                <div class="lotto-time text-success time-government">
                                    <i class="sn-icon sn-icon--daily2"></i>
                                    <?php if($laoList->status !== Constants::status_active){
                                        echo 'ปิดรับแทง';
                                    }else{ ?>
                                    <span class="countdown"
                                          data-finaldate="<?= strtotime($laoList->endDate) * 1000 ?>">
                                    <?= date("H:i:s", strtotime($laoList->endDate)) ?>
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
