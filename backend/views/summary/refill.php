<?php

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-paste"></i></span>
        <h5>สรุปยอดฝากถอน</h5>
    </div>
    <div class="widget-content ">
        <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
                <br>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-daily',
                    'method' => 'post',
                    'action' => Yii::$app->urlManager->createUrl(['summary/refill'])
                ]);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">วันที่</label>
                            <div class="col-md-9  col-sm-9">
                                <?= Html::activeInput('date', $searchModel, 'date_at_search', ['class' => 'form-control', 'value' => $date]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-info pull-right" type="submit"><i class="glyphicon glyphicon-search"></i>
                            ค้นหา
                        </button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <br><br>
        <div class="widget-box">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ธนาคาร</th>
                        <th>ยอดฝาก</th>
                        <th>ยอดเงินที่ปรับแล้ว</th>
                        <th>ยอดถอน</th>
                        <th>ยอดถอนที่ได้รับแล้ว</th>
                        <th>ยอดค้างบัญชี</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($data)) {
                        $sum1 = 0;
                        $sum2 = 0;
                        $sum3 = 0;
                        $sum4 = 0;
                        $sum5 = 0;
                        foreach ($data as $val) {
                            $sum1 = $sum1 + $val['CreditAll'];
                            $sum2 = $sum2 + $val['CreditAllApprove'];
                            $sum3 = $sum3 + $val['WithdrawAll'];
                            $sum4 = $sum4 + $val['WithdrawAllApprove'];
                            $sum5 = $sum5 + ($val['CreditAllApprove'] - $val['WithdrawAllApprove']);
                            ?>
                            <tr>
                                <td><?php echo $val['bank_info']['title']; ?></td>
                                <td class="text-right"><?php echo str_replace('.00', '', number_format($val['CreditAll'], 2)); ?></td>
                                <td class="text-right"><?php echo str_replace('.00', '', number_format($val['CreditAllApprove'], 2)); ?></td>
                                <td class="text-right"><?php echo str_replace('.00', '', number_format($val['WithdrawAll'], 2)); ?></td>
                                <td class="text-right"><?php echo str_replace('.00', '', number_format($val['WithdrawAllApprove'], 2)); ?></td>
                                <td class="text-right"><?php echo str_replace('.00', '', number_format($val['CreditAllApprove'] - $val['WithdrawAllApprove'], 2)); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6">ไม่พบข้อมูล</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                    <?php
                    if (!empty($data)) {
                        ?>
                        <tfoot>
                        <tr>
                            <td class="text-right">รวม</td>
                            <td class="text-right"><?php echo str_replace('.00', '', number_format($sum1, 2)); ?></td>
                            <td class="text-right"><?php echo str_replace('.00', '', number_format($sum2, 2)); ?></td>
                            <td class="text-right"><?php echo str_replace('.00', '', number_format($sum3, 2)); ?></td>
                            <td class="text-right"><?php echo str_replace('.00', '', number_format($sum4, 2)); ?></td>
                            <td class="text-right"><?php echo str_replace('.00', '', number_format($sum5, 2)); ?></td>
                        </tr>
                        </tfoot>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>

        <?php Pjax::end() ?>
        <div class="row-fluid">
            <div class="span12">
                <ul class="site-stats">
                    <li class="bg_dg">
                        <i class="icon-credit-card"></i>
                        <strong>จำนวนยอดฝากตรงวันนี้</strong>
                        <strong><?php echo(!empty($amountCreditTopUpPromotion->amount) ? number_format($amountCreditTopUpPromotion->amount, 2) : 0); ?>
                            ฿</strong>
                    </li>

                    <li class="bg_dg">
                        <i class="icon-credit-card"></i>
                        <strong>จำนวนยอดถอนตรงวันนี้</strong>
                        <strong><?php echo(!empty($amountCreditWithDrawPromotion->amount) ? number_format($amountCreditWithDrawPromotion->amount, 2) : 0); ?>
                            ฿</strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>