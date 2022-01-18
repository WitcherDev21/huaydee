<style>
    a:focus{
        outline: unset !important;
    }
    .intro{
        background: #000000;
    }
    .footer{
        margin-top: unset!important;
    }
    .copyright{
        margin-top: unset!important;
    }
</style>
<?php
/* @var $games \common\models\Games */
use yii\helpers\Url;
use common\libs\Constants;

$css = <<<EOT
	
EOT;
//$this->registerCss ( $css );
$js = <<<EOT
$('.not-open').on('click',function(){
    swal({
        title:'ยังไม่เปิดให้บริการจะเปิดให้บริการเร็วๆนี้'
    });
    $(".swal2-modal").css('background-color', 'red');
    $(".swal2-title").css('color', 'white');
});
EOT;
$this->registerJs ( $js );
?>

<?php foreach($games as $key => $game){
	$class = 'bg-warning-light';
	if($game->status == Constants::status_inactive){
		$class = 'bg-default-light';
	}
?>

<div class="col-md-6 col-sm-6">
    <a href="<?= Url::to([$game->uri])?>" style="display: block; height: 300px; padding-top: 15px;" align='center' <?= $game->uri === '#' ?
        'class="not-open" onclick="return false;"' : '' ?>>
        <?php if ($game->image) {
            $image = $game->image;
        } else {
            $image = 'no-image.png';
        } ?>
        <img src="<?= Yii::getAlias('@gameImage/'). $image ?>">
    </a>
</div>
<?php }?>