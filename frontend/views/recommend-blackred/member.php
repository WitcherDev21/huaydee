<?php

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


$uri = Yii::getAlias('@web');

$js = <<<EOT

EOT;
$this->registerJs($js);
$css = <<<EOT

EOT;
$this->registerCss($css);
?>
<div class="col-xs-12">
    <div class="panel">
        <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
        <div class="tab-content" style="padding-top: 30px;">
            <div class="tab-pane fade in active">
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
        </div>
    </div>
</div>


