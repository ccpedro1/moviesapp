<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LivroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Livros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="livro-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Cadastrar Livro', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'titulo',
            [
                'label' => 'Imagem do livro',
                'attribute' => 'capa',
                'format' => 'html',
                'value' => function ($model) {
                    return yii\bootstrap\Html::img($model->capa, ['width' => '150']);
                }
            ],
            // 'autor.displayname:text:Autor',
            [
                'attribute' => 'autor_id',
                'value' => 'autor.sobrenome',
            ],
            'descricao:html',
            'assistido',
            //'data_criacao',
            //'data_edicao',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
