<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "autor".
 *
 * @property int $id_autor
 * @property string $nome
 * @property string $sobrenome
 * @property string $biografia
 *
 * @property Livro[] $livros
 */
class Autor extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'autor';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['nome', 'sobrenome'], 'required'],
            [['biografia'], 'string'],
            [['nome'], 'string', 'max' => 50],
            [['sobrenome'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id_autor' => 'Id Autor',
            'nome' => 'Nome',
            'sobrenome' => 'Sobrenome',
            'biografia' => 'Biografia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLivros() {
        return $this->hasMany(Livro::className(), ['autor_id' => 'id_autor']);
    }

    public function getDisplayName() {
        return $this->nome . " " . $this->sobrenome;
    }

}
