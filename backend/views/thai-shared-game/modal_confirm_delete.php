<?php
/* @var $orderId string */
?>
<div class="modal fade modal_confirm_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อความแจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>คุณต้องการยกเลิกโพยเลขที่ #<?= $orderId ?> ใช่หรือไม่ ?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ไม่</button>
                <button type="button" class="btn btn-danger btnconfirmdelete">ยกเลิกโพย</button>
            </div>
        </div>
    </div>
</div>