<?php
/**
 * Surveys
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 17:11 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 * This is the model class for table "dpadjogja_surveys".
 *
 * The followings are the available columns in table "dpadjogja_surveys":
 * @property integer $id
 * @property integer $publish
 * @property integer $respondent_id
 * @property integer $service_id
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SurveyAssessment[] $assessments
 * @property SurveyService $service
 * @property SurveyRespondent $respondent
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace dpadjogja\survey\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class Surveys extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $gender;
	public $educationId;
	public $workId;
	public $serviceName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	public $assessments;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'dpadjogja_surveys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['service_id', 'assessments'], 'required'],
			[['publish', 'respondent_id', 'service_id', 'creation_id', 'modified_id'], 'integer'],
			[['respondent_id'], 'safe'],
			[['service_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyService::className(), 'targetAttribute' => ['service_id' => 'id']],
			[['respondent_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyRespondent::className(), 'targetAttribute' => ['respondent_id' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'respondent_id' => Yii::t('app', 'Respondent'),
			'service_id' => Yii::t('app', 'Service'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'gender' => Yii::t('app', 'Gender'),
			'educationId' => Yii::t('app', 'Education'),
			'workId' => Yii::t('app', 'Work'),
			'serviceName' => Yii::t('app', 'Service'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAssessments()
	{
		return $this->hasMany(SurveyAssessment::className(), ['survey_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getService()
	{
		return $this->hasOne(SurveyService::className(), ['id' => 'service_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRespondent()
	{
		return $this->hasOne(SurveyRespondent::className(), ['id' => 'respondent_id']);
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
	 * @return \dpadjogja\survey\models\query\Surveys the active query used by this AR class.
	 */
	public static function find()
	{
		return new \dpadjogja\survey\models\query\Surveys(get_called_class());
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
		$this->templateColumns['gender'] = [
			'attribute' => 'gender',
			'value' => function($model, $key, $index, $column) {
				return SurveyRespondent::getGender($model->respondent->gender);
			},
			'filter' => SurveyRespondent::getGender(),
		];
		$this->templateColumns['educationId'] = [
			'attribute' => 'educationId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->respondent->education) ? $model->respondent->education->education_level : '-';
				// return $model->educationId;
			},
			'filter' => SurveyEducation::getEducation(),
			'visible' => !Yii::$app->request->get('respondent') ? true : false,
		];
		$this->templateColumns['workId'] = [
			'attribute' => 'workId',
			'value' => function($model, $key, $index, $column) {
				return isset($model->respondent->work) ? $model->respondent->work->work_name : '-';
				// return $model->workId;
			},
			'filter' => SurveyWork::getWork(),
			'visible' => !Yii::$app->request->get('respondent') ? true : false,
		];
		$this->templateColumns['service_id'] = [
			'attribute' => 'service_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->service) ? $model->service->service_name : '-';
				// return $model->serviceName;
			},
			'filter' => SurveyService::getService(),
			'visible' => !Yii::$app->request->get('service') ? true : false,
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
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
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
	 * function getAnswerStatus
	 */
	public function getAssessmentStatus()
	{
		$assessments = $this->assessments;
		$return = true;

        if (!is_array($assessments)) {
            return false;
        }

		foreach ($assessments as $key => $item) {
            if (!$item) {
                $return = false;
            }
		}

		return $return;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->gender = $model->respondent->gender;
		// $this->educationId = isset($this->respondent->education) ? $this->respondent->education->education_level : '-';
		// $this->workId = isset($model->respondent->work) ? $model->respondent->work->work_name : '-';
		// $this->serviceName = isset($this->service) ? $this->service->service_name : '-';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->creation_id == null) {
                    $this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            } else {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }

            if ($this->getAssessmentStatus() == false) {
                $this->addError('assessments', Yii::t('app', '{attribute} not complete.', ['attribute' => $this->getAttributeLabel('assessments')]));
            }
        }
        return true;
	}

	/**
	 * After save attributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
			foreach ($this->assessments as $key => $val) {
				$model = new SurveyAssessment();
				$model->survey_id = $this->id;
				$model->instrument_id = $key;
				$model->answer = $val;
				$model->save();
			}
		}
	}
}
