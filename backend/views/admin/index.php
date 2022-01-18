<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\widgets\Alert;
use yii\bootstrap\ActiveForm;

?>
<?php
$apiUri = Url::to(['admin/yeekee-answer']);
$blackredApiUrl = Url::to(['admin/blackred-answer']);
$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;

$js = <<<EOT
var getYeekeepost = setInterval(function() {
    $.ajax({
	    url: '$apiUri',
		type: 'post',
		data: {
		    '$csrf':'$token'
		},
		success: function (result) {
            $('#active-monitor').append('<br><code>'+JSON.stringify(result)+'</code>');
		}
	});
}, 5000);
	
var getYeekeepost = setInterval(function() {
    $.ajax({
	    url: '$blackredApiUrl',
		type: 'post',
		data: {
		    '$csrf':'$token'
		},
		success: function (result) {
            $('#active-monitor-blackred').append('<br><code>'+JSON.stringify(result)+'</code>');
		}
	});
}, 5000);
		
EOT;
$this->registerJs ( $js );
?>

<div class="row-fluid">
	<div class="widget-box">
		<div class="widget-title bg_lg">
			<span class="icon"><i class="icon-cog"></i></span>
			<h5>Generage Game</h5>
		</div>
		<div class="widget-content">
		<?= Alert::widget();?>
			<div class="panel">
				<h4>สร้างเกมส์ ยี่กี ประจำวันที่ <?= date('d/m/Y')?> </h4>
				<?php if(empty($yeekee)){?>
					<a class="btn btn-success" href="<?= Url::to(['admin/generate-yeekee'])?>">YEEKEE GEN(<?= date('d/m/Y')?>)</a>
				<?php }else{?>
					<p class="text-success"><?php echo 'ถูกสร้างแล้วเริ่ม : '.$yeekee->start_at?></p>
				<?php }?>
			</div>
            <?php if (in_array('thai-shared-game', $arrRoles)) { ?>
            <div class="panel">
                <h4>สร้างเกมส์ หวยหุ้น ประจำวันที่ <?= date('d/m/Y')?> </h4>
                <a class="btn btn-success" href="<?= Url::to(['thai-shared/index'])?>">หวยหุ้น (<?= date('d/m/Y')?>)</a>
            </div>
            <?php } ?>
		</div>
	</div>	

	<div class="widget-box">
		<div class="widget-title bg_lg">
			<span class="icon"><i class="icon-cog"></i></span>
			<h5>Tricker Yeekee</h5>
		</div>
		<div class="widget-content">
			<div class="panel">
				<h4>สถานะการเล่นเกมส์ ยี่กี </h4>
				<div id="active-monitor">
				</div>
			</div>
		</div>
	</div>
</div>

