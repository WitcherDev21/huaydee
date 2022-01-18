<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Url; ?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home'])?>">
        <i class="fas fa-chevron-left"></i> หน้าหลัก
    </a>
</div>
<div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4 class="text-danger"><i class="fas fa-vote-yea"></i> เกิดข้อผิดพลาดของระบบ</h4>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 xtarget col-lotto  text-center text-danger">
        <?= $exception ?>
    </div>
</div>
