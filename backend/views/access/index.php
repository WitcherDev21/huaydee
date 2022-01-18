<?php

use yii\grid\GridView;
use common\libs\Constants;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-star"></i></span>
        <h5>ข้อมูลการเข้าใช้งาน</h5>
    </div>

    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

    <div class="widget-content ">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
                <br>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-access',
                    'action' => Yii::$app->urlManager->createUrl(['access/index']),
                    'options' => ['data-pjax' => true],
                    'method' => 'GET'
                ]);

                $list_user_status[] = ['id' => '', 'name' => '-- สถานะ --'];
                $list_user_status[] = ['id' => Constants::user_status_active, 'name' => Constants::$user_status[Constants::user_status_active]];
                $list_user_status[] = ['id' => Constants::user_status_withhold, 'name' => Constants::$user_status[Constants::user_status_withhold]];
                ?>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-md-5 col-sm-5 control-label" style="text-align: right;">Username</label>
                            <div class="col-md-7  col-sm-7">
                                <?= Html::activeInput('text', $searchModel, 'username', ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-5 col-sm-5 control-label" style="text-align: right;">Ip</label>
                            <div class="col-md-7  col-sm-7">
                                <?= Html::activeInput('text', $searchModel, 'ip_address', ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-5 col-sm-5 control-label" style="text-align: right;">สถานะ</label>
                            <div class="col-md-7  col-sm-7">
                                <?= Html::activeDropDownList($searchModel, 'user_status', ArrayHelper::map($list_user_status, 'id', 'name'), ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-5 col-sm-5 control-label" style="text-align: right;">สิทธิ์การเข้าใช้งาน</label>
                            <div class="col-md-7  col-sm-7">
                                <?= Html::activeDropDownList($searchModel, 'authRole', $RolesList, ['prompt' => 'Select...', 'class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา
                        </button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <br><br>


        <div class="table-responsive">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'User ID',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->user->id;
                        }
                    ],
                    [
                        'label' => 'User Name',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'วันที่-เวลา',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->access_at;
                        }
                    ],
                    [
                        'label' => 'IP Address',
                        'value' => function ($model, $key, $index, $column) {
                            return $model->ip_address;
                        }
                    ],
                    [
                        'label' => 'สถานะ',
                        'format' => 'html',
                        'value' => function ($model, $key, $index, $column) {
                            $result = '';
                            if (!empty($model->user->user_status)) {
                                $result = '<a href="javascript:;" class="btn btn-xs btn-' . ($model->user->user_status == 1 ? 'success' : 'danger') . '">' . Constants::$user_status[$model->user->user_status] . '</a>';
                            }
                            return $result;
                        }
                    ],
                    [
                        'label' => 'หมายเหตุ',
                        'value' => function ($model, $key, $index, $column) {
                            return '';
                        }
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>