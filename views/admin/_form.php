<?php
/**
 * Surveys (surveys)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\AdminController
 * @var $model dpadjogja\survey\models\Surveys
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 December 2019, 01:58 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyService;
use dpadjogja\survey\models\SurveyEducation;
use dpadjogja\survey\models\SurveyWork;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="surveys-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>
<?php //echo $form->errorSummary($respondent);?>

<?php $gender = $respondent::getGender();
echo $form->field($respondent, 'gender')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('gender'))]),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('gender'))])], $gender),
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => false,
			'create' => false,
		],
	])
	->label($respondent->getAttributeLabel('gender')); ?>

<?php echo $form->field($respondent, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($respondent->getAttributeLabel('user_id')); ?>

<?php $education = SurveyEducation::getEducation();
echo $form->field($respondent, 'education_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('education_id'))]),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('education_id'))])], $education),
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => false,
			'create' => false,
		],
	])
	->label($respondent->getAttributeLabel('education_id')); ?>

<?php $work = SurveyWork::getWork();
echo $form->field($respondent, 'work_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('work_id'))]),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($respondent->getAttributeLabel('work_id'))])], $work),
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => false,
			'create' => false,
		],
	])
	->label($respondent->getAttributeLabel('work_id')); ?>

<hr/>

<?php $service = SurveyService::getService();
echo $form->field($model, 'service_id')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($model->getAttributeLabel('service_id'))]),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($model->getAttributeLabel('service_id'))])], $service),
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => false,
			'create' => false,
		],
	])
	->label($model->getAttributeLabel('service_id')); ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->publish = 1;
}
echo !$model->isNewRecord ? $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')) : ''; ?>

<?php if (is_array($assessments) && !empty($assessments)) {?>

<hr/>

<?php foreach ($assessments as $assessment) {
		$answer = $assessment->getAnswerForForm();
		$assessmentId = $assessment->id;
		echo $form->field($model, "assessments[$assessmentId]", ['template' => '{beginWrapper}'.$assessment->question.'{input}{error}{hint}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-md-6 col-sm-9 col-xs-12 col-sm-offset-3']])
			->radioList($answer)
			->label($model->getAttributeLabel('assessments'));
	}
}
?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>