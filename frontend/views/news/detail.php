<?php
/* @var $news \common\models\News */
use yii\helpers\Url;
?>
<div class="bar-back d-flex justify-content-between align-items-center">
    <a href="<?= Url::to(['site/home'])?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
    <a href="#" class="btn btn-primary btn-sm d-flex justify-content-around align-items-center">
        <i class="fas fa-newspaper mr-1"></i>
        <span>ข่าวทั้งหมด</span>
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 152px) - 50px);">
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto mb-1 pb-0">
        <div class="lotto-title w-100">
            <h4><?= $news->title ?></h4>
            <span class="badge badge-secondary font-weight-light"><?= date("d-m-Y", strtotime($news->create_at)) ?></span>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto mb-5 pb-0">
        <?= $news->message ?>
    </div>
</div>