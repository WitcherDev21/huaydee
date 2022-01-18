<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\libs\Constants;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SetupLevelPlaytypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setup Level Playtypes';
$this->params['breadcrumbs'][] = $this->title;
$gameId = Yii::$app->request->get('gameId', '0');
?>
<style>
    .non-hover tr:hover {
        background: none !important;
    }
    .non-hover tr.gray:hover{
        background: #eaeaea !important;
    }
</style>
<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="icon-inbox"></i>
        </span>
        <h5>ตั้งค่าจ่ายระดับ</h5>
    </div>
    <div class="widget-content tab-content">
        <div class="widget-box">
            <div class="panel">
                <ul class="nav nav-tabs">
                    <li class="<?= $gameId === '0' ? 'active' : '' ?>">
                        <a href="<?= Url::to(['index', 'gameId' => '0']) ?>">หวยรัฐ,หวยออมสิน,หวยธกส</a>
                    </li>
                    <li class="<?= $gameId === '1' ? 'active' : '' ?>">
                        <a href="<?= Url::to(['index', 'gameId' => '1']) ?>">หวยหุ้น</a>
                    </li>
                </ul>
                <div style="overflow: auto;">
                    <div class="tab-content">
                        <div id="list-current-lottery" class="tab-pane fade in active">
                            <?php $form = ActiveForm::begin(); ?>
                            <table class="table table-bordered non-hover">
                                <thead>
                                    <tr>
                                        <th>ชนิด</th>
                                        <th>ระดับ 1</th>
                                        <th>ระดับ 2</th>
                                        <th>ระดับ 3</th>
                                        <th>ระดับ 4</th>
                                        <th>ระดับ 5</th>
                                        <th>ระดับ 6</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach (Constants::SETUP_LEVEL_PLAYTYPE_NAME[$gameId] as $key => $playType) { ?>
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td rowspan="3"
                                            style="vertical-align : middle;text-align:center;" class="text-nowrap"><?= $playType ?></td>
                                        <?php for ($level = 0; $level < 6; $level++) {
                                            $playTypeCode = Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key];
                                            $value = isset($model[$playTypeCode]['minimumPlay'][$level]) ? $model[$playTypeCode]['minimumPlay'][$level] : 0;
                                            ?>
                                            <td>
                                                <label>Min: </label>
                                                <input type="number" class="form-control"
                                                       name="minimumPlay[<?= Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key] ?>][]"
                                                       value="<?= $value ?>" style="float: right">
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <?php for ($level = 0; $level < 6; $level++) {
                                            $playTypeCode = Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key];
                                            $value = isset($model[$playTypeCode]['maximumPlay'][$level]) ? $model[$playTypeCode]['maximumPlay'][$level] : 0;
                                            ?>
                                            <td>
                                                <label>Max: </label>
                                                <input type="number" class="form-control"
                                                       name="maximumPlay[<?= Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key] ?>][]"
                                                       value="<?= $value ?>" style="float: right">
                                            </td>
                                        <?php } ?>
                                    </tr>
                                    <tr class="gray" style="background-color: #eaeaea">
                                        <?php for ($level = 0; $level < 6; $level++) {
                                            $playTypeCode = Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key];
                                            $value = isset($model[$playTypeCode]['jackPotPerUnit'][$level]) ? $model[$playTypeCode]['jackPotPerUnit'][$level] : 0;
                                            ?>
                                            <td>
                                                <label>JackPotPerUnit: </label>
                                                <input type="number" class="form-control"
                                                       name="jackPotPerUnit[<?= Constants::SETUP_LEVEL_PLAYTYPE_CODE[$gameId][$key] ?>][]"
                                                       value="<?= $value ?>" style="float: right">
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td class="text-right" colspan="7"><?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
