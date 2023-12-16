<?php
/**
 * Survey Respondents (survey-respondent)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\RespondentController
 * @var $model dpadjogja\survey\models\SurveyRespondent
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyEducation;
use dpadjogja\survey\models\SurveyWork;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
?>

<div class="survey-respondent-form">

<?php $form = ActiveForm::begin([
	'options' => ['class' => 'form-horizontal form-label-left'],
	'enableClientValidation' => true,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]); ?>

<?php //echo $form->errorSummary($model);?>

<?php $gender = $model::getGender();
echo $form->field($model, 'gender')
	->widget(Selectize::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($model->getAttributeLabel('gender'))]),
		],
		'items' => ArrayHelper::merge(['' => Yii::t('app', 'Select a {attribute}..', ['attribute' => strtolower($model->getAttributeLabel('gender'))])], $gender),
		'pluginOptions' => [
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('gender')); ?>

<?php echo $form->field($model, 'user_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('user_id')); ?>

<?php $education = SurveyEducation::getEducation();
echo $form->field($model, 'education_id')
	->dropDownList($education, ['prompt' => ''])
	->label($model->getAttributeLabel('education_id')); ?>

<?php $work = SurveyWork::getWork();
echo $form->field($model, 'work_id')
	->dropDownList($work, ['prompt' => ''])
	->label($model->getAttributeLabel('work_id')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>