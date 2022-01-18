<?php

use common\models\BlackredChit;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\YeekeeChitSearch;
?>

<div class="widget-box">
	<div class="widget-title">
		<span class="icon"> <i class="icon-user"></i>
		</span>
		<h5>ระบบแนะนำ</h5>
	</div>
    <?php Pjax::begin(['id' => 'countries-tab1', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]) ?>

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
                                          <?= Html::activeInput('text',$searchModel, 'username',['class'=>'form-control']) ?>
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
                            'attribute' => 'username'
                        ],
                        [ 
                            'label' => 'เอเยนต์',
                            'value' => function ($model) {
                                return (!empty($model['agent_name']) ? $model['agent_name'] : '');
                            } 
                        ],
                        [ 
                            'label' => 'จำนวนคลิกทั้งหมด',
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'value' => function ($model) {
                                return (!empty($model['click']) ? number_format($model['click']) : '');
                            } 
                        ],
                        [ 
                            'label' => 'สมาชิกที่แนะนำได้',
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'value' => function ($model) {
                                return (!empty($model['all_agent']) ? number_format($model['all_agent']) : '');
                            } 
                        ],
                        [ 
                            'label' => 'จำนวนแทงทั้งหมด',
                            'contentOptions' => ['style' => 'text-align: right;'],
                            'value' => function ($model) {
                                return number_format(BlackredChit::getAgentBlackredChit($model['id']),2);
                            } 
                        ],
                        [ 
                            'label' => 'รายได้ทั้งหมด',
                            'contentOptions' => ['style' => 'text-align: right;'],
                            'value' => function ($model) {
                                return number_format(BlackredChit::getAgentBlackredChitAll($model['id']),3);
                            } 
                        ],
                        [ 
                            'label' => 'รายได้ปัจจุบัน',
                            'contentOptions' => ['style' => 'text-align: right;'],
                            'value' => function ($model) {
                                return (!empty($model['comm']) ? number_format($model['comm'],3) : '');
                            } 
                        ],
                        [ 
                            'label' => 'หมายเหตุ',
                            'value' => function ($model, $key, $index, $column) {
                                return '';
                            } 
                        ],
                    ],
		] );
                ?>
            </div>
	</div>
    <?php Pjax::end() ?>
</div>
