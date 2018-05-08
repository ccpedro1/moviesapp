<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comentario_livro".
 *
 * @property int $livro_id
 * @property int $user_id
 * @property string $comentario
 * @property string $data_criacao
 *
 * @property Livro $livro
 * @property User $user
 */
class ComentarioLivro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comentario_livro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['livro_id', 'user_id', 'comentario'], 'required'],
            [['livro_id', 'user_id'], 'integer'],
            [['comentario'], 'string'],
            [['data_criacao'], 'safe'],
            [['livro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Livro::className(), 'targetAttribute' => ['livro_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'livro_id' => 'Livro ID',
            'user_id' => 'User ID',
            'comentario' => 'Comentario',
            'data_criacao' => 'Data Criacao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLivro()
    {
        return $this->hasOne(Livro::className(), ['id' => 'livro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
