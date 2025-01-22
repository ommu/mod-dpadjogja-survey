<?php
/**
 * Survey Instruments (survey-instrument)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\InstrumentController
 * @var $model dpadjogja\survey\models\SurveyInstrument
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 17:23 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyCategory;
use yii\redactor\widgets\Redactor;
use yii\helpers\ArrayHelper;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor', 'imagemanager']
];
?>

<div class="survey-instrument-form">

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

<?php echo $form->errorSummary($model);?>

<?php echo $form->field($model, 'cat_id', ['template' => '{input}', 'options' => ['tag' => null]])->hiddenInput(); ?>

<?php echo $form->field($model, 'question')
	->textarea(['rows' => 6, 'cols' => 50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('question')); ?>

<?php 
$choices = $model::getChoices();
$i = 0;
foreach ($choices as $key => $val) {
	$i++;
    if ($model->isNewRecord && !$model->getErrors()) {
		$model->answer = ArrayHelper::merge($model->answer, [$key => ['key' => $val]]);
    }
	$renderChoice .= $form->field($model, 'answer['.$key.'][key]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => $i == 1 ? 'col-sm-2 col-xs-2 mb-4' : 'col-sm-2 col-xs-2 col-sm-offset-3 mb-4'], 'options' => ['tag' => null]])
		->textInput()
		->label($model->getAttributeLabel('answer'));
	$renderChoice .= $form->field($model, 'answer['.$key.'][val]', ['template' => '{beginWrapper}{input}{endWrapper}', 'horizontalCssClasses' => ['wrapper' => 'col-sm-7 col-xs-10 mb-4'], 'options' => ['tag' => null]])
		->textInput()
		->label($model->getAttributeLabel('answer'));
}

echo $form->field($model, 'answer', ['template' => '{label}'.$renderChoice.'{error}{hint}', 'horizontalCssClasses' => ['error' => 'col-sm-9 col-xs-12 col-sm-offset-3', 'hint' => 'col-sm-9 col-xs-12 col-sm-offset-3']])
	->label($model->getAttributeLabel('answer')); ?>

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