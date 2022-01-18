<?php
use yii\helpers\Url;

$uri = Yii::getAlias('@web');

$js = <<<EOT

EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<ul class="nav nav-tabs">
    <li class="<?= ($active_tab == 'list')?'active':''?>">
        <a href="<?= Url::toRoute(['thai-shared-game/list'])?>" ><i class="fa fa-fw fa-globe"></i> รายการโพยหวยหุ้นล่าสุด</a>
    </li>
    <li class="<?= ($active_tab == 'history')?'active':''?>">
        <a href="<?= Url::toRoute(['thai-shared-game/history'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายการโพยหวยหุ้นย้อนหลัง</a>
    </li>
    <li class="<?= ($active_tab == 'result-number')?'active':''?>">
        <a href="<?= Url::toRoute(['thai-shared-game/result-number'])?>" ><i class="fa fa-fw fa-user-circle"></i> รายการสรุปเลขที่แทง</a>
    </li>
</ul>
