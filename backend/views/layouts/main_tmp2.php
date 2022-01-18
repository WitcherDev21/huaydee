<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;


AppAsset::register($this);

$base = Yii::getAlias('@web');
$baseTheme = $base.'/theme';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="header">
  <h1><a href="index.php">logo.png</a></h1>
</div>
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
      <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">ยินดีต้อนรับ ผู้ใช้งาน</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
          <li><a href="user_account.php"><i class="icon-user"></i> บัญชีผู้ใช้</a></li>
        <li class="divider"></li>
        <li class="divider"></li>
        <li><a href="logout_web.php"><i class="icon-key"></i> ออกจากระบบ</a></li>
      </ul>
    </li>
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">ข้อความ</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="new_messages.php"><i class="icon-plus"></i> ข้อความใหม่</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="inbox_messages.php"><i class="icon-envelope"></i> กล่องข้อความ</a></li>
        <li class="divider"></li>       
        <li class="divider"></li>       
      </ul>
    </li>  
    <li class=""><a title="" href="setting_admin.php"><i class="icon icon-cog"></i> <span class="text">การตั้งค่า</span></a></li>  
    <li class=""><a title="" href=""><i class="icon icon-share-alt"></i> <span class="text">ออกจากระบบ</span></a></li>
    <?php 
    if (Yii::$app->user->isGuest) {
        echo '<li class=""><a title="" href=""><i class="icon icon-share-alt"></i> <span class="text">เข้าสู่ระบบ</span></a></li>';
    }else{
        echo '<li>'
            . Html::beginForm(['/user/security/logout'], 'post')
            . Html::submitButton(
                'ออกจากระบบ xxxx(' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    ?>
    
  </ul>
</div>

<div id="sidebar"><a href="index.php" class="visible-phone"><i class="icon icon-home"></i> หน้าแรก</a>
  <ul>
      
      <li class="active"><a href="index.php"><i class="icon icon-home"></i> <span>หน้าแรก</span></a> </li>
    <li> <a href="members-list.php"><i class="icon icon-user"></i> <span>รายการสมาชิก</span></a> </li>
    <li> <a href="chit_list.php"><i class="icon icon-inbox"></i> <span>รายการโพย</span></a> </li>
    <li><a href="daily_summary.php"><i class="icon icon-th"></i> <span>สรุปยอดรายวัน</span></a></li>
    <li><a href="credit_report.php"><i class="icon icon-bar-chart"></i> <span>รายงานเครดิต</span></a></li>
    <li><a href="translation_pay_master.php"><i class="icon icon-upload-alt"></i> <span>ฝาก-ถอน มาสเตอร์</span></a></li>
    <li><a href="translation_pay_member.php"><i class="icon icon-user-md"></i> <span>ฝาก-ถอน สมาชิก</span></a></li>
    <li><a href="translation_pay_summary.php"><i class="icon icon-paste"></i> <span>สรุปยอดฝากถอน</span></a></li>
    <li><a href="translation_pay_account.php"><i class="icon icon-bar-chart"></i> <span>บัญชีฝาก-ถอน</span></a></li>
    <li><a href="recommended_system.php"><i class="icon icon-comments"></i> <span>ระบบแนะนำ</span></a></li>
    <li><a href="assistant_report.php"><i class="icon icon-thumbs-up"></i> <span>รายการผู้ช่วย</span></a></li>
    <li><a href="access_user_report.php"><i class="icon icon-star"></i> <span>ข้อมูลการเข้าใช้งาน</span></a></li>
    <li><a href="agencys_List.php"><i class="icon icon-briefcase"></i> <span>รายการเอเยนต์</span></a></li>
    <li><a href="result_management_system.php"><i class="icon icon-cogs"></i> <span>ระบบจัดการผล</span></a></li>
    <li><a href="public_relations_web.php"><i class="icon icon-bullhorn"></i> <span>ประกาศหน้าเว็บ</span></a></li>
      
    <p><p>
    <li class="content"> <span>แบนด์วิดท์การใช้งานรายเดือน</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>การใช้พื้นที่ดิสก์</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>
  </ul>
</div>
<div id="content">
    <div class="container-fluid">
        <?php //= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<div class="row-fluid">
    <div id="footer" class="span12"><h5> 2018 &copy; diamond888.com.  <a href="https://www.diamond888.com">ระบบรายงาน Admin </h5></a> </div>
</div>




<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
