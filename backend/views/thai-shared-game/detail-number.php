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
                            'header' => '<div class="text-center">username</div>',
                            'format' => 'html',
                            'value' => function ($model) {            
                                if($model){
                                    if($model->user){
                                        $username = $model->user->username;
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
                            'format' => 'html',
                            'value' => function ($model) {
                                return '<div class="btn btn-xs btn-danger  text-center">ยกเลิกรายบุคล</div>';
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
]) ?>
