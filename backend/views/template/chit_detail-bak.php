<?php
/* @var $YeekeeChit
 * @var $dataUser
 * @var $yeekeechit_id
*/
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
                    <b>เลขที่รายการ ::</b> <?php echo $YeekeeChit->getOrder(); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>งวดที่ ::</b> <?php echo $YeekeeChit->yeekee->round; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>วันที่ / เวลา ::</b> <?php echo (empty($YeekeeChit->update_at) ? date('d/m/Y H:i:s',strtotime($yeekeechit_id->create_at)) : date('d/m/Y H:i:s',strtotime($YeekeeChit->update_at))); ?>
                    <br><br>
                    <b>เงินเดิมพัน ::</b> <?php echo number_format ( $YeekeeChit->total_amount, 2 ); ?>&nbsp;
                    <b>ผลชนะ ::</b>
                        <?php
                        $result = 'รอผล';
                        if($YeekeeChit->status == Constants::status_finish_show_result){
                            if($YeekeeChit->getIsWin()){
                                $result = '<a href="javascript:;" class="btn btn-xs btn-success">'.'ชนะ'.'</a>';
                            }else{
                                $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'แพ้'.'</a>';
                            }										
                        }else if($YeekeeChit->status == Constants::status_cancel){
                            $result = '<a href="javascript:;" class="btn btn-xs btn-danger">'.'ยกเลิก'.'</a>';
                        }
                        echo $result;
                        ?>&nbsp;
                     <b>สถานะ ::</b> 
                        <?php
                        echo '<a href="javascript:;" class="btn btn-xs btn-'.Constants::$statusIcon[$YeekeeChit->status].'">'
                                            .Constants::$status[$YeekeeChit->status].'</a>';
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
                                        <th>level</th>
                                        <th>สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($data)){
                                        $r = 0;
                                        foreach ($data['items'] as $val){
                                            $r++;
                                    ?>
                                            <tr>
                                                <td style="text-align: center;"><?php echo $r; ?></td>
                                                <td><?php echo $val['title']; ?></td>
                                                <td style="text-align: center;"><?php echo $val['number']; ?></td>
                                                <td style="text-align: right;"><?php echo $val['amount']; ?></td>
                                                <td style="text-align: right;"><?php echo $val['jackpot_per_unit']; ?></td>
                                                <td style="text-align: center;"><?php echo $val['play_type_code']; ?></td>
                                                <td style="text-align: right;"><?php echo $val['win_credit']; ?></td>
                                                <td style="text-align: center;"><?php echo isset($val['level']) ? $val['level'] : ''; ?></td>
                                                <td style="text-align: center;"><?php echo $val['status']; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }else{
                                    ?>
                                        <tr><td colspan="8">ไม่พบข้อมูล</td></tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <?php
                                if(!empty($data)){
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
