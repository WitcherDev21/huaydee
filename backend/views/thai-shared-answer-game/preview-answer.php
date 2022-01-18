<?php
/* @var $from
 * @var $thaiSharedGameChit
 * @var $dataProvider
 * @var $active_tab
 * @var $thaiSharedGame
 */

use common\models\ThaiSharedAnswerGameForm;
use kartik\grid\GridView;
use yii\widgets\Pjax;

//$this->title = 'Update Post Credit Transection: ' . $model->id;
$params['breadcrumbs'][] = ['label' => 'รายการโพย', 'url' => ['preview-answer', 'layout' => 'none']];
$params['breadcrumbs'][] = '#' . $thaiSharedGame->order;
?>
<div class="widget-box">
    <div class="widget-title bg_lg">
        <span class="icon"><i class="icon-thumbs-up"></i></span>
        <h5>preview answer</h5>
    </div>
    <div>
        <?= $this->render('_search-preview', [
            'thaiSharedGame' => $thaiSharedGame,
            'model' => new ThaiSharedAnswerGameForm()
        ]) ?>
        <div class="table-responsive">
            <div class="panel">
                <div style="overflow: auto;">
                    <?php Pjax::begin(); ?>
                    <?php
                    echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'showPageSummary' => true,
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\SerialColumn',
                                    'header' => 'ลำดับ'
                                ],
                                [
                                    'label' => 'userId',
                                    'value' => function ($model) {
                                        return $model->userId;
                                    }
                                ],
                                [
                                    'label' => 'username',
                                    'value' => function ($model) {
                                        return $model->user->username;
                                    }
                                ],
                                [
                                    'label' => 'ยอดแทง',
                                    'pageSummary' => true,
                                    'value' => 'amount',
                                ],
                                [
                                    'label' => 'ตัวเลข',
                                    'value' => 'number',
                                ],
                                [
                                    'label' => 'ประเภท',
                                    'options' => ['style' => 'width: 100px;'],
                                    'value' => function ($model) {
                                        return $model->playType->title;
                                    }
                                ],
                                [
                                    'label' => 'จำนวนชุด',
                                    'pageSummary' => true,
                                    'value' => 'setNumber'
                                ],
                                [
                                    'label' => 'ยอดถูก',
                                    'pageSummary' => true,
                                    'value' => function ($model) {
                                        return $model->getWinCredit();
                                    },
                                ],
                            ]
                        ]
                    );
                    ?>
                    <?php yii\widgets\Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
