<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\ThaiSharedGame */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$form = ActiveForm::begin();
if ($model->status !== 9) {
    echo $form->field($model, 'gameId')->dropDownList($games, ['prompt' => 'กรุณาเลือกเกมหวยหุ้น']);
    echo $form->field($model, 'typeSharedGameId')->dropDownList($typeGameShareds, ['prompt' => 'กรุณาเลือกประเภทหวยหุ้น']);
    echo $form->field($model, 'title')->dropDownList($model->getTitles(), ['prompt' => 'กรุณาเลือกชื่อเกม']);
    echo $form->field($model, 'startDate')->widget(DateTimePicker::classname(), [
        'options' => ['autocomplete' => 'off', 'placeholder' => 'Select Start Datetime'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]);
    echo $form->field($model, 'endDate')->widget(DateTimePicker::classname(), [
        'options' => ['autocomplete' => 'off', 'placeholder' => 'Select End Datetime'],
        'pluginOptions' => [
            'autoclose' => true
        ],
    ]);
    echo $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 12],
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern imagetools codesample toc noneditable",
            ],
            'toolbar' => "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ]);
    echo $form->field($model, 'status')->radioList([
        '1' => Yii::t('app', 'Open'),
        '0' => Yii::t('app', 'Close'),
        '2' => Yii::t('app', 'Close Special'),
        '3' => 'ยังไม่เปิดรับแทง',
    ]);
}
echo $form->field($model, 'disabled')->radioList([
    '1' => Yii::t('app', 'Open'),
    '0' => Yii::t('app', 'Close'),
]);
echo $form->field($model, 'limitAuto')->radioList([
    '1' => Yii::t('app', 'Auto'),
    '0' => Yii::t('app', 'Manual'),
]);
?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end(); ?>