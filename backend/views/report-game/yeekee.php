<?php

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
?>

<div class="widget-box">
    <div class="widget-title">
		<span class="icon"> <i class="icon-user"></i>
		</span>
        <h5>report เกมยี่กี้</h5>
    </div>
    <?php Pjax::begin([
        'id' => 'countries-tab1',
        'timeout' => false,
        'enablePushState' => false,
        'clientOptions' => ['method' => 'GET']])
    ?>

    <div class="widget-content ">

        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
                <br>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-recommend',
                    'action' => Yii::$app->urlManager->createUrl(['recommend/blackred']),
                    'options' => ['data-pjax' => true ],
                    'method' => 'GET'
                ]);
                ?>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="col-md-3 col-sm-3 control-label" style="text-align: right;">Username</label>
                            <div class="col-md-9  col-sm-9">
                                <?= Html::input('string', 'username',
                                    isset(Yii::$app->request->queryParams['username']) ?
                                        Yii::$app->request->queryParams['username'] : '',
                                    ['style' => 'width:100%;']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info" type="submit"><i class="glyphicon glyphicon-search"></i> ค้นหา</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <br><br>
        <div class="table-responsive">
            <?php
            echo GridView::widget ( [
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'User name',
                        'value' => function ($model) {
                            return $model->user->username;
                        }
                    ],
                    [
                        'label' => 'จำนวนแทงทั้งหมด',
                        'contentOptions' => ['style' => 'text-align: right;'],
                        'value' => function ($model) {
                            return number_format($model->amount, 3);
                        }
                    ],
                    [
                        'label' => 'รายได้ทั้งหมด',
                        'contentOptions' => ['style' => 'text-align: right;'],
                        'value' => function ($model) {
                            return number_format($model->win_credit, 3);
                        }
                    ],
                    [
                        'label' => 'จำนวนชนะเกมยี่กี้',
                        'contentOptions' => ['style' => 'text-align: right;'],
                        'value' => function ($model) {
                            return number_format($model->win);
                        }
                    ],
                    [
                        'label' => 'จำนวนแพ้เกมยี่กี้',
                        'contentOptions' => ['style' => 'text-align: right;'],
                        'value' => function ($model) {
                            return number_format($model->nowin);
                        }
                    ],
                ],
            ] );
            ?>
        </div>
    </div>
    <?php Pjax::end() ?>
</div>
