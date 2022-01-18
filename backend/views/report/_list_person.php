<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Credit;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostCreditTransectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $active_tab */

$this->title = 'Post Credit Transections';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-xs-12">
	<div class="panel">
<div style="overflow: auto;">
	<?= $this->render('_tab',['active_tab' => $active_tab])?>
	<div class="tab-content">
			<div id="list-current-yeekee" class="tab-pane fade in active">
</br></br>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [ 						
				'label' => 'ผู้ใช้',
				'value' => function($model) {
                    return $model->reciver_id.': '.$model->reciver->username;
                },
			],
            [
                'label' => 'ฝาก+เติมตรง',
                'value' => function($model) {
                    return $model->deposit;
                },
            ],
            [
                'label' => 'ถอน+แพ้+ถอนตรง',
                'value' => function($model) {
                    return $model->withdraw;
                }
            ],
            [
                'label' => 'ยอดเครดิต',
                'value' => function($model) {
                    $credit = Credit::find()->where(['user_id' => $model->reciver_id])->one();
                    return $credit->balance;
                }
            ],
            [
                'label' => 'ส่วนต่าง',
                'value' => function($model) {
                    return $model->total;
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <div style="color: #ff0000">อัพเดทล่าสุด <?= date('d/m/Y H:i:s')?></div>
</div>
</div>
</div>
</div>
</div>
