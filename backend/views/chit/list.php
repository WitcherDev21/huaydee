<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="icon-inbox"></i>
        </span>
        <h5>รายการโพย</h5>
        <span class="label label-info">SSL Secure</span>
    </div>
    <div class="widget-content ">
        <div class="widget-box">
            <div class="widget-title">
                <ul class="nav nav-tabs">
                    <li class="<?php echo (!empty($tab_active) && $tab_active=='tab1' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab1">รายการโพยยี่กี่</a></li>
                    <li class="<?php echo (!empty($tab_active) && $tab_active=='tab2' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab2">รายการโพยยี่กี่ ย้อนหลัง</a></li>
                </ul>
            </div>
            <div class="widget-content tab-content">
                <div id="tab1" class="tab-pane <?php echo (!empty($tab_active) && $tab_active=='tab1' ? 'active' : ''); ?>">
                    <p>
                        <?php
                        echo $this->render('_tab1',[
                            'dataProvider'=>$dataProvider,
                            'searchModel'=>$searchModel
                        ]);
                        ?>
                    </p>
                </div>
                <div id="tab2" class="tab-pane <?php echo (!empty($tab_active) && $tab_active=='tab2' ? 'active' : ''); ?>">
                    <p>
                        <?php
                        echo $this->render('_tab2',[
                            'dataProvider2'=>$dataProvider2,
                            'searchModel'=>$searchModel
                        ]);
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
