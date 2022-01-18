<?php

use common\models\Games;
use common\models\ThaiSharedGame;
use yii\helpers\Url;

$js = <<<EOT
	
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date('Y-m-d H:i:s');
if (isset($id)) {
    $thaiSharedGame = ThaiSharedGame::find()->where(['id' => $id])->one();
}
?>

<div class="card col-xs-12">
    <h4 class="card-header bg-transparent">
        <a href="<?= Url::to(['thai-shared-chit/index']) ?>" style="color: #b42121"><?= $result ?> </a> <?= isset($id) ? '/'.$thaiSharedGame->title : '' ?>
    </h4>
    <div class="card-body">
        <?php if($pass==1 && $reason == 3){?>
            <a href="<?= Url::toRoute(['thai-shared/index'])?>" class="btn btn-info">ดูรายการโพย</a>
            <a href="<?= Url::toRoute(['lottery-lao-game/play','id' => $lotteryGameId])?>" class="btn btn-success">ซื้อหวยต่อ</a>
        <?php }else if($reason=='2'){?>
            <h4><?= $text?></h4>
            <a href="<?= Url::toRoute(['post-credit-transection/create-topup'])?>"  class="btn btn-danger">เติมเครดิต</a>
        <?php }else if($reason=='1'){?>
            <h3 class="text-center text-danger">ระบบปิดรับแทง</h3>
            <h3 class="text-center text-danger">ปิดรับ  <?= $thaiSharedGame->endDate ?></h3>
            <hr>
            <h4><?= $thaiSharedGame->title ?></h4>
            <?php
            echo $thaiSharedGame->description;
            ?>
        <?php }else if($reason=='4'){?>
            <h4><?= $text?></h4>
            <a href="<?= Url::toRoute(['thai-shared/index'])?>"  class="btn btn-danger">ซื้อหวยรัฐบาลเกินจำนวนเงินที่กำหนด</a>
        <?php } ?>
    </div>

</div>