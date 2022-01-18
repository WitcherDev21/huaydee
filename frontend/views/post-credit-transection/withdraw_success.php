<?php
use yii\helpers\Url;
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home'])?>">
        <i class="fas fa-chevron-left"></i> หน้าหลัก
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
        <div class="lotto-title">
            <h4 class="text-success"><i class="fas fa-vote-yea"></i> แจ้งถอนเงินสำเร็จ</h4>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 xtarget col-lotto">
        <div class="text-center my-4">
            <span style="font-size:100px;line-height:1.2;"><i class="far fa-check-circle text-success"></i></span>
            <h4 class="text-success" style="font-family:inherit">แจ้งข้อมูลเรียบร้อย</h4>
        </div>
        <div class="alert alert-secondary text-center">ท่านสามารถ คลิกที่ปุ่ม <b>ตรวจสอบสถานะ</b> เพื่อทำการ
            เช็ครายการของท่านได้ทันที
        </div>

    </div>
    <div class="bg-white p-2 rounded shadow-sm w-100 mb-5">
        <a onclick="location.href='<?= Url::to(['post-credit-transection/withdraw']) ?>'"
           class="btn btn-success btn-block text-white">
            <i class="fas fa-search"></i> ตรวจสอบสถานะ
        </a>
    </div>
</div>