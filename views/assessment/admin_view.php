<?php
/**
 * Survey Assessments (survey-assessment)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\AssessmentController
 * @var $model dpadjogja\survey\models\SurveyAssessment
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 December 2019, 05:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if(!$small) {
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assessments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->survey->respondent->education->id;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
];
} ?>

<div class="survey-assessment-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'surveyRespondentId',
		'value' => function ($model) {
			$surveyRespondentId = Yii::t('app', 'Number #{id}', ['id'=>$model->survey_id]);
			if($surveyRespondentId != '-')
				return Html::a($surveyRespondentId, ['admin/view', 'id'=>$model->survey_id], ['title'=>$surveyRespondentId, 'class'=>'modal-btn']);
			return $surveyRespondentId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'instrumentQuestion',
		'value' => function ($model) {
			$instrumentQuestion = isset($model->instrument) ? $model->instrument->question : '-';
			if($instrumentQuestion != '-')
				return Html::a($instrumentQuestion, ['setting/instrument/view', 'id'=>$model->instrument_id], ['title'=>$instrumentQuestion, 'class'=>'modal-btn']);
			return $instrumentQuestion;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'answer',
		'value' => $model->answer ? $model->answer : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-success btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>