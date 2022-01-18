<?php
use yii\helpers\Url;
?>
<div class="bgwhitealpha text-white shadow-sm rounded xtarget col-lotto d-flex flex-column pb-0">
    <div class="btn-group" role="group" aria-label="Basic example">
        <a class="btn btn-primary_bkk3 w-100" href="<?= Url::to(['post-credit-transection/all']) ?>"><i
                    class="fas fa-wallet"></i> ทั้งหมด</a>
        <a class="btn btn-success_bkk w-100" href="<?= Url::to(['post-credit-transection/deposit']) ?>"><i
                    class="fas fa-folder-plus"></i> ฝาก</a>
        <a class="btn btn-danger_bkk w-100" href="<?= Url::to(['post-credit-transection/withdraw']) ?>"><i
                    class="fas fa-folder-minus"></i> ถอน</a>
    </div>
</div>