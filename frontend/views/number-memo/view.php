<?php
/* @var $numberMemo \common\models\NumberMemo */

/* @var $numberSets array */

use yii\helpers\Url;

$this->registerJsVar('deleteUrl', Url::to(['delete']));
$this->registerJsVar('indexUrl', Url::to(['index']));
$this->registerJsFile(Yii::getAlias('@web/version6/js/index/numbersets.js?1561983814'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="bar-back">
    <a href="<?= Url::to(['number-memo/index']) ?>">
        <i class="fas fa-chevron-left"></i>
        จัดการเลขชุด
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-file-alt"></i> รายละเอียดเลขชุด</h4>
        </div>
        <a href="#" data-id="<?= $numberMemo->id ?>" class="btn btn-danger btn-sm deleteconfirm"><i
                    class="fas fa-trash-alt"></i>
            ลบรายการนี้</a>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded pt-2 px-2 pb-0 xtarget col-lotto">
        <div class="row px-2 align-items-center">
            <div class="col-12 col-sm-12 col-md-6">
                <label><i class="fas fa-tag"></i> ชื่อชุด</label> : <?= $numberMemo->title ?>
            </div>
            <div class="col-12 col-sm-12 col-md-6 text-left text-sm-left text-md-right">
                <label><i class="far fa-calendar"></i> สร้างเมื่อ</label>
                : <?= date('d/m/Y H:i', strtotime($numberMemo->create_at)) ?>
            </div>
            <div class="w-100"></div>
            <div class="col-12 table-secondary border-top border-bottom border-dark mb-1">
                <h6>ชุดตัวเลข</h6>
            </div>
            <div class="col-12">
                <ol class="pl-5 list-numsets">
                    <?php foreach ($numberSets as $numberSet) {
                        foreach ($numberSet as $value) {
                            ?>
                            <li class="p-1 border-bottom">
                                <div class="row text-center">
                                    <div class="col-5 col-sm-5 col-md-2">
                                        <?= $value['title'] ?>
                                    </div>
                                    <div class="col-7 col-sm-5 col-md-8">
                                        <?= $value['number'] ?>
                                    </div>
                                </div>
                            </li>
                        <?php }
                    } ?>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal_confirm_delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ข้อความแจ้งเตือน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>คุณแน่ใจนะว่าต้องการลบ เลขชุดนี้ ?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-danger btnconfirmdelete">ลบ</button>
            </div>
        </div>
    </div>
</div>