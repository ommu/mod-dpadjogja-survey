<?php
/**
 * SurveyRespondent
 *
 * SurveyRespondent represents the model behind the search form about `dpadjogja\survey\models\SurveyRespondent`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dpadjogja\survey\models\SurveyRespondent as SurveyRespondentModel;

class SurveyRespondent extends SurveyRespondentModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'user_id', 'education_id', 'work_id', 'creation_id', 'modified_id'], 'integer'],
			[['gender', 'creation_date', 'modified_date', 'educationLevel', 'workName', 'userDisplayname', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = SurveyRespondentModel::find()->alias('t');
        } else {
            $query = SurveyRespondentModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			// 'education education', 
			// 'work work', 
			// 'user user', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['education_id', '-education_id'])) || (isset($params['educationLevel']) && $params['educationLevel'] != '')) {
            $query->joinWith(['education education']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['work_id', '-work_id'])) || (isset($params['workName']) && $params['workName'] != '')) {
            $query->joinWith(['work work']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || (isset($params['userDisplayname']) && $params['userDisplayname'] != '')) {
            $query->joinWith(['user user']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')) {
            $query->joinWith(['creation creation']);
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
		$attributes['education_id'] = [
			'asc' => ['education.id' => SORT_ASC],
			'desc' => ['education.id' => SORT_DESC],
		];
		$attributes['work_id'] = [
			'asc' => ['work.work_name' => SORT_ASC],
			'desc' => ['work.work_name' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
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
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.education_id' => isset($params['education']) ? $params['education'] : $this->education_id,
			't.work_id' => isset($params['work']) ? $params['work'] : $this->work_id,
			't.gender' => $this->gender,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
		]);

		$query->andFilterWhere(['like', 'education.id', $this->educationLevel])
			->andFilterWhere(['like', 'work.work_name', $this->workName])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
