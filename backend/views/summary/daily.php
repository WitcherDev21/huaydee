<?php
/**
 * @var $tab
 * @var array $blackreds
 * @var $searchModel
 * @var $date
 * @var $dataLotteryLaoWinGames
 */
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$js = <<<EOT

$('.tab13').on('click',function(){
    document.getElementById('tab13').style.display = 'block';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab13';
});
$('.tab12').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'block';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab12';
});
$('.tab11').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'block';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab11';
});
$('.tab10').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'block';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab10';
});
$('.tab9').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'block';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab9';
});
$('.tab8').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'block';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab8';
});
$('.tab7').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'block';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab7';
});
$('.tab6').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'block';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab6';
});
$('.tab5').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'block';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab5';
});
$('.tab4').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'block';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab4';
});
$('.tab3').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'block';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab3';
});
$('.tab2').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'block';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab2';
});
$('.tab1').on('click',function(){
    document.getElementById('tab13').style.display = 'none';
    document.getElementById('tab12').style.display = 'none';
    document.getElementById('tab11').style.display = 'none';
    document.getElementById('tab10').style.display = 'none';
    document.getElementById('tab9').style.display = 'none';
    document.getElementById('tab8').style.display = 'none';
    document.getElementById('tab7').style.display = 'none';
    document.getElementById('tab6').style.display = 'none';
    document.getElementById('tab5').style.display = 'none';
    document.getElementById('tab4').style.display = 'none';
    document.getElementById('tab3').style.display = 'none';
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'block';
    document.getElementById('tab').value = 'tab1';
});
EOT;
$this->registerJs($js);

