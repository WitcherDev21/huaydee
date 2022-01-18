<?php
use yii\helpers\Url;
$timeStampNow = strtotime(date('Y-m-d H:i:s')) * 1000;
$numberMemoUrl = Url::to(['/api-number-memo']);
$numberMemoDetailUrl = Url::to(['/api-number-memo/detail']);
$preSendPoyUrl = Url::to(['/api-poy/yeekee-presend-poy']);
$buyPoyUrl = Url::to(['/api-poy/buy-yeekee']);
$printPoyUrl = Url::to(['print-poy']);
$myPoyMemoUrl = Url::to(['/api-number-memo/my-poy']);
$myPoyDetailMemoUrl = Url::to(['/api-number-memo/my-poy-detail']);
$yeekeeNumberUrl = Url::to(['/api-poy/yeekee-number']);
$yeekeeLoadNumberPostUrl = Url::to(['api-poy/yeekee-post']);

$js = <<<EOT
    var bet_list_detail = '$betListDetail';
    var ying_only = false;
    var timex = $timeStampNow;
    var playId = $yeekeeGame->id;
    var numberMemoUrl = '$numberMemoUrl';
    var numberMemoDetailUrl = '$numberMemoDetailUrl';
    var preSendPoyUrl = '$preSendPoyUrl';
    var buyPoyUrl = '$buyPoyUrl';
    var printPoyUrl = '$printPoyUrl';
    var myPoyMemoUrl = '$myPoyMemoUrl';
    var myPoyDetailMemoUrl = '$myPoyDetailMemoUrl';
    var yeekeeLoadNumberPostUrl = '$yeekeeLoadNumberPostUrl';
    var yeekeeNumber = '$yeekeeNumberUrl';
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
$this->registerJsFile(Yii::getAlias('@web/version6/js/index/pagebet.js?1562253392'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web/version6/css/sweetalert2.min.css'));
$this->registerJsFile(Yii::getAlias('@web/version6/js/sweetalert2.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/tether.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div id="section-content" class="container">
    <div class="bar-back d-flex justify-content-between align-items-center">
        <div id="top"></div>
        <a href="<?= Url::to(['yeekee/index']) ?>" class="mr-auto" id="yingying"
            onclick="removecc('<?= Url::to(["yeekee/index"]) ?>')">
            <i class="fas fa-chevron-left"></i> ย้อนกลับ
        </a>
        <a href="#" class="btn btn-outline-secondary btn-sm mr-1 text-dark" data-toggle="modal"
            data-target="#rule-yeekee">กติกา</a>
        <a href="#" class="btn btn-danger btn-sm btn-numpage" data-id="numpage_2" onclick="yingying('#')">
            <i class="fas fa-calculator"></i>
            ยิงเลข
        </a>
        <a href="#" class="btn btn-dark btn-sm btn-numpage d-none active" data-id="numpage_1"
            onclick="yingying('<?= Url::to(["yeekee/play", "id" => $yeekeeGame->id]) ?>')">
            <i class="fas fa-calculator"></i>แทงเลข
        </a>
    </div>
    <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch d-flex flex-column align-items-stretch"
        style="min-height: calc((100vh - 148.42px) - 50px);">
        <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-0 pb-0">
            <div class="d-flex justify-content-between align-items-center mb-0 flex-fill lotto-title">
                <h4 class="tanghuay-h4">
                    หวยยี่กี<span class="badge badge-pill badge-danger font-weight-light"><small>รอบที่
                            <?= $yeekeeGame->round ?></small></span>
                </h4>
                <div class="tanghuay-time">
                    <i class="sn-icon sn-icon--daily2"></i>
                    <span class="countdown"
                        data-finaldate="<?= strtotime("+2 minutes",strtotime($yeekeeGame->finish_at)) * 1000 ?>">
                        <?php echo date("H:i:s",(strtotime(date('Y-m-d 00:00:00'))+strtotime("+2 minutes",strtotime($yeekeeGame->finish_at)) - time()))?>
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
                        <button class="btn btn-light btn-sm btn-block number-sets triggerPoy" data-toggle="modal"
                            data-target="#poy">
                            <i class="fas fa-list-ol"></i> ดึงโพย
                        </button>
                        <button class="btn btn-danger btn-sm btn-block show-lists triggerPrice">
                            <i class="fas fa-edit"></i> ใส่ราคา
                        </button>
                        <h5 class="title-huay mt-2"><i class="fas fa-receipt"></i> รายการแทง</h5>
                        <span class="bet_num_count"></span>
                        <div class="list-huay flex-fill">
                            <ol class="num-huay" id="show_bet_num"></ol>
                        </div>
                    </div>
                </div>
                <div class="sidebar-tanghuay box__play" id="page_tang">
                    <div id="show-list-huay" class="d-flex justify-content-between" style="width:0px; overflow:hidden;">
                        <span class="btn-move bet_num_count justify-content-center rounded bet_num_count"></span>
                        <a class="btn-move btn-move-right flex-fill">
                            <span>แสดง <i class="fas fa-chevron-circle-right"></i></span>
                        </a>
                    </div>
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
                            if ($code === 'teng_bon_1' || $code === 'teng_lang_nha_3') { ?>
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
                    <div class="bg-option p-1 pb-0 mt-1 rounded box__two-option box__play setting__number" id="2option"
                        style="display:none;">
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
                                    <button class="btn btn-success btn-sm btn-block h-100 bet_two" id="option_2_odd">
                                        สองตัวคี่
                                    </button>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <div class="bg-btn">
                                    <button class="btn btn-success btn-sm btn-block h-100 bet_two" id="option_2_even">
                                        สองตัวคุ่
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 bg-light px-1 pb-1 text-center border my-1">
                        <p class="border-bottom mb-1">
                            <small>รายการที่เลือก</small>
                        </p>
                        <div id="resultoption">
                            <span class="badge badge-danger_bkk font-weight-light" id="teng_bon_3_label">3ตัวบน</span>
                            <span class="badge badge-danger_bkk font-weight-light d-none"
                                id="tode_3_label">3ตัวโต๊ด</span>
                            <span class="badge badge-danger_bkk font-weight-light d-none"
                                id="shuffle_3_label">กลับ<br>3ตัว</span>
                            <span class="badge badge-danger_bkk font-weight-light d-none"
                                id="teng_lang_3_label">3ตัวล่าง</span>
                            <span class="badge badge-danger font-weight-light d-none"
                                id="teng_lang_nha_3_label">3ตัวหน้า</span>
                            <span class="badge badge-primary_bkk font-weight-light d-none"
                                id="teng_bon_2_label">2ตัวบน</span>
                            <span class="badge badge-primary_bkk font-weight-light d-none"
                                id="teng_lang_2_label">2ตัวล่าง</span>
                            <span class="badge badge-primary_bkk font-weight-light d-none"
                                id="shuffle_2_label">กลับ<br>2ตัว</span>
                            <span class="badge badge-success_bkk font-weight-light d-none"
                                id="teng_bon_1_label">วิ่งบน</span>
                            <span class="badge badge-success_bkk font-weight-light d-none"
                                id="teng_lang_1_label">วิ่งล่าง</span>
                            <span class="badge badge-secondary font-weight-light d-none" id="option_2_19_label">19
                                ประตู</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_ble_label">เลขเบิ้ล</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_roodnha_label">รูดหน้า</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_roodlung_label">รูดหลัง</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_low_label">สองตัวต่ำ</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_high_label">สองตัวสูง</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_odd_label">สองตัวคี่</span>
                            <span class="badge badge-secondary font-weight-light d-none"
                                id="option_2_even_label">สองตัวคุ่</span>
                        </div>
                    </div>
                    <div class="group__keyboard" style="">
                        <div class="num-pad box__show-number">
                            <h4 class="text-center">ระบุตัวเลข</h4>
                            <div class="label-number lists">
                                <div id="bet_num">
                                    <label class="number"> </label>
                                    <label class="number"> </label>
                                    <label class="number"> </label>
                                </div>
                            </div>
                            <div class="key-pad box__keyboard">
                                <div class="row p-2">
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="1">1</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="2">2</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="3">3</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-danger_bkk btn-block" data-id="delete"><i
                                                class="fas fa-backspace"></i></button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="4">4</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="5">5</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="6">6</button>
                                    </div>
                                    <div class="col-3">
                                        <button
                                            class="btn btn-secondary btn-block btn-cancel warning btn-cancle-last-add-num">
                                            <span>ยกเลิก</span><span>ล่าสุด</span></button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="7">7</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="8">8</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="9">9</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-dark btn-block btn-reset">
                                            <span>ล้าง</span><span>ข้อมูล</span></button>
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

                    <div class="group__keyboard" style="">
                        <div class="num-pad box__show-number">
                            <h4 class="text-center">ระบุตัวเลข</h4>
                            <div class="label-number lists">
                                <div id="bet_num">
                                    <label class="number"> </label>
                                    <label class="number"> </label>
                                    <label class="number"> </label>
                                </div>
                            </div>
                            <div class="key-pad box__keyboard">
                                <div class="row p-2">

                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="1">1</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="2">2</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="3">3</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-danger_bkk btn-block" data-id="delete"><i
                                                class="fas fa-backspace"></i></button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="4">4</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="5">5</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="6">6</button>
                                    </div>
                                    <div class="col-3">
                                        <button
                                            class="btn btn-secondary btn-block btn-cancel warning btn-cancle-last-add-num">
                                            <span>ยกเลิก</span><span>ล่าสุด</span></button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="7">7</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="8">8</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-outline-primary btn-block" data-id="9">9</button>
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-dark btn-block btn-reset">
                                            <span>ล้าง</span><span>ข้อมูล</span></button>
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


                    <div class="w-100">
                        <div class="row mb-2 py-3 mx-0 bg-dark rounded">
                            <div class="col pr-1">
                                <button class="btn btn-light btn-sm btn-block number-sets border triggerPoy">
                                    <i class="fas fa-list-ol"></i> ดึงโพย
                                </button>
                            </div>
                            <div class="col pl-1">
                                <button class="btn btn-danger_bkk btn-sm btn-block show-lists triggerPrice">
                                    <i class="fas fa-edit"></i> ใส่ราคา
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="box__condition-info">
                        <h2><i class="fas fa-exclamation-circle"></i> เงื่อนไขการแทง</h2>
                        <div class="row">
                            <?php foreach ($playTypes as $code => $playType) { ?>
                            <div class="col-md-12 d-none" id="content_<?= $code ?>">
                                <h3><?= $playType['title'] ?> จ่าย <?= $playType['jackpot_per_unit'] ?></h3>
                                <p>
                                    แทงขั้นต่ำต่อครั้ง : <?= number_format($playType['minimum_play']) ?><br>
                                    แทงสูงสุดต่อครั้ง : <?= number_format($playType['maximum_play']) ?><br>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 mt-1 flex-column mb-5 d-none"
                id="numpage_2">
                <h3 class="text-center"><i class="sn-icon sn-icon--graph2"></i> ผลรวมยี่กี่</h3>
                <h3 class="text-center" id="total_ying"></h3>
                <div class="form-shot-number">
                    <input placeholder="กรอกตัวเลข 5 หลัก" maxlength="5" type="text" readonly="readonly"
                        class="form-control form-control-lg text-center show-input-number">
                    <div class="key-pad box__keyboard yeekee__number" style="display: none;">
                        <div class="row p-2 group">
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="1">1</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="2">2</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="3">3</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-danger btn-block delete__number" data-id="delete"><i
                                        class="fas fa-backspace"></i></button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="4">4</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="5">5</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="6">6</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-secondary btn-block random__number">
                                    <span>สุ่มเลข</span></button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="7">7</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="8">8</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-outline-primary btn-block" data-id="9">9</button>
                            </div>
                            <div class="col-3">
                                <button class="btn btn-success btn-block submit-number yeekee__submit">เพิ่มเลข</button>
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
                <hr class="mt-1 mb-2">
                <div class="d-flex flex-column yeekee__lists-number" style="display: none;">
                    <h5 class="text-center text-success"><i class="fas fa-th-list"></i> รายชื่อผู้ทายเลข </h5>
                    <span id="yresult"></span>
                    <div class="flex-row justify-content-between align-items-stretch mb-1" id="list_ying-1"
                        style="display:none;">
                        <div
                            class="bg-success text-white text-center rounded p-2 w-25 d-flex flex-column justify-content-around">
                            <span class="badge badge-pill badge-light  text-success">อันดับที่ <span
                                    data-id="ly_ranked"></span></span>
                            <h3 class="mb-0"><span data-id="ly_ying"></span></h3>
                        </div>
                        <div
                            class="border border-success bg-light_bkk rounded p-2 ml-1 flex-fill d-flex flex-column justify-content-around">
                            <div><span class="badge badge-pill badge-light text-success"><i
                                        class="fas fa-user-circle"></i>
                                    ผู้ส่งเลข</span> <span data-id="ly_send"></span></div>
                            <hr class="my-1">
                            <div><span class="badge badge-pill badge-light text-success"><i
                                        class="fas fa-calendar-check"></i>
                                    เมื่อ</span> <span data-id="ly_date"></span></div>
                        </div>
                    </div>
                    <div class="flex-row justify-content-between align-items-stretch mb-1" id="list_ying-0"
                        style="display:none;">
                        <div
                            class="bg-secondary text-white text-center rounded p-2 w-25 d-flex flex-column justify-content-around">
                            <span class="badge badge-pill badge-light">อันดับที่ <span
                                    data-id="ly_ranked"></span></span>
                            <h3 class="mb-0"><span data-id="ly_ying"></span></h3>
                        </div>
                        <div
                            class="border border-secondary bg-light_bkk rounded p-2 ml-1 flex-fill d-flex flex-column justify-content-around">
                            <div><span class="badge badge-pill badge-light"><i class="fas fa-user-circle"></i>
                                    ผู้ส่งเลข</span> <span data-id="ly_send"></span></div>
                            <hr class="my-1">
                            <div><span class="badge badge-pill badge-light"><i class="fas fa-calendar-check"></i>
                                    เมื่อ</span> <span data-id="ly_date"></span></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
<?= $this->render('/yeekee/modal_rule', ['yeekeeGame' => $yeekeeGame, 'game' => $game]) ?>