<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Filme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="filme-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'capa')->fileInput() ?>

    <?=
    $form->field($model, 'sinopse')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ])
    ?>

    <?= $form->field($model, 'link_imdb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'assistido')->textInput() ?>

    <?= $form->field($model, 'data_edicao')->widget(DatePicker::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
