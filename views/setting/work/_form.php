<?php
/**
 * Survey Works (survey-work)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\WorkController
 * @var $model dpadjogja\survey\models\SurveyWork
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:25 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="survey-work-form">

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

<?php echo $form->field($model, 'work_name')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('work_name')); ?>

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