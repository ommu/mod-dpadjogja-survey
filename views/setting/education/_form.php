<?php
/**
 * Survey Educations (survey-education)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\EducationController
 * @var $model dpadjogja\survey\models\SurveyEducation
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 08:52 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="survey-education-form">

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

<?php echo $form->field($model, 'education_level')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('education_level')); ?>

<?php echo !$model->isNewRecord ? $form->field($model, 'order')
	->textInput(['type' => 'number', 'min' => '1'])
	->label($model->getAttributeLabel('order')) : ''; ?>

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
    $model->publish = 1;
}
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>