<?php
/* @var $percent */
/* @var $totalIncome */
/* @var $currentIncome */
/* @var $memberCount */
/* @var $sumPlayAmount */
/* @var $countClick */
/* @var $news \common\models\News */

/* @var $recommendUrl string */

use yii\helpers\Url;

$js = <<<EOT
 var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function(e) {
        toastr.success('คัดลอก', 'Success', {timeOut: 1500,preventDuplicates:true});
        e.clearSelection();
 });
EOT;
$this->registerJs($js);

$this->registerJsFile(Yii::getAlias('@web/version6/js/clipboard.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <?= $this->render('_tab') ?>
    <div class="table-secondary border border-secondary shadow-sm rounded p-0 mb-1 mx-1">
        <div class="row px-0 mx-0">
            <div class="col-12 col-sm-12 col-md-2 p-1">
                <div class="p-1 text-center rounded bg-secondary text-white h-100 d-flex flex-row flex-sm-row flex-md-column justify-content-center align-items-center">
                    <small class="mr-1">ส่วนแบ่งรายได้:</small>
                    <span class="text-warning"><?= $percent ?>%</span>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-2 p-1">
                <div class="py-1 text-center rounded bg-white">
                    <small>รายได้ทั้งหมด</small>
                    <h6 class="text-primary thb mb-0" style="font-family:inherit">
                        ฿ <?= number_format($totalIncome, 3) ?></h6>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-2 p-1">
                <div class="py-1 text-center rounded bg-white">
                    <small>รายได้ปัจจุบัน</small>
                    <h6 class="text-success thb mb-0" style="font-family:inherit">
                        ฿ <?= number_format($currentIncome, 3) ?></h6>
                </div>
            </div>
            <div class="col-4 col-sm-4 col-md-2 p-1">
                <div class="py-1 text-center rounded bg-white">
                    <small>สมาชิกแนะนำ</small>
                    <h6 class="text-info mb-0" style="font-family:inherit"><?= $memberCount ?></h6>
                </div>
            </div>
            <div class="col-4 col-sm-4 col-md-2 p-1">
                <div class="py-1 text-center rounded bg-white">
                    <small>แทงทั้งหมด</small>
                    <h6 class="text-info thb mb-0" style="font-family:inherit">
                        ฿ <?= number_format($sumPlayAmount) ?></h6>
                </div>
            </div>
            <div class="col-4 col-sm-4 col-md-2 p-1">
                <div class="py-1 text-center rounded bg-white">
                    <small>คลิกทั้งหมด</small>
                    <h6 class="text-info mb-0" style="font-family:inherit"><?= $countClick ?></h6>
                </div>
            </div>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-1 xtarget col-lotto">
        <h4><i class="fas fa-share-alt-square"></i> ลิ้งค์แนะนำ และแบนเนอร์</h4>
        <div class="card">
            <h5 class="card-header">ลิ้งสำหรับโปรโมท</h5>
            <div class="card-body">
                <textarea id="afurl" name="link" cols="30" rows="2" class="form-control text-center  mb-1"
                          readonly=""><?= $recommendUrl ?></textarea>
                <button id="link-copy" type="button" href="javascript:" data-clipboard-target="#afurl"
                        class="btn btn-danger_bkk btn-block m-1 ">
                    <i class="fas fa-copy"></i> คัดลอกลิงค์แนะนำ
                </button>
            </div>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-5 xtarget col-lotto">
        <h4><i class="fas fa-project-diagram"></i><?= $news->title ?></h4>
        <?= $news->message ?>
    </div>