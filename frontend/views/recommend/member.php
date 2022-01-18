<?php
/* @var $dataProvider */

use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <?= $this->render('_tab') ?>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-1 xtarget col-lotto">
        <h5><i class="fas fa-chalkboard-teacher"></i> สมาชิก</h5>
        <hr>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => "โชว์รายการ {begin} - {end} จาก {totalCount} รายการ",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'ยูสเซอร์เนม',
                    'encodeLabel' => false,
                    'attribute' => 'username',
                ],
                [
                    'label' => 'ราคาแทงทั้งหมด',
                    'attribute' => 'playAmount',
                    'encodeLabel' => false,
                    'format' => ['decimal', 2],
                ],
                [
                    'label' => 'วันที่สมัคร',
                    'attribute' => 'created_at',
                    'encodeLabel' => false,
                ],
            ],
        ]) ?>
    </div>
    <div class="bg-white text-secondary2 shadow-sm rounded pt-2 pb-1 px-2 mb-1">
    </div>
</div>


