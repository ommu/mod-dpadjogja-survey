<?php
/**
 * SurveyAssessment
 *
 * SurveyAssessment represents the model behind the search form about `dpadjogja\survey\models\SurveyAssessment`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 December 2019, 05:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dpadjogja\survey\models\SurveyAssessment as SurveyAssessmentModel;

class SurveyAssessment extends SurveyAssessmentModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'survey_id', 'instrument_id', 'modified_id'], 'integer'],
			[['answer', 'creation_date', 'modified_date', 'surveyRespondentId', 'instrumentQuestion', 'modifiedDisplayname'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = SurveyAssessmentModel::find()->alias('t');
        } else {
            $query = SurveyAssessmentModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			// 'survey.respondent.education survey', 
			// 'instrument.category instrument', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['surveyRespondentId', '-surveyRespondentId'])) || (isset($params['surveyRespondentId']) && $params['surveyRespondentId'] != '')) {
            $query->joinWith(['survey.respondent.education survey']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['instrumentQuestion', '-instrumentQuestion'])) || (isset($params['instrumentQuestion']) && $params['instrumentQuestion'] != '')) {
            $query->joinWith(['instrument instrument']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')) {
            $query->joinWith(['modified modified']);
        }

		$query->groupBy(['id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['surveyRespondentId'] = [
			'asc' => ['survey.id' => SORT_ASC],
			'desc' => ['survey.id' => SORT_DESC],
		];
		$attributes['instrumentQuestion'] = [
			'asc' => ['instrument.question' => SORT_ASC],
			'desc' => ['instrument.question' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.survey_id' => isset($params['survey']) ? $params['survey'] : $this->survey_id,
			't.instrument_id' => isset($params['instrument']) ? $params['instrument'] : $this->instrument_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 't.answer', $this->answer])
			->andFilterWhere(['like', 'survey.id', $this->surveyRespondentId])
			->andFilterWhere(['like', 'instrument.question', $this->instrumentQuestion])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
