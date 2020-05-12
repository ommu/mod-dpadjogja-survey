<?php
/**
 * SurveyInstrument
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 06:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 * This is the model class for table "dpadjogja_survey_instrument".
 *
 * The followings are the available columns in table "dpadjogja_survey_instrument":
 * @property integer $id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $question
 * @property string $answer
 * @property integer $order
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SurveyAssessment[] $assessments
 * @property SurveyCategory $category
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace dpadjogja\survey\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use app\models\Users;

class SurveyInstrument extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['creation_date', 'creationDisplayname', 'modified_date', 'modifiedDisplayname', 'updated_date'];

	public $categoryName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'dpadjogja_survey_instrument';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['cat_id', 'question', 'answer'], 'required'],
			[['publish', 'cat_id', 'order', 'creation_id', 'modified_id'], 'integer'],
			[['question'], 'string'],
			[['order'], 'safe'],
			//[['answer'], 'json'],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyCategory::className(), 'targetAttribute' => ['cat_id' => 'id']],
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
			'cat_id' => Yii::t('app', 'Category'),
			'question' => Yii::t('app', 'Question'),
			'answer' => Yii::t('app', 'Answer'),
			'order' => Yii::t('app', 'Order'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'categoryName' => Yii::t('app', 'Category'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAssessments()
	{
		return $this->hasMany(SurveyAssessment::className(), ['instrument_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(SurveyCategory::className(), ['id' => 'cat_id']);
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
	 * @return \dpadjogja\survey\models\query\SurveyInstrument the active query used by this AR class.
	 */
	public static function find()
	{
		return new \dpadjogja\survey\models\query\SurveyInstrument(get_called_class());
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
		$this->templateColumns['cat_id'] = [
			'attribute' => 'cat_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->category) ? $model->category->category_name : '-';
				// return $model->categoryName;
			},
			'filter' => SurveyCategory::getCategory(),
			'visible' => !Yii::$app->request->get('category') ? true : false,
		];
		$this->templateColumns['question'] = [
			'attribute' => 'question',
			'value' => function($model, $key, $index, $column) {
				return $model->question;
			},
			'format' => 'html',
		];
		$this->templateColumns['answer'] = [
			'attribute' => 'answer',
			'value' => function($model, $key, $index, $column) {
				return self::parseAnswer($model->answer);
			},
			'format' => 'html',
		];
		$this->templateColumns['order'] = [
			'attribute' => 'order',
			'value' => function($model, $key, $index, $column) {
				return $model->order;
			},
			'contentOptions' => ['class'=>'text-center'],
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
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
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
	 * function getInstrument
	 */
	public static function getInstrument($publish=null, $array=true) 
	{
		$model = self::find()->alias('t')
			->select(['t.id', 't.cat_id']);
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('t.cat_id ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'id', 'cat_id');

		return $model;
	}

	/**
	 * function getChoice
	 */
	public static function getChoices()
	{
		$items = array(
			'a' => 'A',
			'b' => 'B',
			'c' => 'C',
			'd' => 'D',
		);

		return $items;
	}

	/**
	 * function getAnswerStatus
	 */
	public function getAnswerStatus()
	{
		$answer = $this->answer;
		$return = true;

		if(!is_array($answer))
			return false;

		foreach ($answer as $key => $item) {
			if(!$item['key'] || !$item['val'])
				$return = false;
		}

		return $return;
	}

	/**
	 * function getAnswerStatus
	 */
	public function getAnswerForForm()
	{
		$answer = $this->answer;
		$items = [];

		foreach ($answer as $key => $item) {
			$items[$key] = $item['val'];
		}

		return $items;
	}

	/**
	 * function parseAnswer
	 */
	public static function parseAnswer($answer, $sep='li')
	{
		if(!is_array($answer) || (is_array($answer) && empty($answer)))
			return '-';

		// $answer = ArrayHelper::map($answer, 'key', 'val');

		if($sep == 'li') {
			return Html::ul($answer, ['item' => function($item, $index) {
				return Html::tag('li', $item['key'].'. '.$item['val']);
			}, 'class'=>'list-unstyled']);
		}

		return implode($sep, $answer);
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->answer = Json::decode($this->answer);
		// $this->categoryName = isset($this->category) ? $this->category->category_name : '-';
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

			if($this->getAnswerStatus() == false)
				$this->addError('answer', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('answer')]));
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			$this->answer = Json::encode($this->answer);
		}
		return true;
	}
}
