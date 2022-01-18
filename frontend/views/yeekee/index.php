<?php

use common\libs\Constants;
use yii\helpers\Url;

$today = date('Y-m-d H:i:s');
$timestampToday = strtotime($today) * 1000;
$this->registerJsVar('timex', $timestampToday, \yii\web\View::POS_HEAD);
?>
<div id="section-content" class="container">
    <div class="bar-back">
        <a href="<?= Url::to(['thai-shared-chit/index']) ?>"><i class="fas fa-chevron-left"></i> แทงหวย</a>
    </div>
    <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch"
         style="min-height: calc((100vh - 140.677px) - 50px);">
        <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-3 xtarget col-lotto mb-5">
            <h3>จับยี่กี</h3>
            <div class="row">
                <?php foreach ($arrYeekee as $yeekee) {
                    $open = false;
                    if (strtotime($yeekee->finish_at) > strtotime($today) && $yeekee->status === Constants::status_active) {
                        $class = 'bg-success-light';
                        $open = true;
                    } elseif ($yeekee->status === Constants::status_cancel || $yeekee->status === Constants::status_inactive) {
                        $class = 'bg-danger-light';
                        $open = false;
                    } else {
                        $class = 'bg-default-light';
                        $open = false;
                    }
                    if (!$open) {
                        ?>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                            <a href="<?= Url::to(['yeekee/play', 'id' => $yeekee->id]) ?>">
                                <div class="lotto-card lotto-close">
                                    <div class="ribbon-lotto">
                                        <span class="round">รอบ</span>รอบที่ <?= $yeekee->round ?>
                                    </div>
                                    <div class="lotto-head lotto-yeekee">
                                        <h5>หวยยี่กี</h5>
                                        <span class="badge badge-dark">ปิดรับ <?= date('H:i', strtotime($yeekee->finish_at)); ?> </span>
                                    </div>
                                    <div class="lotto-time ">
                                        <i class="fas fa-ban"></i><span>ปิดรับแทง</span></div>
                                </div>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                            <a href="<?= Url::to(['yeekee/play', 'id' => $yeekee->id]) ?>">
                                <div class="lotto-card ">
                                    <div class="ribbon-lotto">
                                        <span class="round">รอบ</span>รอบที่ <?= $yeekee->round ?>
                                    </div>
                                    <div class="lotto-head lotto-yeekee">
                                        <h5>หวยยี่กี</h5>
                                        <span class="badge badge-dark">ปิดรับ <?= date('H:i', strtotime($yeekee->finish_at)); ?> </span>
                                    </div>
                                    <div class="lotto-time text-success">
                                        <i class="sn-icon sn-icon--daily2"></i>
                                        <span class="countdown"
                                              data-finaldate="<?= strtotime($yeekee->finish_at) * 1000 ?>">
                                            <?= date("H:i:s", strtotime($yeekee->finish_at)) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php }
                } ?>

                <?php foreach ($arrYeekeeNot as $yeekee2) {
                    $open = false;
                    if (strtotime($yeekee2->finish_at) > strtotime($today) && $yeekee2->status === Constants::status_active) {
                        $class = 'bg-success-light';
                        $open = true;
                    } elseif ($yeekee2->status === Constants::status_cancel || $yeekee2->status === Constants::status_inactive) {
                        $class = 'bg-danger-light';
                        $open = false;
                    } else {
                        $class = 'bg-default-light';
                        $open = false;
                    }
                    if (!$open) {
                        ?>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                            <a href="<?= Url::to(['yeekee/play', 'id' => $yeekee2->id]) ?>">
                                <div class="lotto-card lotto-close">
                                    <div class="ribbon-lotto">
                                        <span class="round">รอบ</span>รอบที่ <?= $yeekee2->round ?>
                                    </div>
                                    <div class="lotto-head lotto-yeekee">
                                        <h5>หวยยี่กี</h5>
                                        <span class="badge badge-dark">ปิดรับ <?= date('H:i', strtotime($yeekee2->finish_at)); ?> </span>
                                    </div>
                                    <div class="lotto-time ">
                                        <i class="fas fa-ban"></i><span>ปิดรับแทง</span></div>
                                </div>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-3">
                            <a href="<?= Url::to(['yeekee/play', 'id' => $yeekee2->id]) ?>">
                                <div class="lotto-card ">
                                    <div class="ribbon-lotto">
                                        <span class="round">รอบ</span>รอบที่ <?= $yeekee2->round ?>
                                    </div>
                                    <div class="lotto-head lotto-yeekee">
                                        <h5>หวยยี่กี</h5>
                                        <span class="badge badge-dark">ปิดรับ <?= date('H:i', strtotime($yeekee2->finish_at)); ?> </span>
                                    </div>
                                    <div class="lotto-time text-success">
                                        <i class="sn-icon sn-icon--daily2"></i>
                                        <span class="countdown"
                                              data-finaldate="<?= strtotime($yeekee2->finish_at) * 1000 ?>">
                                            <?= date("H:i:s", strtotime($yeekee2->finish_at)) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php }
                } ?>

            </div>
        </div>
    </div>
</div>