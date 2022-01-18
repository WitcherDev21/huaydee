<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'image')->widget(FileInput::classname(), [
    'options' => ['accept' => 'image/*'],
    'pluginOptions' => [
        'showUpload' => false,
        'initialPreview' => $model->image ? Html::img($model->photoViewer, ['class'=>'img-thumbnail']) : false,
        'fileActionSettings' => [
            'showRemove' => false,
        ],
    ],
]); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'rule')->widget(TinyMce::className(), [
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
]); ?>

<?= $form->field($model, 'uri')->dropDownList($model->getUrlGame(), ['prompt' => 'กรุณาเลือก url']) ?>

<?= $form->field($model, 'period_des')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'status')->radioList(['1' => Yii::t('app','เปิด'), '0' => Yii::t('app','ปิด')]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end(); ?>