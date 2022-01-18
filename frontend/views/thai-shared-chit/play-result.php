<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
/* @var $lotteryAnswer array */
use yii\helpers\Url;


?>

<div class="bar-back d-flex justify-content-between align-items-center">
    <div id="top"></div>
    <a href="<?= Url::to(['thai-shared-chit/index']) ?>" class="mr-auto"><i class="fas fa-chevron-left"></i>
        ย้อนกลับ</a>
    <a href="#" class="btn btn-outline-secondary btn-sm mr-1 text-dark" data-toggle="modal" data-target="#rule-yeekee">กติกา</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 150px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-award"></i> ผลรางวัล</h4>
        </div>
    </div>
    <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget mb-5">
        <h6><i class="fas fa-star"></i> <?= $lotteryAnswer['title'] ?></h6>
        <div class="card border-dark text-center mb-2">
            <div class="card-header text-danger p-1">
                <?= $lotteryAnswer['title'] ?>
            </div>
            <div class="card-body p-0">
                <div class="card text-center w-100 rounded-0 border-0 m-0">
                    <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                        <?= $lotteryAnswer['three_top']['title'] ?>
                    </div>
                    <div class="card-body border-bottom p-0">
                        <h3 class="card-text mb-2"><?= $lotteryAnswer['three_top']['number'] ?></h3>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div class="card text-center w-50 border-card-right m-0">
                        <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                            <?= $lotteryAnswer['two_top']['title'] ?>
                        </div>
                        <div class="card-body p-0">
                            <h3 class="card-text mb-2"><?= $lotteryAnswer['two_top']['number'] ?></h3>
                        </div>
                    </div>
                    <div class="card text-center w-50 border-card-right m-0">
                        <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                            <?= $lotteryAnswer['two_under']['title'] ?>
                        </div>
                        <div class="card-body p-0">
                            <h3 class="card-text mb-2"><?= $lotteryAnswer['two_under']['number'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rule-yeekee" tabindex="-1" role="dialog" aria-labelledby="rule-yeekee" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:10px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">กติกา</h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h3><strong><?= $lotteryAnswer['title'] ?></strong></h3>
                <?= $lotteryAnswer['description'] ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger_bkk" data-dismiss="modal">ฉันเข้าใจและยอมรับ</button>
            </div>
        </div>
    </div>
</div>