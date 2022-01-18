<div class="col-md-12">
    <div class="card">
        <div class="ibox-title">
            <h4>คะแนนสะสม และ สถานะสมาชิก</h4>
        </div>
        <div class="ibox-content">

            <div class="row">
                <div class="col-xs-5 col-lg-3 col-lg-offset-3">
                    <div class="contact-box text-center" style="margin-bottom:10px;">
                        <h4>คะแนนของคุณ</h4>
                        <h2 class="text-center">
                            <span class="text-navy"><?= number_format($creditTransactionAmount, 2) ?></span>
                        </h2>
                    </div>
                </div>
                <div class="col-xs-5 col-lg-3">
                    <div class="contact-box text-center">
                        <h4>สถานะของคุณ</h4>
                        <h2 class="text-center"><span class="text-navy"><?= $vip->name ?></span></h2>
                    </div>
                </div>
            </div>
            <?php
            $pass = true;
            foreach ($vips as $key => $vip) {
                $percent = ($creditTransactionAmount / $vip->point) * 100;
                ?>
            <div class="row">
                <div class="col-xs-3 text-right"><?= $vip->name ?></div>
                <div class="col-xs-5 col-sm-6 no-padding">
                    <div class="progress progress-striped active m-b-sm">
                        <div style="width: <?= $pass ? $percent : 0 ?>%;" class="progress-bar"></div>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-3"><?= number_format($vip->point) ?></div>
            </div>
            <?php
                if ($creditTransactionAmount < $vip->point) {
                    $pass = false;
                }
            } ?>
        </div>
    </div>
</div>