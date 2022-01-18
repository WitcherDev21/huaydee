<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;

use frontend\assets\AppAssetGuest;
use yii\helpers\Url;

AppAssetGuest::register ( $this );
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>
    <style>
    	@media all and (max-width: 720px){
    		.navbar1 { display:none;}
    	}
    </style>
</head>
<body>
<?php $this->beginBody()?>
<!-- preload -->
	<div class="preload" id="preload">
		<div class="cssload-spin-box"></div>
	</div>
	<!-- End / preload -->

	<div class="page-wrap">

		<!-- header -->
		<header class="header header-fixheight header--fixed" style="background-color:#000000;">
			<div class="container">
				<div class="header__inner">
					<div class="header-logo">
						<a href="<?= Url::toRoute(['site/home'])?>"><img
							src="<?= Yii::getAlias('@web')?>/homepage/assets/img/LOGO999.png"
							alt="" /></a>
					</div>

					<div class="navbar1">
						<a href="#home" class="button button4">หน้าแรก</a> <a
							href="#promotion" class="button button4">โปรโมชั่น สิทธิพิเศษ</a>
						<a href="#payment" class="button button4">อัตราการจ่าย</a> <a
							href="#sla" class="button button4">กติกา</a> <a href="#contact"
							class="button button4">ติดต่อเรา</a>

					</div>


				</div>
			</div>
		</header>
		<!-- End / header -->

		<!-- Content-->
		<div class="md-content">
		<?= $content?>
		</div>

	
		<!-- End / Section -->
		<!-- footer-01 -->


		<footer class="footer-01 md-skin-dark">
			<section class="md-section" id="sla" style="background-color: #fff;">
				<div class="footer-01__widget">

					<div class="sec-title sec-title__lg-title md-text-center">
						<h2 class="sec-title__title">กติกาการแจ้งฝากเงิน</h2>
						<span class="sec-title__divider"></span>
					</div>
					<!-- End / sec-title -->
					<div class="container">

						<h4>-บัญชีที่โอนเข้าต้องชื่อ นายอรรถพล เท่านั้น
							และท่านต้องฝากเงินเข้ามาเป็นเศษ เช่น 1,047 บาท เป็นต้น
							เพราะจะได้ง่ายต่อการตรวจสอบ เพื่อจะได้ปรับยอดได้รวดเร็ว</h4>
						<h4>-การแจ้งฝากต้องใส่ จำนวนเงิน เวลา วันที่ ให้ตรงกับที่โอนมา
							โดยดูได้จากในสลิปที่โอนเข้ามา</h4>
						<h4>-ท่านควรแจ้งฝากทันที หลังจากทำการโอน
							เพื่อไม่ให้เกิดปัญหาภายหลัง</h4>
						<h4>-บัญชีที่แจ้งฝาก-ถอน
							ชื่อบัญชีของลูกค้าต้องตรงกันกับบัญชีที่สมัครเท่านั้น</h4>
						<h4>-แจ้งฝากขั้นต่ำที่ 100 บาท</h4>
						<h4>-การแจ้งถอนต้องมียอดแทงหลังการฝาก 50 % จึงจะสามารถถอนได้</h4>
						<br>
						<div>
							<h4>คำเตือน</h4>
						</div>
						<h4>หากมีการแจ้งยอดเงิน ธนาคาร เวลาไม่ตรงตามที่โอนมา
							เจ้าหน้าที่จะยังไม่ดำเนินการแจ้งฝาก
							ให้ท่านตรวจทานวันเวลาและบัญชีให้ถูกต้องก่อนส่งแจ้งใหม่อีกรอบ
							หากแจ้งยอดไม่ตรงอีกครั้ง เจ้าหน้าที่จะทำการระงับ user ไว้ก่อน
							เพื่อให้ user ติดต่อเข้ามาสอบถาม แจ้งฝากซ้ำในยอดเดิม
							หากตรวจพบว่าเป็นการจงใจเพื่อหวังผลประโยช จะทำการระงับ user จนกว่า
							user ติดต่อเข้ามาสอบถาม
							ถ้าบัญชีที่แจ้งฝากเข้ามาไม่ตรงกับบัญชีที่สมัครไว้ จะทำการระงับ
							user ทันทีจนกว่า user จะติดต่อเข้ามาสอบถาม *เพื่อป้องกันมิจฉาชีพ
						</h4>
					</div>
			
			</section>
			<section class="md-section" id="contact"
				style="background-color: #fff;">
				<div class="footer-01__widget">
					<div class="sec-title sec-title__lg-title md-text-center">
						<h2 class="sec-title__title">ติดต่อเรา</h2>
						<span class="sec-title__divider"></span>
					</div>
					<!-- End / sec-title -->
				</div>
			</section>


			<!-- copyright-01 -->
			<div class="copyright-01 md-text-center">
				<div class="container">
					<p class="copyright-01__copy">2018 &copy; Copyright
						www.diamondbet999.com. All rights Reserved.</p>
				</div>
			</div>
			<!-- End / copyright-01 -->

		</footer>
		<!-- End / footer-01 -->
	</div>
<?php $this->endBody()?>

						
						</body>
</html>
<?php $this->endPage()?>
