<?php
/**
 * Survey Respondents (survey-respondent)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\RespondentController
 * @var $model dpadjogja\survey\models\search\SurveyRespondent
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyRespondent;
use dpadjogja\survey\models\SurveyEducation;
use dpadjogja\survey\models\SurveyWork;
?>

<div class="survey-respondent-search search-form">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
		'options' => [
			'data-pjax' => 1
		],
	]); ?>

		<?php echo $form->field($model, 'userDisplayname');?>

		<?php $education = SurveyEducation::getEducation();
		echo $form->field($model, 'education_id')
			->dropDownList($education, ['prompt'=>'']);?>

		<?php $work = SurveyWork::getWork();
		echo $form->field($model, 'work_id')
			->dropDownList($work, ['prompt'=>'']);?>

		<?php $gender = $model::getGender();
			echo $form->field($model, 'gender')
			->dropDownList($gender, ['prompt'=>'']);?>

		<?php echo $form->field($model, 'creation_date')
			->input('date');?>

		<?php echo $form->field($model, 'creationDisplayname');?>

		<?php echo $form->field($model, 'modified_date')
			->input('date');?>

		<?php echo $form->field($model, 'modifiedDisplayname');?>

		<div class="form-group">
			<?php echo Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']); ?>
			<?php echo Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']); ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>