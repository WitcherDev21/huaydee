<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
use yii\helpers\Url;

$this->registerJsFile(Yii::getAlias('@web/version6/js/laos/tang_laos.js?1563854294'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="bgwhitealpha text-secondary shadow-sm rounded p-1 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
    <div class="lotto-title">
        <h4><i class="fas fa-ticket-alt"></i> <?= strpos($thaiSharedGame->title, 'เวียดนาม') ? 'หวยเวียดนาม' : 'หวยลาว' ?></h4>
    </div>
    <a href="#" class="btn btn-outline-secondary btn-sm mr-1" data-toggle="modal" data-target="#rule-laoslotto">
        กติกา&amp; วิธีเล่น
    </a>
</div>
<div class="row px-0 mb-1 mx-0">
    <div class="col-4 p-1 pb-0">
        <a href="<?= Url::to(['play', 'id' => $thaiSharedGame->id])?>"
           class="btn-af btn btn-outline-danger_l btn-block d-flex flex-column active">
            <i class="fas fa-medal"></i>
            <?= strpos($thaiSharedGame->title, 'เวียดนาม') ? 'ผลหวยเวียดนาม' : 'ผลหวยลาว' ?>
        </a>
    </div>
    <div class="col-4 p-1 pb-0">
        <a href="<?= Url::to(['tang', 'id' => $thaiSharedGame->id])?>"
           class="btn-af btn btn-outline-danger_l btn-block d-flex flex-column">
            <i class="fas fa-money-check-alt"></i>
            <?= strpos($thaiSharedGame->title, 'เวียดนาม') ? 'แทงหวยชุดเวียดนาม' : 'แทงหวยชุดลาว' ?>
        </a>
    </div>
    <div class="col-4 p-1 pb-0">
        <a href="<?= Url::to(['poy', 'id' => $thaiSharedGame->id])?>"
           class="btn-af btn btn-outline-danger_l btn-block d-flex flex-column">
            <i class="fas fa-edit"></i>
            รายการที่แทง
        </a>
    </div>
</div>