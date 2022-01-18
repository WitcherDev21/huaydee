<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\libs\Constants;
use frontend\component\GameRule;
use yii\base\Widget;

?>


<?php
$apiUri = Url::toRoute(['yeekee/check-procesed']);
$csrf = \yii::$app->request->csrfParam;
$token = \yii::$app->request->csrfToken;
$urlResult = Url::toRoute(['yeekee/result','yeekee_id'=>$yeekee_id]);
$js = <<<EOT
	var getResult = setInterval(function() {
	  	 $.ajax({
		       url: '$apiUri',
		       type: 'post',
		       data: {
		       		'$csrf':'$token',
		       		'yeekee_id':$yeekee_id
		       	},
		       success: function (result) {
		          	console.log(result);
                    if(result == true){
                        window.location.replace('$urlResult');
                    }
                    

		       }
		  });

	}, 10000);
EOT;
$this->registerJs ( $js );
$css = <<<EOT
.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
EOT;
$this->registerCss ( $css );
?>

<div class="col-xs-12" align="center">
			<h4>ระบบกำลังประมวลผล <a href="<?= Url::toRoute(['yeekee/play','yeekee_id'=>$yeekee_id])?>"><i class="fa fa-refresh"></i></a></h4>
			<div class="loader"></div>
			
</div>