$totalAmountYeekee = 0;
$totalWinCreditYeekee = 0;
$totalAmountBlackred = 0;
$totalWinCreditBlackred = 0;
$totalAmountLotteryLaoSet = 0;
$totalWinCreditLotteryLaoSet = 0;
$totalAmountLotteryGovernment = 0;
$totalWinCreditLotteryGovernment = 0;
$totalAmountLotteryThaiShared = 0;
$totalWinCreditLotteryThaiShared = 0;
$totalAmountLotteryGsbThaiShared = 0;
$totalWinCreditLotteryGsbThaiShared = 0;
$totalAmountLotteryBaccThaiShared = 0;
$totalWinCreditLotteryBaccThaiShared = 0;
$totalAmountLotteryLaosChampasakThaiShared = 0;
$totalWinCreditLotteryLaosChampasakThaiShared = 0;
$totalAmountLotteryVietnam4DThaiShared = 0;
$totalWinCreditLotteryVietnam4DThaiShared = 0;
$totalAmountLotteryVietnamSet = 0;
$totalWinCreditLotteryVietnamSet = 0;
$totalAmountLotteryReserve = 0;
$totalWinCreditLotteryReserve = 0;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-th"></i></span>
        <h5>สรุปยอดรายวัน</h5>
    </div>
    <div class="widget-content ">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
                <br>
                <?php Pjax::begin(['id' => 'countries-tab1', 'clientOptions' => ['method' => 'GET']]) ?>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-daily',
                    'method' => 'post',
                    'action' => Yii::$app->urlManager->createUrl(['summary/daily']),
                ]);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">วันที่</label>
                            <div class="col-md-9  col-sm-9">
                                <input id="tab" name="tab" value="<?= $tab ?>" hidden>
                                <?php
                                //                                        echo $form->field($searchModel, 'date_at_search')->textInput(['class'=>'form-control','value'=>$date])->label(false);
                                ?>
                                <?= Html::activeInput('date', $searchModel, 'date_at_search', ['class' => 'form-control', 'value' => $date]) ?>

                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">งวดที่</label>
                            <div class="col-md-9  col-sm-9">
                                <?php
                                echo $form->field($searchModel, 'round')->input('string')->label(false);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-info pull-right" type="submit"><i class="glyphicon glyphicon-search"></i>
                            ค้นหา
                        </button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <br><br>
        <div class="widget-box">
            <div class="widget-title">
                <ul class="nav nav-tabs">
                    <li class="<?= $tab == 'tab1' ? 'active' : '' ?>"><a class="tab1" data-toggle="tab" href="#tab1">จับยี่กี่</a>
                    </li>
                    <li class="<?= $tab == 'tab3' ? 'active' : '' ?>"><a class="tab3" data-toggle="tab" href="#tab3">หวยไทย-ต่างประเทศ</a>
                    </li>
                    <li class="<?= $tab == 'tab6' ? 'active' : '' ?>"><a class="tab6" data-toggle="tab" href="#tab6">หวยดาวน์โจน</a>
                    </li>
                    <li class="<?= $tab == 'tab7' ? 'active' : '' ?>"><a class="tab7" data-toggle="tab" href="#tab7">หวยรัฐ</a>
                    </li>
                    <li class="<?= $tab == 'tab9' ? 'active' : '' ?>"><a class="tab9" data-toggle="tab" href="#tab9">หวยออมสิน</a>
                    </li>
                    <li class="<?= $tab == 'tab10' ? 'active' : '' ?>"><a class="tab10" data-toggle="tab" href="#tab10">หวย ธกส</a>
                    </li>
                    <li class="<?= $tab == 'tab5' ? 'active' : '' ?>"><a class="tab5" data-toggle="tab" href="#tab5">หวยลาวชุด</a>
                    </li>
                    <li class="<?= $tab == 'tab11' ? 'active' : '' ?>"><a class="tab11" data-toggle="tab" href="#tab11">หวยลาว จำปาสัก</a>
                    </li>
                    <li class="<?= $tab == 'tab12' ? 'active' : '' ?>"><a class="tab12" data-toggle="tab" href="#tab12">หวยฮานอย 4D</a>
                    </li>
                    <li class="<?= $tab == 'tab13' ? 'active' : '' ?>"><a class="tab13" data-toggle="tab" href="#tab13">หวยลาวทดแทน</a>
                    </li>
                    <li class="<?= $tab == 'tab8' ? 'active' : '' ?>"><a class="tab8" data-toggle="tab" href="#tab8">หวยเวียดนามชุด</a>
                    </li>
                    <li class="<?= $tab == 'tab4' ? 'active' : '' ?>"><a class="tab4" data-toggle="tab" href="#tab3">สรุปหวยทั้งหมด</a>
                    </li>
                </ul>
            </div>
            <div id="tab1" style="<?= $tab == 'tab1' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($data)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under'];
                            foreach ($data as $key => $val) {
                                $amount = 0;
                                $win_credit = 0;

                                foreach ($arr as $t) {
                                    $amount = $amount + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $win_credit = $win_credit + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    $totalAmountYeekee = $totalAmountYeekee + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $totalWinCreditYeekee = $totalWinCreditYeekee + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                จับยี่กี่ งวดที่ <?php echo $key; ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amount, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_credit, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $total = $amount - $win_credit;
                                                        echo number_format($total, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab2" style="<?= $tab == 'tab2' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php foreach ($blackreds as $keyBlackred => $blackred) { ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse"
                                           data-parent="#accordion"
                                           href="#tabblackred<?= $keyBlackred; ?>" aria-expanded="true"
                                           aria-controls="tabblackred<?= $keyBlackred; ?>">
                                            ดำแดง งวดที่ <?= $keyBlackred; ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="tabblackred<?= $keyBlackred; ?>" class="panel-collapse collapse in"
                                     role="tabpanel"
                                     aria-labelledby="heading">
                                    <div class="panel-body">
                                        <p>
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>การแทง</th>
                                                <th>วันที่ - เวลา</th>
                                                <th>ยอดแทง</th>
                                                <th>ยอดถูก</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($blackred as $key => $blackredChit) {
                                                $totalAmountBlackred += $blackredChit['amount'];
                                                $totalWinCreditBlackred += $blackredChit['win_credit'];
                                                ?>
                                                <tr>
                                                    <td><?= $blackredChit['round'] ?></td>
                                                    <td><?= $blackredChit['date'] ?></td>
                                                    <td><?= $blackredChit['amount'] ? number_format($blackredChit['amount'], 2) : 0 ?></td>
                                                    <td><?= $blackredChit['win_credit'] ? number_format($blackredChit['win_credit'], 2) : 0 ?></td>
                                                </tr>
                                                <?php if ($key === 1) { ?>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($totalAmountBlackred, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($totalWinCreditBlackred, 2); ?></td>
                                                <?php } ?>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div id="tab3" style="<?= $tab == 'tab3' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataShared as $key => $val) {
                                $amountLotteryThaiShared = 0;
                                $discountLotteryThaiShared = 0;
                                $win_creditLotteryThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryThaiShared = $amountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryThaiShared = $discountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryThaiShared = $win_creditLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryThaiShared = $totalAmountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryThaiShared = $totalAmountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryThaiShared = $totalWinCreditLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalThaiShared = $amountLotteryThaiShared - $win_creditLotteryThaiShared;
                                                        echo number_format($totalThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab5" style="<?= $tab == 'tab5' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataLotteryLaoSets)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['four_dt', 'four_tod', 'three_top', 'three_tod', 'two_ft', 'two_bk', 'amount'];
                            foreach ($dataLotteryLaoSets as $key => $val) {
                                $amount = 0;
                                $discount = 0;
                                $win_credit = 0;

                                foreach ($arr as $t) {
                                    $amount = $amount + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $win_credit = $win_credit + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    $totalAmountLotteryLaoSet = $totalAmountLotteryLaoSet + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $totalWinCreditLotteryLaoSet = $totalWinCreditLotteryLaoSet + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวหน้า</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_ft']) ? number_format($val['two_ft']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_ft']) ? number_format($val['two_ft']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวหลัง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_bk']) ? number_format($val['two_bk']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_bk']) ? number_format($val['two_bk']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        จำนวนชุด <?php echo(!empty($val['amount']) ? $val['amount']['totalSet'] : '0'); ?></td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['amount']['amount']) ? number_format($val['amount']['amount']) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_credit, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: center;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $amountSet = (!empty($val['amount']) ? number_format($val['amount']['amount'], 2) : '0');
                                                        $totalAmountLaosSet = $amountSet - $win_credit;
                                                        echo number_format($totalAmountLaosSet, 2);?>
                                                    </td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab8" style="<?= $tab == 'tab8' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataLotteryVietnamSets)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['four_dt', 'four_tod', 'three_top', 'three_tod', 'two_ft', 'two_bk', 'amount'];
                            foreach ($dataLotteryVietnamSets as $key => $val) {
                                $amount = 0;
                                $discount = 0;
                                $win_credit = 0;

                                foreach ($arr as $t) {
                                    $amount = $amount + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $win_credit = $win_credit + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    $totalAmountLotteryVietnamSet = $totalAmountLotteryVietnamSet + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $totalWinCreditLotteryVietnamSet = $totalWinCreditLotteryVietnamSet + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวหน้า</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_ft']) ? number_format($val['two_ft']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_ft']) ? number_format($val['two_ft']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวหลัง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_bk']) ? number_format($val['two_bk']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_bk']) ? number_format($val['two_bk']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        จำนวนชุด <?php echo(!empty($val['amount']) ? $val['amount']['totalSet'] : '0'); ?></td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['amount']['amount']) ? number_format($val['amount']['amount']) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_credit, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: center;"></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน<?php
                                                        $amountSet = (!empty($val['amount']) ? number_format($val['amount']['amount'], 2) : '0');
                                                        $totalAmountVietnamSet = $amountSet - $win_credit;
                                                        echo number_format($totalAmountVietnamSet, 2);?>
                                                    </td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div id="tab6" style="<?= $tab == 'tab6' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataDownJoans)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataDownJoans as $key => $val) {
                                $amountLotteryThaiShared = 0;
                                $discountLotteryThaiShared = 0;
                                $win_creditLotteryThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryThaiShared = $amountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryThaiShared = $discountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryThaiShared = $win_creditLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryThaiShared = $totalAmountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryThaiShared = $totalAmountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryThaiShared = $totalWinCreditLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalThaiShared = $amountLotteryThaiShared - $win_creditLotteryThaiShared;
                                                        echo number_format($totalThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab7" style="<?= $tab == 'tab8' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataLotterys)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataLotterys as $key => $val) {
                                $amountLotteryThaiShared = 0;
                                $discountLotteryThaiShared = 0;
                                $win_creditLotteryThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryThaiShared = $amountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryThaiShared = $discountLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryThaiShared = $win_creditLotteryThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryGovernment = $totalAmountLotteryGovernment + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryGovernment = $totalAmountLotteryGovernment + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryGovernment = $totalWinCreditLotteryGovernment + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบนหมุน 2 ครั้ง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top2']) ? number_format($val['three_top2']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top2']) ? number_format($val['three_top2']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top2']) ? number_format($val['three_top2']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวล่างหมุน 2 ครั้ง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_und2']) ? number_format($val['three_und2']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_und2']) ? number_format($val['three_und2']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_und2']) ? number_format($val['three_und2']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalGovernment = $amountLotteryThaiShared - $win_creditLotteryThaiShared;
                                                        echo number_format($totalGovernment, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab9" style="<?= $tab == 'tab9' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataGsbShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataGsbShared as $key => $val) {
                                $amountLotteryGsbThaiShared = 0;
                                $discountLotteryGsbThaiShared = 0;
                                $win_creditLotteryGsbThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryGsbThaiShared = $amountLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryGsbThaiShared = $discountLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryGsbThaiShared = $win_creditLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryGsbThaiShared = $totalAmountLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryGsbThaiShared = $totalAmountLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryGsbThaiShared = $totalWinCreditLotteryGsbThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryGsbThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryGsbThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryGsbThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalAmountGsb = $amountLotteryGsbThaiShared - $win_creditLotteryGsbThaiShared;
                                                        echo number_format($totalAmountGsb, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab10" style="<?= $tab == 'tab10' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataBaccShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataBaccShared as $key => $val) {
                                $amountLotteryBaccThaiShared = 0;
                                $discountLotteryBaccThaiShared = 0;
                                $win_creditLotteryBaccThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryBaccThaiShared = $amountLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryBaccThaiShared = $discountLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryBaccThaiShared = $win_creditLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryBaccThaiShared = $totalAmountLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryBaccThaiShared = $totalAmountLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryBaccThaiShared = $totalWinCreditLotteryBaccThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryBaccThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryBaccThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryBaccThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalAmountBacc = $amountLotteryBaccThaiShared - $win_creditLotteryBaccThaiShared;
                                                        echo number_format($totalAmountBacc, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab11" style="<?= $tab == 'tab11' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataLaosChampasakShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataLaosChampasakShared as $key => $val) {
                                $amountLotteryLaosChampasakThaiShared = 0;
                                $discountLotteryLaosChampasakThaiShared = 0;
                                $win_creditLotteryBLaosChampasakThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryLaosChampasakThaiShared = $amountLotteryLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryLaosChampasakThaiShared = $discountLotteryLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryBLaosChampasakThaiShared = $win_creditLotteryBLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryLaosChampasakThaiShared = $totalAmountLotteryLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryLaosChampasakThaiShared = $totalAmountLotteryLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryLaosChampasakThaiShared = $totalWinCreditLotteryLaosChampasakThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryLaosChampasakThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryLaosChampasakThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryBLaosChampasakThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalAmountChampasak = $amountLotteryLaosChampasakThaiShared - $win_creditLotteryBLaosChampasakThaiShared;
                                                        echo number_format($totalAmountChampasak, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab12" style="<?= $tab == 'tab12' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataVietnam4DShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataVietnam4DShared as $key => $val) {
                                $amountLotteryVietnam4DThaiShared = 0;
                                $discountLotteryVietnam4DThaiShared = 0;
                                $win_creditLotteryVietnam4DThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryVietnam4DThaiShared = $amountLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryVietnam4DThaiShared = $discountLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryVietnam4DThaiShared = $win_creditLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryVietnam4DThaiShared = $totalAmountLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryVietnam4DThaiShared = $totalAmountLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryVietnam4DThaiShared = $totalWinCreditLotteryVietnam4DThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryVietnam4DThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryVietnam4DThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryVietnam4DThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalAmountVietnam4D = $amountLotteryVietnam4DThaiShared - $win_creditLotteryVietnam4DThaiShared;
                                                        echo number_format($totalAmountVietnam4D, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab13" style="<?= $tab == 'tab13' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <?php
                    if (!empty($dataLotteryReserveShared)) {
                        ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php
                            $arr = ['three_top', 'three_tod', 'two_top', 'two_under', 'run_top', 'run_under', 'three_top2', 'three_und2'];
                            foreach ($dataLotteryReserveShared as $key => $val) {
                                $amountLotteryReserveThaiShared = 0;
                                $discountLotteryReserveThaiShared = 0;
                                $win_creditLotteryReserveThaiShared = 0;
                                foreach ($arr as $t) {
                                    $amountLotteryReserveThaiShared = $amountLotteryReserveThaiShared + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    $discountLotteryReserveThaiShared = $discountLotteryReserveThaiShared + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    $win_creditLotteryReserveThaiShared = $win_creditLotteryReserveThaiShared + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                    if (isset($val[$t]['discount']) && $val[$t]['discount'] > 0) {
                                        $totalAmountLotteryReserve = $totalAmountLotteryReserve + (!empty($val[$t]) ? $val[$t]['discount'] : 0);
                                    } else {
                                        $totalAmountLotteryReserve = $totalAmountLotteryReserve + (!empty($val[$t]) ? $val[$t]['amount'] : 0);
                                    }
                                    $totalWinCreditLotteryReserve = $totalWinCreditLotteryReserve + (!empty($val[$t]) ? $val[$t]['win_credit'] : 0);
                                }
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $key; ?>">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion" href="#tabchit<?php echo $key; ?>__"
                                               aria-expanded="true" aria-controls="tabchit<?php echo $key; ?>">
                                                <?= $key ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="tabchit<?php echo $key; ?>" class="panel-collapse collapse in"
                                         role="tabpanel" aria-labelledby="heading<?php echo $key; ?>">
                                        <div class="panel-body">
                                            <p>
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th>การแทง</th>
                                                    <th>วันที่ - เวลา</th>
                                                    <th>ยอดแทง</th>
                                                    <th>ยอดส่วนลด</th>
                                                    <th>ยอดถูก</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>วิ่งบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_top']) ? number_format($val['run_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>วิ่งล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['run_under']) ? number_format($val['run_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_tod']) ? number_format($val['three_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สามตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['three_top']) ? number_format($val['three_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวบน</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_top']) ? number_format($val['two_top']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สองตัวล่าง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['two_under']) ? number_format($val['two_under']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวตรง</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_dt']) ? number_format($val['four_dt']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>สี่ตัวโต๊ด</td>
                                                    <td style="text-align: center;"><?php echo $val['create_at']; ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['amount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['discount'], 2) : '0'); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo(!empty($val['four_tod']) ? number_format($val['four_tod']['win_credit'], 2) : '0'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($amountLotteryReserveThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($discountLotteryReserveThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($win_creditLotteryReserveThaiShared, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">กำไร-ขาดทุน</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php
                                                        $totalAmountReserveThaiShared = $amountLotteryReserveThaiShared - $win_creditLotteryReserveThaiShared;
                                                        echo number_format($totalAmountReserveThaiShared, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                    <td style="text-align: right; padding-right: 20px;"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id="tab4" style="<?= $tab == 'tab4' ? 'display: block;' : 'display: none;' ?>" class="widget-content tab-content">
                <div class="tab-pane active">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingsummary">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse"
                                       data-parent="#accordion" href="#tabchitsummary__"
                                       aria-expanded="true" aria-controls="tabchitsummary">
                                        สรุปยอดรายวัน <?= $date ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="tabchitsummary" class="panel-collapse collapse in"
                                 role="tabpanel" aria-labelledby="headingsummary">
                                <div class="panel-body">
                                    <p>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>ประเภทหวย</th>
                                            <th>ยอดแทง</th>
                                            <th>ยอดถูก</th>
                                            <th>ยอดคงเหลือ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>ยี่กี่ 88 รอบ</td>
                                            <td style="text-align: center;"><?php echo $totalAmountYeekee; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditYeekee; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountYeekee - $totalWinCreditYeekee; ?></td>
                                        </tr>
                                        <tr>
                                            <td>ดำแดง</td>
                                            <td style="text-align: center;"><?php echo $totalAmountBlackred; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditBlackred; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountBlackred - $totalWinCreditBlackred; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยไทย-ต่างประเทศ</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryThaiShared - $totalWinCreditLotteryThaiShared; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยออมสิน</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryGsbThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryGsbThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryGsbThaiShared - $totalWinCreditLotteryGsbThaiShared; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวย ธกส</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryBaccThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryBaccThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryBaccThaiShared - $totalWinCreditLotteryBaccThaiShared; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยลาว จำปาสัก</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryLaosChampasakThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryLaosChampasakThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryLaosChampasakThaiShared - $totalWinCreditLotteryLaosChampasakThaiShared; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยรัฐบาลไทย,หวยรัฐบาลไทย-แบบมีส่วนลด</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryGovernment; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryGovernment; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryGovernment - $totalWinCreditLotteryGovernment; ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยลาวชุด,หวยลาวชุดแบบมีส่วนลด</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryLaoSet; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryLaoSet; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryLaoSet - $totalWinCreditLotteryLaoSet ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยเวียดนามชุด</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryVietnamSet; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryVietnamSet; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryVietnamSet - $totalWinCreditLotteryVietnamSet ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยฮานอย 4D</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryVietnam4DThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryVietnam4DThaiShared; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryVietnam4DThaiShared - $totalWinCreditLotteryVietnam4DThaiShared ?></td>
                                        </tr>
                                        <tr>
                                            <td>หวยลาวทดแทน</td>
                                            <td style="text-align: center;"><?php echo $totalAmountLotteryReserve; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalWinCreditLotteryReserve; ?></td>
                                            <td style="text-align: right; padding-right: 20px;"><?php echo $totalAmountLotteryReserve - $totalWinCreditLotteryReserve ?></td>
                                        </tr>
                                        <tr>
                                            <td>กำไร-ขาดทุน</td>
                                            <td style="text-align: center;">
                                                <?php
                                                $totalAmount = $totalAmountYeekee + $totalAmountBlackred +
                                                    $totalAmountLotteryGovernment + $totalAmountLotteryThaiShared +
                                                    $totalAmountLotteryLaoSet + $totalAmountLotteryVietnamSet +
                                                    $totalAmountLotteryGsbThaiShared + $totalAmountLotteryBaccThaiShared +
                                                    $totalAmountLotteryLaosChampasakThaiShared + $totalAmountLotteryVietnam4DThaiShared +
                                                    $totalAmountLotteryReserve;
                                                echo $totalAmount;
                                                ?>
                                            </td>
                                            <td style="text-align: right; padding-right: 20px;">
                                                <?php
                                                $totalWinCredit = $totalWinCreditYeekee + $totalWinCreditBlackred +
                                                    $totalWinCreditLotteryGovernment + $totalWinCreditLotteryThaiShared +
                                                    $totalWinCreditLotteryLaoSet + $totalWinCreditLotteryVietnamSet +
                                                    $totalWinCreditLotteryGsbThaiShared + $totalWinCreditLotteryBaccThaiShared +
                                                    $totalWinCreditLotteryLaosChampasakThaiShared + $totalWinCreditLotteryVietnam4DThaiShared +
                                                    $totalWinCreditLotteryReserve;
                                                echo $totalWinCredit;
                                                ?>
                                            </td>
                                            <td style="text-align: right; padding-right: 20px;">
                                                <?php echo
                                                    ($totalAmountLotteryGovernment + $totalAmountLotteryThaiShared +
                                                        $totalAmountBlackred + $totalAmountYeekee + $totalAmountLotteryLaoSet +
                                                        $totalAmountLotteryVietnamSet + $totalAmountLotteryGsbThaiShared +
                                                        $totalAmountLotteryBaccThaiShared + $totalAmountLotteryLaosChampasakThaiShared +
                                                        $totalAmountLotteryVietnam4DThaiShared + $totalAmountLotteryReserve) -
                                                    ($totalWinCreditLotteryThaiShared + $totalWinCreditBlackred +
                                                        $totalWinCreditYeekee + $totalWinCreditLotteryGovernment +
                                                        $totalWinCreditLotteryLaoSet + $totalWinCreditLotteryVietnamSet +
                                                        $totalWinCreditLotteryGsbThaiShared + $totalWinCreditLotteryBaccThaiShared +
                                                        $totalWinCreditLotteryLaosChampasakThaiShared +
                                                        $totalWinCreditLotteryVietnam4DThaiShared + $totalWinCreditLotteryReserve)
                                                ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



