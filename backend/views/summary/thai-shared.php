<?php

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$js = <<<EOT

$('.tab2').on('click',function(){
    document.getElementById('tab2').style.display = 'block';
    document.getElementById('tab1').style.display = 'none';
    document.getElementById('tab').value = 'tab2';
});
$('.tab1').on('click',function(){
    document.getElementById('tab2').style.display = 'none';
    document.getElementById('tab1').style.display = 'block';
    document.getElementById('tab').value = 'tab1';
});
EOT;
$this->registerJs($js);
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
                                <input id="tab" name="tab" value="<?= $tab ?>"hidden>
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
                    <li class="<?= $tab == 'tab1' ? 'active' : '' ?>"><a class="tab1" data-toggle="tab" href="#tab1">จับยี่กี่</a></li>
                    <li class="<?= $tab == 'tab2' ? 'active' : '' ?>"><a class="tab2" data-toggle="tab" href="#tab2">ดำแดง</a></li>
                </ul>
            </div>
            <div class="widget-content tab-content">
                <div id="tab1" class="tab-pane active" style="<?= $tab == 'tab1' ? 'display: block;' : 'display: none;' ?>">

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
            <div class="widget-content tab-content">
                <div id="tab2" style="<?= $tab == 'tab2' ? 'display: block;' : 'display: none;' ?>">
                    <?php foreach ($blackreds as $keyBlackred => $blackred) {?>
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
                                            $totalAmount = 0;
                                            $totalWinCredit = 0;
                                            foreach ($blackred as $key => $blackredChit) {
                                                $totalAmount += $blackredChit['amount'];
                                                $totalWinCredit += $blackredChit['win_credit'];
                                                ?>
                                            <tr>
                                                <td><?= $blackredChit['round'] ?></td>
                                                <td><?= $blackredChit['date'] ?></td>
                                                <td><?= $blackredChit['amount'] ? number_format($blackredChit['amount'], 2) : 0 ?></td>
                                                <td><?= $blackredChit['win_credit'] ? number_format($blackredChit['win_credit'], 2) : 0 ?></td>
                                            </tr>
                                                <?php if($key === 1) { ?>
                                                    <td></td>
                                                    <td style="text-align: right; padding-right: 20px;">ยอดรวม</td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($totalAmount, 2); ?></td>
                                                    <td style="text-align: right; padding-right: 20px;"><?php echo number_format($totalWinCredit, 2); ?></td>
                                                <?php }?>
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
        </div>
    </div>
</div>



