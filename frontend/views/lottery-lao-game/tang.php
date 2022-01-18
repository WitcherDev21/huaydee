<?php
/* @var $thaiSharedGame \common\models\ThaiSharedGame */
/* @var $settingLotteryLaoSet \common\models\SettingLotteryLaoSet */
/* @var $lotteryConfig array */
use yii\helpers\Url;

$lotteryLaoGameBuyUrl = Url::to(['lottery-lao-game/buy']);
$lotteryLaoPoyUrl = Url::to(['lottery-lao-game/poy', 'id' => $thaiSharedGame->id]);
$gameTitle = $thaiSharedGame->game->title;
$js = <<<EOT
    var lotterys = [];
    var lottery_config = JSON.parse('$lotteryConfig');
    var lotteryLaoGameBuyUrl = '$lotteryLaoGameBuyUrl';
    var thaiSharedGameId = '$thaiSharedGame->id';
    var lotteryLaoPoyUrl = '$lotteryLaoPoyUrl';
    var gameTitle = '$gameTitle';
    window.localStorage.setItem('lottery', JSON.stringify(lotterys));
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
    if (!testTab()) {
      swal({
        title:"กรุณาใช้งานเพียง tap เดียวเท่านั้น"
      });
    }
});', \yii\web\View::POS_READY);
$this->registerJsFile(Yii::getAlias('@web/version6/js/laos/tang_laos.js?1563854294'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
    <div class="bar-back">
        <a href="<?= Url::to(['site/home']) ?>">
            <i class="fas fa-chevron-left"></i> หน้าหลัก
        </a>
    </div>
    <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
        <?= $this->render('_tab', ['thaiSharedGame' => $thaiSharedGame]); ?>
        <div class="w-100 my-2 border-bottom"></div>

        <div class="bgwhitealpha shadow-sm rounded p-2 mb-1 xtarget col-lotto d-flex flex-column flex-sm-column flex-md-row justify-content-between">
            <h4 class="mb-0 text-center">
                <i class="fas fa-star"></i> <?= strpos($thaiSharedGame->title, 'เวียดนาม') ? 'แทงหวยชุดเวียดนาม' : 'แทงหวยชุดลาว' ?>
            </h4>
            <div class="d-flex flex-row flex-sm-row flex-md-row-reverse justify-content-between justify-content-sm-between justify-content-md-end">
                <div class="p-1">
                <span class="badge badge-dark font-weight-light">
                    ประจำวันที่</span> วันที่ <?= date('d M Y', strtotime($thaiSharedGame->endDate)) ?>
                </div>

            </div>
        </div>
        <div class="bg-white shadow-sm rounded py-2 px-1 mb-5">
            <div class="w-100 p-1 mt-2">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="group__keyboard">
                            <div class="num-pad box__show-number">
                                <h4 class="text-center">ระบุตัวเลข 4 หลัก</h4>
                                <div class="label-number lists d-flex justify-content-center">
                                    <label class="number"><span></span></label>
                                    <label class="number"></label>
                                    <label class="number"></label>
                                    <label class="number"></label>
                                </div>
                                <div class="key-pad box__keyboard">
                                    <div class="row p-2">
                                        <?php for ($number = 1; $number <= 3; $number++) { ?>
                                            <div class="col-3">
                                                <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                    <?= $number ?>
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button class="btn btn-danger_bkk btn-block" data-id="delete">
                                                <i class="fas fa-backspace"></i></button>
                                        </div>
                                        <?php for ($number = 4; $number <= 6; $number++) { ?>
                                            <div class="col-3">
                                                <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                    <?= $number ?>
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button class="btn btn-secondary btn-block btn-cancel warning btn-cancle-last-add-num">
                                                <span>ยกเลิก</span>
                                                <span>ล่าสุด</span>
                                            </button>
                                        </div>
                                        <?php for ($number = 7; $number <= 9; $number++) { ?>
                                            <div class="col-3">
                                                <button class="btn btn-outline-primary btn-block" data-id="<?= $number ?>">
                                                    <?= $number ?>
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="col-3">
                                            <button class="btn btn-dark btn-block btn-reset btn-reset-lottery">
                                                <span>ล้าง</span><span>ข้อมูล</span>
                                            </button>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-blank" disabled=""></button>
                                        </div>
                                        <div class="col-3">
                                            <button class="btn btn-outline-primary btn-block" data-id="0">
                                                0
                                            </button>
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
                    </div>

                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row p-0 m-0">
                            <div class="col-12 p-1">
                                <div class="w-100 rounded border p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-clipboard-list"></i> รายการแทง</span>
                                        <span class="badge badge-secondary font-weight-normal">ราคาชุดละ <?= $settingLotteryLaoSet->value ?> บาท</span>
                                    </div>
                                    <div class="w-100 border border-secondary border-bottom-0 m-1"></div>
                                    <div class="d-flex justify-content-between">
                                        <span>ชุดเลข</span>
                                        <span>จำนวนชุด</span>
                                    </div>
                                    <span id="lottery_list">
                                    <div class="d-none justify-content-between align-items-center">
                                        <h1 style="letter-spacing: 10px;">{lottery-number}</h1>
                                        <div class="input-group w-50">
                                            <input type="tel" class="form-control text-right lottery_amount" placeholder="" aria-label="" aria-describedby="basic-addon2" min="1" max="1000" onKeyUp="check_max_set({lottery-id})" value="{lottery-amount}" id="lottery_amount_{lottery-id}" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">ชุด</span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                                </div>
                                <div class="d-flex flex-row flex-sm-row flex-md-row-reverse justify-content-between justify-content-sm-between justify-content-md-end">
                                    <div class="p-1">
                                        <span class="badge badge-dark font-weight-light">ประจำวันที่</span>
                                        วันที่ <?= date('d M Y', strtotime($thaiSharedGame->endDate)) ?>
                                    </div>

                                </div>
                            </div>
                            <div class="col-6 p-1">
                                <div class="w-100 rounded table-primary p-1 text-center">
                                    ใช้ Credit ทั้งหมด
                                    <h4 class="mb-0 total_price">0</h4>
                                </div>
                            </div>
                            <div class="col-6 p-1">
                                <div class="w-100 rounded table-secondary p-1 text-center">
                                    Credit คงเหลือ
                                    <h4 class="mb-0 my-balance">
                                        <?= number_format($user->getCreditBalance(),2) ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-12 p-1">
                                <button class="btn btn-success_bkk btn-block triggerSendLottery">ยืนยัน</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->render('modal_rule', ['thaiSharedGame' => $thaiSharedGame]) ?>