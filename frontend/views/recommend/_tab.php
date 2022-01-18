<?php

use yii\helpers\Url;

?>
<div class="bgwhitealpha text-secondary shadow-sm rounded p-1 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
    <div class="lotto-title">
        <h4><i class="fas fa-thumbs-up"></i> แนะนำเพื่อน</h4>
    </div>
</div>
<div class="row px-0 mb-1 mx-0">

    <div class="col-3 p-1 pb-0">
        <a href="<?= Url::to(['recommend/index']) ?>"
           class="btn-af btn btn-outline-danger_bkk btn-block d-flex flex-column <?= Yii::$app->controller->action->id === 'index' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i>
            ภาพรวม </a>
    </div>
    <div class="col-3 p-1 pb-0">
        <a href="<?= Url::to(['recommend/member']) ?>" class="btn-af btn btn-outline-danger_bkk btn-block d-flex flex-column <?= Yii::$app->controller->action->id === 'member' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            สมาชิก </a>
    </div>
    <div class="col-3 p-1 pb-0">
        <a href="<?= Url::to(['recommend/income']) ?>"
           class="btn-af btn btn-outline-danger_bkk btn-block d-flex flex-column <?= Yii::$app->controller->action->id === 'income' ? 'active' : '' ?>">
           <i class="fas fa-file-invoice-dollar"></i>
            รายได้ </a>
    </div>
    <div class="col-3 p-1 pb-0">
        <a href="<?= Url::to(['recommend/withdraw'])?>"
           class="btn-af btn btn-outline-danger_bkk btn-block d-flex flex-column <?= Yii::$app->controller->action->id === 'withdraw' ? 'active' : '' ?>">
            <i class="fas fa-money-bill-alt"></i>
            ถอนรายได้ </a>
    </div>
</div>
<div class="w-100 my-2 border-bottom"></div>