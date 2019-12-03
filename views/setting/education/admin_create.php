<?php
/**
 * Survey Educations (survey-education)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\EducationController
 * @var $model dpadjogja\survey\models\SurveyEducation
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 3 December 2019, 08:52 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Educations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="survey-education-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
