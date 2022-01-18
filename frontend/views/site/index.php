<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'my application';
$str = <<<EOT

EOT;

$this->registerJs ( $str );

$css = <<<EOT

EOT;
$this->registerCss ( $css );
?>

<!-- Content-->


	<!-- hero -->
	<div class="hero" id="home">

		<!-- swiper swiper-container -->
		<div class="swiper swiper-container">
			<div class="swiper-wrapper">
				<div class="hero__wrapper"
					style="background-image: url('<?= Yii::getAlias('@web')?>/homepage/assets/img/slider/banner6.jpg');">
					<div class="hero__inner">
						<div class="container">
							<h1 class="hero__title">ด่วน! สมัครสมาชิกวันนี้</h1>
							<p class="hero__desc">เน้นสร้างรายได้ให้ตัวเอง รับคอมมิชชั่น 5 %</p>
							<p class="hero__desc">RUAYDEE96.COM</p>
							<!-- btn -->

							<a class="btn btn-primary" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>&ensp; &ensp;
							&ensp; &ensp;
							<!-- End / btn -->
							<a class="btn btn-success" href="<?= Url::toRoute(['site/home'])?>">>> เข้าเล่น </a>
							<!-- End / btn -->

						</div>
					</div>
				</div>

			</div>
			<div class="swiper-pagination-custom"></div>
			<div class="swiper-button-custom">
				<div class="swiper-button-prev-custom">
					<i class="fa fa-angle-left"></i>
				</div>
				<div class="swiper-button-next-custom">
					<i class="fa fa-angle-right"></i>
				</div>
			</div>
		</div>
		<!-- End / swiper swiper-container -->
	</div>
	<!-- hero -->
	<!-- cta-02 -->
	<div class="cta-02">
		<div class="container">
			<div class="row">
				<div class="col-lg-9 ">
					<h3 class="cta-02__title">*** สอบถามรายละเอียดเพิ่มเติม โทรสอบถาม
						0-000-0000 สอบถามได้ตลอดเวลา</h3>
				</div>
				<div class="col-lg-3  md-text-right">

					<!-- btn -->
					<a class="buttons buttons6" href="#contact">>> ติดต่อเรา </a>

				</div>
			</div>
		</div>
	</div>
	<!-- End / cta-02 -->

	<!-- Section -->
	<section class="md-section" id="promotion">
		<div class="container">
			<div class="row">
				<div
					class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">

					<!-- sec-title -->
					<div class="sec-title sec-title__lg-title md-text-center">
						<h2 class="sec-title__title">โปรชั่น สิทธิพิเศษ</h2>
						<span class="sec-title__divider"></span>
					</div>
					<!-- End / sec-title -->

				</div>
			</div>
			<div class="row row-eq-height">
				<div class="col-sm-6 col-md-6 col-lg-4 ">

					<!-- services -->
					<div class="services">
						<div class="services__img">
							<img src="<?= Yii::getAlias('@web')?>/homepage/assets/img/service/promotion1.jpg" alt="" />
						</div>
						<h2 class="services__title">สมัครสมาชิกเติมเงินครั้งแรก ฟรี 20%
							สูงสุด 1000</h2>
						<div class="services__desc"></div>

						<!-- btn -->
						<a class="btn btn btn-primary btn-custom" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>
						<!-- End / btn -->

					</div>
					<!-- End / services -->

				</div>
				<div class="col-sm-6 col-md-6 col-lg-4 ">

					<!-- services -->
					<div class="services">
						<div class="services__img">
							<img src="<?= Yii::getAlias('@web')?>/homepage/assets/img/service/promotion2.jpg" alt="" />
						</div>
						<h2 class="services__title">ระบบแนะนำ 5% ทุกยอดแทง</h2>
						<div class="services__desc"></div>
						</br>

						<!-- btn -->
						<a class="btn btn btn-primary btn-custom" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>
						<!-- End / btn -->

					</div>
					<!-- End / services -->

				</div>
				<div class="col-sm-6 col-md-6 col-lg-4 ">

					<!-- services -->
					<div class="services">
						<div class="services__img">
							<img src="<?= Yii::getAlias('@web')?>/homepage/assets/img/service/show1.jpg" alt="" />
						</div>
						<h2 class="services__title">ลโปรโมชั่นวันเกิด 1,500
							ต้องมียอดแทงเกิน 5,000 ขึ้นไป</h2>
						<div class="services__desc"></div>

						<!-- btn -->
						<a class="btn btn btn-primary btn-custom" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>
						<!-- End / btn -->

					</div>
					<!-- End / services -->

				</div>

			</div>
			<div class="row row-eq-height">
				<div class="col-sm-6 col-md-6 col-lg-4 ">

					<!-- services -->
					<div class="services">
						<div class="services__img">
							<img src="<?= Yii::getAlias('@web')?>/homepage/assets/img/service/promotion1.jpg" alt="" />
						</div>
						<h2 class="services__title">ยอดเทิร์นทุก 100,000 ฟรีโบนัส 1,000</h2>
						<div class="services__desc"></div>

						<!-- btn -->
						<a class="btn btn btn-primary btn-custom" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>
						<!-- End / btn -->

					</div>
					<!-- End / services -->

				</div>
				<div class="col-sm-6 col-md-6 col-lg-4 ">

					<!-- services -->
					<div class="services">
						<div class="services__img">
							<img src="<?= Yii::getAlias('@web')?>/homepage/assets/img/service/promotion2.jpg" alt="" />
						</div>
						<h2 class="services__title">ทุกยอดถอนระบบแนะนำ +10%</h2>
						<div class="services__desc"></div>

						<!-- btn -->
						<a class="btn btn btn-primary btn-custom" href="<?= Url::toRoute(['user/registration/register'])?>">สมัครสมาชิก </a>
						<!-- End / btn -->

					</div>
					<!-- End / services -->

				</div>


			</div>
		</div>
	</section>
	<!-- End / Section -->

	<!-- Section -->
	<section class="md-section md-skin-dark" id="payment"
		style="background-image: url(<?= Yii::getAlias('@web')?>/homepage/assets/img/bg/demo.jpg&amp;quot;); padding-bottom: 50px;">
		<div class="md-overlay"></div>
		<div class="container">
			<div class="container">
				<div class="row">
					<div
						class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">

						<!-- sec-title -->
						<div class="sec-title sec-title__lg-title md-text-center">
							<h2 class="sec-title__title">อัตราการจ่าย</h2>
							<span class="sec-title__divider"></span>
						</div>
						<!-- End / sec-title -->

					</div>
				</div>
				<div class="row">
					<div class="sec-title sec-title__lg-title md-text-left">
						<h4>
							<table id="customers">
								<thead>
									<tr>
										<th>ประเภทหวย/ราคาจ่าย</th>
										<th>ราคาปกติ</th>
										<th>VIP1</th>
										<th>VIP2</th>
										<th>VIP3</th>
										<th>VIP4</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>3 ตัวตรง</td>
										<td>800</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>3 ตัวโต๊ด</td>
										<td>130</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>วิ่งบน</td>
										<td>3.3</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>วิ่งล่าง</td>
										<td>4.3</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>2 ตัวบน</td>
										<td>95</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>2 ตัวล่าง</td>
										<td>95</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>แดง</td>
										<td>1:1</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td>ดำ</td>
										<td>1:0.95</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</section>

<!-- End / Section -->

