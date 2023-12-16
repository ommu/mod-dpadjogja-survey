<?php
/**
 * SurveyAssessment
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 December 2019, 01:53 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 * This is the model class for table "dpadjogja_survey_assessment".
 *
 * The followings are the available columns in table "dpadjogja_survey_assessment":
 * @property integer $id
 * @property integer $survey_id
 * @property integer $instrument_id
 * @property string $answer
 * @property string $creation_date
 * @property string $modified_date
 * @property integer $modified_id
 *
 * The followings are the available model relations:
 * @property Surveys $survey
 * @property SurveyInstrument $instrument
 * @property Users $modified
 *
 */

namespace dpadjogja\survey\models;

use Yii;
use app\models\Users;

class SurveyAssessment extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname'];

	public $surveyRespondentId;
	public $instrumentQuestion;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'dpadjogja_survey_assessment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['survey_id', 'instrument_id', 'answer'], 'required'],
			[['survey_id', 'instrument_id', 'modified_id'], 'integer'],
			[['creation_date', 'modified_date'], 'safe'],
			[['answer'], 'string', 'max' => 128],
			[['survey_id'], 'exist', 'skipOnError' => true, 'targetClass' => Surveys::className(), 'targetAttribute' => ['survey_id' => 'id']],
			[['instrument_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyInstrument::className(), 'targetAttribute' => ['instrument_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'survey_id' => Yii::t('app', 'Survey'),
			'instrument_id' => Yii::t('app', 'Instrument'),
			'answer' => Yii::t('app', 'Answer'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'surveyRespondentId' => Yii::t('app', 'Survey'),
			'instrumentQuestion' => Yii::t('app', 'Instrument'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSurvey()
	{
		return $this->hasOne(Surveys::className(), ['id' => 'survey_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstrument()
	{
		return $this->hasOne(SurveyInstrument::className(), ['id' => 'instrument_id']);
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
	 * @return \dpadjogja\survey\models\query\SurveyAssessment the active query used by this AR class.
	 */
	public static function find()
	{
		return new \dpadjogja\survey\models\query\SurveyAssessment(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
        parent::init();

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['surveyRespondentId'] = [
			'attribute' => 'surveyRespondentId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->survey) ? $model->survey->respondent->education->id : '-';
				// return $model->surveyRespondentId;
			},
			'visible' => !Yii::$app->request->get('survey') ? true : false,
		];
		$this->templateColumns['instrumentQuestion'] = [
			'attribute' => 'instrumentQuestion',
			'value' => function($model, $key, $index, $column) {
				return isset($model->instrument) ? $model->instrument->question : '-';
				// return $model->instrumentQuestion;
			},
			'format' => 'html',
			'visible' => !Yii::$app->request->get('instrument') ? true : false,
		];
		$this->templateColumns['answer'] = [
			'attribute' => 'answer',
			'value' => function($model, $key, $index, $column) {
				return $model->answer;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
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
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

        } else {
            $model = self::findOne($id);
            return $model;
        }
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->surveyRespondentId = isset($this->survey) ? $this->survey->respondent->education->id : '-';
		// $this->instrumentQuestion = isset($this->instrument) ? $this->instrument->category->category_name : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if (!$this->isNewRecord) {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
        }
        return true;
	}
}
