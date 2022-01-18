<?php
use yii\helpers\Url;
use yii\bootstrap\Html;

$js = <<<EOT
	
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date('Y-m-d H:i:s');
?>

<div class="card col-xs-12">
    <h4 class="card-header bg-transparent">
        <?= $result?>
    </h4>
    <div class="card-body">
        <?php if($reason == 3){?>
            <a href="<?= Url::toRoute(['blackredchit/list-current'])?>" class="btn btn-info">ดูรายการโพย</a>
            <a href="<?= Url::toRoute(['blackred/index'])?>" class="btn btn-success">วางเดิมพันต่อ</a>
        <?php }else if($reason == 2){?>
            <h4><?= $text?></h4>
            <a href="<?= Url::toRoute(['post-credit-transection/create-topup'])?>"  class="btn btn-danger">เติมเครดิต</a>
        <?php }else if($reason == 1){?>
            <h4><?= $text?></h4>
            <a href="<?= Url::toRoute(['blackred/index'])?>"  class="btn btn-danger">หมดเวลาเล่น</a>
        <?php } else if($reason == 4){?>
            <h4><?= $text?></h4>
            <a href="<?= Url::toRoute(['blackred/index'])?>"  class="btn btn-danger">กรุณายกเลิกโพยก่อนหน้า</a>
        <?php } ?>
    </div>

</div>
