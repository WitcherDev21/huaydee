<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-bar-chart"></i></span>
		<h5>บัญชีฝาก-ถอน</h5>
	</div>
	<div class="widget-content ">
            <div style="padding-bottom: 20px;">
                <h2>แก้ไข บัญชีฝาก-ถอน</h2>
            </div>

            <?php
            echo $this->render('_form_account_refill',[
                'modelUserHasBankSearch'=>$modelUserHasBankSearch,
                'BankList'=>$BankList,
                'dataUserHasBank'=>$dataUserHasBank
            ]);
            ?>
	</div>
</div>
