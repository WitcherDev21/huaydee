<?php
/* @var $dataProvider \common\models\NumberMemo */

use yii\grid\GridView;
use yii\helpers\Url;

$this->registerJsVar('deleteUrl', Url::to(['delete']));
$this->registerJsVar('indexUrl', Url::to(['index']));
$this->registerJsFile(Yii::getAlias('@web/version6/js/index/numbersets.js?1561983814'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="bar-back">
    <a href="<?= Url::to(['site/home']) ?>"><i class="fas fa-chevron-left"></i> หน้าหลัก</a>
</div>
<div class="p-2 w-100 bg-light_bkk main-content align-self-stretch" style="min-height: calc((100vh - 140px) - 50px);">
    <div class="bgwhitealpha text-secondary shadow-sm rounded p-2 px-2 xtarget col-lotto d-flex flex-row justify-content-between mb-1 pb-0">
        <div class="lotto-title">
            <h4><i class="fas fa-list-ol"></i> จัดการเลขชุด</h4>
        </div>
        <a href="<?= Url::to(['number-memo/create']) ?>" class="btn btn-success_bkk btn-sm"><i
                    class="fas fa-plus"></i> สร้างเลขชุด</a>
    </div>
    <div class="bgwhitealpha text-secondary2 shadow-sm rounded py-2 xtarget col-lotto">
        <div id="numbersets_wrapper" class="dataTables_wrapper form-inline no-footer">
            <div class="row">
                <div class="col-6 col-sm-6 col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            'id',
                            'title',
                            'create_at',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{all}',
                                'buttons' => [
                                    'all' => function ($url, $model, $key) {
                                        return '
                                    <div class="btn-group dropleft">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item" href="'.Url::to(['number-memo/view', 'id' => $model->id]).'">
                                            รายละเอียด
                                        </a>
                                        <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="delete_my_setnumber('.$model->id.')">
                                                <i class="fas fa-trash-alt"></i> ลบ
                                            </a>
                                        </div>
                                    </div>';
                                    }
                                ],
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>