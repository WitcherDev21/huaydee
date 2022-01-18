<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;

$js = <<<EOT

EOT;
$this->registerJs ( $js );
$css = <<<EOT
	
EOT;
$this->registerCss ( $css );

$today = date ( 'Y-m-d H:i:s' );
?>
<div class="col-xs-12">
	<div class="panel">
	<?= $this->render('_tab',['active_tab'=>$active_tab])?>
	<div class="tab-content">
			<div id="list-current-yeekee" class="tab-pane fade in active">
			
		<h4>ยอดแทงทั้งหมดของวันนี้</h4>
  		<table class="table table-bordered">
  			<thead>
	  			<tr>
	  				<th>วันที่</th>
	  				<th>ออกผลแล้ว</th>
	  				<th>ยังไม่ออกผล</th>
	  				<th>รวม</th>
	  			</tr>
  			</thead>
  			<tbody>
  				<tr>	
  					<td>วันนี้</td>
  					<td><?= $inDay['sum_total_amount_finish']?></td>
  					<td><?= $inDay['sum_total_amount_inprogress']?></td>
  					<td><?= $inDay['sum_total_amount_finish'] +  $inDay['sum_total_amount_inprogress']?></td>
  				</tr>
  			</tbody>
  		</table>
  		<h4>สรุปยอดแทงย้อนหลัง (รวม <?= $total ?> บาท)</h4>
  		<table class="table table-bordered">
  			<thead>
	  			<tr>
	  				<th>วันที่</th>
	  				<th>ออกผลแล้ว</th>
	  				<th>ยังไม่ออกผล</th>
	  				<th>รวม</th>
	  			</tr>
  			</thead>
  			<tbody>
  			<?php foreach($arrHistory as $date=>$his){?>
  				<tr>	
  					<td><?= date('d/m/Y',strtotime($date.' 00:00:00'))?></td>
  					<td><?= $his['sum_total_amount_finish']?></td>
  					<td><?= $his['sum_total_amount_inprogress']?></td>
  					<td><?= $his['sum_total_amount_finish'] +  $his['sum_total_amount_inprogress']?></td>
  				</tr>
  			<?php }?>
  			</tbody>
  		</table>
	</div>
</div>
</div>
</div>
