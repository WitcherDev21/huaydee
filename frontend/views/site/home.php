<?php
/* @var $credit string */
/* @var $news \common\models\News */
/* @var $creditTransection \common\models\CreditTransection */

use yii\helpers\Url;

$js = <<<EOT

$('.notOpen').click(function() {
    toastr.error('จะเปิดให้บริการเร็วๆนี้');
});
EOT;

$this->registerJs($js);
?>
<div class="d-flex flex-column justify-content-between card-main shadow px-3 py-2 pt-3 text-danger my-2 mx-md-0 mx-2 border-gold border border-3">
    <div class="d-flex justify-content-between">
        <font size="3" color="White"><span class="d-block text-gold"><i class="far fa-wallet text-white"></i> เงินคงเหลือ</span></font>
       
    </div>
    <div class="d-flex flex-column pt-3 pb-1">
        <h2 class="d-block text-center thb text-blue font-weight-bold" data-id="credit_balance"><?= number_format($credit, 2) ?></h2>
        <span class="text-center text-white"><?= Yii::$app->user->identity->username ?></span>
    </div>
    <div class="d-flex justify-content-between align-items-end">
        <div class="text-left">
            <span class="badge badge-pill badge-danger text-black">ทำรายการเมื่อ</span><br>
            <font size="3" color="White"><small><?= $creditTransection ? $creditTransection->create_at : 'ไม่มีรายการ' ?></small></font>
        </div>
        <div class="text-right">
            <span class="badge badge-pill badge-danger text-black">รายการล่าสุด</span><br>
           <font size="3" color="White"> <small><?= $creditTransection ? $creditTransection->remark : 'ไม่มีรายการ' ?></small> </font>
        </div>
    </div>
</div>

