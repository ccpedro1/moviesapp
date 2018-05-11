<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use app\models\Autor;

/* @var $this yii\web\View */
/* @var $model app\models\Livro */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="livro-form">

    <?php
    $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                 'enableAjaxValidation' => true,
                 'validationUrl' => 'validate',
    ]);
    ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'capa')->fileInput() ?>

    <?= $form->field($model, 'autor_id')->dropDownList(ArrayHelper::map(Autor::find()->select(['nome', 'sobrenome', 'id_autor'])->all(), 'id_autor', 'displayName'), ['class' => 'form-control inline-block']); ?>

    <?=
    $form->field($model, 'descricao')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ])
    ?>

    <?= $form->field($model, 'assistido')->dropDownList([ 'n'=> 'Não', 's'=>'Sim'],['class' => 'btn btn-default']) ?>
    
    <?= $form->field($model, 'data_edicao')->widget(DatePicker::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
