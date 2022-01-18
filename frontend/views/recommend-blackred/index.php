<?php

use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\base\Widget;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\bootstrap\ActiveForm;

$uri = Yii::getAlias('@web');
$this->registerJsFile('@web/js/copy2clipboard.js');

$js = <<<EOT
    $("textarea[name='link']").on('focus', function() { 
        $(this).select(); 
        //alert('555');
    });

$('.link-copy').on('click',function(){
console.log(select_all_and_copy(document.getElementById('copy-clipboard')));
alert('copied');
});


EOT;
$this->registerJs($js);
$css = <<<EOT
    h2{
        color: #1ab394;
    }

EOT;
$this->registerCss($css);
?>


<div class="col-xs-12">
    <div class="panel">
        <?= $this->render('_tab', ['active_tab' => $activeTab]) ?>
        <div class="tab-content panel-body">
            <div class="tab-pane fade in active">
                <div class="row">
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>ส่วนแบ่งรายได้</h4>
                                <h2><?= $percent ?>%</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>จำนวนคลิกทั้งหมด</h4>
                                <h2><?= $countClick ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>สมาชิกที่แนะนำได้</h4>
                                <h2><?= $memberCount ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>จำนวนแทงทั้งหมด</h4>
                                <h2><?= number_format($sumPlayAmount) ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>รายได้ทั้งหมด</h4>
                                <h2>
                                    <?php echo number_format($totleIncome, 3) ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-4 col-xs-6">
                        <div class="panel panel-default">
                            <div class="text-center">
                                <h4>รายได้ปัจจุบัน</h4>
                                <h2><?= number_format($currentIncome, 3) ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12">
    <div class="card">
        <h4 class="card-header bg-transparent">
            <?= $news->title ?>
        </h4>
        <div class="card-body">
            <?= $news->message ?>
        </div>

    </div>
</div>

<div class="col-xs-12 col-md-6">
    <div class="card">
        <h4 class="card-header bg-transparent">
            ข้อความโปรโมทและลิงค์
        </h4>
        <div class="card-body">
            <div class="card">
                <div class="card-header text-center">
                    ลิ้งสำหรับโปรโมท
                    <a class="link-copy" href="javascript:"><i class="fa fa-copy"></i> Copy</a>

                </div>
                <div class="card-body">
                    <?= Html::textarea('link', $recommendUrl, ['id' => 'copy-clipboard', 'class' => 'form-control', 'rows' => '3', 'readonly' => 'true']) ?>
            </div>
        </div>
    </div>
</div>


