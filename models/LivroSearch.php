<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Livro;
use app\models\Autor;

/**
 * LivroSearch represents the model behind the search form of `app\models\Livro`.
 */
class LivroSearch extends Livro {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['capa', 'titulo', 'autor_id', 'descricao', 'assistido', 'data_criacao', 'data_edicao'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Livro::find();
        $query->leftJoin('autor', 'autor.id_autor=livro.autor_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'data_criacao' => $this->data_criacao,
            'data_edicao' => $this->data_edicao,
        ]);

        $query->andFilterWhere(['like', 'capa', $this->capa])
                ->andFilterWhere(['like', 'titulo', $this->titulo])
                ->andFilterWhere(['like', 'descricao', $this->descricao])
                ->andFilterWhere(['like', 'assistido', $this->assistido])
                ->andFilterWhere(['like', 'autor.sobrenome', $this->autor_id]);

        return $dataProvider;
    }

}
