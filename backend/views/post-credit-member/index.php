<?php
use common\component\ModalCommon;
/* @var $dataProviderCurrent */
/**/

$js = <<<EOT
		 
EOT;
$this->registerJs($js);
$css = <<<EOT
	
EOT;
$this->registerCss($css);

$today = date('Y-m-d H:i:s');
?>


<?php
$this->registerJs("$(function() {
   $('.popupModal').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-detail').load($(this).attr('href'));
   });
});");
yii\bootstrap\Modal::begin([
    'id' => 'modal',
    'headerOptions' => ['class' => 'modal-header-primary'],
    'size' => 'modal-lg',
    'header' => '<h4 class="modal-title">รายงานเครดิต</h4>',
    'footer' => '<button type="reset" class="btn btn-default" data-dismiss="modal">ปิด</button>',
]);
?>
<div class="modal-detail">

</div>
<?php
yii\bootstrap\Modal::end();
?>


<div class="col-xs-12">
    <div class="panel">
        <h3>ฝาก-ถอน สมาชิก</h3>
        <ul class="nav nav-tabs">
            <li class="<?php echo(empty($active) || $active == 'Current' ? 'active' : ''); ?>"><a href="#list-current" data-toggle="tab"><i
                            class="fa fa-fw fa-calendar"></i> รายการ แจ้ง ฝาก-ถอน</a></li>
            <li class="<?php echo(!empty($active) && $active == 'History' ? 'active' : ''); ?>"><a href="#list-history" data-toggle="tab"><i
                            class="fa fa-history"></i> รายการ แจ้ง ฝาก-ถอนย้อนหลัง</a></li>
        </ul>
        <div class="tab-content" style="background-color: #fff;">
            <div id="list-current"
                 class="tab-pane fade in <?php echo(empty($active) || $active == 'Current' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_list1', [
                    'dataProvider' => $dataProviderCurrent,
                    'searchModel' => $searchModelCurrent,
                    'type' => 'Current'
                ]);
                ?>
            </div>
            <div id="list-history"
                 class="tab-pane fade in <?php echo(!empty($active) && $active == 'History' ? 'active' : ''); ?>">
                <?php
                echo $this->render('_list2', [
                    'dataProvider' => $dataProviderHistory,
                    'searchModel' => $searchModelHistory,
                    'type' => 'History'
                ]);
                ?>
            </div>

        </div>
    </div>
</div>
