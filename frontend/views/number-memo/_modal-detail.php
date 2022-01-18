<?php
?>

<div class="modal inmodal" id="modal-number-sets-<?= $memo->id?>"
	tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content animated fadeIn">
			<div class="modal-header">
				<h3 class="modal-title"><?= $memo->title?></h3>
				<button type="button" class="close" data-dismiss="modal"
					aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<table class="table table-bordered table-striped table-hover">
				<tbody>
                    <tr>
                        <th>เกม</th>
                        <th>
                            <?php
                            $game = \common\models\Games::find()->where(['id' => $memo->gameId])->one();
                            echo $game->title;
                            ?>
                        </th>
                    </tr>
					<?php 
					$items = json_decode($memo->json_value);
					$items = empty($items)?[]:$items;
					
					foreach ($items as $code=>$item){
						$item_obj = json_decode($item);
						foreach($item_obj as $number => $obj){
					?>
					<tr>
						<th><?= isset($playType[$code])?$playType[$code]:''?></th>
						<th><?= $number?></th>
					</tr>
					<?php }
					}?>
				</tbody>
			</table>
		</div>
	</div>
</div>