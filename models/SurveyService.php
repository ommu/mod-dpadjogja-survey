<?php
/**
 * SurveyService
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 2 December 2019, 23:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 * This is the model class for table "dpadjogja_survey_service".
 *
 * The followings are the available columns in table "dpadjogja_survey_service":
 * @property integer $id
 * @property integer $publish
 * @property string $service_name
 * @property integer $order
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property Surveys[] $surveys
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace dpadjogja\survey\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class SurveyService extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];

	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'dpadjogja_survey_service';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['service_name'], 'required'],
			[['publish', 'order', 'creation_id', 'modified_id'], 'integer'],
			[['order'], 'safe'],
			[['service_name'], 'string', 'max' => 64],
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
			'service_name' => Yii::t('app', 'Service'),
			'order' => Yii::t('app', 'Order'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'surveys' => Yii::t('app', 'Surveys'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSurveys($count=false, $publish=1)
	{
		if($count == false)
			return $this->hasMany(Surveys::className(), ['service_id' => 'id'])
			->alias('surveys')
			->andOnCondition([sprintf('%s.publish', 'surveys') => $publish]);

		$model = Surveys::find()
			->alias('t')
			->where(['service_id' => $this->id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$surveys = $model->count();

		return $surveys ? $surveys : 0;
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
	 * @return \dpadjogja\survey\models\query\SurveyService the active query used by this AR class.
	 */
	public static function find()
	{
		return new \dpadjogja\survey\models\query\SurveyService(get_called_class());
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
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['service_name'] = [
			'attribute' => 'service_name',
			'value' => function($model, $key, $index, $column) {
				return $model->service_name;
			},
		];
		$this->templateColumns['order'] = [
			'attribute' => 'order',
			'value' => function($model, $key, $index, $column) {
				return $model->order;
			},
			'contentOptions' => ['class'=>'center'],
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
		$this->templateColumns['surveys'] = [
			'attribute' => 'surveys',
			'value' => function($model, $key, $index, $column) {
				$surveys = $model->getSurveys(true);
				return Html::a($surveys, ['admin/manage', 'service'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} surveys', ['count'=>$surveys]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
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
	 * function getService
	 */
	public static function getService($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.service_name']);
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('t.order ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'id', 'service_name');

		return $model;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
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
