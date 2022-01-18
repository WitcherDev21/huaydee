<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\PlayType;
use common\libs\Constants;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;

$this->registerJsFile(Yii::getAlias('@web/js/poy.js?1552053594'), ['depends' => [\yii\web\JqueryAsset::className()]]);


?>
<div class="widget-box">
    <div class="widget-title">
        <span class="icon">
            <i class="icon-inbox"></i>
        </span>
        <h5>รายการสรุปเลขที่แทง</h5>
        <span class="label label-info">SSL Secure</span>
    </div>
    <div class="widget-content tab-content">
        <div class="widget-box">
            <div class="panel">
                <?= $this->render('_tab', ['active_tab' => $active_tab]) ?>
                <div style="overflow: auto;">
                    <div class="tab-content">
                        <div id="list-current-lottery" class="tab-pane fade in active">
                            <?= $this->render('_search_result_number', [
                                'searchModel' => $searchModel,
                                'playTypeObjs' => $playTypeObjs
                            ]) ?>

                            <?php Pjax::begin(['id' => 'result-number']) ?>
                            <?php
                            //$request = Yii::$app->request;
                            //echo "<pre>",var_dump($request->get('ThaiSharedGameChitDetailSearch')['createdAt']),"</pre>";
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'label' => 'ประเภท-เกม',
                                        'value' => function($model) {
                                           $playType = PlayType::find()->where(['id' => $model['playTypeId']])->one();
                                           return $playType->game->title . '-' . $playType->title;
                                        }
                                    ],
                                    [
                                        'label' => 'เลข',
                                        'value' => 'number',
                                    ],
                                    [
                                        'label' => 'ยอดเงินแทง',
                                        'value' => 'amount',
                                    ],
                                    [   
                                        'label' => 'รายละเอียด',
                                        'format' => 'html',
                                        'value' => function ($model) {    
                                            $request = Yii::$app->request;                                        
                                            //exit();
                                            $btn = 'btn-info';
                                            $text = 'View';
                                            $url =
                                            [
                                                        'thai-shared-game/detail-number',
                                                        'thaiSharedGameChitId' => $model['thaiSharedGameChitId'],
                                                        'number' => $model['number'],
                                                        'from' => 'result-number',
                                                        'playTypeId' => $model['playTypeId'],
                                                        'createdAt' => $request->get('ThaiSharedGameChitDetailSearch')['createdAt'],
                                                        'endDate' => $request->get('ThaiSharedGameChitDetailSearch')['endDate'],
                                                        'title' => $request->get('ThaiSharedGameChitDetailSearch')['title'],
                                            ];
                                            $result = Html::a(Yii::t('app', ' {modelClass}', [
                                                'modelClass' => $text,
                                            ]), $url,
                                                [
                                                    'class' => 'btn btn-xs ' . $btn,
                                                    'style' => 'color:#ffffff;'
                                                ]
                                            );
                                            return $result;
                                        }                  
                                    ],
                                    [   
                                        'label' => 'Action',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $request = Yii::$app->request;      
                                            //echo "<pre>",var_dump($model['thaiSharedGameChitId']),"</pre>";
                                            //exit();
                                            $btn = 'btn-danger';
                                            $text = 'ยกเลิกเลขนี้';
                                            $url =
                                            [
                                                        'thai-shared-chit/cancelnumber',
                                                        //'thaiSharedGameChitId' => $model['thaiSharedGameChitId'],
                                                        'number' => $model['number'],
                                                        'createdAt' => $request->get('ThaiSharedGameChitDetailSearch')['createdAt'],
                                                        'endDate' => $request->get('ThaiSharedGameChitDetailSearch')['endDate'],
                                                        'playtypeid' => $request->get('ThaiSharedGameChitDetailSearch')['playTypeId'],
                                            ];
                                            $result = Html::a(Yii::t('app', ' {modelClass}', [
                                                'modelClass' => $text,
                                            ]), $url,
                                                [
                                                    'class' => 'data-update btn btn-xs ' . $btn,
                                                    'style' => 'color:#ffffff;', 
                                                    'data-target' => '#update-modal',
                                                    'data-id' => $model['number'],                                                    
                                                    'data-start' => $request->get('ThaiSharedGameChitDetailSearch')['createdAt'],
                                                    'data-end' => $request->get('ThaiSharedGameChitDetailSearch')['endDate'],
                                                    'data-playtypeid' => $request->get('ThaiSharedGameChitDetailSearch')['playTypeId'],
                                                    'data-toggle' => 'modal',
                                                ]
                                            );
                                            return $result;
                                        }                  
                                    ]
                                ],

                            ]);
                            ?>
                            <?php Pjax::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php 
Modal::begin ([
    'id' => 'update-modal',
    'header' => '<h4 class = "modal-title"> ข้อความแจ้งเตือน </h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal"> ปิด </a><button type="button" data-key="" data-start="" data-end="" data-playtypeid="" class="btn btn-danger confirmdeletenumber">ยกเลิกเลขนี้</button>',
]); 
echo '<h6>คุณต้องการยกเลิกเลขที่ #<span></span> ใช่หรือไม่ ?</h6>';
$requestUpdateUrl = Url::toRoute ('cancelnumberconfirm');
$updateJs=<<<JS
    var modalConfirm = function (callback,nb,start,end,playtypeid) {
        $('.data-update').on('click', function () {
        //console.log($(this).data('start'));
        $('.modal-body  span').html($(this).data('id'));
        $('.modal-footer button.confirmdeletenumber').attr('data-key',$(this).data('id'));   
        $('.modal-footer button.confirmdeletenumber').attr('data-start',$(this).data('start'));        
        $('.modal-footer button.confirmdeletenumber').attr('data-end',$(this).data('end'));      
        $('.modal-footer button.confirmdeletenumber').attr('data-playtypeid',$(this).data('playtypeid'));      
    });
    $(".modal-footer button.confirmdeletenumber").on("click", function () {
        callback(true,$(this).data('key'),$(this).data('start'),$(this).data('end'),$(this).data('playtypeid'));        
        $("#update-modal").modal('hide');
    });
};
modalConfirm(function (confirm,nb,start,end,playtypeid) {
    console.log(end);
    if (confirm) {
        abort_poy(nb,start,end,playtypeid);
        //console.info(s);
    }
});
function abort_poy(nb,start,end,playtypeid) {
        //show('loading', true);
        $.ajax({
            url: 'cancel-number?number='+nb+'&createdAt='+start+'&endDate='+end+'&playTypeId='+playtypeid,
            cache: false,
            type: 'post',
            data: {
                poy_id: $(this).data('key'),
                createdAt: $(this).data('start'),
                endDate: $(this).data('end'),
                playtypeid: $(this).data('playtypeid'),
                _csrf: yii.getCsrfToken()
            },
            success: function (data) {
                            swal({
                                title: "ข้อความแจ้งเตือน!",
                                text:  data.message+' กำลังโหลดหน้า...',
                                type: data.s,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        window.setTimeout(function(){ } ,3000);
                        location.reload();              
                //window.location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //show('loading', false);
            }
        });

};
JS;

$this->registerJs($updateJs);
Modal::end();
?>