<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comentario_filme".
 *
 * @property int $filme_id
 * @property int $user_id
 * @property string $comentario
 * @property string $data_criacao
 *
 * @property Filme $filme
 * @property User $user
 */
class ComentarioFilme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comentario_filme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filme_id', 'user_id', 'comentario'], 'required'],
            [['filme_id', 'user_id'], 'integer'],
            [['comentario'], 'string'],
            [['data_criacao'], 'safe'],
            [['filme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Filme::className(), 'targetAttribute' => ['filme_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filme_id' => 'Filme ID',
            'user_id' => 'User ID',
            'comentario' => 'Comentario',
            'data_criacao' => 'Data Criacao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilme()
    {
        return $this->hasOne(Filme::className(), ['id' => 'filme_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
