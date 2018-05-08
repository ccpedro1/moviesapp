<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filme".
 *
 * @property int $id
 * @property string $capa
 * @property string $titulo
 * @property string $sinopse
 * @property string $link_imdb
 * @property int $assistido
 * @property string $data_criacao
 * @property string $data_edicao
 *
 * @property ComentarioFilme[] $comentarioFilmes
 * @property NotaFilme[] $notaFilmes
 */
class Filme extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'filme';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['titulo'], 'required'],
            [['sinopse'], 'string'],
            [['link_imdb'], 'url', 'defaultScheme' => 'http'],
            [['capa'], 'file', 'extensions' => 'jpg, jpeg, png, gif'],
            [['titulo'], 'string', 'max' => 100],
            [['assistido'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'capa' => 'Imagem do Filme',
            'titulo' => 'Titulo',
            'sinopse' => 'Sinopse',
            'link_imdb' => 'Link IMDB',
            'assistido' => 'Assistido',
            'data_edicao' => 'Data Edicao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarioFilmes() {
        return $this->hasMany(ComentarioFilme::className(), ['filme_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaFilmes() {
        return $this->hasMany(NotaFilme::className(), ['filme_id' => 'id']);
    }

}
