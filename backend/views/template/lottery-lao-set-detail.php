<?php
/* @var $dataUser \common\models\User */
/* @var $thaiSharedGameChit \common\models\ThaiSharedGameChit */
use common\libs\Constants;

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><span class="glyphicon glyphicon-file"></span></span>
        <h5>รายละเอียดโพย</h5>
    </div>
    <div class="widget-content ">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลสมาชิก</h3>
            </div>
            <div class="panel-body">
                <b>Username ::</b> <?php echo $dataUser->username; ?><br><br>
                <b>อีเมล์ ::</b> <?php echo $dataUser->email; ?><br><br>
                <b>เบอร์โทร ::</b> <?php echo $dataUser->tel; ?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">ข้อมูลโพย</h3>
            </div>
            <div class="panel-body">
                <b>เลขที่รายการ ::</b> <?php echo $thaiSharedGameChit->getOrder(); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>เกม ::</b> <?php echo $thaiSharedGameChit->thaiSharedGame->title; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>วันที่ / เวลา
                    ::</b> <?php echo date('d/m/Y H:i:s', strtotime($thaiSharedGameChit->thaiSharedGame->startDate)); ?>
                - <?php echo date('d/m/Y H:i:s', strtotime($thaiSharedGameChit->thaiSharedGame->endDate)); ?>
                <br><br>
                <b>เงินเดิมพัน ::</b> <?php echo number_format($thaiSharedGameChit->totalAmount, 2); ?>&nbsp;
                <b>ผลชนะ ::</b>
                <?php
                $result = 'รอผล';
                if ($thaiSharedGameChit->thaiSharedGame->status == Constants::status_finish_show_result) {
                    if ($thaiSharedGameChit->getIsWin()) {
                        $result = '<a href="javascript:;" class="btn btn-xs btn-success">' . 'ชนะ' . '</a>';
                    } else {
                        $result = '<a href="javascript:;" class="btn btn-xs btn-danger">' . 'แพ้' . '</a>';
                    }
                } else if ($thaiSharedGameChit->thaiSharedGame->status == Constants::status_cancel) {
                    $result = '<a href="javascript:;" class="btn btn-xs btn-danger">' . 'ยกเลิก' . '</a>';
                }
                echo $result;
                ?>&nbsp;
                <b>สถานะ ::</b>
                <?php
                echo '<a href="javascript:;" class="btn btn-xs btn-' . Constants::$statusIcon[$thaiSharedGameChit->thaiSharedGame->status] . '">'
                    . Constants::$status[$thaiSharedGameChit->thaiSharedGame->status] . '</a>';
                ?>
            </div>
        </div>
        <div class="table-responsive">
            <div class="modal-detail">
                <div style="padding: 20px;">
                    <div class="row">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ประเภทหวย</th>
                                <th>เลขที่แทง</th>
                                <th>ราคาที่แทง</th>
                                <th>ราคาจ่าย</th>
                                <th>เลขที่ออก</th>
                                <th>ผลได้เสีย</th>
                                <th>สถานะ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($data)) {
                                $r = 0;
                                foreach ($data['items'] as $val) {
                                    $r++;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $r; ?></td>
                                        <td><?php if ($val['flag_result'] === 1) {
                                                echo 'หวยลาวชุด: ' . $val['title'];
                                            } else{
                                                echo 'หวยลาวชุด';
                                            } ?></td>
                                        <td style="text-align: center;"><?php echo $val['number']; ?></td>
                                        <td style="text-align: right;"><?php echo $val['amount']; ?></td>
                                        <td style="text-align: right;"><?php
                                            if ($thaiSharedGameChit->thaiSharedGame->status == Constants::status_finish_show_result) {
                                                echo $val['jackpot_per_unit'];
                                            }else{
                                                echo 'รอผล';
                                            } ?>
                                        </td>
                                        <td style="text-align: center;"><?php echo $val['textAnswer']; ?></td>
                                        <td style="text-align: right;"><?php echo $val['win_credit']; ?></td>
                                        <td style="text-align: center;"><?php
                                            if ($thaiSharedGameChit->thaiSharedGame->status == Constants::status_finish_show_result) {
                                                echo $val['status'];
                                            }else{
                                                echo 'รอผล';
                                            }?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8">ไม่พบข้อมูล</td>
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
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><?php echo $data['amount_total']; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;"><?php echo $data['win_credit']; ?></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                                <?php
                            }
                            ?>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