<div class="pb-3 align-self-stretch px-md-0 px-2">
    <div class="row px-md-auto px-2">
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['thai-shared-chit/index']) ?>" class="menu-grid border border-gold radius-15">
                <!-- <div class="ribbon ribbon-top-left"><span>HOT</span></div> -->

                <span class="fa-layers fa-fw">
					<i class="far fa-credit-card"></i>
                </span>
                <br>
                แทงหวย </a>
        </div>

        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['lottery-lao-game/index'])?>" class="menu-grid border border-gold radius-15">
               <i class="far fa-newspaper"></i>
                <br>
                หวยชุด </a>
        </div>
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="#" class="menu-grid border border-gold radius-15 notOpen">
               <i class="fas fa-cloud-download-alt"></i>
                <br>
                ดาวน์โหลด </a>
        </div>
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['lotto/report']) ?>" class="menu-grid border border-gold radius-15">
                <i class="fas fa-trophy"></i><br>
                ผลรางวัล </a>
        </div>

        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['thai-shared/index']) ?>" class="menu-grid border border-gold radius-15">
               <i class="fas fa-clipboard"></i><br>
                โพยหวย </a>
        </div>

        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['number-memo/index']) ?>" class="menu-grid border border-gold radius-15">
               <i class="fas fa-list-ul"></i><br>
                สร้างเลขชุด </a>
        </div>

        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="<?= Url::to(['recommend/index']) ?>" class="menu-grid border border-gold radius-15">
                <i class="fas fa-users"></i><br>
                แนะนำเพื่อน </a>
        </div>
        <div class="col-3 col-sm-3 col-md-3 col-lg-3 px-md-auto px-1">
            <a href="#" class="menu-grid border border-gold radius-15 notOpen">
                <i class="fas fa-donate"></i>
                <br>
                แจ็คพอต
            </a>
        </div>
        <div class="w-100"></div>
        <div class="col-6 col-md-6 col-lg-6 px-md-auto px-1 mb-2">
            <button onclick="location.href='<?= Url::to(['post-credit-transection/create-topup']) ?>'" class="glow-on-hover w-100 d-block menu-grid menu-money rounded radius-15 no-focus" style="box-shadow: inset 0px 0px 0px green !important; background: #28a745;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="mr-2">
                        <i class="fas fa-exchange-alt" style="color: #f8f9fa;"></i>
                    </div>
                    <div class="text-left">
                        ฝากเงิน
                    </div>
                </div>
            </button>
        </div>
        <div class="col-6 col-md-6 col-lg-6 px-md-auto px-1 mb-2">
            <button onclick="location.href='<?= Url::to(['post-credit-transection/create-withdraw']) ?>'" class="silver-on-hover menu-grid menu-money w-100 d-block rounded radius-15 no-focus"  style="box-shadow: inset 0px 0px 0px blue !important; background:  #0070f0;">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="mr-2">
                        <i class="fas fa-exchange-alt" style="color: #f8f9fa;"></i>
                    </div>
                    <div class="text-left">
                        ถอนเงิน
                    </div>
                </div>
            </button>
        </div>
        <div class="col-6 col-md-6 col-lg-6 px-md-auto px-1">
            <a href="<?= Url::to(['post-credit-transection/all']) ?>" class="menu-grid border rounded radius-15">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="mr-2">
                        <i class="fas fa-exchange-alt text-blue-2"></i>
                    </div>
                    <div class="text-left">
                        รายการแจ้ง<br>ฝาก/ถอน
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-6 col-lg-6 px-md-auto px-1">
            <a href="<?= Url::to(['credit-transection/list-current']) ?>" class="menu-grid border rounded radius-15">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="mr-2">
                        <i class="fas fa-wallet text-blue-2"></i>
                    </div>
                    <div class="text-left">
                        รายงาน<br>การเงิน
                    </div>
                </div>
            </a>
        </div>
    </div>

    <ul class="nav nav-tabs nav-justified rounded mb-2 radius-15" id="menucredit" role="tablist">
        <li class="nav-item rounded">
            <a class="nav-link text-blue-2 rounded bg-white border" href="#tab1content" data-toggle="tab" id="tab1contentt" role="tab" aria-controls="home" aria-selected="true">ประกาศ</a></li>
    </ul>
    <!------------ tab menu ---------------->
    <div class="tab-content" id="myTabContent">
        <div id="tab1content" class="tab-pane fade active show" role="tabpanel"
             aria-labelledby="tab1contentt">
            <div class="mb-1 py-1 rounded col-lotto">

                <div class="accordion bg-transparent" id="accordionCredit">

                    <div class="row">
                        <div class="col">
                            <div class="box-news d-flex flex-column radius-15">
                                <div class="h-box-news d-flex justify-content-between align-content-center py-3">
                                    <div class="float-left">
                                        <div class="icon-news">
                                            <i class="fa fa-bullhorn text-dark"></i>
                                        </div>
                                        <div class="txt-h text-dark">
                                            <span><b>ประกาศ</b><br><small>ข่าวสารจากทีมงาน</small></span></div>

                                    </div>
                                    <div class="float-right">
                                        <a href="<?= Url::to(['news/index'])?>"
                                           class="btn bg-gold btn-sm text-white rounded radius-15 px-3">ข่าวเพิ่มเติม</a>
                                    </div>
                                </div>
                                <div class="txt-box-news">
                                    <div class="row px-2">
                                        <?php foreach ($news as $key => $new) {
                                            if ($key === 0) {
                                                $img = '189815.jpg';
                                            } elseif ($key === 1) {
                                                $img = '189816.jpg';
                                            } else {
                                                $img = '189817.jpg';
                                            }
                                            ?>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 px-1">
                                                <div class="d-flex p-1 w-100">
                                                    <a href="<?= Url::to(['news/detail', 'id' => $new->id])?>">
                                                        <img src="<?= Yii::getAlias('@web/img/').$img ?>" data-action="<?= $key ?>">
                                                        <div class="text-news">
                                                            <h1><?= $new->title ?></h1>
                                                            <p class="ellipsis"><?= strip_tags($new->message) ?></p>
                                                            <span class="badge badge-secondary"><?= date("d-m-Y", strtotime($new->create_at)) ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>