<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "livro".
 *
 * @property int $id
 * @property string $capa
 * @property string $titulo
 * @property string $autor_id
 * @property string $descricao
 * @property int $assistido
 * @property string $data_criacao
 * @property string $data_edicao
 *
 * @property ComentarioLivro[] $comentarioLivros
 * @property NotaLivro[] $notaLivros
 */
class Livro extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'livro';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['titulo'], 'required'],
            [['titulo'], 'unique'],
            [['descricao'], 'string'],
            [['autor_id'], 'integer'],
            [['data_edicao'], 'date'],
            [['capa'], 'file', 'extensions' => 'jpg, jpeg, gif, png'],
            [['titulo'], 'string', 'max' => 100],
            [['assistido'], 'string', 'max' => 1],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Autor::className(), 'targetAttribute' => ['autor_id' => 'id_autor']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'capa' => 'Capa do livro',
            'titulo' => 'Titulo',
            'autor_id' => 'Autor',
            'descricao' => 'Descricao',
            'assistido' => 'Assistido',
            'data_edicao' => 'Data Edicao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComentarioLivros() {
        return $this->hasMany(ComentarioLivro::className(), ['livro_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaLivros() {
        return $this->hasMany(NotaLivro::className(), ['livro_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutor() {
        return $this->hasOne(Autor::className(), ['id_autor' => 'autor_id']);
    }

}
