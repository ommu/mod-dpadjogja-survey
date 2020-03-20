<?php
/**
 * SurveyRespondent
 *
 * This is the ActiveQuery class for [[\dpadjogja\survey\models\SurveyRespondent]].
 * @see \dpadjogja\survey\models\SurveyRespondent
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\models\query;

class SurveyRespondent extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \dpadjogja\survey\models\SurveyRespondent[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \dpadjogja\survey\models\SurveyRespondent|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
