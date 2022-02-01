<?php
/* @var $from
 * @var $thaiSharedGameChit
 * @var $dataProvider
 * @var $active_tab
 */
use common\models\ThaiSharedAnswerGame;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\libs\Constants;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

//echo "<pre>",var_dump($dataProvider->query->where[2]['number']),"</pre>";exit();
$numberja = $dataProvider->query->where[2]['number'];
$this->registerJsFile(Yii::getAlias('@web/js/poy.js?1552053594'), ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsVar('poy', $numberja );
//$this->registerJsVar('poyCancelUrl', Url::to(['thai-shared-chit/cancel', 'id' => $thaiSharedGameChit->id, 'uid' => $thaiSharedGameChit->userId]));
$this->registerJsVar('poyCancelUrl', Url::to(['thai-shared-chit/cancelnumber', 'number' => $numberja ]));
$js = <<<EOT
	$('.popupModal').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-content').load($(this).attr('href'));
   });
EOT;
$this->registerJs ( $js );
$css = <<<EOT
	
EOT;
$this->registerCss ( $css );

//$this->title = 'Update Post Credit Transection: ' . $model->id;
$params['breadcrumbs'][] = ['label' => 'รายการเลขที่', 'url' => [$from, 'layout' => 'none']];
$params['breadcrumbs'][] = '#'.$numberja ;
?>
<div class="col-xs-12">
    <div class="panel">
        <?= $this->render('_tab', ['active_tab' => $active_tab])?>
        <?php if(false){ ?>
            <a href="#" class="btn btn-outline-danger btn-sm abort_poynumber">ยกเลิกเลขนี้</a>
        <?php } ?>
        
        <div style="overflow: auto;">
            <?php Pjax::begin(); ?>
            <?= Breadcrumbs::widget([
                'homeLink'=>false,
                'links' => isset($params['breadcrumbs']) ? $params['breadcrumbs'] : [],
            ]) ;?>
            <?php
            echo GridView::widget ( [
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'label' => 'ID',
                            'value' => function ($model) {
                                return $model->id;
                            }
                        ],
                        [
                            'header' => '<div class="text-center">username</div>',
                            'format' => 'html',
                            'value' => function ($model) {            
                                if($model){
                                    if($model->user){
                                        $username = $model->user->username."<small style='color: #cacaca;'>(".$model->user->id.")</small>";
                                    }else{
                                        $username = 'ไม่มีข้อมูล';
                                    }
                                }   else{
                                    $username = 'none';
                                }                 
                                return '<div class="text-left">' . $username . '</div>';
                            }
                        ],
                        [
                            'label' => 'ประเภทหวย',
                            'value' => function ($model) {
                                return $model->playType->title;
                            }
                        ],
                        [
                            'header' => '<div class="text-center">เลขที่แทง</div>',
                            'format' => 'html',
                            'value' => function ($model) {
                                return '<div class="text-right">' . $model->number. '</div>';
                            }
                        ],
                        [
                            'label' => 'ราคาที่แทง',
                            'value' => function ($model) {
                                return number_format($model->amount);
                            }
                        ],
                        [
                            'label' => 'ยอดจ่ายจริง',
                            'value' => function ($model) {
                                return number_format($model->discount);
                            }
                        ],
                        [
                            'label' => 'ราคาจ่าย',
                            'value' => function ($model) {
                                if ($model->jackPotPerUnit) {
                                    return $model->jackPotPerUnit;
                                }
                                return $model->playType->jackpot_per_unit;
                            }
                        ],
                        [
                            'label' => 'เลขที่ออก',
                            'format' => 'html',
                            'value' => function ($model) {
                                $thaiSharedAnswerGames = ThaiSharedAnswerGame::find()->where([
                                    'thaiSharedGameId' => $model->thaiSharedGameChit->thaiSharedGameId,
                                    'playTypeId' => $model->playTypeId
                                ])->all();
                                $textAnswer = '';
                                if ($model->thaiSharedGameChit->status !== Constants::status_finish_show_result) {
                                    return 'รอผล';
                                }
                                foreach ($thaiSharedAnswerGames as $thaiSharedAnswerGame) {
                                    $textAnswer .= $thaiSharedAnswerGame->number.'<br>';
                                }
                                return $textAnswer;
                            }
                        ],
                        [
                            'label' => 'ผลได้เสีย',
                            'format'=>'html',
                            'footer' => number_format($thaiSharedGameChit->getTotalWinCredit(),2),
                            'value' => function ($model) {
                                $result = '';
                                if($model->win_credit > 0){
                                    $result = '<div style="color:'.Constants::color_credit_in.'"> +'.number_format($model->win_credit, 2).'</div>';
                                }else{
                                    $result = '<div style="color:'.'#000000'.'"> '.'0'.'</div>';
                                }
                                return $result;
                            }
                        ],
                        [
                            'label' => 'สถานะ',
                            'format' => 'html',
                            'value' => function ($model) {
                                $result = '';
                                if($model->thaiSharedGameChit->status == Constants::status_finish_show_result){
                                    if($model->flag_result == 1){
                                        $result = '<a href="javascript:;" class="btn btn-xs btn-success" style="color: #ffffff;">'.'ชนะ'.'</a>';
                                    }else{
                                        $result = '<a href="javascript:;" class="btn btn-xs btn-danger" style="color: #ffffff;">'.'แพ้'.'</a>';
                                    }
                                }
                                return $result;
                            }
                        ],
                        [   
                            'label' => 'ยกเลิก',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $request = Yii::$app->request;      
                                //echo "<pre>",var_dump($model['thaiSharedGameChitId']),"</pre>";
                                //exit();
                                $btn = 'btn-danger';
                                $text = 'ยกเลิกเลขนี้รายบุคล';
                                $url =
                                [
                                            'thai-shared-chit/cancelnumberbyuser',
                                            //'thaiSharedGameChitId' => $model['thaiSharedGameChitId'],
                                            'number' => $model['number'],
                                            'uid' => $model->user->id,
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
                                        'data-uid' => $model->user->id,
                                    ]
                                );
                                return $result;
                            }                  
                        ]
                    ],
                    'showFooter' => true,

                ]
            );
            ?>
            <?php yii\widgets\Pjax::end(); ?>
        </div>
        
    </div>
