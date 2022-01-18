<style>
    a:hover {
        color: !important;
    }
</style>
<?php
use yii\helpers\Url;
use yii\bootstrap\Html;
use common\libs\Constants;

$js = <<<EOT
		
var x = setInterval(function() {

	  var now = new Date().getTime();

	  $('.countdown-items').each(function(index,item){
		
	  		var countDownDate = $(item).data('finish_at')*1000; //php to java time
		  	var distance = countDownDate - now;

		    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		  	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	
		  	var h = (hours<10?'0':'') + hours;
      		var m = (minutes<10?'0':'') + minutes;
			var s = (seconds<10?'0':'') + seconds;
			
			var text = days + ' วัน ' + '<br>' + h+':'+m+':'+s;
		  	$(item).html(text);
		  
		  if (distance < 0) {
		    $($(item)).parent().toggleClass('bg-success-light bg-default-light');
			$(item).toggleClass('countdown-items text-danger');
		    $(item).text('ปิดรับแทง');
		  }
	  });
		if($('.countdown-items').length == 0){
			clearInterval(x);
		}
		
	}, 1000);
		
		
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date('Y-m-d H:i:s');
?>
<?= Html::hiddenInput('today',$today,['data-today'=>strtotime($today)]);?>
<div class="card col-md-12">
    <h4 class="card-header bg-transparent">
        <a href="<?= Url::toRoute(['lotto/index'])?>" style="color:red">หวยรัฐบาลไทย</a>
    </h4>
    <div class="card-body">
        <?php
        foreach($lotteryGames as $lotteryGame){

            $open = false;
            if(strtotime($lotteryGame->startDate) < strtotime($today) && strtotime($lotteryGame->endDate) > strtotime($today) && $lotteryGame->status === 1){
                $class = 'bg-success-light';
                $open = true;
            }elseif ($lotteryGame->status === 2) {
                $class = 'bg-danger-light';
                $open = false;
            }else{
                $class = 'bg-default-light';
                $open = false;
            }


            ?>
            <div class="col-md-6 col-xs-6">

                <a href="<?= Url::to(['thai-shared-chit/play', 'id' => $lotteryGame->id])?>"
                   title = "<?= Constants::$status[$lotteryGame->status]?>"
                   class="text-center <?= $class?>"
                   style="display: block;
				margin-left: -10px;
    			margin-right: -10px;
    			">
                    <h4> <?= $lotteryGame->title ?></h4>

                    ปิดรับ: <?= date('d/m/Y H:i:s',strtotime($lotteryGame->endDate));?>
                    <?php if($open){?>
                        <h3 class="countdown-items" data-finish_at=<?= strtotime($lotteryGame->endDate)?>>
                            <?php
                            $datetime1 = new DateTime();
                            $datetime2 = new DateTime($lotteryGame->endDate);
                            $interval = $datetime1->diff($datetime2);
                            echo $interval->format('%d วัน <br> %H:%I:%S');
                            ?>
                        </h3>
                    <?php }else{?>
                        <h3 class="text-danger" <?= $lotteryGame->status === 2 ? 'style="color: white !important;"' : '' ?>>
                            <?= 'ปิดรับแทง'?>
                        </h3>
                    <?php }?>
                </a>
            </div>
        <?php }?>
    </div>
</div>