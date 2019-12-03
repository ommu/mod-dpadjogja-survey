<?php
/**
 * SurveyAssessment
 *
 * This is the ActiveQuery class for [[\dpadjogja\survey\models\SurveyAssessment]].
 * @see \dpadjogja\survey\models\SurveyAssessment
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 December 2019, 01:53 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\models\query;

class SurveyAssessment extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \dpadjogja\survey\models\SurveyAssessment[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \dpadjogja\survey\models\SurveyAssessment|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
