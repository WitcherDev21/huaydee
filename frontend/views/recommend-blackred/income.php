<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\bootstrap\ActiveForm;

$uri = Yii::getAlias('@web');

$js = <<<EOT

EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>


<div class="col-xs-12">
	<div class="panel">
	<?= $this->render('_tab',['active_tab'=>$active_tab])?>		
		<div class="tab-content">
			<div class="tab-pane fade in active">
				<br>

    				<div class="alert alert-warning text-center" role="alert">รายได้ค่าแนะนำสมาชิก หรือระบบ aff ที่ท่านแนะนำได้ จะแสดงหลังจากหวยบิลนั้นออกผลแล้วนะค่ะ จึงแจ้งมาเพื่อทราบ ขอบคุณค่ะ</div>
    				<ul class="nav nav-tabs">
    				<?php foreach($months as $month){
    				    ?>
                    	<li class="<?= ($active_month == $month)?'active':''?>">
                    		<a href="<?= Url::toRoute(['recommend-blackred/income','month'=>$month])?>" ><i class="fa fa-fw fa-calendar"></i><?= $month?></a>
                    	</li>
                    <?php }?>
                    </ul>
                
        			<table class="table table-striped">
        				<tbody>
      						<?php 
      						$sum = 0;
      						foreach ($incomes as $income){
      						$sum += $income['income'];
      						?>
      						<tr>
      							<td><?= $income['day']?></td>
      							<td><?= $income['income']?></td>
      						</tr>
      						<?php }?>
      						<tr>
      							<td>รวม</td>
      							<td><?= $sum?></td>
      						</tr>
        				</tbody>
        			</table>

			</div>
		</div>
	</div>
</div>