</div>

<?php echo $this->render('modal_delete_number', [
        'number' => $numberja 
]);



Modal::begin ([
    'id' => 'update-modal',
    'header' => '<h4 class = "modal-title"> ข้อความแจ้งเตือน </h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal"> ปิด </a><button type="button" data-uid=""  data-key="" data-start="" data-end="" data-playtypeid="" class="btn btn-danger cancelnumberbyuserconfirm">ยกเลิกเลขนี้</button>',
]); 
echo '<h6>คุณต้องการยกเลิกเลขที่ #<span></span> ของคุณ <user></user> ใช่หรือไม่ ?</h6>';
$requestUpdateUrl = Url::toRoute ('cancelnumberconfirm');
$updateJs=<<<JS
    var modalConfirmNumberByUser = function (callback,nb,start,end,playtypeid,uid) {
        $('.data-update').on('click', function () {
        $('.modal-body  span').html($(this).data('id'));
        $('.modal-footer button.cancelnumberbyuserconfirm').attr('data-key',$(this).data('id'));   
        $('.modal-footer button.cancelnumberbyuserconfirm').attr('data-start',$(this).data('start'));        
        $('.modal-footer button.cancelnumberbyuserconfirm').attr('data-end',$(this).data('end'));      
        $('.modal-footer button.cancelnumberbyuserconfirm').attr('data-playtypeid',$(this).data('playtypeid'));      
        $('.modal-footer button.cancelnumberbyuserconfirm').attr('data-uid',$(this).data('uid'));  
    });
    $(".modal-footer button.cancelnumberbyuserconfirm").on("click", function () {
        callback(true,$(this).data('key'),$(this).data('start'),$(this).data('end'),$(this).data('playtypeid'),$(this).data('uid'));        
        $("#update-modal").modal('hide');
    });
};
modalConfirmNumberByUser(function (confirm,nb,start,end,playtypeid,uid) {
    console.log(end);
    if (confirm) {
        abort_poyNumberByUser(nb,start,end,playtypeid,uid);
        //console.info(s);
    }
});
function abort_poyNumberByUser(nb,start,end,playtypeid,uid) {
        //show('loading', true);
        $.ajax({
            url: 'cancel-number-by-user?number='+nb+'&createdAt='+start+'&endDate='+end+'&playTypeId='+playtypeid+'&uid='+uid,
            cache: false,
            type: 'post',
            data: {
                poy_id: $(this).data('key'),
                createdAt: $(this).data('start'),
                endDate: $(this).data('end'),
                playtypeid: $(this).data('playtypeid'),
                uid: $(this).data('uid'),
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
                        //location.reload();              
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