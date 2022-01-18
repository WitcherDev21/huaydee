<?php

use common\libs\Constants;
use yii\widgets\ListView;
use yii\helpers\Url;

?>
<div id="section-content" class="container">
    <div class="bar-back d-flex justify-content-between align-items-center">
        <div id="top"></div>
        <a href="<?= Url::to(['yeekee/index'])?>" class="mr-auto">
            <i class="fas fa-chevron-left"></i>ย้อนกลับ
        </a>
        <a href="#" class="btn btn-outline-secondary btn-sm mr-1 text-dark" data-toggle="modal"
           data-target="#rule-yeekee">กติกา</a>
    </div>
    <?php if ($yeekee->status == Constants::status_finish_show_result) { ?>
        <div class="p-2 w-100 bg-light_bkk main-content align-self-stretch"
             style="min-height: calc((100vh - 148.42px) - 50px);">
            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
                <div class="lotto-title">
                    <h4><i class="fas fa-award"></i> ผลรางวัล</h4>
                </div>
            </div>

            <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget mb-1">
                <h6><i class="fas fa-trophy"></i> จับยี่กี่ <span
                            class="badge badge-dark">รอบที่ <?= $yeekee->round ?></span></h6>
                <div class="card border-dark text-center mb-2">
                    <div class="card-header text-danger p-1">
                        จับยี่กี่ - รอบที่ <?= $yeekee->round ?>
                    </div>
                    <div class="card-body p-0">
                        <small class="text-secondary">- ปิดรับการทายผลตัวเลขออกรางวัล -</small>
                        <div class="card text-center w-100 rounded-0 border-right-0 border-bottom-0 border-left-0 m-0">
                            <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                                ผลรางวัล
                            </div>
                            <div class="card-body border-bottom p-0">
                                <h3 class="number text-center">
                                    <?= $yeekee->getResults('other'); ?>
                                    <span class="number text-success"><?= $yeekee->getResults('two_under'); ?></span>
                                    <span class="number text-danger"><?= $yeekee->getResults('three_top'); ?></span>
                                </h3>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="card text-center w-50 border-card-right m-0">
                                <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                                    3ตัวบน
                                </div>
                                <div class="card-body p-0">
                                    <h3 class="card-text mb-1"><?= $yeekee->getResults('three_top'); ?></h3>
                                </div>
                            </div>
                            <div class="card text-center w-50 border-card-right m-0">
                                <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                                    2ตัวล่าง
                                </div>
                                <div class="card-body p-0">
                                    <h3 class="card-text mb-1"><?= $yeekee->getResults('two_under'); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="card text-center w-100 rounded-0 border-right-0 border-bottom-0 border-left-0 m-0">
                            <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                                ผลรวมยิงเลข
                            </div>
                            <div class="card-body p-0">
                                <h5 class="number text-center"><?= $yeekeePost ?> </h5>
                            </div>
                        </div>
                        <div class="card text-center w-100 rounded-0 border-right-0 border-bottom-0 border-left-0 m-0">
                            <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0">
                                เลขแถวที่ 16
                            </div>
                            <div class="card-body border-bottom p-0">
                                <h5 class="number text-primary text-center">
                                    <?= str_pad($yeekee->yeekeePostId16->post_num, 5, '0', STR_PAD_LEFT); ?>
                                </h5>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="card text-center w-50 border-card-right m-0">
                                <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0 d-flex flex-column flex-sm-column flex-md-row align-items-center justify-content-center w-100"
                                     style="line-height:1.2;">
                                    <span class="d-block mr-1">สมาชิกยิงเลขได้</span>
                                    <u>อันดับ 1</u>
                                </div>
                                <div class="card-body p-0">
                                    <h6 class="card-text mb-2"><?= $yeekee->yeekeePostId1->post_name ?></h6>
                                </div>
                            </div>
                            <div class="card text-center w-50 border-card-right m-0">
                                <div class="card-header text-secondary sub-card-header bg-transparent p-0 border-0 d-flex flex-column flex-sm-column flex-md-row align-items-center justify-content-center w-100"
                                     style="line-height:1.2;">
                                    <span class="d-block mr-1">สมาชิกยิงเลขได้</span>
                                    <u>อันดับ 16</u>
                                </div>
                                <div class="card-body p-0">
                                    <h6 class="card-text mb-2"><?= $yeekee->yeekeePostId16->post_name ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bgwhitealpha shadow-sm rounded p-2 h-100 xtarget mb-5">
                <div class="d-flex flex-column">
                    <h5 class="text-center text-success">
                        <i class="fas fa-th-list"></i>
                    </h5>
                    <?php
                    $key = 1;
                    echo ListView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'maxButtonCount' => 3,

                            // Customzing options for pager container tag
                            'options' => [
                                'tag' => 'ul',
                                'class' => 'pagination b-pagination pagination-md justify-content-center',
                                'id' => 'pager-container',
                                'aria-disabled' => 'false',
                                'aria-label' => 'Pagination',
                                'prevPageLabel' => false,
                                'nextPageLabel' => false,
                            ],

                            // Customzing CSS class for pager link
                            'linkOptions' => [
                                'class' => 'page-link',
                                'aria-checked' => 'true',
                                'tag' => 'li'
                            ],
                            'activePageCssClass' => 'active',

                            // Customzing CSS class for navigating link
                            'pageCssClass' => 'page-item',
                            'disabledPageCssClass' => 'page-item',
                            'disabledListItemSubTagOptions' => ['tag' => 'span', 'class' => 'page-link'],
                        ],
                        'options' => [
                            'class' => 'row',
                        ],
                        'itemView' => function ($model, $key, $index, $widget) {
                            return $this->render('_list', ['model' => $model, 'index' => $index]);
                        },
                        'summary' => '',
                        'layout' => "{items}\n<div class='col-12'><nav>{pager}</nav></div>",
                        'itemOptions' => [
                            'tag' => 'div',
                            'class' => 'col-12 pt-1 pb-1'
                        ],
                        'emptyText' => '',
                    ]); ?>
                </div>
            </div>

        </div>
    <?php }elseif ($yeekee->status == Constants::status_cancel){ ?>
        <div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140.677px) - 50px);">
            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
                <div class="lotto-title">
                    <h4><i class="fas fa-exclamation-triangle"></i> จับยี่กี - รอบที่ <?= $yeekee->round ?></h4>
                </div>
            </div>
            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 xtarget col-lotto">
                <div class="text-center my-4">
                    <span style="font-size:100px;line-height:1.2;"></span>
                    <h4 class="text-danger" style="font-family:inherit"><?= Constants::$status[$yeekee->status]?></h4>
                </div>
            </div>
        </div>
    <?php }else{ ?>
        <div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140.677px) - 50px);">
            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row mb-1 pb-0">
                <div class="lotto-title">
                    <h4><i class="fas fa-exclamation-triangle"></i> จับยี่กี - รอบที่ <?= $yeekee->round ?></h4>
                </div>
            </div>
            <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 xtarget col-lotto">
                <div class="text-center my-4">
                    <span style="font-size:100px;line-height:1.2;"><i class="fas fa-spinner fa-spin text-danger"></i></span>
                    <h4 class="text-danger" style="font-family:inherit"> กำลังรอผลหวย</h4>
                </div>
                <div class="alert alert-danger text-center">กรุณา คลิกที่ปุ่ม <b>Refresh</b> เพื่อทำรายการใหม่อีกครั้ง</div>

            </div>
            <div class="bg-white p-2 rounded shadow-sm w-100 mb-5">
                <a href="" class="btn btn-dark btn-block text-white"><i class="fas fa-sync-alt"></i> Refresh</a>
            </div>
        </div>
    <?php } ?>
</div>
<?= $this->render('/yeekee/modal_rule', ['yeekeeGame' => $yeekee, 'game' => $game]) ?>