<?php
echo $curr = date("Y-m-d H:i:s");
/* @var $thaiLotterys array */
/* @var $now string */
/* @var $foreignLotterys array */
/* @var $gameLotterys array */

/* @var $news \common\models\News */

use common\models\ThaiSharedGame;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$style= <<< CSS
.help-block{
    color: white;
    margin-top: 10px;
}
CSS;
$this->registerCss($style);

$localStorageJs = <<<EOT
localStorage.clear();
EOT;
$this->registerJs($localStorageJs);
?>
    <div id="fb-root"></div>

    <div id="app">
        <div class="fixed-bg"></div>
        <?php if(Yii::$app->session->hasFlash('alert')) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 0px;">
                <strong>ลงทะเบียนสำเร็จ</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <div class="navbar">
            <div class="container">
                <div class="d-flex flex-row justify-content-between w-100">
                    <div class="notice-bar flex-fill">
                        <div class="txt-notice">
                            <ul id="marquee1" class="marquee">
                                <li>&nbsp;ยินดีต้อนรับทุกท่านเข้าสู่เว็บ RUAYDEE96
                                    เว็บหวยออนไลน์ที่มาแรงที่สุดในตอนนี้
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>    <!--- end navbar --->
        <div class="container">
            
            <section id="loginbox" class="mb-2">

            <div class="col-lg pt-2 pc-view text-center">
                <a href="https://lin.ee/GLvHplm" target="_blank">
                <img src="<?= Yii::getAlias('@web/version6/images/ads-bar-01.jpg') ?>" width="100%" height="auto" class="img-responsive"></a>
            </div>

                <div class="bglogin p-2">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="indexlogo">
                                <div class="logoindex"><img
                                            src="<?= Yii::getAlias('@web/version6/images/demolotto.png') ?>"
                                            style="height: 60px;"
                                            alt="RUAYDEE96"
                                            title="RUAYDEE96"/></div>
                            </div><br>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-9">
                            <?php $form = ActiveForm::begin([
                                'id' => 'login-form',
                                'method' => 'post',
                            ]) ?>
                            <input type="hidden" name="csrf_token" value="dc61db166ac16d6e9048a5c493bef756"/>

                            <input type="hidden" name="login" value="1"/>
                            <div class="form-row form-middle">
                                <div class="col-sm-12 col-md-4 col-lg-3 p-1">
                                    <div class="form-group mb-0">
                                        <?= $form->field($model, 'login',
                                            ['inputOptions' => ['class' => 'form-control', 'tabindex' => '1']]
                                        )->input('text', ['required' => true])->textInput(['placeholder' => 'ชื่อผู้ใช้'])->label(false);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-3 p-1">
                                    <div class="form-group mb-0">
                                        <?= $form->field(
                                            $model,
                                            'password',
                                            ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])
                                            ->passwordInput(['placeholder' => 'รหัสผ่าน'])
                                            ->label(false) ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-2 p-1">
                                    <div class="row">
                                        <div class="col col-6 col-md-12 text-left text-sm-left text-md-right remember">
                                            <div class="form-group">
                                                <div class="form-check text-white">
                                                    <?= $form->field($model, 'rememberMe')->checkbox(['label' => 'จำฉันไว้ในระบบ', 'tabindex' => '3']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-2 p-1">
                                    <div class="dropdown bootstrap-select form-control">
                                        <select id="lang" data-show-content="true" class="form-control" tabindex="-98">
                                            <option data-content="<span class='flag-icon flag-icon-th'></span> ไทย" selected="selected" value="thai"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-2 p-1">
                                    <button type="submit" class="btn btn-danger_bkk btn-block">เข้าสู่ระบบ</button>
                                </div>

                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
                <div class="subbglogin p-2">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-9 rule text-right">
                            <a href="#" class="float-left" data-toggle="modal" data-target="#ModalRate">อัตราการจ่าย</a>
                            <a href="#" data-toggle="modal" data-target="#ModalRule">กฏกติกาและข้อบังคับ</a>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-3 submenulogin">

                            <div class="row">
                                <div class="col-6 pr-1">
                                    <div class="btoutline">
                                        <a href="https://lin.ee/GLvHplm" class="btn silver-btn btn-sm btn-block"
                                           target="_blank"><i class="fas fa-headset"></i> ติดต่อเรา</a>
                                    </div>
                                </div>
                                <div class="col-6 pl-1">
                                    <div class="btoutline2">
                                        <a href="<?= Url::to(['/user/register?from=QUhDN2o0cE1GemlwclYyTjRUTzFUdz09']) ?>"
                                           class="btn btn-outline-dark-2 btn-sm btn-block btn-register-lottovip"><i
                                                    class="fas fa-user-plus"></i> สมัครสมาชิก</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="ModalRule" tabindex="-1" role="dialog" aria-labelledby="ModalRule"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="border-radius:10px;">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">กฏและกติกา</h5>
                            <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?= $news->message ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ฉันเข้าใจและยอมรับ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal rule -->

            <!-- modal rule -->
            <div class="modal fade" id="ModalRate" tabindex="-1" role="dialog" aria-labelledby="ModalRate"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="border-radius:10px;">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">อัตราการจ่าย</h5>
                            <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="<?= Yii::getAlias('@web/version6/images/ads-banner-01.jpg') ?>"
                                 style="max-width: 100%;max-height: 100%;height: inherit !important;"><br><br>
                                 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal rate -->

            <section id="contentbox">

                <div class="row px-2">
                    <!-- ADS Banner -->
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha blue shadow-sm rounded p-2 h-100">
                            <a href="https://lin.ee/GLvHplm" target="_blank">
                                <img src="<?= Yii::getAlias('@web/version6/images/ads-banner-01.jpg') ?>" width="100%" height="auto" class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha blue shadow-sm rounded p-2 h-100">
                            <a href="https://lin.ee/GLvHplm" target="_blank">
                                <img src="<?= Yii::getAlias('@web/version6/images/ads-banner-02.jpg') ?>" width="100%" height="auto" class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <!-- ADS Banner End -->
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha blue shadow-sm rounded p-2 h-100">
                            <?php if ($yeekeeLastActive) { ?>
                                <h6 class="text-white"><i class="far fa-gem" style="color: #000"></i> จับยี่กี VIP - รอบที่ <?= $yeekeeLastActive->round ?>
                                    <span class="badge badge-danger"><?= $now ?></span>
                                </h6>
                                <div class="row">
                                    <div class="col pr-1">
                                        <div class="card text-center border-0">
                                            <div class="card-header bg-blue text-white p-1">
                                                3ตัวบน<br>
                                            </div>
                                            <div class="card-body p-1">
                                                <p class="card-text font-weight-bold">
                                                    <?= empty($yeekeeLastActive->getResults('three_top')) ? 'xxx' : $yeekeeLastActive->getResults('three_top') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col pl-1">
                                        <div class="card text-center border-0">
                                            <div class="card-header bg-blue text-white p-1">
                                                2ตัวล่าง<br>

                                            </div>
                                            <div class="card-body p-1">
                                                <p class="card-text font-weight-bold">
                                                    <?= empty($yeekeeLastActive->getResults('two_under')) ? 'xx' : $yeekeeLastActive->getResults('two_under') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha blue shadow-sm rounded p-2 h-100 xtarget" id="government">
                            <h6 class="text-white"><i class="fas fa-crown" style="color: #000"></i> หวยรัฐบาลไทย <span
                                        class="badge badge-danger"><?= $gameLotterys['date'] ?></span>
                            </h6>
                            <div class="card border-0 text-center mb-2">
                                <div class="card-body p-1">
                                    <p class="font-weight-bold mb-0"><?= $gameLotterys['firstResult'] ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col pr-1">
                                    <div class="card border-0 text-center">
                                        <div class="card-header text-white bg-blue p-1">
                                            <?= $gameLotterys['three_front']['title'] ?><br>
                                        </div>
                                        <div class="card-body p-1">
                                            <p class="card-text mb-0 font-weight-bold"><?= $gameLotterys['three_front']['number'] ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col px-1">
                                    <div class="card border-0 text-center">
                                        <div class="card-header text-white bg-blue p-1">
                                            <?= $gameLotterys['three_back']['title'] ?><br>
                                        </div>
                                        <div class="card-body p-1">
                                            <p class="card-text mb-0 font-weight-bold"><?= $gameLotterys['three_back']['number'] ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col pl-1">
                                    <div class="card border-0 text-center">
                                        <div class="card-header text-white bg-blue p-1">
                                            <?= $gameLotterys['two_under']['title'] ?><br>
                                        </div>
                                        <div class="card-body p-1">
                                            <p class="card-text mb-0 font-weight-bold"><?= $gameLotterys['two_under']['number'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget" id="government">
                            <h6><i class="fas fa-crown"></i> หวยออมสิน <span
                                        class="badge badge-danger"><?= $gsb['date'] ?></span>
                            </h6>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 px-1">
                                <div class="card border-light-2 text-center shadow-sm mb-2">
                                    <div class="card-header text-blue bg-header p-1">
                                        รางวัลเลขท้าย 6 ตัว
                                    </div>
                                    <div class="card-body p-1">
                                        <p class="card-text"><?= $gsb['result'] !== '' && $gsb['result'] !== null ? $gsb['result'] : 'xxxxxxx,xxxxxxx' ?></p>
                                    </div>
                                </div>
                                <div class="card border-light-2 text-center shadow-sm mb-2">
                                    <div class="card-header text-blue bg-header p-1">
                                        <span class="flag-icon flag-icon-gsb"></span>
                                        หวยออมสิน
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="d-flex flex-row">
                                            <div class="card text-center w-50 border-card-right m-0">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $gsb['three_top']['title'] ?><br>
                                                    <!--<small>(750)</small>-->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $gsb['three_top']['number'] ?></p>
                                                </div>
                                            </div>
                                            <div class="card text-center w-50 border-card-right m-0">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $gsb['two_under']['title'] ?><br>
                                                    <!--<small>(90)</small>-->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $gsb['two_under']['number'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget" id="government">
                            <h6><i class="fas fa-crown"></i> หวย ธกส <span
                                        class="badge badge-danger"><?= $bacc['date'] ?></span>
                            </h6>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 px-1">
                                <div class="card border-light-2 text-center shadow-sm mb-2">
                                    <div class="card-header text-blue bg-header p-1">
                                        เลขที่ออก

                                    </div>
                                    <div class="card-body p-1">
                                        <p class="card-text"><?= $bacc['result'] !== '' && $bacc['result'] !== null ? $bacc['result'] : 'xxxxxx' ?></p>
                                    </div>
                                </div>
                                <div class="card border-light-2 text-center shadow-sm mb-2">
                                    <div class="card-header text-blue bg-header p-1">
                                        <span class="flag-icon flag-icon-baac"></span>
                                        หวย ธกส
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="d-flex flex-row">
                                            <div class="card text-center w-50 border-card-right m-0">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $bacc['three_top']['title'] ?><br>
                                                    <!--<small>(750)</small>-->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $bacc['three_top']['number'] ?></p>
                                                </div>
                                            </div>
                                            <div class="card text-center w-50 border-card-right m-0">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $bacc['two_under']['title'] ?><br>
                                                    <!--<small>(90)</small>-->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $bacc['two_under']['number'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($thaiLotterys)): ?>
                <div class="bgwhitealpha shadow-sm rounded p-2 xtarget" id="thaiStock">
                    <h6><i class="fas fa-star"></i> หวยหุ้นไทย <span class="badge badge-danger"><?= $now ?></span></h6>

                    <div class="card-group">
                        <?php foreach ($thaiLotterys as $thaiLottery) { ?>
                            <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                                <div class="card border-light-2 text-center shadow-sm mb-8">
                                    <div class="card-header text-blue bg-header p-1">
                                        <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiLottery['title']), 'icon') ?>"></span>
                                        <?= $thaiLottery['title'] ?>
                                    </div>
                                    <div class="card-body p-0">

                                        <div class="d-flex flex-row">

                                            <div class="card text-center w-50 border-card-right">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $thaiLottery['three_top']['title'] ?> <br>
                                                    <!-- <small>(750)</small> -->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $thaiLottery['three_top']['number'] ?></p>
                                                </div>
                                            </div>

                                            <div class="card text-center w-50 border-card-right">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $thaiLottery['two_under']['title'] ?><br>
                                                    <!-- <small>(90)</small> -->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $thaiLottery['two_under']['number'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php endif ?>

                <?php if(!empty($foreignLotterys)): ?>
                <div class="bgwhitealpha shadow-sm rounded p-2 xtarget" id="foreignStock">
                    <h6><i class="far fa-star"></i> หวยหุ้นต่างประเทศ <span
                                class="badge badge-danger"><?= $now ?></span></h6>
                    <div class="carousel-view">
                        <div id="myCarousel1" class="carousel slide" data-ride="carousel">
                            <ol id="nav1" class="carousel-indicators nav-slide">
                                <?php foreach ($foreignLotterys as $key => $foreignLottery) { ?>
                                    <li data-target="#myCarousel1"
                                        data-slide-to="<?= $key ?>" class="<?php echo ($key === 0) ? 'active' : ''; ?>">
                                        <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
                                        <?= $foreignLottery['title'] ?>
                                    </li>
                                <?php } ?>
                            </ol>
                            <div class="d-flex justify-content-between py-1">
                                <a href="#myCarousel1" role="button" data-slide="prev" class="btn btn-dark btn-sm">
                                    <span><i class="fas fa-chevron-left"></i> Previous</span>
                                </a>
                                <a href="#myCarousel1" role="button" data-slide="next" class="btn btn-dark btn-sm">
                                    <span>Next <i class="fas fa-chevron-right"></i></span>
                                </a>
                            </div>
                            <div class="carousel-inner" role="listbox">
                                <?php foreach ($foreignLotterys as $key => $foreignLottery) { ?>
                                    <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                        <div class="card border-light-2 text-center shadow-sm mb-2">
                                            <div class="card-header text-blue bg-header p-1">
                                                <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
                                                <?= $foreignLottery['title'] ?>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="d-flex flex-row">
                                                    <div class="card text-center w-50 border-card-right m-0">
                                                        <div class="card-header sub-card-header bg-transparent p-0">
                                                            <?= $foreignLottery['three_top']['title'] ?><br>
                                                            <!-- <small>(750)</small>-->
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <p class="card-text"><?= $foreignLottery['three_top']['number'] ?></p>
                                                        </div>
                                                    </div>

                                                    <div class="card text-center w-50 border-card-right m-0">
                                                        <div class="card-header sub-card-header bg-transparent p-0">
                                                            <?= $foreignLottery['two_under']['title'] ?><br>
                                                            <!-- <small>(90)</small> -->
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <p class="card-text"><?= $foreignLottery['two_under']['number'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div><!-- end carousel-view -->
                    <div class="pc-view">
                        <div class="row px-0 m-0">
                            <?php foreach ($foreignLotterys as $foreignLottery) { ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 px-1">
                                    <div class="card border-light-2 text-center shadow-sm mb-2">
                                        <div class="card-header text-blue bg-header p-1">
                                            <span class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
                                            <?= $foreignLottery['title'] ?>
                                        </div>
                                        <div class="card-body p-0">

                                            <div class="d-flex flex-row">

                                                <div class="card text-center w-50 border-card-right m-0">
                                                    <div class="card-header sub-card-header bg-transparent p-0">
                                                        <?= $foreignLottery['three_top']['title'] ?><br>
                                                        <!--<small>(750)</small>-->
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <p class="card-text"><?= $foreignLottery['three_top']['number'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="card text-center w-50 border-card-right m-0">
                                                    <div class="card-header sub-card-header bg-transparent p-0">
                                                        <?= $foreignLottery['two_under']['title'] ?><br>
                                                        <!--<small>(90)</small>-->
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <p class="card-text"><?= $foreignLottery['two_under']['number'] ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div><!-- end card column -->

                    </div><!-- end pc view -->
                </div>
                <!-- end หวยหุ้นต่างประเทศ -->
                <?php endif ?>

                <div class="bgwhitealpha shadow-sm rounded p-2 xtarget" id="yeekee">
                    <h6><i class="fas fa-trophy"></i> จับยี่กี VIP <span class="badge badge-danger"><?= $now ?></span></h6>

                    <div class="mobile-view">
                        <ul class="nav nav-pills mb-3 w-100 bg-white rounded p-1" id="pills-tab" role="tablist">
                            <li class="nav-item w-50">
                                <a class="nav-link active btn btn-outline-dark btn-sm mx-1" id="pills-short-tab" data-toggle="pill" href="#pills-short" role="tab" aria-controls="pills-short" aria-selected="true">มุมมองสั้น</a>
                            </li>
                            <li class="nav-item w-50">
                                <a class="nav-link btn btn-outline-dark btn-sm mx-1" id="pills-long-tab" data-toggle="pill" href="#pills-long" role="tab" aria-controls="pills-long" aria-selected="false">มุมมองยาว</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-short" role="tabpanel" aria-labelledby="pills-short-tab">
                            <div class="carousel-view">
                                <div class="bg-danger p-1 my-1 text-center text-white"></div>
                                <div id="myCarousel2" class="carousel slide" data-ride="carousel">
                                    <ol id="nav2" class="carousel-indicators nav-slide-yeekee">
                                        <?php foreach ($yeekees as $key => $yeekee) { ?>
                                            <li data-target="#myCarousel2" data-slide-to="<?= $key ?>" class="<?php echo ($key === 0) ? 'active' : ''; ?>">
                                                <span></span>
                                            </li>
                                        <?php } ?>
                                    </ol>
                                    <div class="d-flex justify-content-between py-1">
                                        <a href="#myCarousel2" role="button" data-slide="prev" class="btn btn-dark btn-sm">
                                            <span><i class="fas fa-chevron-left"></i> Previous</span>
                                        </a>
                                        <a href="#myCarousel2" role="button" data-slide="next" class="btn btn-dark btn-sm">
                                            <span>Next <i class="fas fa-chevron-right"></i></span>
                                        </a>
                                    </div>
                                    <div class="carousel-inner" role="listbox">
                                        <?php foreach ($yeekees as $key => $yeekee) { ?>
                                            <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                                <div class="card border-light-2 text-center shadow-sm mb-2">
                                                    <div class="card-header text-blue bg-header p-1">
                                                        จับยี่กี VIP - รอบที่ <?= $yeekee->round ?>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="d-flex flex-row">
                                                            <div class="card text-center w-50 border-card-right m-0">
                                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                                    3ตัวบน<br>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <p class="card-text">
                                                                        <?= empty($yeekee->getResults('three_top')) ? 'xxx' : $yeekee->getResults('three_top') ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="card text-center w-50 border-card-right m-0">
                                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                                    2ตัวล่าง<br>

                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <p class="card-text">
                                                                        <?= empty($yeekee->getResults('two_under')) ? 'xx' : $yeekee->getResults('two_under') ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-long" role="tabpanel" aria-labelledby="pills-long-tab">
                            <div class="row px-0 m-0">
                                <?php foreach ($yeekees as $key => $yeekee) { ?>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-3 px-1">
                                        <div class="card border-light-2 text-center shadow-sm mb-2">
                                            <div class="card-header text-blue bg-header p-1">
                                                จับยี่กี VIP - รอบที่ <?= $yeekee->round ?>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="d-flex flex-row">
                                                    <div class="card text-center w-50 border-card-right m-0">
                                                        <div class="card-header sub-card-header bg-transparent p-0">
                                                            3ตัวบน<br>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <p class="card-text">
                                                                <?= empty($yeekee->getResults('three_top')) ? 'xxx' : $yeekee->getResults('three_top') ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card text-center w-50 border-card-right m-0">
                                                        <div class="card-header sub-card-header bg-transparent p-0">
                                                            2ตัวล่าง<br>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <p class="card-text">
                                                                <?= empty($yeekee->getResults('two_under')) ? 'xx' : $yeekee->getResults('two_under') ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="pc-view">
                        <div class="row px-0 m-0">
                            <?php foreach ($yeekees as $key => $yeekee) { ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3 px-1">
                                    <div class="card border-light-2 text-center shadow-sm mb-2">
                                        <div class="card-header text-blue bg-header p-1">
                                            จับยี่กี VIP - รอบที่ <?= $yeekee->round ?> </div>
                                        <div class="card-body p-0">
                                            <div class="d-flex flex-row">
                                                <div class="card text-center w-50 border-card-right m-0">
                                                    <div class="card-header sub-card-header bg-transparent p-0">
                                                        3ตัวบน<br>

                                                    </div>
                                                    <div class="card-body p-0">
                                                        <p class="card-text">
                                                            <?= empty($yeekee->getResults('three_top')) ? 'xxx' : $yeekee->getResults('three_top') ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="card text-center w-50 border-card-right m-0">
                                                    <div class="card-header sub-card-header bg-transparent p-0">
                                                        2ตัวล่าง<br>

                                                    </div>
                                                    <div class="card-body p-0">
                                                        <p class="card-text">
                                                            <?= empty($yeekee->getResults('two_under')) ? 'xx' : $yeekee->getResults('two_under') ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end contentbox -->

        </div>

        <footer class="page-footer font-small bg-danger pt-1 mt-5">
            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">© 2021 Copyright -
                <a href="<?= \yii\helpers\Url::to(['site/index'])?>"> RUAYDEE96.COM</a>
            </div>
            <!-- Copyright -->
        </footer>

        <div class="btn-group button-select-category">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                <i class="fas fa-times"></i> <i class="fas fa-search"></i>
            </button>
            <div class="dropdown-menu" x-placement="top-start"
                 style="position: absolute; transform: translate3d(0px, -2px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a href="#government" id="bt-government" class="dropdown-item"><i class="fas fa-crown"></i>
                    หวยรัฐบาลไทย</a>
                <a href="#thaiStock" id="bt-thaiStock" class="dropdown-item"><i class="fas fa-star"></i> หวยหุ้นไทย</a>
                <a href="#foreignStock" id="bt-foreignStock" class="dropdown-item"><i class="far fa-star"></i>
                    หวยหุ้นต่างประเทศ</a>
                <a id="back-top" class="dropdown-item text-dark" href="#top"><i class="fas fa-arrow-up"></i>
                    กลับไปด้านบน</a>
            </div>
        </div>
    </div>
<?php $js = <<< EOT
$(function () {
        $(window).on('load', function () {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "10000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "show",
                "hideMethod": "hide"
            };
        });
    });
EOT;
$this->registerJs($js); ?>