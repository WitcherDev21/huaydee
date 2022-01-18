<?php
/**
 * Created by PhpStorm.
 * User: topte
 * Date: 9/25/2018
 * Time: 9:12 PM
 */


/* @var $this yii\web\View */
/* @var $model common\models\Game */

$this->title = Yii::t('app', 'สร้างเกมส์หวยหุ้น');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'เกมส์หวยหุ้น'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5><?= $this->title ?></h5>
    </div>
    <div class="widget-content">
        <?= $this->render('_form', [
            'model' => $model,
            'games' => $games,
            'typeGameShareds' => $typeGameShareds,
        ]) ?>
    </div>
</div>