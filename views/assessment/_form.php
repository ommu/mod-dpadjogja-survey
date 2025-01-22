<?php
/**
 * Survey Assessments (survey-assessment)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\AssessmentController
 * @var $model dpadjogja\survey\models\SurveyAssessment
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 December 2019, 05:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyInstrument;
?>

<div class="survey-assessment-form">

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

<?php echo $form->field($model, 'survey_id')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('survey_id')); ?>

<?php $instrument = SurveyInstrument::getInstrument();
echo $form->field($model, 'instrument_id')
	->dropDownList($instrument, ['prompt' => ''])
	->label($model->getAttributeLabel('instrument_id')); ?>

<?php echo $form->field($model, 'answer')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('answer')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>