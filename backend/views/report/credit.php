<?php
/* @var $tab */
/* @var $searchModel1 */
/* @var $searchModel2 */
/* @var $dataProvider1 */
/* @var $dataProvider2 */
/* @var $searchModel3 */
/* @var $searchModel4 */
/* @var $dataProvider3 */
/* @var $dataProvider4 */

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-bar-chart"></i></span>
        <h5>รายงานเครดิต</h5>
    </div>
    <div class="widget-content ">
        <div class="widget-box">
            <div class="widget-title">
                <ul class="nav nav-tabs">
                    <li class="<?php echo($tab == '1' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab1">รายงานเครดิต</a>
                    </li>
                    <li class="<?php echo($tab == '2' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab2">รายงานเครดิต
                            ย้อนหลัง</a></li>
                    <li class="<?php echo($tab == '3' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab3">รายงานเครดิตเติมตรง</a>
                    </li>
                    <li class="<?php echo($tab == '4' ? 'active' : ''); ?>"><a data-toggle="tab" href="#tab4">รายงานเครดิตเติมตรง
                            ย้อนหลัง</a></li>
                </ul>
            </div>
            <div class="widget-content tab-content" style="background: #ffffff;">
                <div id="tab1" class="tab-pane <?php echo($tab == '1' ? 'active' : ''); ?>">
                    <p>
                    <div class="row">
                        <?php
                        echo $this->render('_credit_list', [
                            'searchModel' => $searchModel1,
                            'dataProvider' => $dataProvider1,
                            'tab' => '1'
                        ]);
                        ?>
                    </div>
                    </p>
                </div>

                <div id="tab2" class="tab-pane <?php echo($tab == '2' ? 'active' : ''); ?>">
                    <p>
                    <div class="row">
                        <?php
                        echo $this->render('_credit_list', [
                            'searchModel' => $searchModel2,
                            'dataProvider' => $dataProvider2,
                            'tab' => '2'
                        ]);
                        ?>
                    </div>
                    </p>
                </div>


                <div id="tab3" class="tab-pane <?php echo($tab == '3' ? 'active' : ''); ?>">
                    <p>
                    <div class="row">
                        <?php
                        echo $this->render('_credit_list', [
                            'searchModel' => $searchModel3,
                            'dataProvider' => $dataProvider3,
                            'tab' => '3'
                        ]);
                        ?>
                    </div>
                    </p>
                </div>

                <div id="tab4" class="tab-pane <?php echo($tab == '4' ? 'active' : ''); ?>">
                    <p>
                    <div class="row">
                        <?php
                        echo $this->render('_credit_list', [
                            'searchModel' => $searchModel4,
                            'dataProvider' => $dataProvider4,
                            'tab' => '4'
                        ]);
                        ?>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>