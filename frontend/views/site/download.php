<?php
/* @var $news \common\models\News */

use yii\helpers\Url;

?>
<div class="bar-back d-flex justify-content-between align-items-center">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="mb-1 py-1 bg-light rounded col-lotto">
        <div class="accordion bg-transparent" id="accordionCredit">
            <div class="row">
                <div class="col">
                    <div class="box-news d-flex flex-column">
                        <div class="h-box-news d-flex justify-content-between align-content-center">
                            <div class="float-left">
                                <div class="icon-news">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="txt-h">
                                    <span><span><b>Download</b><br><small>Application</small></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="txt-box-news">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 px-1">
                                <div class="d-flex p-1 w-100" style="margin-left: 15%">

                                    <a href="<?= Yii::getAlias('@web/application/android-lotto.apk')?>">
                                        <button type="button" class="btn btn-primary_bkk4"><i class="fab fa-android" ></i>Android</button>
                                    </a>
                                    <a href="#">
                                        <button type="button" class="btn btn-primary_bkk5"><i class="fab fa-apple"></i>IOS</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>