<?php
/* @var $thaiLotterys array */
/* @var $now string */
/* @var $foreignLotterys array */
/* @var $date string */

/* @var $gameLotterys array */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\ThaiSharedGame;
use yii\helpers\ArrayHelper;

?>
<div class="bar-back my-2 radius-15 border">
    <a href="<?= Url::to(['site/home']) ?>" class="text-blue-2"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div
    class="bgwhitealpha text-secondary radius-15 shadow-sm rounded p-2 py-2 px-2 xtarget col-lotto d-flex flex-row mb-2 pb-0">
    <div class="lotto-title">
        <h4><i class="fas fa-award"></i> <b>ผลรางวัล</b></h4>
    </div>
</div>
<div class="align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">

    <div class="bg-white p-2 rounded shadow-sm w-100 mb-1 d-none">
        <?php ActiveForm::begin([
            'action' => ['report'],
            'class' => 'form-horizontal',
            'method' => 'get'
        ]) ?>
        <label><i class="fas fa-calendar-alt"></i> วันที่</label>
        <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
            <input type="date" class="form-control datetimepicker-input mb-0" name="date"
                value="<?= date('Y-m-d', strtotime($date)) ?>">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">ตกลง</button>
            </span>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="text-secondary xtarget col-lotto radius-15">
        <section id="contentbox">

            <div class="row px-2">
                <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                    <?php if ($yeekeeLastActive) { ?>
                    <div class="bgwhitealpha border-danger shadow-sm rounded p-2 h-100">
                        <h6 class="text-blue-2"><i class="far fa-gem"></i> จับยี่กี - รอบที่
                            <?= $yeekeeLastActive->round ?>
                            <span class="badge badge-danger"><?= $now ?></span>
                        </h6>
                        <div class="row">
                            <div class="col pr-1">
                                <div class="card border-danger text-center">
                                    <div class="card-header text-blue-2 p-1">
                                        3ตัวบน<br>
                                    </div>
                                    <div class="card-body p-1">
                                        <p class="card-text">
                                            <?= empty($yeekeeLastActive->getResults('three_top')) ? 'xxx' : $yeekeeLastActive->getResults('three_top') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col pl-1">
                                <div class="card border-danger text-center">
                                    <div class="card-header text-blue-2 p-1">
                                        2ตัวล่าง<br>
                                    </div>
                                    <div class="card-body p-1">
                                        <p class="card-text">
                                            <?= empty($yeekeeLastActive->getResults('two_under')) ? 'xx' : $yeekeeLastActive->getResults('two_under') ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                    <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget radius-15" id="government">
                        <h6><i class="fas fa-crown"></i> หวยรัฐบาลไทย
                            <span class="badge badge-dark">
                                <?= $gameLotterys['date'] ?>
                            </span>
                        </h6>
                        <?php if (isset($gameLotterys['three_front']['number']) && isset($gameLotterys['three_back']['number']) && isset($gameLotterys['two_under']['number'])) { ?>
                        <div class="card border-dark text-center mb-2">
                            <div class="card-header text-blue-2 p-1">
                                หวยรัฐบาลไทย
                            </div>
                            <div class="card-body p-1">
                                <div class="row">
                                    <div class="col-12 border-bottom">
                                        <p class="card-text mb-0">3 ตัวบน</p>
                                        <h4><?= $gameLotterys['firstResult'] ?></h4>
                                    </div>
                                    <div class="col pr-0">
                                        <div class="card text-center border-0">
                                            <div class="card-header text-dark p-1  border-bottom-0">
                                                <?= $gameLotterys['three_front']['title'] ?><br>
                                            </div>
                                            <div class="card-body p-1">
                                                <h4 class="card-text"><?= $gameLotterys['three_front']['number'] ?></h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col px-0">
                                        <div class="card text-center border-0">
                                            <div class="card-header text-dark p-1 border-left border-right border-bottom-0">
                                                <?= $gameLotterys['three_back']['title'] ?><br>
                                            </div>
                                            <div class="card-body p-1 border-left border-right">
                                                <h4 class="card-text"><?= $gameLotterys['three_back']['number'] ?></h4>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col pl-0">
                                        <div class="card text-center border-0">
                                            <div class="card-header text-dark p-1 border-bottom-0">
                                                <?= $gameLotterys['two_under']['title'] ?><br>
                                            </div>
                                            <div class="card-body p-1">
                                                <h4 class="card-text"><?= $gameLotterys['two_under']['number'] ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <?php } ?>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 mb-2 px-1">
                    <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget" id="government">
                        <h6>
                            <i class="fas fa-crown"></i> หวยออมสิน <span
                                class="badge badge-dark"><?= $gsb['date'] ?></span>
                        </h6>
                        <?php if (isset($gsb['three_top']['title']) && isset($gsb['two_under']['number'])) { ?>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 px-1">
                            <div class="card border-dark text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
                                    รางวัลเลขท้าย 6 ตัว
                                </div>
                                <div class="card-body p-1">
                                    <p class="card-text">
                                        <?= $gsb['result'] !== '' && $gsb['result'] !== null ? $gsb['result'] : 'xxxxxxx,xxxxxxx' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="card border-dark text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
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
                        <?php } ?>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-6 mb-2 px-1">
                    <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget" id="government">
                        <h6>
                            <i class="fas fa-crown"></i> หวย ธกส <span
                                class="badge badge-dark"><?= $bacc['date'] ?></span>
                        </h6>
                        <?php if (isset($bacc['three_top']['title']) && isset($bacc['two_under']['number'])) { ?>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 px-1">
                            <div class="card border-dark text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
                                    เลขที่ออก
                                </div>
                                <div class="card-body p-1">
                                    <p class="card-text">
                                        <?= $bacc['result'] !== '' && $bacc['result'] !== null ? $bacc['result'] : 'xxxxxx' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="card border-dark text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
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
                        <?php } ?>
                    </div>
                </div>

                
            </div>

            <?php if(!empty($thaiLotterys)): ?>
            <div class="bgwhitealpha shadow-sm rounded p-2 xtarget" id="thaiStock">
                <h6><i class="fas fa-star"></i> หวยหุ้นไทย <span class="badge badge-dark"><?= $now ?></span></h6>

                <div class="card-group">
                    <!-- end หุ้นไทยบ่าย -->
                    <?php foreach ($thaiLotterys as $thaiLottery) { ?>
                    <div class="col-sm-12 col-md-6 col-lg-6 mb-2 px-1">
                        <div class="card border-dark text-center mb-8">
                            <div class="card-header text-blue-2 p-1">
                                <span
                                    class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($thaiLottery['title']), 'icon') ?>"></span>
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
                <h6><i class="far fa-star"></i> หวยหุ้นต่างประเทศ <span class="badge badge-dark"><?= $now ?></span></h6>
                <div class="carousel-view">
                    <div id="myCarousel1" class="carousel slide" data-ride="carousel">
                        <ol id="nav1" class="carousel-indicators nav-slide">
                            <?php foreach ($foreignLotterys as $key => $foreignLottery) { ?>
                            <li data-target="#myCarousel1" data-slide-to="<?= $key ?>">
                                <span
                                    class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
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
                                <div class="card border-dark text-center mb-2">
                                    <div class="card-header text-blue-2 p-1">
                                        <span
                                            class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
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
                                                    <p class="card-text"><?= $foreignLottery['three_top']['number'] ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="card text-center w-50 border-card-right m-0">
                                                <div class="card-header sub-card-header bg-transparent p-0">
                                                    <?= $foreignLottery['two_under']['title'] ?><br>
                                                    <!-- <small>(90)</small> -->
                                                </div>
                                                <div class="card-body p-0">
                                                    <p class="card-text"><?= $foreignLottery['two_under']['number'] ?>
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
                </div><!-- end carousel-view -->
                <div class="pc-view">
                    <div class="row px-0 m-0">
                        <?php foreach ($foreignLotterys as $foreignLottery) { ?>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 px-1">
                            <div class="card border-dark text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
                                    <span
                                        class="<?= ArrayHelper::getValue(ThaiSharedGame::instance()->getOptions($foreignLottery['title']), 'icon') ?>"></span>
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
            <?php endif ?>

            <div class="bgwhitealpha shadow-sm rounded p-2 xtarget" id="yeekee">
                <h6><i class="fas fa-trophy"></i> จับยี่กี <span class="badge badge-dark"><?= $now ?></span></h6>

                <div class="mobile-view">
                    <ul class="nav nav-pills mb-3 w-100 bg-white rounded p-1" id="pills-tab" role="tablist">
                        <li class="nav-item w-50">
                            <a class="nav-link active btn btn-outline-dark btn-sm mx-1" id="pills-short-tab"
                                data-toggle="pill" href="#pills-short" role="tab" aria-controls="pills-short"
                                aria-selected="true">มุมมองสั้น</a>
                        </li>
                        <li class="nav-item w-50">
                            <a class="nav-link btn btn-outline-dark btn-sm mx-1" id="pills-long-tab" data-toggle="pill"
                                href="#pills-long" role="tab" aria-controls="pills-long"
                                aria-selected="false">มุมมองยาว</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-short" role="tabpanel"
                        aria-labelledby="pills-short-tab">
                        <div class="carousel-view">
                            <div class="bg-danger p-1 my-1 text-center text-white"></div>
                            <div id="myCarousel2" class="carousel slide" data-ride="carousel">
                                <ol id="nav2" class="carousel-indicators nav-slide-yeekee">
                                    <?php foreach ($yeekees as $key => $yeekee) { ?>
                                    <li data-target="#myCarousel2" data-slide-to="<?= $key ?>"
                                        class="<?= $key === 0 ? 'active' : '' ?>">
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
                                        <div class="card border-secondary text-center mb-2">
                                            <div class="card-header text-blue-2 p-1">
                                                จับยี่กี - รอบที่ <?= $yeekee->round ?>
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
                                <div class="card border-secondary text-center mb-2">
                                    <div class="card-header text-blue-2 p-1">
                                        จับยี่กี - รอบที่ <?= $yeekee->round ?>
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
                            <div class="card border-secondary text-center mb-2">
                                <div class="card-header text-blue-2 p-1">
                                    จับยี่กี - รอบที่ <?= $yeekee->round ?> </div>
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

    </div>
</div>

<div class="btn-group button-select-category">
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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