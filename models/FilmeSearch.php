<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Filme;

/**
 * FilmeSearch represents the model behind the search form of `app\models\Filme`.
 */
class FilmeSearch extends Filme
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['capa', 'titulo', 'sinopse', 'link_imdb', 'assistido', 'data_criacao', 'data_edicao'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Filme::find();

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
            ->andFilterWhere(['like', 'sinopse', $this->sinopse])
            ->andFilterWhere(['like', 'link_imdb', $this->link_imdb])
            ->andFilterWhere(['like', 'assistido', $this->assistido]);

        return $dataProvider;
    }
}
