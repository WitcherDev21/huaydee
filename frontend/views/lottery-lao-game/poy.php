<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
/* @var $thaiSharedGameChitDetails \common\models\ThaiSharedGameChitDetail */
/* @var $settingLotteryLaoSet \common\models\SettingLotteryLaoSet */

use yii\helpers\Url;
use common\libs\Constants;
use common\models\ThaiSharedGameChitDetail;

$this->registerJsVar('printPoyUrl', Url::to(['print']), \yii\web\View::POS_HEAD);
$this->registerJsVar('loadingImage', Yii::getAlias('@web/version6/images/laos/loading.gif'))
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>">
        <i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <?= $this->render('_tab', ['thaiSharedGame' => $thaiSharedGame]) ?>
    <div class="w-100 my-2 border-bottom"></div>

    <div class="bgwhitealpha shadow-sm rounded p-2 mb-1 xtarget col-lotto d-flex flex-column flex-sm-column flex-md-row justify-content-between">
        <h4 class="mb-0 text-center">
            <i class="fas fa-list"></i> รายการที่แทง
        </h4>
        <div>
            <span class="badge badge-danger_l font-weight-light w-100">
                ผลรางวัลออกเลขตามสลากหวยพัฒนา (<?= $thaiSharedGame->title ?>)
            </span>
        </div>
    </div>
    <div class="bg-white shadow-sm rounded py-2 px-1 mb-5">
        <?php foreach ($thaiSharedGameChitDetails as $key => $thaiSharedGameChitDetail) {
            if ($thaiSharedGameChitDetail->thaiSharedGameChit->status === Constants::status_playing) {
                $bgClass = 'bg-warning';
            } elseif ($thaiSharedGameChitDetail->thaiSharedGameChit->status === Constants::status_finish_show_result) {
                $isWin = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                    'numberSetLottery' => $thaiSharedGameChitDetail->numberSetLottery,
                    'flag_result' => 1,
                    'thaiSharedGameId' => $thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGameId
                ])->count();
                $bgClass = $isWin ? 'bg-success_bkk' : 'bg-danger_bkk';
            } else {
                $bgClass = 'bg-danger';
            }
            ?>
            <div class="row p-0 m-0 mb-2 border border-dark">
                <div class="col-2 col-sm-2 col-md-1 d-flex flex-column justify-content-center align-items-center bg-secondary text-white p-1">
                    <small style="line-height:1;">ลำดับ</small>
                    <b><?= $key + 1 ?></b>
                </div>
                <div class="col-6 col-sm-6 col-md-9 p-1 bg-light_bkk4 border border-left-0 border-right-0 border-top-0 d-flex justify-content-center align-items-center">
                    <h4 style="letter-spacing:5px;" class="mb-0"><?= $thaiSharedGameChitDetail->numberSetLottery ?></h4>
                </div>
                <div class="col-4 col-sm-4 col-md-2 <?= $bgClass ?> d-flex justify-content-center align-items-center px-0">
                    <small style="line-height:1.2">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <?php
                        if ($thaiSharedGameChitDetail->thaiSharedGameChit->status === Constants::status_playing) {
                            echo 'รอประกาศผล';
                        } elseif ($thaiSharedGameChitDetail->thaiSharedGameChit->status === Constants::status_finish_show_result) {
                            $isWin = ThaiSharedGameChitDetail::find()->joinWith('thaiSharedGameChit')->where([
                                'numberSetLottery' => $thaiSharedGameChitDetail->numberSetLottery,
                                'flag_result' => 1,
                                'thaiSharedGameId' => $thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGameId
                            ])->count();
                            echo $isWin ? 'ชนะ' : 'แพ้';
                        } ?>

                    </small>
                </div>
                <div class="w-100"></div>
                <div class="col-5 col-sm-5 col-md-2 bg-dark text-white pt-1 px-1">
                    <small>No. #<?= $thaiSharedGameChitDetail->thaiSharedGameChit->id ?></small>
                </div>
                <div class="col-7 col-sm-7 col-md-2 bg-dark text-white text-right text-sm-right text-md-center pt-1 px-1">
                    <small class="smallspan">
                        <span>ซื้อเมื่อ</span>
                        <span>วันที่ <?= date('d M Y', strtotime($thaiSharedGameChitDetail->createdAt)) ?></span>
                    </small>
                </div>
                <div class="col-8 col-sm-8 col-md-2 bg-dark text-white text-left text-sm-left text-md-right pt-1 px-1">
                    <small>
                        <span>ประจำวันที่</span>
                        <span>วันที่ <?= date('d M Y', strtotime($thaiSharedGameChitDetail->thaiSharedGameChit->thaiSharedGame->endDate)) ?></span>
                    </small>
                </div>
                <div class="col-4 col-sm-4 col-md-2 bg-dark text-white text-right pt-1 px-1">
                    <small>จำนวน <?= $thaiSharedGameChitDetail->setNumber ?> ชุด</small>
                </div>
                <div class="col-12 col-sm-12 col-md-2 bg-light_bkk border border-secondary border-bottom-0 text-dark text-center text-sm-center text-md-right pt-1 px-1">
                    <b>
                        <small>ราคา</small>
                        <?= $thaiSharedGameChitDetail->setNumber * $settingLotteryLaoSet->value ?> ฿
                    </b>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?= $this->render('modal_rule', ['thaiSharedGame' => $thaiSharedGame]) ?>
<?= $this->render('modal_print') ?>