<?php
/**
 * @var array $arrSpecialPlay
 * @var array $groupPlayType
 * @var $resultChitList
 * @var $numberMemoList
 * @var $total
 * @var $thaiSharedGame
 */

use common\models\ThaiSharedGameChit;
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\libs\Constants;
use common\models\LimitLotteryByGamePlayTypeSet;
use common\models\LimitLotteryByGamePlayType;

$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;

$post_frequency_per_sec = Constants::post_frequency_per_sec;
$swal_width = 50;

?>
    <form id="post_form" action="<?= Url::toRoute(['lottery-game/buy']) ?>" method="post">
        <?= Html::hiddenInput('thaiSharedGameId', $thaiSharedGame->id) ?>
        <?= Html::hiddenInput('play_data', '') ?>
        <?= Html::hiddenInput($csrf, $token) ?>
    </form>

    <div class="col-md-12 ">
        <div class="card">
            <div class="card-body">

                <h4>
                    <a href="<?= Url::toRoute(['lotto/index']) ?>" style="color: red">แทงหวย</a>/
                    <?= $thaiSharedGame->game->title . ' ' . $thaiSharedGame->round ?>
                </h4>
                <nav class="nav nav-pills nav-justified">
                    <a class="nav-link group-play active" href="#tap-content-1" data-toggle="tab"
                       data-group_id="1"
                       data-href-id="tap-content-1">สามตัว</a>
                    <a class="nav-link group-play" href="#tap-content-6" data-toggle="tab"
                       data-group_id="6"
                       data-href-id="tap-content-4">สามตัวหมุน 2 ครั้ง</a>
                    <a class="nav-link group-play" href="#tap-content-2" data-toggle="tab"
                       data-group_id="2"
                       data-href-id="tap-content-2">สองตัว</a>
                    <a class="nav-link group-play" href="#tap-content-3" data-toggle="tab"
                       data-group_id="3"
                       data-href-id="tap-content-3">เลขวิ่ง</a>
                    <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#exampleModal">เลขชุด/ดึงโพย</a>
                    <a class="nav-link group-play" href="#tap-content-rule"
                       data-toggle="tab">อัตราการจ่าย <?= $thaiSharedGame->game->title . ' ' . $thaiSharedGame->round ?></a>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-sm-12">
        <div class="tab-content" id="daimond-content">
            <div class="row tab-pane animated fadeIn" id="tap-content-rule">
                <div class="col-xs-12">
                    <div class="card">
                        <h4 class="card-header bg-transparent">
                            กติกา <?= $thaiSharedGame->game->title . ' ' . $thaiSharedGame->round ?></h4>
                        <div class="card-body">
                            <?= $thaiSharedGame->description ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            foreach ($groupPlayType as $i1 => $group) {
                $split = 10;
                if (in_array($group ['number_length'], [1, 2])) {
                    $split = 1;
                }
                ?>
                <div class="row tab-pane <?= $i1 == 0 ? 'active' : '' ?>" id="tap-content-<?= $group['group_id'] ?>">
                    <div class="col-xs-12">
                        <div class="card">
                            <div class="card-header bg-transparent">
                                <h4 class="col-xs-6">ปิดรับ <?= $thaiSharedGame->endDate ?></h4>
                                <div class="col-xs-6">
                                    <form class="navbar-form navbar-right">
                                        <div class="form-group">
                                            <div class="input-group">
										<span id="basic-addon1" class="input-group-addon"><i
                                                    class="fa fa-search"></i></span>
                                                <input number="" type="number" placeholder="ค้นหาตัวเลข"
                                                       data-group_id="<?= $group['group_id'] ?>"
                                                       class="form-control fillter-number">
                                            </div>
                                        </div>
                                        <button type="button" class="btn  btn-default btn-swap" data-selected="0"
                                                data-group_id="<?= $group['group_id'] ?>">
                                            <i class="fa fa-fw fa-random"></i>กลับเลข
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row" style="">
                                    <?php foreach ($group['play_type_list'] as $playType) {
                                        $limitLotteryGamePlayTypeSet = LimitLotteryByGamePlayTypeSet::find()->where([
                                            'playTypeId' => $playType['playTypeId'],
                                            'title' => $thaiSharedGame->title,
                                            'status' => 1
                                        ])->one();
                                        $level = 0;
                                        $total = 0;
                                        if ($limitLotteryGamePlayTypeSet) {
                                            $thaiSharedGameChit = ThaiSharedGameChit::find()->select('sum(amount) as totalAmount, sum(discount) as totalDiscount')
                                                ->joinWith('thaiSharedGameChitDetails')->where([
                                                    'playTypeId' => $playType['playTypeId'],
                                                    'thaiSharedGameId' => $thaiSharedGame->id
                                                ])->andWhere(['!=', 'status', Constants::status_cancel])->one();
                                            if ($thaiSharedGameChit->totalDiscount > 0) {
                                                $total = $thaiSharedGameChit->totalDiscount;
                                            } else if ($thaiSharedGameChit->totalAmount > 0) {
                                                $total = $thaiSharedGameChit->totalAmount;
                                            }
                                            $min = $total + 1;
                                            $max = $total + 1;
                                            $limitLotteryByGamePlayTypes = LimitLotteryByGamePlayType::find()->where([
                                                'limitLotteryGamePlayTypeSetId' => $limitLotteryGamePlayTypeSet->id])
                                                ->andWhere(['<=', 'min', $min])->andWhere(['>=', 'max', $max])
                                                ->one();
                                            $level = $limitLotteryByGamePlayTypes->level;
                                        }
                                        ?>
                                        <div class="col-xs-5">
                                            <button type="button"
                                                    class="btn btn-block btn-default play-type btn-lg"
                                                    data-selected="0"
                                                    style="font-size: 13px;"
                                                    data-min=<?= $playType['min'] ?>
                                                    data-max=<?= $playType['max'] ?>
                                                    data-group_id="<?= $group['group_id'] ?>"
                                                    data-play_type="<?= $playType['code'] ?>"
                                                    data-level="<?= $level ?>">
                                                <?php echo $playType['title'] . '(' . $playType['jackpot'] . ')'; ?>
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="overlay-disable" style="margin-top: 4px;"
                                         id="overlay-disable_<?= $group['group_id'] ?>"></div>
                                    <div class="col-xs-12">
                                        <!-- ------spacial----------- -->
                                        <?php if ($group['number_range'] == 100) { ?>
                                            <hr>
                                            <div class="row">
                                                <?php foreach ($arrSpecialPlay as $special) { ?>
                                                    <div class="col-md-4 text-center m-t-md">
                                                        <strong><?= $special['title'] ?></strong>
                                                        <div class="btn-group">
                                                            <?php foreach ($special['set_num'] as $num) { ?>
                                                                <button type="button"
                                                                        class="btn btn-default btn-sm special-play"
                                                                        data-code="<?= $special['code'] ?>"
                                                                        data-num="<?= $num ?>"
                                                                        data-group_id="<?= $group['group_id'] ?>"
                                                                ><?= $num ?></button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <p class="text-center text-danger" style="margin-top: 5px;"><strong>โหมดการเล่น
                                                    19 ประตู, รูดหน้า และรูดหลัง ระบบการเล่นเลขที่ซ้ำ จะคิดเพียงเลขเดียว
                                                    (หากต้องการแทงเลขซ้ำ ให้แทงโพยทีละครั้ง)</strong></p>
                                        <?php } ?>
                                        <!-- ----------------- -->
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="tabbable">
                                            <?php if ($split != 1) { ?>
                                                <ul class="nav nav-tabs nav-justified">
                                                    <?php for ($i = 0; $i < $group['number_range']; $i += ($group['number_range'] / $split)) { ?>
                                                        <li class="<?= $i == 0 ? ' active' : '' ?>">
                                                            <a href="#tab_<?= $group['group_id'] . '_' . $i ?>"
                                                               data-toggle="tab"
                                                               class="btn-block"><?= str_pad($i, $group['number_length'], '0', STR_PAD_LEFT) ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                            <div class="tab-content">
                                                <?php
                                                foreach ($group['play_type_list'] as $playType) {
                                                    for ($i = 0; $i < $group['number_range']; $i += ($group['number_range'] / $split)) { ?>
                                                        <div class="tab-pane animated fadeIn <?= $i == 0 ? 'active' : '' ?>"
                                                             id="tab_<?= $group['group_id'] . '_' . $i ?>">
                                                            <div class="row" style="margin-top: 10px;">
                                                                <div class="col-xs-12">
                                                                    <?php for ($k = 0; $k < ($group['number_range'] / $split); $k++) { ?>
                                                                        <div class="column">
                                                                            <button type="button"
                                                                                    class="btn btn-block btn-default number-play"
                                                                                    data-selected="0"
                                                                                    data-group_id="<?= $group['group_id'] ?>"
                                                                                    data-play_type="<?= $playType['code'] ?>"
                                                                            ><?= str_pad($k + $i, $group['number_length'], '0', STR_PAD_LEFT) ?></button>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <h4 class="card-header bg-transparent">รายการแทง
                        <a href="javascript:;" data-toggle="modal" data-target="#exampleModal"
                           class="pull-right btn btn-warning">เลขชุด/ดึงโพย</a>
                    </h4>
                    <div class="card-body">
                        <form class="from-control" id='form-play'>
                            <p>ยังไม่มีรายการแทง</p>
                        </form>

                        <div class="panel panel-default" style="display:none;" id="set-all-price">
                            <div class="panel-heading">
                                <h3 class="panel-title">ปรับเท่ากันหมด</h3>
                            </div>
                            <div class="panel-body">
                                <div class="input-group">
                                    <input name="set_all_price" type="number" value="1" class="form-control" min="1"
                                           max="500">
                                    <span class="input-group-btn">
										<button class="btn btn-success" type="button" id="btn-set-all-price">
											<i class="fa fa-refresh"></i>
										</button>
								</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-xs-12">
                            <strong class="discount_play" style="display:none;">ส่วนลด : <span
                                        class="discount_play_number"></span></strong>
                            <?= \yii\helpers\Html::input('hidden', 'discount_play_number', '') ?>
                        </div>
                        <div class="col-xs-12">
                            <strong class="total_play" style="display:none;">จำนวนเงินทั้งหมด : <span
                                        class="total_play_number"></span></strong>
                            <?= \yii\helpers\Html::input('hidden', 'total_play_number', '') ?>
                            <p></p>
                        </div>
                        <div class="col-xs-12">
                            <strong class="sum_play" style="display:none;">คงเหลือ : <span
                                        class="sum_play_number"></span></strong>
                            <?= \yii\helpers\Html::input('hidden', 'sum_play_number', '') ?>
                            <p></p>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <button type="button" class="btn btn-primary btn-block post_number-btn"
                                    style="display:none;" data-toggle="modal" data-target="#play-confirm">แทงพนัน
                            </button>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <button type="button" class="btn btn-danger btn-block clear_number" style="display:none;">
                                ล้างข้อมูล
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <h4 class="card-header bg-transparent">เงื่อนไขการแทง</h4>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($allPlayType as $playType) {
                                $total = 0;
                                $limitLotteryGamePlayTypeSet = LimitLotteryByGamePlayTypeSet::find()->where([
                                    'playTypeId' => $playType['playTypeId'],
                                    'title' => $thaiSharedGame->title,
                                    'status' => 1
                                ])->one();
                                if ($limitLotteryGamePlayTypeSet) {
                                    $thaiSharedGameChit = ThaiSharedGameChit::find()->select('sum(amount) as totalAmount, sum(discount) as totalDiscount')
                                        ->joinWith('thaiSharedGameChitDetails')->where([
                                            'playTypeId' => $playType['playTypeId'],
                                            'thaiSharedGameId' => $thaiSharedGame->id
                                        ])->andWhere(['!=', 'status', Constants::status_cancel])->one();
                                    if ($thaiSharedGameChit->totalDiscount > 0) {
                                        $total = $thaiSharedGameChit->totalDiscount;
                                    } else if ($thaiSharedGameChit->totalAmount > 0) {
                                        $total = $thaiSharedGameChit->totalAmount;
                                    }
                                    $min = $total + 1;
                                    $max = $total + 1;
                                    $limitLotteryByGamePlayTypeObj = LimitLotteryByGamePlayType::find()->where([
                                        'limitLotteryGamePlayTypeSetId' => $limitLotteryGamePlayTypeSet->id])
                                        ->andWhere(['<=', 'min', $min])->andWhere(['>=', 'max', $max])
                                        ->one();
                                    ?>
                                    <div class="col-xs-12 condition-play-type" style="display: none;"
                                         id="condition-<?= $playType['code'] ?>">
                                        <h4 class="no-margins"><?= $playType['title'] ?></h4>
                                        แทงขั้นต่ำ : <?= number_format($playType['min']) ?> <br>
                                        แทงสูงสุดต่อครั้ง : <?= number_format($playType['max']) ?> <br>
                                        <hr>
                                        <?php if ($limitLotteryByGamePlayTypeObj) {
                                            $limitLotteryByGamePlayTypeArray[$playType['playTypeId']] = [
                                                'level' => $limitLotteryByGamePlayTypeObj->level,
                                                'min' => number_format($limitLotteryByGamePlayTypeObj->min),
                                                'max' => number_format($limitLotteryByGamePlayTypeObj->max),
                                                'totalBuy' => $limitLotteryByGamePlayTypeObj->max - $total,
                                            ];
                                            ?>
                                            <h4 class="no-margins">
                                                Level: <?= $limitLotteryByGamePlayTypeObj->level ?></h4>
                                            แทงขั้นต่ำ : <?= number_format($limitLotteryByGamePlayTypeObj->min) ?> <br>
                                            แทงสูงสุดต่อครั้ง : <?= number_format($limitLotteryByGamePlayTypeObj->max) ?>
                                            <br>
                                            ยอดคงเหลือที่จะแทงได้: <?= $limitLotteryByGamePlayTypeObj->max - $total ?>
                                            <br>
                                            <hr>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-xs-12 condition-play-type" style="display: none;"
                                         id="condition-<?= $playType['code'] ?>">
                                        <h4 class="no-margins"><?= $playType['title'] ?></h4>
                                        แทงขั้นต่ำ : <?= number_format($playType['min']) ?> <br>
                                        แทงสูงสุดต่อครั้ง : <?= number_format($playType['max']) ?> <br>
                                        <hr>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

<?= $this->render('_modal-pull-chit', [
    'resultChitList' => $resultChitList,
    'numberMemoList' => $numberMemoList
]); ?>

<?= $this->render('_modal-confirm', [
    'thaiSharedGame' => $thaiSharedGame
]); ?>

<?php
$allPlayType = [];
foreach ($groupPlayType as $arrPlayType) {
    foreach ($arrPlayType['play_type_list'] as $playType) {
        $allPlayType[$playType['code']] = $playType;
    }
}

$objPlayType = json_encode($allPlayType);
$limitLotteryByGamePlayTypeJsons = json_encode($limitLotteryByGamePlayTypeArray);
$minimum_set_all = Constants::minimum_set_all;
$maximum_set_all = Constants::maximum_set_all;
$urlLimitLotteryGamePlayType = Url::to(['api-check-lottery-condition-limit-game-play-type/limit-lottery-game-play-type']);
$js = <<<EOT
var objPlayType = $objPlayType;
var minimum_set_all = $minimum_set_all;
var maximum_set_all = $maximum_set_all;
var post_frequency_per_sec = $post_frequency_per_sec;
var urlLimitLotteryGamePlayType = '$urlLimitLotteryGamePlayType';
var titleLotteryByGame = '$thaiSharedGame->title';
var thaiSharedGameId = $thaiSharedGame->id;
var limitLotteryByGamePlayTypeJsons = $limitLotteryByGamePlayTypeJsons;
var x = setInterval(function() {
	  		var now = new Date().getTime();
	  		var countDownDate = $('#countdown-item').data('finish_at')*1000; //php to java time
		  	var distance = countDownDate - now;

		  	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  	var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		  	var h = (hours<10?'0':'') + hours;
      		var m = (minutes<10?'0':'') + minutes;
			var s = (seconds<10?'0':'') + seconds;

			var text = h+':'+m+':'+s;
		  	$('#countdown-item').text(text);

		  if (distance < 0) {
			clearInterval(x);
		    $('#countdown-item').text('ปิดรับแทง');
		    $('#send-yeekeepost').hide();
		  }

	}, 1000);
		     
	$('#send-yeekeepost').on('submit', function(e){

		if($('input[name="post_num"]').val().length != 5){
            swal({
              width: '$swal_width%',
              title: 'Warning!',
              html: '<div style="font-size:18px;">'+'กรุณากรอกให้ครบ 5 ตัว'+'</div>',
              type: 'warning',
              confirmButtonText: 'OK'
            });

			$('input[name="post_num"]').focus();
			e.preventDefault();
			return;
		}

		var countdown_repost = post_frequency_per_sec;
		$('#btn-save').prop('disabled',true);
		$('#btn-save').toggleClass('btn-success btn-default');
		$('#btn-save').text(countdown_repost);

		var disable_post = setInterval(function() {
			if(countdown_repost <= 0){
				clearInterval(disable_post);
				$('#btn-save').prop('disabled',false);
				$('#btn-save').toggleClass('btn-default btn-success');
				$('#btn-save').text('เพิ่มเลข');
			}else{
				countdown_repost--;
				$('#btn-save').text(countdown_repost);
		
			}
		},1000);

		var form = $(this);
		var formData = {
				'$csrf':'$token',
				post_num: $('input[name="post_num"]').val(),
				yeekee_id: $('input[name="yeekee_id"]').val()
		};

	    $.ajax({
	        url: form.attr("action"),
	        type: form.attr("method"),
	        data: formData,
	        success: function (result) {
				if(result.result == true){
					$('input[name="post_num"]').val('');
					$('#yeekee-post>tbody>tr').remove();
	
					$(result['data']).each(function(index,item){
	
						item = `<tr class="`+ item['class'] +`">`+
					      `<th scope="row">`+ item['running'] +`</th>`+
					      `<td>` + item['post_num'] + `</td>` +
					      `<td>` + item['post_name'] + `</td>` +
					      `<td>` + item['send_at'] + `</td>` +
					     `</tr>`;
	
						$('#yeekee-post>tbody').append(item);
					});
					$('#sum-result').text(result['sum_result']);
				}else{
                    swal({
                      width: '$swal_width%',
                      title: 'Warning!',
                      html: '<div style="font-size:18px;">'+'คุณยิงถี่เกินไป กรุณารอครบ '+countdown_repost+' sec. ก่อน'+'</div>',
                      type: 'warning',
                      confirmButtonText: 'OK'
                    });
				}
	        },
	        error: function () {
	            swal({
                  width: '$swal_width%',
                  title: 'Warning!',
                  text: 'Some thing error!',
                  type: 'warning',
                  confirmButtonText: 'OK'
                });
	        }
	    });

	    e.preventDefault();
	});

//play-type click
$('.play-type').on('click',function(){
	btn = $(this);
	group_id = btn.data('group_id');
	if(btn.data('selected') == '0'){
		btn.data('selected','1');
		btn.addClass('btn-primary');

	}else{
		btn.data('selected','0');
		btn.removeClass('btn-primary');						
	}
	displayOverlay(group_id);
	displayButton(group_id);	
	displayCondition();
});

							
function displayOverlay(group_id){
	selected_play_type = $('.play-type[data-group_id="'+group_id+'"].btn-primary');	
	var level;
	$(selected_play_type).each(function(i, item) {
	        var level = $(item).data('level');
	    if (level >= 0) {
            if (level == 0) {
                $('.btn.btn-block.btn-default.number-play').css('background-color','');
            }else if (level == 1) {
                $('.btn.btn-block.btn-default.number-play').css('background-color','');
            }else if (level == 2) {
                $('.btn.btn-block.btn-default.number-play').css('background-color','#FFFFC2');
            }else if (level == 3) {
                $('.btn.btn-block.btn-default.number-play').css('background-color','#FDD017');
            }else if (level == 4) {
                $('.btn.btn-block.btn-default.number-play').css('background-color','#F70D1A');
            }
        }
    });
	if(selected_play_type.length<=0){
		$('#overlay-disable_'+ group_id).show();					
	}else{
		$('#overlay-disable_'+ group_id).hide();				
	}
}

//click tab nav number group
$('.nav-li-number').on('click',function(){
	$($(this).parent().find('li.active')).removeClass('active');
	$(this).addClass('active');	
});

$('.fillter-number').on('keyup',function(){
	searchFillter($(this));
});
$('.fillter-number').on('change',function(){
	searchFillter($(this));
});
function searchFillter(input_search){
	group_id = input_search.data('group_id');
	num = input_search.val();	
	$('.number-play').show();
	$('.number-play:not(:contains("'+num+'"))[data-group_id="'+group_id+'"]').hide();	
}
							
//selecting number in game
$('.number-play').on('click',function(e){
				
	btn_num = $(this);
	group_id = btn_num.data('group_id');		
	is_swap = $('.btn-swap[data-group_id="'+group_id+'"]').attr('data-selected');


	if(is_swap == 1){
		if($(btn_num).hasClass('btn-info')){
            if($(btn_num).hasClass('btn-info')){
                swal({
                  width: '$swal_width%',
                  title: '',
                  html: "<div style='font-size:18px;'>การกดแทงเลขที่เคยแทงแล้ว จะเป็นการยกเลิกการแทงเลขนั่นๆ, <br>คุณต้องการยกเลิก ใช่ หรือ ไม่ใช่ ?</div>",
                  type: 'info',
                  showCancelButton: true,
                  confirmButtonText: 'ใช่',
                  cancelButtonText: 'ไม่ใช่'
                }).then((result) => {
                  if (result.value) {
        		      pickedNumber(btn_num);	
                      displayButton(group_id);	
                  }
                });
            }else{
                pickedNumber(btn_num);
                displayButton(group_id);
            }			
		}else{
			str_num = btn_num.text();
			arr_num = str_num.split('');
			arr_swap_num = [];
			n = 0;
			for(i = 0; i< arr_num.length; i ++){
				tmp = arr_num[i];			
				for(j = 0; j < arr_num.length; j ++){				
					if(i!=j){
						tmp += ''+arr_num[j];
					}								
				}
				if(arr_swap_num.indexOf(tmp)<0){
					arr_swap_num[n++] = tmp;
				}
								
				tmp = arr_num[i];
				for(j = (arr_num.length - 1); j >= 0; j --){
					if(i!=j){
						tmp += ''+arr_num[j];
					}				
				}	
				if(arr_swap_num.indexOf(tmp)<0){
					arr_swap_num[n++] = tmp;
				}
			}

			$.each(arr_swap_num,function(i,num){	
				btn_num = $('.number-play:contains("'+num+'")');							
				results = btn_num.filter(function( index,btn ) {
			    	if($(btn).text() == num){
						return btn;			
					}
			  	});		
				pickedNumber($(results[0]));
			});		
            displayButton(group_id);						
		}

	}else{
        if($(btn_num).hasClass('btn-info')){
            swal({
              width: '$swal_width%',
              title: '',
              html: "<div style='font-size:18px;'>การกดแทงเลขที่เคยแทงแล้ว จะเป็นการยกเลิกการแทงเลขนั่นๆ, <br>คุณต้องการยกเลิก ใช่ หรือ ไม่ใช่ ?</div>",
              type: 'info',
              showCancelButton: true,
              confirmButtonText: 'ใช่',
              cancelButtonText: 'ไม่ใช่'
            }).then((result) => {
              if (result.value) {
    		      pickedNumber(btn_num);	
                  displayButton(group_id);	
              }
            });
        }else{
            pickedNumber(btn_num);
            displayButton(group_id);
        }
	}
	//displayButton(group_id);					
});

function pickedNumber(btn_num){
	if($(btn_num).hasClass('btn-info')){
		btn_num.data('selected',0);						
	}else{
		btn_num.data('selected',1);
	}
	group_id = btn_num.data('group_id');						
	number = btn_num.text();
	
	selected_play_type = $('.play-type[data-group_id="'+group_id+'"].btn-primary');					
							
	old_text = $('input[name="play_data"]').val();
	old_obj = {};
							
	if(old_text != ''){
		old_obj = JSON.parse(old_text);
	}
				
	selected_play_type.each(function(i,item){
		key = $(item).data('play_type');
		min = $(item).data('min');
						
		old_data = {};			
		if(old_obj[key] != undefined){
			old_data = JSON.parse(old_obj[key]);
		}			

		new_data = {};
		if(btn_num.data('selected')==0){
			$.each(old_data,function(filter_number,value){
				if(number != filter_number){
					new_data[filter_number] = value;	
				}
			});	
			old_data = new_data;
		}else{
			old_data[number] = min;
		}
		strdata = JSON.stringify(old_data);
		old_obj[key] = strdata;
	});							
							
	data = JSON.stringify(old_obj);
	$('input[name="play_data"]').val(data);							
}

$('div').delegate('.btn-delete','click',function(){
	btn = $(this); 
	data = $('input[name="play_data"]').val();
	old_obj = {};
	if(data != ''){
		old_obj = JSON.parse(data);
	}								
	code = btn.data('code');
	number = btn.data('number');
	group_id = btn.data('group_id');
	
	$.each(old_obj,function(i,item){
		old_obj2 = {};
		if(item != ''){
			old_obj2 = JSON.parse(item);
		}	
		if(code == i){
			delete old_obj2[number]; 
		}		
		strdata = JSON.stringify(old_obj2);
		old_obj[i] = strdata;
	});
	data = JSON.stringify(old_obj);
	$('input[name="play_data"]').val(data);
	displayButton(group_id);
});
								
$('div').delegate('.play-number','keyup',function(e){
	input = $(this); 
	data = $('input[name="play_data"]').val();
    max = input.attr('max');
    min = input.attr('min');
    code = input.attr('data-code');
    number = input.attr('data-number');
    playTypeTitle = objPlayType[code];
    title = playTypeTitle.title;
	old_obj = {};
	if(data != ''){
		old_obj = JSON.parse(data);
	}								
	code = input.data('code');
	number = input.data('number');
	group_id = input.data('group_id');
	value = input.val();
	
	$.each(old_obj,function(i,item){
		old_obj2 = {};
		if(item != ''){
			old_obj2 = JSON.parse(item);
		}	
		if(code == i){
			old_obj2[number] = value; 
		}		
		strdata = JSON.stringify(old_obj2);
		old_obj[i] = strdata;
	});
	data = JSON.stringify(old_obj);
	$('input[name="play_data"]').val(data);			
});
	
$('div').delegate('.play-number','change',function(e){
    input = $(this);
    group_id = $(this).data('group_id');
    if($(this).val() == '' || $(this).val() % 1 != 0){
        swal({
              width: '$swal_width%',
              title: 'กรุณาระบุเป็นตัวเลข',
              type: 'warning',
              confirmButtonText: 'close'
        });
        input.val(input.attr('min'));
        e.preventDefault();
		return false;
    }else{
        displayButton(group_id);
    }
});							
	
//special play
$('.special-play').on('click',function(){
	btn = $(this);
	group_id = btn.data('group_id');
	selected_play_type = $('.play-type[data-group_id="'+group_id+'"].btn-primary');
								
	num = btn.data('num');
	code = btn.data('code');
								
	old_text = $('input[name="play_data"]').val();
	old_obj = {};
							
	if(old_text != ''){
		old_obj = JSON.parse(old_text);
	}
	
	selected_play_type.each(function(i,item){
		key = $(item).data('play_type');
		min = $(item).data('min');
		
		old_obj2 = {};
		if(old_obj[key] != undefined){
			old_obj2 = JSON.parse(old_obj[key]);
		}
								
		if(code == 'door19'){
			
			for(i=0;i<10;i++){				
				play_num = num+''+i;
				if(old_obj2[play_num] == undefined){
					old_obj2[play_num] = min;
				}
				play_num = i+''+num;
				if(old_obj2[play_num] == undefined){
					old_obj2[play_num] = min;
				}
			}
			old_obj[key] = JSON.stringify(old_obj2);
		}else if(code == 'rood_front'){
			for(i=0;i<10;i++){				
				play_num = num+''+i;
				if(old_obj2[play_num] == undefined){
					old_obj2[play_num] = min;
				}
			}
			old_obj[key] = JSON.stringify(old_obj2);
		}else if(code == 'rood_back'){
			for(i=0;i<10;i++){				
				play_num = i+''+num;
				if(old_obj2[play_num] == undefined){
					old_obj2[play_num] = min;
				}
			}
			old_obj[key] = JSON.stringify(old_obj2);
		}										
	});
								
	data = JSON.stringify(old_obj);
	$('input[name="play_data"]').val(data);
	displayButton(group_id);	
});
$('.clear_number').on('click',function(){

    swal({
      width: '$swal_width%',
      title: 'ยืนยันการล้างข้อมูล',
      html: "<div style='font-size:18px;'>ลบชุดตัวเลขที่คุณได้เลือกไว้ทั้งหมด</div>",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ใช่',
      cancelButtonText: 'ไม่ใช่'
    }).then((result) => {
      if (result.value) {
	      $('input[name="play_data"]').val('');
	      $('input[name="set_all_price"]').val('1');
		  displayButton('');
      }
    });
});

function displayButton(group_id){
	selected_play_type = [];
	if(typeof(group_id) != undefined){
		selected_play_type = $('.play-type[data-group_id="'+group_id+'"].btn-primary');	
	}	
	$('.number-play').removeClass('btn-info');
							
	data = $('input[name="play_data"]').val();
	obj = {};
	if(data != ''){
		obj = JSON.parse(data);
	}
			
	data_draw = {};
	$.each(selected_play_type,function(i,item){
		key = $(item).data('play_type');
		data_draw[key] = obj[key];				
	});
							
	$.each(data_draw,function(key,item){
		if(item != undefined){
			data = JSON.parse(item);	
			$.each(data,function(number,value){
			    $('.number-play:contains("'+number+'")').css('background-color', '');
				$('.number-play:contains("'+number+'")').addClass('btn-info');
			});	
		}
	});
			
	str = '';
    str_confirm = '';
    total_play = 0;
    discount_play = 0;
    three_top_play = 0;
    three_tod_play = 0;
    three_top2_play = 0;
    three_under2_play = 0;
    two_top_play = 0;
    two_under_play = 0;
    run_top_play = 0;
    run_under_play = 0;
    run_top = 0;
    run_under = 0;
    discount_play_three_top = 0;
    discount_play_three_tod = 0;
    discount_play_three_top2 = 0;
    discount_play_three_tod2 = 0;
    discount_play_two_top = 0;
    discount_play_two_under = 0;
    discount_play_run_top = 0;
    discount_play_run_under = 0;
    total_discount = 0;
	$.each(obj,function(code,item){
		playType = objPlayType[code];
		title = playType.title;
		jeckpot = playType.jackpot;
        var win = jeckpot; 
        code = playType.code;
		min = playType.min;
		max = playType.max;
		playTypeId = playType.playTypeId;
								
		data = JSON.parse(item);
		str2 = '';		
        str_confirm_sub = '';	
		$.each(data,function(number,value){
		    if (value === 'memo-minimum') {
		        value = min;
		    }
            check = 0;
            if((1*value) > (1*max)){
                value = max;
                check = 1;
            }
            if((1*value) < (1*min)){
                value = min;
                check = 1;
            }
            if (limitLotteryByGamePlayTypeJsons) {
                var jsonLimitLotteryGamePlayType = limitLotteryByGamePlayTypeJsons[playTypeId];
                if (jsonLimitLotteryGamePlayType) {
                    if((1*value) > (1*jsonLimitLotteryGamePlayType.totalBuy)){
                        value = (1*jsonLimitLotteryGamePlayType.totalBuy);
                        check = 2;
                    }
                }
            }
            if(check == 1){
                swal({
                    width: '$swal_width%',
                    title: 'ใส่ราคาไม่ตรงเงื่อนไข!</br>กรุณาตรวจสอบยอดแทงขั้นต่ำ',
                    html: '<div style="font-size:18px;">เลข  '+number+' '+title+' ใส่ราคาได้ต่ำสุด '+ min + ' บาท  ใส่ราคาได้ไม่เกิน '+ max+' บาท</div>',
                    type: 'warning',
                    confirmButtonText: 'close'
                });
            }else if(check == 2) {
                console.log(value);
                swal({
                    width: '$swal_width%',
                    title: 'ใส่ราคาไม่ตรงเงื่อนไขหวยอั่น!</br> กรุณาตรวจสอบเงื่อนไขการแทง',
                    html: '<div style="font-size:18px;">Level: ' + jsonLimitLotteryGamePlayType.level + 'ใส่ราคาได้ไม่เกิน '+ jsonLimitLotteryGamePlayType.totalBuy + ' บาท</div>',
                    type: 'warning',
                    confirmButtonText: 'close'
                });
            }
            discount_play = playType.discount;
            if (code == 'three_top') {
                three_top_play += 1*value;
                if (discount_play > 0) {
                    discount_play_three_top = three_top_play * discount_play / 100;
                }
            }else if (code == 'three_tod') {
                three_tod_play += 1*value;
                discount_play_three_tod = three_tod_play * discount_play / 100;
			}else if (code == 'three_top2') {
			    three_top2_play += 1*value;
                discount_play_three_top2 = three_top2_play * discount_play / 100;
			}else if (code == 'three_und2') {
			    three_under2_play += 1*value;
			    discount_play_three_tod2 = three_under2_play * discount_play / 100;
			}else if (code == 'two_top') {
			    two_top_play += 1*value;
			    discount_play_two_top = two_top_play * discount_play / 100;
			}else if (code == 'two_under') {
			    two_under_play += 1*value;
			    discount_play_two_under = two_under_play * discount_play / 100;
			}else if (code == 'run_top') {
			    run_top_play += 1*value;
			    discount_play_run_top = run_top_play * discount_play / 100;
			}else if (code == 'run_under') {
			    run_under_play += 1*value;
			    discount_play_run_under = run_under_play * discount_play / 100;
			}
			total_discount = discount_play_three_top + discount_play_three_tod + discount_play_three_top2 + discount_play_three_tod2 + discount_play_two_top + discount_play_two_under + discount_play_run_top + discount_play_run_under;
			total_play += 1*value;
			str2 += `
			<div class="input-group">
				<span class="input-group-addon">`+ number +`</span> 
					<input type="number" value=` + value + ` class="form-control play-number"
						data-code="`+ code +`"
						data-number="`+ number +`"
						data-group_id="`+ group_id +`"
						data-value="`+ value +`"
						min="`+ min +`"
						max="`+ max +`"
						>								
						<span class="input-group-addon" style="padding:5px;"> ชนะ `+ (win * value).toFixed(2) +`</span>
						<span class="input-group-btn">
						<button class="btn btn-danger btn-delete" type="button"
							data-code="`+ code +`"
							data-number="`+ number +`"
							data-group_id="`+ group_id +`"
						>
							<i class="fa fa-trash"></i>
						</button>
				</span>
			</div>`;
            
            str_confirm_sub += 
                       `<div class="row">
        					<div class="col-xs-6 m-t-none m-b-xs">แทงเลข `+ number +`</div>
        					<div class="col-xs-6 text-right">`+ value +`</div>
        				</div>`;
		});
						
		str += 
		`<div class="panel panel-default">
			<div class="panel-heading">								
				<h3 class="panel-title">`+ title +`</h3>			
			</div>
			<div class="panel-body">`
				+ str2 +
			`</div>
		</div>`;

        str_confirm += 
           `<div class="col-xs-12">
				<h4 class="pull-left">`+ title +`</h4>
				<h4 class="pull-right">ราคา</h4>
				<div class="clearfix"></div>`
				+ str_confirm_sub +
				`<hr>
			</div>`;
	});
    $('.modal-confirm-body').html(str_confirm);
	$('#form-play').html(str);	
	if(str!=''){
		$('.clear_number').show();
		$('.post_number-btn').show();
        $('.total_play').show();
        $('.discount_play').show();
        $('.sum_play').show();
		$('#set-all-price').show();
	}else{
		$('.clear_number').hide();
		$('.post_number-btn').hide();	
        $('.total_play').hide();
        $('.discount_play').hide();
        $('.sum_play').hide();
		$('#set-all-price').hide();
	}
	total_discount = parseFloat(total_discount).toFixed(2);
	sum_play = total_play - total_discount;
	sum_play = parseFloat(sum_play).toFixed(2);
    $('.total_play_number').text(total_play);
    $('.discount_play_number').text(total_discount);
    $('.sum_play_number').text(sum_play);
    
    $('input[name="total_play_number"]').val(total_play);
    $('input[name="discount_play_number"]').val(total_discount);
    $('input[name="discount_play_number"]').val(sum_play);
}

$('.btn-swap').on('click',function(){
	op = $(this);
	select = op.attr('data-selected');				
	if(select == 0){
		op.attr('data-selected',1);
		op.addClass('btn-info');
	}else{
		op.attr('data-selected',0);	
		op.removeClass('btn-info');
	}
	
});
							
$('.group-play').on('click',function(){
    $('.group-play').removeClass('active');
    $(this).addClass('active');
	group_id = $(this).data('group_id');
	displayOverlay(group_id);
	displayButton(group_id);
						
	position = $('#daimond-content').position().top;
	window.scroll({
	  top: position, 
	  left: 0, 
	  behavior: 'smooth' 
	});
});

/*
//ปรับราคาเท่ากันหมด
$('input[name="set_all_price"]').on('keyup',function(){
    all_price = $('input[name="set_all_price"]').val();	
    if(all_price != ''){
    	old_text = $('input[name="play_data"]').val();
    	old_obj = {};		
    	if(old_text != ''){
    		old_obj = JSON.parse(old_text);
    		$.each(old_obj,function(key,item){
    			if(item != undefined){
    				old_data = JSON.parse(item);
    				$.each(old_data,function(number,value){
    					old_data[number] = all_price;
    				});	
    				strdata = JSON.stringify(old_data);
    				old_obj[key] = strdata;
    			}					
    		});
    		data = JSON.stringify(old_obj);
    		$('input[name="play_data"]').val(data);
    	}
    	group_id = $('.nav.nav-pills.nav-justified a.active').data('group_id');
    	displayButton(group_id);
    }
});
*/

/*
$('input[name="set_all_price"]').on('blur',function(){
    swal({
      width: '$swal_width%',
      title: 'Confirm?',
      html: '<div style="font-size:18px;">'+'ต้องการปรับราคาแทงทุกรายการเท่ากัน?'+'</div>',
      type: 'info',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน'
    }).then((result) => {
      if (result.value) {
        setAllPrice();
      }
    });
});
*/
$('#btn-set-all-price').on('click',function(e){
    swal({
      width: '$swal_width%',
      title: 'Confirm?',
      html: '<div style="font-size:18px;">'+'ต้องการปรับราคาแทงทุกรายการเท่ากัน?'+'</div>',
      type: 'info',
      showCancelButton: true,
      confirmButtonText: 'ยืนยัน'
    }).then((result) => {
      if (result.value) {
        setAllPrice();
      }
    })

});

function setAllPrice(){
	all_price = $('input[name="set_all_price"]').val();	
    if(all_price != ''){
    	if(all_price < minimum_set_all){
            swal({
                  width: '$swal_width%',
                  title: 'Warning!',
                  html: '<div style="font-size:18px;">'+'กรุณาระบุเงินขั้นต่ำอีกครั้ง '+minimum_set_all+' ขึ้นไป</div>',
                  type: 'warning',
                  confirmButtonText: 'close'
            });
    			
    		$('input[name="set_all_price"]').val('');	
    		e.preventDefault();
    		return false;
    	}

        //$('input[name="set_all_price"]').val(minimum_set_all);
    	old_text = $('input[name="play_data"]').val();
    	old_obj = {};
    							
    	if(old_text != ''){
    		old_obj = JSON.parse(old_text);
    		$.each(old_obj,function(key,item){
    			if(item != undefined){
    				old_data = JSON.parse(item);
    				$.each(old_data,function(number,value){
    					old_data[number] = all_price;
    				});	
    				strdata = JSON.stringify(old_data);
    				old_obj[key] = strdata;
    			}
    								
    		});
    		data = JSON.stringify(old_obj);
    		$('input[name="play_data"]').val(data);
    	}
    	group_id = $('.nav.nav-pills.nav-justified a.active').data('group_id');
    	displayButton(group_id);
    }
	
}
			
function displayCondition(){
	$('.condition-play-type').hide();
	data = $('.play-type.btn-primary');
	$.each(data,function(key,item){
		code = $(item).data('play_type');
		$('#condition-'+code).show();							
	});
}

EOT;
$this->registerJs($js);
$css = <<<EOT
	.card{
		margin-top:3px;
	}

	.overlay-disable {
	    position: absolute;
	    top: 0;
	    left: 0;
	    margin-left: 15px;
	    width: calc(100% - 30px);
	    height: calc(100% - 6px);
	    background: black;
	    opacity: .5;
	    z-index: 10;
	}

	
	.animated {
	    transition: .5s;
	}
	.fadeIn {
	    animation-name: fadeIn;
	}
	.animated {
	    animation-duration: 1s;
	    animation-fill-mode: both;
	}
								
	.column {
	    width: 10%;
		padding: 2px;
		float: left;
	}
	.nav.nav-tabs.nav-justified li {
	    float: left;
		padding: 2px;
	    width: 10%;
	}
	.nav.nav-tabs.nav-justified li a{
		white-space: nowrap;					
	}
	@media only screen and (max-width: 601px){
		.nav.nav-tabs.nav-justified li {
		    float: left;
		    width: 20%;
		}
		.tab-pane .column {
			float: left;
	    	width: 20%!important;
		}
	}
								
	.panel-body .input-group{
		margin-top:3px;					
	}

	
EOT;
$this->registerCss($css);
?>