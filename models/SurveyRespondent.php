<?php
/**
 * SurveyRespondent
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 * This is the model class for table "dpadjogja_survey_respondent".
 *
 * The followings are the available columns in table "dpadjogja_survey_respondent":
 * @property integer $id
 * @property integer $user_id
 * @property integer $education_id
 * @property integer $work_id
 * @property string $gender
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property SurveyEducation $education
 * @property SurveyWork $work
 * @property Users $user
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace dpadjogja\survey\models;

use Yii;
use app\models\Users;

class SurveyRespondent extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname'];

	public $educationLevel;
	public $workName;
	public $userDisplayname;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'dpadjogja_survey_respondent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['education_id', 'work_id', 'gender'], 'required'],
			[['user_id', 'education_id', 'work_id', 'creation_id', 'modified_id'], 'integer'],
			[['gender'], 'string'],
			[['user_id'], 'safe'],
			[['education_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyEducation::className(), 'targetAttribute' => ['education_id' => 'id']],
			[['work_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyWork::className(), 'targetAttribute' => ['work_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'user_id' => Yii::t('app', 'User'),
			'education_id' => Yii::t('app', 'Education'),
			'work_id' => Yii::t('app', 'Work'),
			'gender' => Yii::t('app', 'Gender'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'educationLevel' => Yii::t('app', 'Education'),
			'workName' => Yii::t('app', 'Work'),
			'userDisplayname' => Yii::t('app', 'User'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getEducation()
	{
		return $this->hasOne(SurveyEducation::className(), ['id' => 'education_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWork()
	{
		return $this->hasOne(SurveyWork::className(), ['id' => 'work_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \dpadjogja\survey\models\query\SurveyRespondent the active query used by this AR class.
	 */
	public static function find()
	{
		return new \dpadjogja\survey\models\query\SurveyRespondent(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->user) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
		$this->templateColumns['gender'] = [
			'attribute' => 'gender',
			'value' => function($model, $key, $index, $column) {
				return self::getGender($model->gender);
			},
			'filter' => self::getGender(),
		];
		$this->templateColumns['education_id'] = [
			'attribute' => 'education_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->education) ? $model->education->education_level : '-';
				// return $model->educationLevel;
			},
			'filter' => SurveyEducation::getEducation(),
			'visible' => !Yii::$app->request->get('education') ? true : false,
		];
		$this->templateColumns['work_id'] = [
			'attribute' => 'work_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->work) ? $model->work->work_name : '-';
				// return $model->workName;
			},
			'filter' => SurveyWork::getWork(),
			'visible' => !Yii::$app->request->get('work') ? true : false,
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creationDisplayname'] = [
			'attribute' => 'creationDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
				// return $model->creationDisplayname;
			},
			'visible' => !Yii::$app->request->get('creation') ? true : false,
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getGender
	 */
	public static function getGender($value=null)
	{
		$items = array(
			'male' => Yii::t('app', 'Male'),
			'female' => Yii::t('app', 'Female'),
		);

		if($value !== null)
			return $items[$value];
		else
			return $items;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->educationLevel = isset($this->education) ? $this->education->id : '-';
		// $this->workName = isset($this->work) ? $this->work->work_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->user_id == null)
				$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}