<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\libs\Constants;
use dosamigos\tinymce\TinyMce;
?>
<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-bullhorn"></i></span>
		<h5>ประกาศหน้าเว็บ</h5>
	</div>
    
        <?php if(count(Yii::$app->session->getAllFlashes()) > 0){ ?>
                <div style="padding: 20px 20px 0 20px;">
                <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message){
                    echo yii\bootstrap\Alert::widget([
                        'options' => [
                            'class' => 'alert-'.$message['type'],
                        ],
                        'body' => $message['message'],
                    ]);
                } ?>
                </div>
        <?php } ?>
    
	<div class="widget-content">
            <?php
            $form = ActiveForm::begin([
                'id' => 'form-news',
                'action' => Yii::$app->urlManager->createUrl(['page/newssave']),
                'method' => 'POST'
            ]); 
            ?>
            <?php
            foreach ($News as $key => $val){
                echo Html::checkbox('News['.$val->id.'][status]',$val->status,['value'=>Constants::status_active]); 
//                echo $form->field($model, '['.$val->id.']message')->textarea(['class'=>'form-control', 'rows'=>'5', 'value'=>$val->message])->label($val->title);
                //echo $form->field($model, '['.$val->id.']status')->checkbox(['value'=>$val->status,'checked'=>'checked'])->label('status');
                echo $form->field($model, '['.$val->id.']message')->widget(TinyMce::className(), [
                    'options' => ['rows' => 12, 'value'=>$val->message],
//                    'language' => 'es',
                    'clientOptions' => [
                        'plugins' => [
                            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                            "searchreplace wordcount visualblocks visualchars code fullscreen",
                            "insertdatetime media nonbreaking save table contextmenu directionality",
                            "emoticons template paste textcolor colorpicker textpattern imagetools codesample toc noneditable",
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                    ]
                ])->label($val->title);
            }
            ?>
              
            <div class="text-right" style="padding-top: 20px;">       
                <input type="submit" name="btnSave" id="btnSave" value="save" class="btn btn-primary" style="width:180px; margin-right: 5px;"  />
            </div>  
            <?php ActiveForm::end(); ?>
	</div>
</div>