<?php
/* @var $incomes array */
/* @var $currentDate*/
use yii\helpers\Url;

$url = Url::to(['recommend/income']);
$this->registerJsFile(Yii::getAlias('@web/version6/js/moment.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web/version6/js/tempusdominus-bootstrap-4.min.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web/version6/css/tempusdominus-bootstrap-4.min.css'));

$js = <<<EOT
 $('#datetimepicker5').datetimepicker({
    autoclose: false,
    format: "MM/YYYY"
 });

$("#datetimepicker5").on("change.datetimepicker", function (e) {
    var date = new Date(e.date);
    var newDate = date.getFullYear() + '-' + (date.getMonth() + 1);
    window.location.href = '$url'+'?month=' + newDate;
});
EOT;

$this->registerJs($js);
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <?= $this->render('_tab') ?>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-1 xtarget col-lotto">
        <h4 class=""><i class="fas fa-money-check"></i> รายได้</h4>
        <div class="w-100 my-2 border-bottom"></div>
        <label><i class="fas fa-calendar-alt"></i> เลือกเดือน</label>
        <div class="input-group date" id="datetimepicker5" data-target-input="nearest">
            <input type="text" value="<?= $currentDate ?>" class="form-control datetimepicker-input mb-0" data-target="#datetimepicker5" data-toggle="datetimepicker" data-container="body" id="date" name="date" />
            <div class="input-group-append" data-target="#datetimepicker5" data-toggle="datetimepicker">
                <button class="btn btn-outline-secondary"><i class="fas fa-calendar my-0"></i></button>
            </div>
        </div>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded p-2 mb-5 xtarget col-lotto">
        <p class="alert alert-primary_bkk text-center">
            รายได้ ระบบแนะนำ จะถอนเข้าเป็นเงินเครดิต หากสงสัยโปรดติดต่อเอเย่นต์ที่ท่านสมัครสมาชิก </p>
        <h5 class="w-100 text-center"><?= $currentDate ?></h5>
        <hr>
        <?php
        $sum = 0;
        foreach ($incomes as $income) {
        $sum += $income['income'];
        ?>
        <div class="d-flex flex-row mb-2 w-100">
            <div class="table-secondary border rounded-left p-2 d-inline w-50 mr-0"><?= $income['day'] ?></div>
            <div class="bg-white border rounded-right text-right text-sm-right text-md-left p-2 d-inline w-50 ml-0">
                <span class="thb">฿ <?= number_format($income['income'], 3) ?></span></div>
        </div>
        <?php } ?>
        <div class="d-flex flex-row mb-2 w-100">
            <div class="table-success border border-success rounded-left p-2 d-inline w-50 mr-0">ยอดรวม (เดือน)</div>
            <div class="bg-white border border-success border-left-0 rounded-right text-right text-sm-right text-md-left text-success p-2 d-inline w-50 ml-0">
                <span class="thb">฿ <?= number_format($sum, 3) ?></span></div>
        </div>
    </div>
</div>