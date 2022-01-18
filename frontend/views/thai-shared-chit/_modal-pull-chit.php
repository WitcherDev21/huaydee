<?php
use yii\helpers\Url;

$js = <<<EOT
	$('.btn-pull-chit').on('click',function(e){		
		obj = $(this).data('chit-detail');
		textObj = JSON.stringify(obj);
		$('input[name="play_data"]').val(textObj);
		
		$('#exampleModal').modal('hide');
		displayButton('');
	});
		
	
		 
EOT;
$this->registerJs ( $js );
$css = <<<EOT

EOT;
$this->registerCss ( $css );

$today = date ( 'Y-m-d H:i:s' );
?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">เลขชุด/ดึงโพย</h3>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="bd-example bd-example-tabs">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#num-set">เลือกเลขชุด</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pull-chit">ดึงโพย</a></li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade  active in" id="num-set">
                            <br>
                            <div class="col-md-12 text-center">
                                <a class="btn btn-info" href="<?= Url::toRoute(['number-memo/index'])?>" style="color:#ffffff;"><i class="fa fa-cogs"></i> จัดการเลขชุด</a>
                                <a class="btn btn-info" href="<?= Url::toRoute(['number-memo/edit'])?>" style="color:#ffffff;"><i class="fa fa-plus"></i> สร้างเลขชุด</a>
                            </div>
                            <div class="col-md-12 text-center">
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">ชื่อชุด</th>
                                            <th scope="col">เกม</th>
                                            <th scope="col">วันที่สร้างชุด</th>
                                            <th scope="col">เลือก</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($numberMemoList as $memo){ ?>
                                            <tr class="">
                                                <th scope="row"><?= $memo->title?></th>
                                                <td scope="row">
                                                    <?php
                                                    $game = \common\models\Games::find()->where(['id' => $memo->gameId ])->one();
                                                    echo $game->title
                                                    ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i',strtotime($memo->create_at))?></td>
                                                <td><a href="javascript:;" class="btn btn-info btn-pull-chit" data-chit-detail='<?= $memo->json_value?>'>เลือก</a></td>
                                            </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pull-chit">
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">เลขที่</th>
                                        <th scope="col">โพย</th>
                                        <th scope="col">วันที่</th>
                                        <th scope="col">เลือก</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($resultChitList as $chitMap){
                                        $chit = $chitMap['model'];
                                        $chitJson = $chitMap['map_val'];
                                        ?>
                                        <tr class="">
                                            <th scope="row">#<?= $chit->id ?></th>
                                            <td><?= $chit->thaiSharedGame->title ?> / <?= date('d/m/Y',strtotime($chit->thaiSharedGame->createdAt))?></td>
                                            <td><?= date('d/m/Y H:i',strtotime($chit->createdAt))?></td>
                                            <td><a href="javascript:;" class="btn btn-info btn-pull-chit" data-chit-detail='<?= $chitJson?>'>เลือก</a></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
