<?php
/**
 * Survey Services (survey-service)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\ServiceController
 * @var $model dpadjogja\survey\models\SurveyService
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 3 December 2019, 17:02 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="survey-service-form">

<?php $form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left'],
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

<?php echo $form->field($model, 'service_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('service_name')); ?>

<?php echo !$model->isNewRecord ? $form->field($model, 'order')
	->textInput(['type'=>'number', 'min'=>'1'])
	->label($model->getAttributeLabel('order')) : ''; ?>

<?php if($model->isNewRecord && !$model->getErrors())
	$model->publish = 1;
echo $form->field($model, 'publish')
	->checkbox()
	->label($model->getAttributeLabel('publish')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>