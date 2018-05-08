<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FilmeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Filmes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filme-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a('Cadastrar Filme', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'titulo',
            [
                'label' => 'Imagem do Filme',
                'attribute' => 'capa',
                'format' => 'html',
                'value' => function ($model) {
                    return yii\bootstrap\Html::img($model->capa, ['width' => '150']);
                }
            ],
            'sinopse:html',
            [
                'label' => 'Link IMDB',
                'attribute' => 'link_imdb',
                'format' => 'html',
                'value' => function ($model) {
                    return yii\bootstrap\Html::a('Link IMDB', $model->link_imdb);
                }
            ],
            //'assistido',
            //'data_criacao',
            //'data_edicao',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>
</div>
