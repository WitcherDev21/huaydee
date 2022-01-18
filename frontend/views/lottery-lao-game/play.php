<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
/* @var $lists array */
use yii\helpers\Url;
?>
    <div class="bar-back">
        <a href="<?= Url::to(['site/home']) ?>">
            <i class="fas fa-chevron-left"></i> หน้าหลัก
        </a>
    </div>
    <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">

        <?= $this->render('_tab', ['thaiSharedGame' => $thaiSharedGame]); ?>
        <div class="w-100 my-2 border-bottom"></div>

        <div class="bgwhitealpha shadow-sm rounded p-2 mb-1 xtarget col-lotto d-flex flex-column flex-sm-column flex-md-row justify-content-between">
            <h4 class="mb-0 text-center">
                <i class="fas fa-star"></i> <?= strpos($thaiSharedGame->title, 'เวียดนาม') ? 'VIETNAM LOTTERY' : 'LAOS LOTTERY' ?>
            </h4>
            <div>
            <span class="badge badge-danger_l font-weight-light w-100">
                ผลรางวัลออกเลขตามสลากหวยพัฒนา (<?= $thaiSharedGame->title ?>)
            </span>
            </div>
        </div>
        <div class="bg-white shadow-sm rounded py-2 px-1 mb-5">
            <div class="row p-0 m-0">
                <div class="col-12 col-sm-12 col-md-12 col-lg-9 px-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-row flex-sm-row flex-md-row-reverse justify-content-between justify-content-sm-between justify-content-md-end">
                                <div class="p-1">
                                <span class="badge badge-dark font-weight-light">
                                    ประจำวันที่
                                </span>
                                    วันที่ <?= date('d M Y', strtotime($thaiSharedGame->endDate)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 p-1 mt-2">
                <div class="row px-2">
                    <div class="col-12 col-sm-12 col-md-4 px-1">
                        <div class="card border-dark text-center mb-2">
                            <div class="card-header p-1">
                                <b>สี่ตัวตรง</b><br>
                                <small>(มูลค่า <?= number_format($lists['four_dt']['jackpot_per_unit']) ?> บาท)</small>
                            </div>
                            <div class="card-body p-1">
                                <h3 class="card-text"><?= $lists['four_dt']['number'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 px-1">
                        <div class="card border-dark text-center mb-2">
                            <div class="card-header p-1">
                                <b>สามตัวตรง</b><br>
                                <small>(มูลค่า <?= number_format($lists['three_top']['jackpot_per_unit']) ?> บาท)</small>
                            </div>
                            <div class="card-body p-1">
                                <h3 class="card-text"><?= $lists['three_top']['number'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 px-1">
                        <div class="card border-dark text-center mb-2">
                            <div class="card-header p-1">
                                <b>สามตัวหน้า</b><br>
                                <small>(มูลค่า <?= number_format($lists['three_ft']['jackpot_per_unit']) ?> บาท)</small>
                            </div>
                            <div class="card-body p-1">
                                <h3 class="card-text"><?= $lists['three_ft']['number'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-6 col-sm-6 col-md-3 p-1">
                        <div class="card border-dark text-center">
                            <div class="card-header p-1">
                                <b>สี่ตัวโต๊ด</b><br>
                                <small style="line-height:1.2;display:inline-block;">
                                    (<?= $lists['four_tod']['jackpot_per_unit'] ?> ฿)
                                </small>
                            </div>
                            <div class="card-body p-1">
                                <h5 class="card-text"><?= $lists['four_tod']['number'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 p-1">
                        <div class="card border-dark text-center">
                            <div class="card-header p-1">
                                <b>สามตัวโต๊ด</b><br>
                                <small style="line-height:1.2;display:inline-block;">
                                    (<?= number_format($lists['three_tod']['jackpot_per_unit']) ?> ฿)
                                </small>
                            </div>
                            <div class="card-body p-1">
                                <h5 class="card-text"><?= $lists['three_tod']['number'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 p-1">
                        <div class="card border-dark text-center">
                            <div class="card-header p-1">
                                <b>สองตัวหน้า</b><br>
                                <small style="line-height:1.2;display:inline-block;">
                                    (<?= number_format($lists['two_ft']['jackpot_per_unit']) ?> ฿)
                                </small>
                            </div>
                            <div class="card-body p-1">
                                <h5 class="card-text"><?= $lists['two_ft']['number'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 p-1">
                        <div class="card border-dark text-center">
                            <div class="card-header p-1">
                                <b>สองตัวหลัง</b><br>
                                <small style="line-height:1.2;display:inline-block;">
                                    (<?= number_format($lists['two_bk']['jackpot_per_unit']) ?> ฿)
                                </small>
                            </div>
                            <div class="card-body p-1">
                                <h5 class="card-text"><?= $lists['two_bk']['number'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->render('modal_rule', ['thaiSharedGame' => $thaiSharedGame]) ?>