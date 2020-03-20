<?php
/**
 * Survey Categories (survey-category)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\CategoryController
 * @var $model dpadjogja\survey\models\SurveyCategory
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 2 December 2019, 23:44 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="survey-category-form">

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

<?php echo $form->field($model, 'category_name')
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('category_name')); ?>

<?php echo $form->field($model, 'category_desc')
	->textarea(['rows'=>3, 'cols'=>50])
	->label($model->getAttributeLabel('category_desc')); ?>

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