<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
/* @var $playTypes array */
/* @var $betListDetail array */
use yii\helpers\Url;
use common\libs\Constants;

$timeStampNow = strtotime(date('Y-m-d H:i:s')) * 1000;
$numberMemoUrl = Url::to(['/api-number-memo']);
$numberMemoDetailUrl = Url::to(['/api-number-memo/detail']);
$preSendPoyUrl = Url::to(['/api-poy/pre-send-poy']);
$buyPoyUrl = Url::to(['/api-poy/buy']);
$printPoyUrl = Url::to(['print-poy']);
$myPoyMemoUrl = Url::to(['/api-number-memo/my-poy']);
$myPoyDetailMemoUrl = Url::to(['/api-number-memo/my-poy-detail']);
$js = <<<EOT
    var bet_list_detail = '$betListDetail';
    var ying_only = false;
    var timex = $timeStampNow;
    var playId = $thaiSharedGame->id;
    var numberMemoUrl = '$numberMemoUrl';
    var numberMemoDetailUrl = '$numberMemoDetailUrl';
    var preSendPoyUrl = '$preSendPoyUrl';
    var buyPoyUrl = '$buyPoyUrl';
    var printPoyUrl = '$printPoyUrl';
    var myPoyMemoUrl = '$myPoyMemoUrl';
    var myPoyDetailMemoUrl = '$myPoyDetailMemoUrl';
    let localStorageTimeout = 15 * 1000; // 15,000 milliseconds = 15 seconds.
    let localStorageResetInterval = 10 * 1000; // 10,000 milliseconds = 10 seconds.
    let localStorageTabKey = 'test-application-browser-tab';
    let sessionStorageGuidKey = 'browser-tab-guid';
    
    function createGUID() {
      let guid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
        /*eslint-disable*/
        let r = Math.random() * 16 | 0,
          v = c == 'x' ? r : (r & 0x3 | 0x8);
        /*eslint-enable*/
        return v.toString(16);
      });
    
      return guid;
    }
    
    /**
     * Compare our tab identifier associated with this session (particular tab)
     * with that of one that is in localStorage (the active one for this browser).
     * This browser tab is good if any of the following are true:
     * 1.  There is no localStorage Guid yet (first browser tab).
     * 2.  The localStorage Guid matches the session Guid.  Same tab, refreshed.
     * 3.  The localStorage timeout period has ended.
     *
     * If our current session is the correct active one, an interval will continue
     * to re-insert the localStorage value with an updated timestamp.
     *
     * Another thing, that should be done (so you can open a tab within 15 seconds of closing it) would be to do the following (or hook onto an existing onunload method):
     *		window.onunload = () => { 
                    localStorage.removeItem(localStorageTabKey);
          };
     */
    function testTab() {
      let sessionGuid = sessionStorage.getItem(sessionStorageGuidKey) || createGUID();
      let tabObj = JSON.parse(localStorage.getItem(localStorageTabKey)) || null;
    
        sessionStorage.setItem(sessionStorageGuidKey, sessionGuid);
    
      // If no or stale tab object, our session is the winner.  If the guid matches, ours is still the winner
      if (tabObj === null || (tabObj.timestamp < new Date().getTime() - localStorageTimeout) || tabObj.guid === sessionGuid) {
        function setTabObj() {
          let newTabObj = {
            guid: sessionGuid,
            timestamp: new Date().getTime()
          };
          localStorage.setItem(localStorageTabKey, JSON.stringify(newTabObj));
        }
        setTabObj();
        setInterval(setTabObj, localStorageResetInterval);
        return true;
      } else {
        // An active tab is already open that does not match our session guid.
        return false;
      }
    }
