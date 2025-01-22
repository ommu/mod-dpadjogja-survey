<?php
/**
 * Survey Settings (survey-setting)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\AdminController
 * @var $model dpadjogja\survey\models\SurveySetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 02 December 2019, 22:25 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use app\components\widgets\ActiveForm;
?>

<div class="survey-setting-form">

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

<?php 
if ($model->isNewRecord && !$model->getErrors()) {
	$model->license = $model->licenseCode();
}
echo $form->field($model, 'license')
	->textInput(['maxlength' => true])
	->label($model->getAttributeLabel('license'))
	->hint(Yii::t('app', 'Enter the your license key that is provided to you when you purchased this plugin. If you do not know your license key, please contact support team.').'<br/>'.Yii::t('app', 'Format: XXXX-XXXX-XXXX-XXXX')); ?>

<?php $permission = $model::getPermission();
echo $form->field($model, 'permission', ['template' => '{label}{beginWrapper}{hint}{input}{error}{endWrapper}'])
	->radioList($permission)
	->label($model->getAttributeLabel('permission'))
	->hint(Yii::t('app', 'Select whether or not you want to let the public (visitors that are not logged-in) to view the following sections of your social network. In some cases (such as Profiles, Blogs, and Albums), if you have given them the option, your users will be able to make their pages private even though you have made them publically viewable here. For more permissions settings, please visit the General Settings page.')); ?>

<?php echo $form->field($model, 'meta_description')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_description')); ?>

<?php echo $form->field($model, 'meta_keyword')
	->textarea(['rows' => 6, 'cols' => 50])
	->label($model->getAttributeLabel('meta_keyword')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end(); ?>

</div>