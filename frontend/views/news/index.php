<?php
/* @var $news \common\models\News */

use yii\helpers\Url;

?>
<div class="bar-back d-flex justify-content-between align-items-center">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
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
                                    <span><span><b>ประกาศ</b><br><small>ข่าวสารจากทีมงาน</small></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="txt-box-news">
                            <div class="row px-2">
                                <?php foreach ($news as $key => $new) {
                                    if ($key === 0) {
                                        $img = '189815.jpg';
                                    } elseif ($key === 1) {
                                        $img = '189816.jpg';
                                    } elseif ($key === 3){
                                        $img = '189814.jpg';
                                    }else {
                                        $img = '189817.jpg';
                                    }
                                    ?>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-4 px-1">
                                        <div class="d-flex p-1 w-100">
                                            <a href="<?= Url::to(['news/detail', 'id' => $new->id]) ?>">
                                                <img src="<?= Yii::getAlias('@web/img/').$img ?>" data-action="<?= $key ?>">
                                                <div class="text-news">
                                                    <h1><?= $new->title ?></h1>
                                                    <p class="ellipsis"><?= strip_tags($new->message) ?></p>
                                                    <span class="badge badge-secondary"><?= date("d-m-Y", strtotime($new->create_at)) ?></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>