EOT;
$this->registerJs($js, \yii\web\View::POS_HEAD);
$this->registerJs('$(function () {
    define_poy();
    show_bet_num();
    if (!testTab()) {
      swal({
        title:"กรุณาใช้งานเพียง tap เดียวเท่านั้น"
      });
    }
});', \yii\web\View::POS_READY);
$this->registerJsFile(Yii::getAlias('@web/version6/js/index/pagebet.js?5029816306'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web/version6/css/sweetalert2.min.css'));
$this->registerJsFile(Yii::getAlias('@web/version6/js/sweetalert2.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/tether.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="bar-back d-flex justify-content-between align-items-center">
    <div id="top"></div>
    <a href="../thai-shared-chit/index" class="mr-auto" id="yingying" onclick="removecc('../')">
        <i class="fas fa-chevron-left"></i> ย้อนกลับ
    </a>
    <a href="../thai-shared-chit/index" class="btn btn-outline-secondary btn-sm mr-1 text-dark" data-toggle="modal"
        data-target="#rule-yeekee">
        กติกา
    </a>
    <a href="#" class="btn btn-dark btn-sm btn-numpage d-none active" data-id="numpage_1" onclick="yingying('../')">
        <i class="fas fa-calculator"></i>ย้อนกลับ
    </a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch d-flex flex-column align-items-stretch"
    style="min-height: calc((100vh - 150px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-0 pb-0">
        <div class="d-flex justify-content-between align-items-center mb-0 flex-fill lotto-title">
            <h4 class="tanghuay-h4"><?= $thaiSharedGame->title ?>
                <span
                    class="badge badge-pill badge-danger font-weight-light"><small><?= date('d M Y', strtotime($thaiSharedGame->endDate)) ?></small>
                </span>
            </h4>
            <div class="tanghuay-time">
                <i class="sn-icon sn-icon--daily2"></i>
                <span class="countdown-number countdown"
                    data-finaldate="<?= strtotime($thaiSharedGame->endDate) * 1000 ?>">
                    <?= date("H:i:s", strtotime($thaiSharedGame->endDate)) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row-menu-grid">
        <div class="d-flex flex-row justify-content-between align-items-stretch p-1 mt-1 pr-2 bg-white rounded shadow-sm flex-fill cart-item-lists"
            id="numpage_1">
            <div class="d-flex flex-column flex-fill box__play" id="show_poy_list">
                <div id="hide-list-huay">
                    <a class="btn-move btn-move-left mb-1">
                        <span><i class="fas fa-chevron-circle-left"></i> ซ่อน</span>
                    </a>
                </div>
                <div class="sidebar-huay d-flex flex-column flex-fill" id="sidebar-huay">
                    <button class="btn btn-light btn-sm btn-block number-sets triggerPoy">
                        <i class="fas fa-list-ol"></i> ดึงโพย </button>
                    <button class="btn btn-danger btn-sm btn-block show-lists triggerPrice">
                        <i class="fas fa-edit"></i> ใส่ราคา </button>
                    <h5 class="title-huay mt-2"><i class="fas fa-receipt"></i> รายการแทง</h5>
                    <span class="bet_num_count"></span>
                    <div class="list-huay flex-fill">
                        <ol class="num-huay" id="show_bet_num"></ol>
                    </div>
                </div>
            </div>
            <div class="sidebar-tanghuay box__play" id="page_tang">
                <div id="show-list-huay" class="d-flex justify-content-between" style="width:0px; overflow:hidden;">
                    <span class="btn-move bet_num_count justify-content-center rounded"></span>
                    <a class="btn-move btn-move-right flex-fill">
                        <span>แสดง <i class="fas fa-chevron-circle-right"></i></span>
                    </a>
                </div>
                <nav>
                    <div class="nav nav-tabs nav-pills nav-justified" id="nav-tab1" role="tablist">
                        <a class="nav-item nav-link  p-1" id="nav-keyboard-tab" data-toggle="tab" href="#nav-keyboard"
                            role="tab" aria-controls="nav-keyboard" aria-selected="false">กดเลขเอง</a>
                        <a class="nav-item nav-link active p-1" id="nav-panghuay-tab" data-toggle="tab"
                            href="#nav-panghuay" role="tab" aria-controls="nav-panghuay"
                            aria-selected="true">เลือกจากแผง</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade" id="nav-keyboard" role="tabpanel" aria-labelledby="nav-keyboard-tab">

                        <!-- กดเลขเอง -->
                        <div class="row px-2 row-btn-tanghuay setting__number">
                            <?php foreach ($playTypes as $code => $playType) {
                            if ($code === 'teng_bon_3' || $code === 'tode_3' || $code === 'teng_lang_3' || $code === 'teng_lang_nha_3') {
                                $buttonClassPlayType = 'bg-danger text-white';
                                $buttonClassPlayTypeName = 'btn btn-outline-red btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-tanghuay';
                            } elseif ($code === 'teng_bon_2' || $code === 'teng_lang_2') {
                                $buttonClassPlayType = 'bg-primary text-white';
                                $buttonClassPlayTypeName = 'btn btn-outline-blue btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-tanghuay bet_two';
                            } else {
                                $buttonClassPlayType = 'bg-success text-white';
                                $buttonClassPlayTypeName = 'btn btn-outline-green btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-tanghuay bet_run';
                            }
                            if ($code === 'teng_bon_1' && $thaiSharedGame->gameId !== Constants::LOTTERYGAMEDISCOUNT &&  $thaiSharedGame->gameId !== Constants::LOTTERYGAME || $code === 'teng_lang_nha_3') { ?>
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-1">
                                <button
                                    class="btn btn-outline-red btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-tanghuay"
                                    id="shuffle_3">
                                    <div class="bg-danger text-white">
                                        <i><small>3</small></i><i>ตัวกลับ</i>
                                    </div>
                                    <div class="text-center"><i class="fas fa-random"></i></div>
                                </button>
                            </div>
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-1">
                                <button
                                    class="btn btn-outline-blue btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-tanghuay bet_two"
                                    id="shuffle_2">
                                    <div class="bg-primary text-white"><i><small>2</small></i><i>ตัวกลับ</i></div>
                                    <div class="text-center"><i class="fas fa-random"></i></div>
                                </button>
                            </div>
                            <?php } ?>
                            <div class="col-6 col-sm-6 col-md-4 col-lg-3 p-1">
                                <button class="<?= $buttonClassPlayTypeName ?>" id="<?= $code ?>">
                                    <div class="<?= $buttonClassPlayType ?>">
                                        <i><?= $playType['title'] ?></i>
                                    </div>
                                    <div><?= $playType['jackpot_per_unit'] ?></div>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="bg-option p-1 pb-0 mt-1 rounded box__two-option box__play setting__number"
                            id="2option" style="display:none;">
                            <i class="fas fa-bars"></i> ตัวเลือกเพิ่มเติม
                            <div class="d-flex flex-lg-row justify-content-around align-content-stretch flex-wrap">
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-danger btn-sm btn-block h-100 bet_two option2btn"
                                            id="option_2_19">
                                            19 ประตู
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-danger btn-sm btn-block h-100 bet_two" id="option_2_ble">
                                            เลขเบิ้ล
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-primary btn-sm btn-block h-100 bet_two option2btn"
                                            id="option_2_roodnha">
                                            รูดหน้า
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-primary btn-sm btn-block h-100 bet_two option2btn"
                                            id="option_2_roodlung">
                                            รูดหลัง
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-info btn-sm btn-block h-100 bet_two" id="option_2_low">
                                            สองตัวต่ำ
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-info btn-sm btn-block h-100 bet_two" id="option_2_high">
                                            สองตัวสูง
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-success btn-sm btn-block h-100 bet_two"
                                            id="option_2_odd">
                                            สองตัวคี่
                                        </button>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <div class="bg-btn">
                                        <button class="btn btn-success btn-sm btn-block h-100 bet_two"
                                            id="option_2_even">
                                            สองตัวคุ่
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 bg-white px-1 pb-1 text-center border my-1">
                            <p class="border-bottom mb-1">
                                <small>รายการที่เลือก</small>
                            </p>
                            <div id="resultoption">
                                <span class="badge badge-danger_bkk font-weight-light d-none" id="teng_bon_3_label">
                                    3ตัวบน
                                </span>
                                <span class="badge badge-danger_bkk font-weight-light d-none" id="tode_3_label">
                                    3ตัวโต๊ด
                                </span>
                                <span class="badge badge-danger_bkk font-weight-light d-none" id="shuffle_3_label">
                                    กลับ<br>3ตัว
                                </span>
                                <span class="badge badge-danger_bkk font-weight-light d-none" id="teng_lang_3_label">
                                    3ตัวล่าง
                                </span>
                                <span class="badge badge-danger_bkk font-weight-light d-none"
                                    id="teng_lang_nha_3_label">
                                    3ตัวหน้า
                                </span>
                                <span class="badge badge-primary_bkk font-weight-light d-none" id="teng_bon_2_label">
                                    2ตัวบน
                                </span>
                                <span class="badge badge-primary_bkk font-weight-light d-none" id="teng_lang_2_label">
                                    2ตัวล่าง
                                </span>
                                <span class="badge badge-primary_bkk font-weight-light d-none" id="shuffle_2_label">
                                    กลับ<br>2ตัว
                                </span>
                                <span class="badge badge-success_bkk font-weight-light d-none" id="teng_bon_1_label">
                                    วิ่งบน
                                </span>
                                <span class="badge badge-success_bkk font-weight-light d-none" id="teng_lang_1_label">
                                    วิ่งล่าง
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_19_label">
                                    19 ประตู
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_ble_label">
                                    เลขเบิ้ล
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none"
                                    id="option_2_roodnha_label">
                                    รูดหน้า
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none"
                                    id="option_2_roodlung_label">
                                    รูดหลัง
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_low_label">
                                    สองตัวต่ำ
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_high_label">
                                    สองตัวสูง
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_odd_label">
                                    สองตัวคี่
                                </span>
                                <span class="badge badge-secondary font-weight-light d-none" id="option_2_even_label">
                                    สองตัวคุ่
                                </span>
                            </div>
                        </div>
                        <div class="group__keyboard">
                            <div class="num-pad box__show-number">
                                <h4 class="text-center">ระบุตัวเลข</h4>
                                <div class="label-number lists">
                                    <div id="bet_num">
                                        <label class="number"></label>
                                        <label class="number"></label>
                                        <label class="number"></label>
                                    </div>
                                </div>
                                <div class="key-pad box__keyboard">
                                    <div class="row p-2">
                                        <?php
                                    $number = 1;
                                    for ($number = 1; $number <= 3; $number++) { ?>
                                        <div class="col-3">
                                            <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                <?= $number ?>
                                            </button>
                                        </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button class="btn btn-danger_bkk btn-block" data-id="delete">
                                                <i class="fas fa-backspace"></i>
                                            </button>
                                        </div>
                                        <?php
                                    $number = 4;
                                    for ($number = 4; $number <= 6; $number++) { ?>
                                        <div class="col-3">
                                            <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                <?= $number ?>
                                            </button>
                                        </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button
                                                class="btn btn-secondary btn-block btn-cancel warning btn-cancle-last-add-num">
                                                <span>ยกเลิก</span><span>ล่าสุด</span>
                                            </button>
                                        </div>
                                        <?php
                                    $number = 7;
                                    for ($number = 7; $number <= 9; $number++) { ?>
                                        <div class="col-3">
                                            <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                <?= $number ?>
                                            </button>
                                        </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button class="btn btn-dark btn-block btn-reset">
                                                <span>ล้าง</span><span>ข้อมูล</span>
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-blank" disabled=""></button>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-outline-primary btn-block" data-id="0">0</button>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-blank" disabled=""></button>
                                        </div>
                                        <div class="col-3">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- กดเลขเอง -->

                    </div>
                    <div class="tab-pane fade show active" id="nav-panghuay" role="tabpanel"
                        aria-labelledby="nav-panghuay-tab">
                        <div class="w-100 p-1">
                            <ul class="nav nav-pills nav-justified mb-2" id="number-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link p-1 py-3 active btn-outline-red" id="pills-3-tab"
                                        data-toggle="pill" href="#pills-3" role="tab" aria-controls="pills-3"
                                        aria-selected="true">สามตัว</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link p-1 py-3 btn-outline-blue" id="pills-2-tab" data-toggle="pill"
                                        href="#pills-2" role="tab" aria-controls="pills-2"
                                        aria-selected="false">สองตัว</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link p-1 py-3 btn-outline-green" id="pills-run-tab" data-toggle="pill"
                                        href="#pills-run" role="tab" aria-controls="pills-run"
                                        aria-selected="false">เลขวิ่ง</a>
                                </li>
                            </ul>
                            <div class="w-100 limitnum" style="display: none">
                                <div class="row mb-2 py-3 mx-0 bg-dark rounded">
                                    <div class="col pr-1">
                                        <font color="#FFCC00"><b><i class="fas fa-exclamation-circle"></i> สีเหลือง
                                                = เลขนี้มีคนแทงเยอะอัตราจ่ายอาจมีการเปลี่ยนแปลง<b></font>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="pills-tabContent-number">

                                <div class="tab-pane fade show active" id="pills-3" role="tabpanel"
                                    aria-labelledby="pills-3-tab">

                                    <div class="row m-0">
                                        <div class="col-12 col-sm-12 col-md-8 p-1">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="ค้นหาตัวเลข"
                                                    aria-label="ค้นหาตัวเลข" aria-describedby="button-addon2"
                                                    id="search-number">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="button-addon2"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-4 p-1">
                                            <div class="row-btn-tanghuay">
                                                <button
                                                    class="btn btn-outline-secondary btn-block btn-sm btn-panghuay py-2"
                                                    id="shuffle_3"><i class="fas fa-random"></i> กลับตัวเลข
                                                </button>
                                            </div>
                                        </div>

                                        <?php foreach ($playTypes as $code => $playType) {
                                            if ($code === 'teng_bon_3') {
                                                $buttonClassPlayType = 'bg-danger text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-red btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_three active';
                                            }

                                            if ( $code === 'tode_3') {
                                                $buttonClassPlayType = 'bg-danger text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-red btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_three';
                                            }
                                           
                                            if ($code === 'teng_bon_3'|| $code === 'tode_3') { ?>

                                        <div class="col-6 p-1">
                                            <div class="row-btn-tanghuay">
                                                <button class="<?= $buttonClassPlayTypeName ?>" id="<?= $code ?>">
                                                    <div class="<?= $buttonClassPlayType ?>">
                                                        <i><?= $playType['title'] ?></i>
                                                    </div>
                                                    <div><?= $playType['jackpot_per_unit'] ?></div>
                                                </button>
                                            </div>
                                        </div>
                                        <?php } } ?>
                                    </div>

                                    <div id="samtua">
                                        <div class="" id="oversamtua"></div>
                                        <ul class="nav nav-pills mb-3 justify-content-center" id="numlist-tab1"
                                            role="tablist">
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num active show" id="tab-000"
                                                    data-toggle="pill" href="#pills-000" role="tab"
                                                    aria-controls="pills-000" aria-selected="true">000</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-100"
                                                    data-toggle="pill" href="#pills-100" role="tab"
                                                    aria-controls="pills-100" aria-selected="false">100</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-200"
                                                    data-toggle="pill" href="#pills-200" role="tab"
                                                    aria-controls="pills-200" aria-selected="false">200</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-300"
                                                    data-toggle="pill" href="#pills-300" role="tab"
                                                    aria-controls="pills-300" aria-selected="false">300</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-400"
                                                    data-toggle="pill" href="#pills-400" role="tab"
                                                    aria-controls="pills-400" aria-selected="false">400</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-500"
                                                    data-toggle="pill" href="#pills-500" role="tab"
                                                    aria-controls="pills-500" aria-selected="false">500</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num" id="tab-600"
                                                    data-toggle="pill" href="#pills-600" role="tab"
                                                    aria-controls="pills-600" aria-selected="false">600</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-700"
                                                    data-toggle="pill" href="#pills-700" role="tab"
                                                    aria-controls="pills-700" aria-selected="false">700</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-800"
                                                    data-toggle="pill" href="#pills-800" role="tab"
                                                    aria-controls="pills-800" aria-selected="false">800</a>
                                            </li>
                                            <li class="nav-item flex-fill text-center">
                                                <a class="nav-link border m-1 px-1 txt-num " id="tab-900"
                                                    data-toggle="pill" href="#pills-900" role="tab"
                                                    aria-controls="pills-900" aria-selected="false">900</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="numlist-tabContent1">
                                            <div class="text-center tab-pane fade active show" id="pills-000"
                                                role="tabpanel" aria-labelledby="pills-000-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="000">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="000">
                                                        000
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="001">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="001">
                                                        001
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="002">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="002">
                                                        002
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="003">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="003">
                                                        003
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="004">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="004">
                                                        004
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="005">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="005">
                                                        005
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="006">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="006">
                                                        006
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="007">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="007">
                                                        007
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="008">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="008">
                                                        008
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="009">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="009">
                                                        009
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="010">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="010">
                                                        010
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="011">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="011">
                                                        011
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="012">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="012">
                                                        012
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="013">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="013">
                                                        013
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="014">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="014">
                                                        014
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="015">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="015">
                                                        015
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="016">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="016">
                                                        016
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="017">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="017">
                                                        017
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="018">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="018">
                                                        018
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="019">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="019">
                                                        019
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="020">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="020">
                                                        020
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="021">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="021">
                                                        021
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="022">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="022">
                                                        022
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="023">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="023">
                                                        023
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="024">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="024">
                                                        024
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="025">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="025">
                                                        025
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="026">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="026">
                                                        026
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="027">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="027">
                                                        027
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="028">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="028">
                                                        028
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="029">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="029">
                                                        029
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="030">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="030">
                                                        030
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="031">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="031">
                                                        031
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="032">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="032">
                                                        032
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="033">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="033">
                                                        033
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="034">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="034">
                                                        034
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="035">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="035">
                                                        035
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="036">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="036">
                                                        036
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="037">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="037">
                                                        037
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="038">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="038">
                                                        038
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="039">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="039">
                                                        039
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="040">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="040">
                                                        040
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="041">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="041">
                                                        041
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="042">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="042">
                                                        042
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="043">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="043">
                                                        043
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="044">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="044">
                                                        044
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="045">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="045">
                                                        045
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="046">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="046">
                                                        046
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="047">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="047">
                                                        047
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="048">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="048">
                                                        048
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="049">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="049">
                                                        049
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="050">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="050">
                                                        050
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="051">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="051">
                                                        051
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="052">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="052">
                                                        052
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="053">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="053">
                                                        053
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="054">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="054">
                                                        054
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="055">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="055">
                                                        055
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="056">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="056">
                                                        056
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="057">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="057">
                                                        057
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="058">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="058">
                                                        058
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="059">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="059">
                                                        059
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="060">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="060">
                                                        060
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="061">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="061">
                                                        061
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="062">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="062">
                                                        062
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="063">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="063">
                                                        063
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="064">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="064">
                                                        064
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="065">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="065">
                                                        065
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="066">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="066">
                                                        066
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="067">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="067">
                                                        067
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="068">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="068">
                                                        068
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="069">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="069">
                                                        069
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="070">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="070">
                                                        070
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="071">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="071">
                                                        071
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="072">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="072">
                                                        072
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="073">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="073">
                                                        073
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="074">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="074">
                                                        074
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="075">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="075">
                                                        075
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="076">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="076">
                                                        076
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="077">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="077">
                                                        077
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="078">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="078">
                                                        078
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="079">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="079">
                                                        079
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="080">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="080">
                                                        080
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="081">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="081">
                                                        081
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="082">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="082">
                                                        082
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="083">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="083">
                                                        083
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="084">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="084">
                                                        084
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="085">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="085">
                                                        085
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="086">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="086">
                                                        086
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="087">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="087">
                                                        087
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="088">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="088">
                                                        088
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="089">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="089">
                                                        089
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="090">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="090">
                                                        090
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="091">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="091">
                                                        091
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="092">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="092">
                                                        092
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="093">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="093">
                                                        093
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="094">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="094">
                                                        094
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="095">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="095">
                                                        095
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="096">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="096">
                                                        096
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="097">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="097">
                                                        097
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="098">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="098">
                                                        098
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="099">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="099">
                                                        099
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-100" role="tabpanel"
                                                aria-labelledby="pills-100-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="100">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="100">
                                                        100
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="101">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="101">
                                                        101
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="102">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="102">
                                                        102
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="103">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="103">
                                                        103
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="104">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="104">
                                                        104
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="105">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="105">
                                                        105
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="106">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="106">
                                                        106
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="107">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="107">
                                                        107
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="108">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="108">
                                                        108
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="109">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="109">
                                                        109
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="110">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="110">
                                                        110
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="111">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="111">
                                                        111
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="112">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="112">
                                                        112
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="113">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="113">
                                                        113
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="114">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="114">
                                                        114
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="115">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="115">
                                                        115
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="116">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="116">
                                                        116
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="117">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="117">
                                                        117
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="118">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="118">
                                                        118
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="119">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="119">
                                                        119
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="120">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="120">
                                                        120
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="121">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="121">
                                                        121
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="122">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="122">
                                                        122
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="123">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="123">
                                                        123
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="124">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="124">
                                                        124
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="125">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="125">
                                                        125
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="126">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="126">
                                                        126
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="127">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="127">
                                                        127
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="128">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="128">
                                                        128
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="129">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="129">
                                                        129
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="130">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="130">
                                                        130
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="131">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="131">
                                                        131
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="132">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="132">
                                                        132
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="133">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="133">
                                                        133
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="134">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="134">
                                                        134
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="135">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="135">
                                                        135
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="136">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="136">
                                                        136
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="137">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="137">
                                                        137
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="138">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="138">
                                                        138
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="139">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="139">
                                                        139
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="140">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="140">
                                                        140
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="141">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="141">
                                                        141
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="142">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="142">
                                                        142
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="143">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="143">
                                                        143
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="144">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="144">
                                                        144
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="145">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="145">
                                                        145
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="146">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="146">
                                                        146
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="147">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="147">
                                                        147
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="148">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="148">
                                                        148
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="149">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="149">
                                                        149
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="150">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="150">
                                                        150
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="151">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="151">
                                                        151
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="152">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="152">
                                                        152
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="153">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="153">
                                                        153
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="154">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="154">
                                                        154
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="155">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="155">
                                                        155
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="156">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="156">
                                                        156
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="157">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="157">
                                                        157
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="158">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="158">
                                                        158
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="159">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="159">
                                                        159
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="160">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="160">
                                                        160
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="161">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="161">
                                                        161
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="162">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="162">
                                                        162
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="163">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="163">
                                                        163
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="164">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="164">
                                                        164
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="165">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="165">
                                                        165
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="166">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="166">
                                                        166
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="167">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="167">
                                                        167
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="168">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="168">
                                                        168
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="169">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="169">
                                                        169
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="170">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="170">
                                                        170
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="171">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="171">
                                                        171
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="172">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="172">
                                                        172
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="173">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="173">
                                                        173
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="174">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="174">
                                                        174
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="175">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="175">
                                                        175
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="176">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="176">
                                                        176
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="177">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="177">
                                                        177
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="178">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="178">
                                                        178
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="179">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="179">
                                                        179
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="180">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="180">
                                                        180
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="181">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="181">
                                                        181
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="182">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="182">
                                                        182
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="183">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="183">
                                                        183
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="184">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="184">
                                                        184
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="185">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="185">
                                                        185
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="186">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="186">
                                                        186
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="187">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="187">
                                                        187
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="188">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="188">
                                                        188
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="189">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="189">
                                                        189
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="190">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="190">
                                                        190
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="191">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="191">
                                                        191
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="192">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="192">
                                                        192
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="193">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="193">
                                                        193
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="194">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="194">
                                                        194
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="195">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="195">
                                                        195
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="196">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="196">
                                                        196
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="197">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="197">
                                                        197
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="198">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="198">
                                                        198
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="199">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="199">
                                                        199
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-200" role="tabpanel"
                                                aria-labelledby="pills-200-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="200">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="200">
                                                        200
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="201">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="201">
                                                        201
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="202">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="202">
                                                        202
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="203">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="203">
                                                        203
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="204">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="204">
                                                        204
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="205">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="205">
                                                        205
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="206">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="206">
                                                        206
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="207">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="207">
                                                        207
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="208">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="208">
                                                        208
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="209">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="209">
                                                        209
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="210">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="210">
                                                        210
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="211">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="211">
                                                        211
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="212">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="212">
                                                        212
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="213">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="213">
                                                        213
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="214">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="214">
                                                        214
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="215">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="215">
                                                        215
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="216">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="216">
                                                        216
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="217">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="217">
                                                        217
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="218">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="218">
                                                        218
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="219">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="219">
                                                        219
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="220">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="220">
                                                        220
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="221">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="221">
                                                        221
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="222">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="222">
                                                        222
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="223">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="223">
                                                        223
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="224">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="224">
                                                        224
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="225">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="225">
                                                        225
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="226">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="226">
                                                        226
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="227">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="227">
                                                        227
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="228">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="228">
                                                        228
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="229">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="229">
                                                        229
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="230">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="230">
                                                        230
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="231">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="231">
                                                        231
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="232">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="232">
                                                        232
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="233">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="233">
                                                        233
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="234">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="234">
                                                        234
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="235">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="235">
                                                        235
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="236">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="236">
                                                        236
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="237">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="237">
                                                        237
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="238">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="238">
                                                        238
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="239">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="239">
                                                        239
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="240">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="240">
                                                        240
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="241">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="241">
                                                        241
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="242">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="242">
                                                        242
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="243">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="243">
                                                        243
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="244">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="244">
                                                        244
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="245">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="245">
                                                        245
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="246">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="246">
                                                        246
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="247">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="247">
                                                        247
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="248">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="248">
                                                        248
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="249">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="249">
                                                        249
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="250">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="250">
                                                        250
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="251">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="251">
                                                        251
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="252">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="252">
                                                        252
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="253">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="253">
                                                        253
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="254">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="254">
                                                        254
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="255">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="255">
                                                        255
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="256">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="256">
                                                        256
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="257">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="257">
                                                        257
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="258">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="258">
                                                        258
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="259">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="259">
                                                        259
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="260">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="260">
                                                        260
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="261">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="261">
                                                        261
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="262">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="262">
                                                        262
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="263">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="263">
                                                        263
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="264">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="264">
                                                        264
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="265">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="265">
                                                        265
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="266">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="266">
                                                        266
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="267">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="267">
                                                        267
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="268">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="268">
                                                        268
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="269">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="269">
                                                        269
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="270">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="270">
                                                        270
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="271">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="271">
                                                        271
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="272">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="272">
                                                        272
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="273">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="273">
                                                        273
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="274">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="274">
                                                        274
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="275">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="275">
                                                        275
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="276">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="276">
                                                        276
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="277">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="277">
                                                        277
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="278">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="278">
                                                        278
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="279">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="279">
                                                        279
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="280">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="280">
                                                        280
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="281">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="281">
                                                        281
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="282">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="282">
                                                        282
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="283">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="283">
                                                        283
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="284">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="284">
                                                        284
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="285">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="285">
                                                        285
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="286">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="286">
                                                        286
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="287">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="287">
                                                        287
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="288">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="288">
                                                        288
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="289">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="289">
                                                        289
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="290">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="290">
                                                        290
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="291">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="291">
                                                        291
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="292">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="292">
                                                        292
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="293">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="293">
                                                        293
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="294">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="294">
                                                        294
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="295">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="295">
                                                        295
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="296">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="296">
                                                        296
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="297">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="297">
                                                        297
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="298">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="298">
                                                        298
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="299">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="299">
                                                        299
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-300" role="tabpanel"
                                                aria-labelledby="pills-300-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="300">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="300">
                                                        300
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="301">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="301">
                                                        301
                                                    </label>
                                                </div>

                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="302">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="302">
                                                        302
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="303">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="303">
                                                        303
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="304">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="304">
                                                        304
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="305">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="305">
                                                        305
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="306">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="306">
                                                        306
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="307">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="307">
                                                        307
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="308">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="308">
                                                        308
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="309">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="309">
                                                        309
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="310">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="310">
                                                        310
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="311">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="311">
                                                        311
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="312">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="312">
                                                        312
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="313">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="313">
                                                        313
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="314">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="314">
                                                        314
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="315">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="315">
                                                        315
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="316">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="316">
                                                        316
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="317">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="317">
                                                        317
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="318">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="318">
                                                        318
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="319">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="319">
                                                        319
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="320">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="320">
                                                        320
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="321">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="321">
                                                        321
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="322">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="322">
                                                        322
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="323">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="323">
                                                        323
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="324">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="324">
                                                        324
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="325">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="325">
                                                        325
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="326">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="326">
                                                        326
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="327">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="327">
                                                        327
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="328">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="328">
                                                        328
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="329">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="329">
                                                        329
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="330">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="330">
                                                        330
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="331">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="331">
                                                        331
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="332">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="332">
                                                        332
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="333">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="333">
                                                        333
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="334">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="334">
                                                        334
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="335">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="335">
                                                        335
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="336">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="336">
                                                        336
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="337">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="337">
                                                        337
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="338">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="338">
                                                        338
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="339">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="339">
                                                        339
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="340">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="340">
                                                        340
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="341">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="341">
                                                        341
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="342">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="342">
                                                        342
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="343">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="343">
                                                        343
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="344">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="344">
                                                        344
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="345">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="345">
                                                        345
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="346">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="346">
                                                        346
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="347">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="347">
                                                        347
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="348">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="348">
                                                        348
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="349">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="349">
                                                        349
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="350">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="350">
                                                        350
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="351">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="351">
                                                        351
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="352">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="352">
                                                        352
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="353">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="353">
                                                        353
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="354">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="354">
                                                        354
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="355">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="355">
                                                        355
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="356">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="356">
                                                        356
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="357">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="357">
                                                        357
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="358">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="358">
                                                        358
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="359">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="359">
                                                        359
                                                    </label>
                                                </div>

                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="360">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="360">
                                                        360
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="361">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="361">
                                                        361
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="362">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="362">
                                                        362
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="363">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="363">
                                                        363
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="364">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="364">
                                                        364
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="365">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="365">
                                                        365
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="366">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="366">
                                                        366
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="367">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="367">
                                                        367
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="368">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="368">
                                                        368
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="369">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="369">
                                                        369
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="370">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="370">
                                                        370
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="371">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="371">
                                                        371
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="372">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="372">
                                                        372
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="373">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="373">
                                                        373
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="374">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="374">
                                                        374
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="375">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="375">
                                                        375
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="376">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="376">
                                                        376
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="377">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="377">
                                                        377
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="378">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="378">
                                                        378
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="379">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="379">
                                                        379
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="380">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="380">
                                                        380
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="381">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="381">
                                                        381
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="382">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="382">
                                                        382
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="383">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="383">
                                                        383
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="384">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="384">
                                                        384
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="385">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="385">
                                                        385
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="386">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="386">
                                                        386
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="387">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="387">
                                                        387
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="388">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="388">
                                                        388
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="389">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="389">
                                                        389
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="390">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="390">
                                                        390
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="391">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="391">
                                                        391
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="392">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="392">
                                                        392
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="393">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="393">
                                                        393
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="394">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="394">
                                                        394
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="395">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="395">
                                                        395
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="396">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="396">
                                                        396
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="397">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="397">
                                                        397
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="398">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="398">
                                                        398
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="399">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="399">
                                                        399
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-400" role="tabpanel"
                                                aria-labelledby="pills-400-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="400">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="400">
                                                        400
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="401">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="401">
                                                        401
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="402">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="402">
                                                        402
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="403">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="403">
                                                        403
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="404">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="404">
                                                        404
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="405">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="405">
                                                        405
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="406">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="406">
                                                        406
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="407">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="407">
                                                        407
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="408">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="408">
                                                        408
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="409">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="409">
                                                        409
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="410">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="410">
                                                        410
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="411">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="411">
                                                        411
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="412">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="412">
                                                        412
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="413">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="413">
                                                        413
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="414">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="414">
                                                        414
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="415">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="415">
                                                        415
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="416">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="416">
                                                        416
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="417">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="417">
                                                        417
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="418">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="418">
                                                        418
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="419">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="419">
                                                        419
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="420">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="420">
                                                        420
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="421">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="421">
                                                        421
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="422">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="422">
                                                        422
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="423">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="423">
                                                        423
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="424">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="424">
                                                        424
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="425">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="425">
                                                        425
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="426">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="426">
                                                        426
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="427">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="427">
                                                        427
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="428">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="428">
                                                        428
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="429">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="429">
                                                        429
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="430">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="430">
                                                        430
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="431">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="431">
                                                        431
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="432">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="432">
                                                        432
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="433">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="433">
                                                        433
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="434">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="434">
                                                        434
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="435">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="435">
                                                        435
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="436">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="436">
                                                        436
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="437">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="437">
                                                        437
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="438">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="438">
                                                        438
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="439">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="439">
                                                        439
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="440">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="440">
                                                        440
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="441">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="441">
                                                        441
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="442">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="442">
                                                        442
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="443">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="443">
                                                        443
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="444">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="444">
                                                        444
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="445">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="445">
                                                        445
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="446">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="446">
                                                        446
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="447">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="447">
                                                        447
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="448">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="448">
                                                        448
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="449">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="449">
                                                        449
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="450">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="450">
                                                        450
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="451">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="451">
                                                        451
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="452">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="452">
                                                        452
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="453">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="453">
                                                        453
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="454">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="454">
                                                        454
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="455">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="455">
                                                        455
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="456">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="456">
                                                        456
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="457">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="457">
                                                        457
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="458">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="458">
                                                        458
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="459">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="459">
                                                        459
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="460">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="460">
                                                        460
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="461">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="461">
                                                        461
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="462">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="462">
                                                        462
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="463">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="463">
                                                        463
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="464">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="464">
                                                        464
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="465">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="465">
                                                        465
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="466">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="466">
                                                        466
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="467">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="467">
                                                        467
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="468">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="468">
                                                        468
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="469">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="469">
                                                        469
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="470">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="470">
                                                        470
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="471">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="471">
                                                        471
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="472">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="472">
                                                        472
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="473">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="473">
                                                        473
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="474">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="474">
                                                        474
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="475">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="475">
                                                        475
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="476">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="476">
                                                        476
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="477">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="477">
                                                        477
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="478">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="478">
                                                        478
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="479">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="479">
                                                        479
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="480">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="480">
                                                        480
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="481">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="481">
                                                        481
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="482">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="482">
                                                        482
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="483">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="483">
                                                        483
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="484">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="484">
                                                        484
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="485">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="485">
                                                        485
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="486">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="486">
                                                        486
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="487">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="487">
                                                        487
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="488">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="488">
                                                        488
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="489">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="489">
                                                        489
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="490">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="490">
                                                        490
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="491">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="491">
                                                        491
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="492">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="492">
                                                        492
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="493">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="493">
                                                        493
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="494">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="494">
                                                        494
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="495">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="495">
                                                        495
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="496">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="496">
                                                        496
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="497">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="497">
                                                        497
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="498">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="498">
                                                        498
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="499">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="499">
                                                        499
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-500" role="tabpanel"
                                                aria-labelledby="pills-500-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="500">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="500">
                                                        500
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="501">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="501">
                                                        501
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="502">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="502">
                                                        502
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="503">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="503">
                                                        503
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="504">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="504">
                                                        504
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="505">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="505">
                                                        505
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="506">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="506">
                                                        506
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="507">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="507">
                                                        507
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="508">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="508">
                                                        508
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="509">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="509">
                                                        509
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="510">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="510">
                                                        510
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="511">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="511">
                                                        511
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="512">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="512">
                                                        512
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="513">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="513">
                                                        513
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="514">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="514">
                                                        514
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="515">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="515">
                                                        515
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="516">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="516">
                                                        516
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="517">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="517">
                                                        517
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="518">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="518">
                                                        518
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="519">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="519">
                                                        519
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="520">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="520">
                                                        520
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="521">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="521">
                                                        521
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="522">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="522">
                                                        522
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="523">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="523">
                                                        523
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="524">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="524">
                                                        524
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="525">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="525">
                                                        525
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="526">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="526">
                                                        526
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="527">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="527">
                                                        527
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="528">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="528">
                                                        528
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="529">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="529">
                                                        529
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="530">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="530">
                                                        530
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="531">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="531">
                                                        531
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="532">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="532">
                                                        532
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="533">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="533">
                                                        533
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="534">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="534">
                                                        534
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="535">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="535">
                                                        535
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="536">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="536">
                                                        536
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="537">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="537">
                                                        537
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="538">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="538">
                                                        538
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="539">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="539">
                                                        539
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="540">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="540">
                                                        540
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="541">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="541">
                                                        541
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="542">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="542">
                                                        542
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="543">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="543">
                                                        543
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="544">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="544">
                                                        544
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="545">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="545">
                                                        545
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="546">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="546">
                                                        546
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="547">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="547">
                                                        547
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="548">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="548">
                                                        548
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="549">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="549">
                                                        549
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="550">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="550">
                                                        550
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="551">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="551">
                                                        551
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="552">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="552">
                                                        552
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="553">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="553">
                                                        553
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="554">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="554">
                                                        554
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="555">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="555">
                                                        555
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="556">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="556">
                                                        556
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="557">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="557">
                                                        557
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="558">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="558">
                                                        558
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="559">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="559">
                                                        559
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="560">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="560">
                                                        560
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="561">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="561">
                                                        561
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="562">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="562">
                                                        562
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="563">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="563">
                                                        563
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="564">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="564">
                                                        564
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="565">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="565">
                                                        565
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="566">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="566">
                                                        566
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="567">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="567">
                                                        567
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="568">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="568">
                                                        568
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="569">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="569">
                                                        569
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="570">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="570">
                                                        570
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="571">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="571">
                                                        571
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="572">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="572">
                                                        572
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="573">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="573">
                                                        573
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="574">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="574">
                                                        574
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="575">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="575">
                                                        575
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="576">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="576">
                                                        576
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="577">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="577">
                                                        577
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="578">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="578">
                                                        578
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="579">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="579">
                                                        579
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="580">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="580">
                                                        580
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="581">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="581">
                                                        581
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="582">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="582">
                                                        582
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="583">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="583">
                                                        583
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="584">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="584">
                                                        584
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="585">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="585">
                                                        585
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="586">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="586">
                                                        586
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="587">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="587">
                                                        587
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="588">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="588">
                                                        588
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="589">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="589">
                                                        589
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="590">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="590">
                                                        590
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="591">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="591">
                                                        591
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="592">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="592">
                                                        592
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="593">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="593">
                                                        593
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="594">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="594">
                                                        594
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="595">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="595">
                                                        595
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="596">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="596">
                                                        596
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="597">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="597">
                                                        597
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="598">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="598">
                                                        598
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="599">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="599">
                                                        599
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade" id="pills-600" role="tabpanel"
                                                aria-labelledby="pills-600-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="600">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="600">
                                                        600
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="601">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="601">
                                                        601
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="602">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="602">
                                                        602
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="603">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="603">
                                                        603
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="604">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="604">
                                                        604
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="605">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="605">
                                                        605
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="606">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="606">
                                                        606
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="607">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="607">
                                                        607
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="608">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="608">
                                                        608
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="609">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="609">
                                                        609
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="610">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="610">
                                                        610
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="611">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="611">
                                                        611
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="612">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="612">
                                                        612
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="613">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="613">
                                                        613
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="614">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="614">
                                                        614
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="615">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="615">
                                                        615
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="616">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="616">
                                                        616
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="617">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="617">
                                                        617
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="618">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="618">
                                                        618
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="619">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="619">
                                                        619
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="620">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="620">
                                                        620
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="621">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="621">
                                                        621
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="622">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="622">
                                                        622
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="623">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="623">
                                                        623
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="624">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="624">
                                                        624
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="625">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="625">
                                                        625
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="626">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="626">
                                                        626
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="627">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="627">
                                                        627
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="628">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="628">
                                                        628
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="629">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="629">
                                                        629
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="630">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="630">
                                                        630
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="631">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="631">
                                                        631
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="632">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="632">
                                                        632
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="633">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="633">
                                                        633
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="634">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="634">
                                                        634
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="635">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="635">
                                                        635
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="636">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="636">
                                                        636
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="637">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="637">
                                                        637
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="638">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="638">
                                                        638
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="639">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="639">
                                                        639
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="640">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="640">
                                                        640
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="641">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="641">
                                                        641
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="642">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="642">
                                                        642
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="643">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="643">
                                                        643
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="644">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="644">
                                                        644
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="645">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="645">
                                                        645
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="646">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="646">
                                                        646
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="647">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="647">
                                                        647
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="648">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="648">
                                                        648
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="649">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="649">
                                                        649
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="650">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="650">
                                                        650
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="651">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="651">
                                                        651
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="652">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="652">
                                                        652
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="653">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="653">
                                                        653
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="654">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="654">
                                                        654
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="655">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="655">
                                                        655
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="656">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="656">
                                                        656
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="657">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="657">
                                                        657
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="658">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="658">
                                                        658
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="659">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="659">
                                                        659
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="660">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="660">
                                                        660
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="661">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="661">
                                                        661
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="662">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="662">
                                                        662
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="663">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="663">
                                                        663
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="664">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="664">
                                                        664
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="665">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="665">
                                                        665
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="666">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="666">
                                                        666
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="667">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="667">
                                                        667
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="668">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="668">
                                                        668
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="669">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="669">
                                                        669
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="670">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="670">
                                                        670
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="671">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="671">
                                                        671
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="672">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="672">
                                                        672
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="673">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="673">
                                                        673
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="674">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="674">
                                                        674
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="675">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="675">
                                                        675
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="676">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="676">
                                                        676
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="677">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="677">
                                                        677
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="678">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="678">
                                                        678
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="679">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="679">
                                                        679
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="680">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="680">
                                                        680
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="681">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="681">
                                                        681
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="682">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="682">
                                                        682
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="683">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="683">
                                                        683
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="684">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="684">
                                                        684
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="685">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="685">
                                                        685
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="686">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="686">
                                                        686
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="687">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="687">
                                                        687
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="688">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="688">
                                                        688
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="689">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="689">
                                                        689
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="690">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="690">
                                                        690
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="691">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="691">
                                                        691
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="692">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="692">
                                                        692
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="693">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="693">
                                                        693
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="694">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="694">
                                                        694
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="695">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="695">
                                                        695
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="696">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="696">
                                                        696
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="697">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="697">
                                                        697
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="698">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="698">
                                                        698
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="699">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="699">
                                                        699
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-700" role="tabpanel"
                                                aria-labelledby="pills-700-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="700">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="700">
                                                        700
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="701">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="701">
                                                        701
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="702">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="702">
                                                        702
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="703">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="703">
                                                        703
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="704">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="704">
                                                        704
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="705">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="705">
                                                        705
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="706">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="706">
                                                        706
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="707">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="707">
                                                        707
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="708">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="708">
                                                        708
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="709">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="709">
                                                        709
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="710">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="710">
                                                        710
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="711">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="711">
                                                        711
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="712">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="712">
                                                        712
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="713">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="713">
                                                        713
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="714">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="714">
                                                        714
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="715">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="715">
                                                        715
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="716">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="716">
                                                        716
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="717">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="717">
                                                        717
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="718">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="718">
                                                        718
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="719">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="719">
                                                        719
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="720">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="720">
                                                        720
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="721">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="721">
                                                        721
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="722">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="722">
                                                        722
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="723">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="723">
                                                        723
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="724">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="724">
                                                        724
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="725">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="725">
                                                        725
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="726">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="726">
                                                        726
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="727">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="727">
                                                        727
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="728">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="728">
                                                        728
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="729">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="729">
                                                        729
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="730">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="730">
                                                        730
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="731">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="731">
                                                        731
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="732">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="732">
                                                        732
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="733">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="733">
                                                        733
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="734">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="734">
                                                        734
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="735">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="735">
                                                        735
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="736">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="736">
                                                        736
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="737">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="737">
                                                        737
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="738">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="738">
                                                        738
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="739">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="739">
                                                        739
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="740">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="740">
                                                        740
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="741">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="741">
                                                        741
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="742">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="742">
                                                        742
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="743">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="743">
                                                        743
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="744">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="744">
                                                        744
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="745">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="745">
                                                        745
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="746">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="746">
                                                        746
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="747">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="747">
                                                        747
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="748">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="748">
                                                        748
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="749">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="749">
                                                        749
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="750">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="750">
                                                        750
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="751">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="751">
                                                        751
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="752">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="752">
                                                        752
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="753">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="753">
                                                        753
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="754">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="754">
                                                        754
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="755">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="755">
                                                        755
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="756">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="756">
                                                        756
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="757">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="757">
                                                        757
                                                    </label>
                                                </div>

                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="758">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="758">
                                                        758
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="759">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="759">
                                                        759
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="760">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="760">
                                                        760
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="761">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="761">
                                                        761
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="762">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="762">
                                                        762
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="763">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="763">
                                                        763
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="764">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="764">
                                                        764
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="765">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="765">
                                                        765
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="766">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="766">
                                                        766
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="767">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="767">
                                                        767
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="768">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="768">
                                                        768
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="769">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="769">
                                                        769
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="770">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="770">
                                                        770
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="771">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="771">
                                                        771
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="772">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="772">
                                                        772
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="773">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="773">
                                                        773
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="774">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="774">
                                                        774
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="775">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="775">
                                                        775
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="776">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="776">
                                                        776
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="777">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="777">
                                                        777
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="778">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="778">
                                                        778
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="779">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="779">
                                                        779
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="780">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="780">
                                                        780
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="781">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="781">
                                                        781
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="782">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="782">
                                                        782
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="783">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="783">
                                                        783
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="784">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="784">
                                                        784
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="785">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="785">
                                                        785
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="786">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="786">
                                                        786
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="787">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="787">
                                                        787
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="788">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="788">
                                                        788
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="789">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="789">
                                                        789
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="790">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="790">
                                                        790
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="791">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="791">
                                                        791
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="792">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="792">
                                                        792
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="793">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="793">
                                                        793
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="794">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="794">
                                                        794
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="795">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="795">
                                                        795
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="796">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="796">
                                                        796
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="797">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="797">
                                                        797
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="798">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="798">
                                                        798
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="799">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="799">
                                                        799
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-800" role="tabpanel"
                                                aria-labelledby="pills-800-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="800">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="800">
                                                        800
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="801">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="801">
                                                        801
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="802">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="802">
                                                        802
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="803">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="803">
                                                        803
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="804">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="804">
                                                        804
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="805">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="805">
                                                        805
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="806">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="806">
                                                        806
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="807">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="807">
                                                        807
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="808">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="808">
                                                        808
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="809">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="809">
                                                        809
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="810">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="810">
                                                        810
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="811">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="811">
                                                        811
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="812">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="812">
                                                        812
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="813">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="813">
                                                        813
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="814">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="814">
                                                        814
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="815">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="815">
                                                        815
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="816">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="816">
                                                        816
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="817">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="817">
                                                        817
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="818">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="818">
                                                        818
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="819">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="819">
                                                        819
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="820">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="820">
                                                        820
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="821">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="821">
                                                        821
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="822">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="822">
                                                        822
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="823">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="823">
                                                        823
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="824">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="824">
                                                        824
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="825">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="825">
                                                        825
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="826">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="826">
                                                        826
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="827">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="827">
                                                        827
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="828">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="828">
                                                        828
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="829">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="829">
                                                        829
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="830">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="830">
                                                        830
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="831">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="831">
                                                        831
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="832">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="832">
                                                        832
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="833">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="833">
                                                        833
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="834">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="834">
                                                        834
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="835">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="835">
                                                        835
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="836">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="836">
                                                        836
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="837">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="837">
                                                        837
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="838">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="838">
                                                        838
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="839">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="839">
                                                        839
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="840">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="840">
                                                        840
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="841">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="841">
                                                        841
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="842">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="842">
                                                        842
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="843">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="843">
                                                        843
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="844">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="844">
                                                        844
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="845">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="845">
                                                        845
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="846">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="846">
                                                        846
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="847">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="847">
                                                        847
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="848">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="848">
                                                        848
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="849">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="849">
                                                        849
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="850">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="850">
                                                        850
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="851">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="851">
                                                        851
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="852">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="852">
                                                        852
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="853">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="853">
                                                        853
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="854">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="854">
                                                        854
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="855">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="855">
                                                        855
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="856">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="856">
                                                        856
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="857">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="857">
                                                        857
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="858">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="858">
                                                        858
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="859">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="859">
                                                        859
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="860">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="860">
                                                        860
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="861">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="861">
                                                        861
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="862">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="862">
                                                        862
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="863">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="863">
                                                        863
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="864">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="864">
                                                        864
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="865">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="865">
                                                        865
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="866">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="866">
                                                        866
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="867">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="867">
                                                        867
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="868">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="868">
                                                        868
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="869">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="869">
                                                        869
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="870">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="870">
                                                        870
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="871">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="871">
                                                        871
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="872">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="872">
                                                        872
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="873">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="873">
                                                        873
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="874">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="874">
                                                        874
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="875">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="875">
                                                        875
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="876">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="876">
                                                        876
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="877">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="877">
                                                        877
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="878">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="878">
                                                        878
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="879">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="879">
                                                        879
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="880">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="880">
                                                        880
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="881">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="881">
                                                        881
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="882">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="882">
                                                        882
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="883">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="883">
                                                        883
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="884">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="884">
                                                        884
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="885">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="885">
                                                        885
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="886">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="886">
                                                        886
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="887">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="887">
                                                        887
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="888">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="888">
                                                        888
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="889">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="889">
                                                        889
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="890">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="890">
                                                        890
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="891">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="891">
                                                        891
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="892">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="892">
                                                        892
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="893">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="893">
                                                        893
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="894">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="894">
                                                        894
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="895">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="895">
                                                        895
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="896">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="896">
                                                        896
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="897">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="897">
                                                        897
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="898">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="898">
                                                        898
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="899">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="899">
                                                        899
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="text-center tab-pane fade " id="pills-900" role="tabpanel"
                                                aria-labelledby="pills-900-tab">
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="900">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="900">
                                                        900
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="901">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="901">
                                                        901
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="902">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="902">
                                                        902
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="903">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="903">
                                                        903
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="904">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="904">
                                                        904
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="905">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="905">
                                                        905
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="906">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="906">
                                                        906
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="907">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="907">
                                                        907
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="908">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="908">
                                                        908
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="909">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="909">
                                                        909
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="910">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="910">
                                                        910
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="911">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="911">
                                                        911
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="912">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="912">
                                                        912
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="913">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="913">
                                                        913
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="914">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="914">
                                                        914
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="915">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="915">
                                                        915
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="916">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="916">
                                                        916
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="917">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="917">
                                                        917
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="918">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="918">
                                                        918
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="919">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="919">
                                                        919
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="920">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="920">
                                                        920
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="921">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="921">
                                                        921
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="922">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="922">
                                                        922
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="923">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="923">
                                                        923
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="924">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="924">
                                                        924
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="925">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="925">
                                                        925
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="926">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="926">
                                                        926
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="927">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="927">
                                                        927
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="928">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="928">
                                                        928
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="929">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="929">
                                                        929
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="930">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="930">
                                                        930
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="931">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="931">
                                                        931
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="932">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="932">
                                                        932
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="933">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="933">
                                                        933
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="934">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="934">
                                                        934
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="935">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="935">
                                                        935
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="936">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="936">
                                                        936
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="937">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="937">
                                                        937
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="938">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="938">
                                                        938
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="939">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="939">
                                                        939
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="940">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="940">
                                                        940
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="941">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="941">
                                                        941
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="942">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="942">
                                                        942
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="943">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="943">
                                                        943
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="944">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="944">
                                                        944
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="945">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="945">
                                                        945
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="946">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="946">
                                                        946
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="947">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="947">
                                                        947
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="948">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="948">
                                                        948
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="949">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="949">
                                                        949
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="950">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="950">
                                                        950
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="951">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="951">
                                                        951
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="952">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="952">
                                                        952
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="953">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="953">
                                                        953
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="954">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="954">
                                                        954
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="955">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="955">
                                                        955
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="956">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="956">
                                                        956
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="957">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="957">
                                                        957
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="958">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="958">
                                                        958
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="959">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="959">
                                                        959
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="960">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="960">
                                                        960
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="961">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="961">
                                                        961
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="962">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="962">
                                                        962
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="963">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="963">
                                                        963
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="964">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="964">
                                                        964
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="965">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="965">
                                                        965
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="966">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="966">
                                                        966
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="967">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="967">
                                                        967
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="968">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="968">
                                                        968
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="969">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="969">
                                                        969
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="970">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="970">
                                                        970
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="971">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="971">
                                                        971
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="972">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="972">
                                                        972
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="973">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="973">
                                                        973
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="974">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="974">
                                                        974
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="975">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="975">
                                                        975
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="976">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="976">
                                                        976
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="977">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="977">
                                                        977
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="978">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="978">
                                                        978
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="979">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="979">
                                                        979
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="980">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="980">
                                                        980
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="981">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="981">
                                                        981
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="982">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="982">
                                                        982
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="983">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="983">
                                                        983
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="984">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="984">
                                                        984
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="985">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="985">
                                                        985
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="986">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="986">
                                                        986
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="987">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="987">
                                                        987
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="988">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="988">
                                                        988
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="989">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="989">
                                                        989
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="990">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="990">
                                                        990
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="991">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="991">
                                                        991
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="992">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="992">
                                                        992
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="993">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="993">
                                                        993
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="994">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="994">
                                                        994
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="995">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="995">
                                                        995
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="996">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="996">
                                                        996
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="997">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="997">
                                                        997
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="998">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="998">
                                                        998
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle column d-inline" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger btn-sm mb-1 panghuay_number"
                                                        data-id="999">
                                                        <input type="checkbox" autocomplete="off" name="threenumber"
                                                            value="999">
                                                        999
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">

                                    <div class="row m-0">
                                        <div class="col-12 col-sm-12 col-md-8 p-1">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="ค้นหาตัวเลข"
                                                    aria-label="ค้นหาตัวเลข" aria-describedby="button-addon2"
                                                    id="search-number2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="button-addon2"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-4 p-1">
                                            <button href=""
                                                class="btn btn-outline-secondary btn-block btn-sm btn-panghuay py-2"
                                                id="shuffle_2"><i class="fas fa-random"></i> กลับตัวเลข
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row m-0">
                                        <?php foreach ($playTypes as $code => $playType) {
                                            if ($code === 'teng_bon_2') {
                                                $buttonClassPlayType = 'bg-primary text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-blue btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_two';
                                            }

                                            if ( $code === 'teng_lang_2') {
                                                $buttonClassPlayType = 'bg-primary text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-blue btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_two';
                                            }
                                           
                                            if ($code === 'teng_bon_2'|| $code === 'teng_lang_2') { ?>

                                            <div class="col-6 p-1">
                                                <div class="row-btn-tanghuay">
                                                    <button class="<?= $buttonClassPlayTypeName ?>" id="<?= $code ?>">
                                                        <div class="<?= $buttonClassPlayType ?>">
                                                            <i><?= $playType['title'] ?></i>
                                                        </div>
                                                        <div><?= $playType['jackpot_per_unit'] ?></div>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } } ?>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 pt-1 pb-0 px-0 px-sm-0 px-md-2">
                                            <div id="ninetybtn" class="overlay-disable"></div>
                                            <span class="d-block d-sm-block d-md-inline">19 ประตู</span>
                                            <div class="btn-group btn-group-sm d-flex" role="group">
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="0" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="0">
                                                        0
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="1" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="1">
                                                        1
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="2" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="2">
                                                        2
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="3" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="3">
                                                        3
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="4" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="4">
                                                        4
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="5" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="5">
                                                        5
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="6" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="6">
                                                        6
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="7" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="7">
                                                        7
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="8" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="8">
                                                        8
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="9" data-option="option_2_19">
                                                        <input type="checkbox" autocomplete="off" name="ninety"
                                                            value="9">
                                                        9
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 pt-1 pb-0 px-0 px-sm-0 px-md-2">
                                            <div id="roodfrontbtn" class="overlay-disable"></div>
                                            <span class="d-block d-sm-block d-md-inline">รูดหน้า</span>
                                            <div class="btn-group btn-group-sm d-flex" role="group">
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="0" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="0">
                                                        0
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="1" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="1">
                                                        1
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="2" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="2">
                                                        2
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="3" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="3">
                                                        3
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="4" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="4">
                                                        4
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="5" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="5">
                                                        5
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="6" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="6">
                                                        6
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="7" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="7">
                                                        7
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="8" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="8">
                                                        8
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="9" data-option="option_2_roodnha">
                                                        <input type="checkbox" autocomplete="off" name="roodfront"
                                                            value="9">
                                                        9
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 pt-1 pb-0 px-0 px-sm-0 px-md-2">
                                            <div id="roodbackbtn" class="overlay-disable"></div>
                                            <span class="d-block d-sm-block d-md-inline">รูดหลัง</span>
                                            <div class="btn-group btn-group-sm d-flex" role="group">
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="0" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="0">
                                                        0
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="1" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="1">
                                                        1
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="2" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="2">
                                                        2
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="3" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="3">
                                                        3
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="4" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="4">
                                                        4
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="5" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="5">
                                                        5
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="6" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="6">
                                                        6
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="7" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="7">
                                                        7
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="8" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="8">
                                                        8
                                                    </label>
                                                </div>
                                                <div class="btn-group-toggle d-inline flex-fill" data-toggle="buttons">
                                                    <label
                                                        class="btn btn-outline-secondary btn-rood btn-sm mb-1 panghuay_option_2"
                                                        data-id="9" data-option="option_2_roodlung">
                                                        <input type="checkbox" autocomplete="off" name="roodback"
                                                            value="9">
                                                        9
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 border-top my-1"></div>
                                        <div class="col-12 text-center px-0 py-1" id="numlist-tabContent2">
                                            <div id="twonumberbtn" class="overlay-disable"></div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="00">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="00">
                                                    00
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="01">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="01">
                                                    01
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="02">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="02">
                                                    02
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="03">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="03">
                                                    03
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="04">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="04">
                                                    04
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="05">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="05">
                                                    05
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="06">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="06">
                                                    06
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="07">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="07">
                                                    07
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="08">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="08">
                                                    08
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="09">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="09">
                                                    09
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="10">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="10">
                                                    10
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="11">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="11">
                                                    11
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="12">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="12">
                                                    12
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="13">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="13">
                                                    13
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="14">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="14">
                                                    14
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="15">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="15">
                                                    15
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="16">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="16">
                                                    16
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="17">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="17">
                                                    17
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="18">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="18">
                                                    18
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="19">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="19">
                                                    19
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="20">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="20">
                                                    20
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="21">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="21">
                                                    21
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="22">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="22">
                                                    22
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="23">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="23">
                                                    23
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="24">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="24">
                                                    24
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="25">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="25">
                                                    25
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="26">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="26">
                                                    26
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="27">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="27">
                                                    27
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="28">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="28">
                                                    28
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="29">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="29">
                                                    29
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="30">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="30">
                                                    30
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="31">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="31">
                                                    31
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="32">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="32">
                                                    32
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="33">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="33">
                                                    33
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="34">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="34">
                                                    34
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="35">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="35">
                                                    35
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="36">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="36">
                                                    36
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="37">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="37">
                                                    37
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="38">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="38">
                                                    38
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="39">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="39">
                                                    39
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="40">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="40">
                                                    40
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="41">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="41">
                                                    41
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="42">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="42">
                                                    42
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="43">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="43">
                                                    43
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="44">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="44">
                                                    44
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="45">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="45">
                                                    45
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="46">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="46">
                                                    46
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="47">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="47">
                                                    47
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="48">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="48">
                                                    48
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="49">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="49">
                                                    49
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="50">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="50">
                                                    50
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="51">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="51">
                                                    51
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="52">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="52">
                                                    52
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="53">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="53">
                                                    53
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="54">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="54">
                                                    54
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="55">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="55">
                                                    55
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="56">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="56">
                                                    56
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="57">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="57">
                                                    57
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="58">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="58">
                                                    58
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="59">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="59">
                                                    59
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="60">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="60">
                                                    60
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="61">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="61">
                                                    61
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="62">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="62">
                                                    62
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="63">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="63">
                                                    63
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="64">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="64">
                                                    64
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="65">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="65">
                                                    65
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="66">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="66">
                                                    66
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="67">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="67">
                                                    67
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="68">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="68">
                                                    68
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="69">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="69">
                                                    69
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="70">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="70">
                                                    70
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="71">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="71">
                                                    71
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="72">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="72">
                                                    72
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="73">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="73">
                                                    73
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="74">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="74">
                                                    74
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="75">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="75">
                                                    75
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="76">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="76">
                                                    76
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="77">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="77">
                                                    77
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="78">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="78">
                                                    78
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="79">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="79">
                                                    79
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="80">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="80">
                                                    80
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="81">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="81">
                                                    81
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="82">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="82">
                                                    82
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="83">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="83">
                                                    83
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="84">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="84">
                                                    84
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="85">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="85">
                                                    85
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="86">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="86">
                                                    86
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="87">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="87">
                                                    87
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="88">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="88">
                                                    88
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="89">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="89">
                                                    89
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="90">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="90">
                                                    90
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="91">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="91">
                                                    91
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="92">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="92">
                                                    92
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="93">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="93">
                                                    93
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="94">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="94">
                                                    94
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="95">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="95">
                                                    95
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="96">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="96">
                                                    96
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="97">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="97">
                                                    97
                                                </label>
                                            </div>

                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="98">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="98">
                                                    98
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle d-inline column" data-toggle="buttons">
                                                <label class="btn btn-limitnum btn-sm mb-1 panghuay_number"
                                                    data-id="99">
                                                    <input type="checkbox" autocomplete="off" name="twonumber"
                                                        value="99">
                                                    99
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="pills-run" role="tabpanel"
                                    aria-labelledby="pills-run-tab">

                                    <div class="row m-0">
                                        <div class="col-12 col-sm-12 col-md-12 p-1">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" placeholder="ค้นหาตัวเลข"
                                                    aria-label="ค้นหาตัวเลข" aria-describedby="button-addon2"
                                                    id="search-number3">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="button-addon2"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row m-0">
                                    <?php foreach ($playTypes as $code => $playType) {
                                            if ($code === 'teng_bon_1') {
                                                $buttonClassPlayType = 'bg-success text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-green btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_run';
                                            }

                                            if ( $code === 'teng_lang_1') {
                                                $buttonClassPlayType = 'bg-success text-white flex-fill';
                                                $buttonClassPlayTypeName = 'btn btn-outline-green btn-sm btn-block h-100 d-flex justify-content-between align-items-center btn-panghuay bet_run';
                                            }
                                           
                                            if ($code === 'teng_bon_1'|| $code === 'teng_lang_1') { ?>

                                            <div class="col-6 p-1">
                                                <div class="row-btn-tanghuay">
                                                    <button class="<?= $buttonClassPlayTypeName ?>" id="<?= $code ?>">
                                                        <div class="<?= $buttonClassPlayType ?>">
                                                            <i><?= $playType['title'] ?></i>
                                                        </div>
                                                        <div><?= $playType['jackpot_per_unit'] ?></div>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } } ?>
                                        <div id="numlist-tabContent3"
                                            class="col-12 p-1 d-flex flex-wrap flex-row justify-content-center">
                                            <div id="runnumberbtn" class="overlay-disable"></div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="0">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="0">
                                                    0
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="1">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="1">
                                                    1
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="2">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="2">
                                                    2
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="3">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="3">
                                                    3
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="4">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="4">
                                                    4
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="5">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="5">
                                                    5
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="6">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="6">
                                                    6
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="7">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="7">
                                                    7
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="8">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="8">
                                                    8
                                                </label>
                                            </div>
                                            <div class="btn-group-toggle flex-fill column p-1" data-toggle="buttons">
                                                <label
                                                    class="btn btn-outline-secondary btn-block txt-num mb-1 btn-outline-danger panghuay_number"
                                                    data-id="9">
                                                    <input type="checkbox" autocomplete="off" name="runnumber"
                                                        value="9">
                                                    9
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="row mb-2 py-3 mx-0 bg-dark rounded">
                        <div class="col pr-1">
                            <button class="btn btn-light btn-sm btn-block number-sets border triggerPoy">
                                <i class="fas fa-list-ol"></i> ดึงโพย </button>
                        </div>
                        <div class="col pl-1">
                            <button class="btn btn-danger btn-sm btn-block show-lists triggerPrice">
                                <i class="fas fa-edit"></i> ใส่ราคา </button>
                        </div>
                    </div>
                </div>
                <div class="box__condition-info">
                    <h2><i class="fas fa-exclamation-circle"></i> เงื่อนไขการแทง</h2>
                    <div class="row">
                        <?php foreach ($playTypes as $code => $playType) { ?>
                        <div class="col-md-12 d-none" id="content_<?= $code ?>">
                            <h3><?= $playType['title'] ?></h3>
                            <p>
                                แทงขั้นต่ำต่อครั้ง : <?= number_format($playType['minimum_play']) ?><br>
                                แทงสูงสุดต่อครั้ง : <?= number_format($playType['maximum_play']) ?><br>
                                แทงสูงสุดต่อ user :
                                <?= isset($playType['maximum_by_user']) ? number_format($playType['maximum_by_user']) : '-' ?><br>
                        </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->render('/thai-shared-chit/modal_rule', ['thaiSharedGame' => $thaiSharedGame]) ?>