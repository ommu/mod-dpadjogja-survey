<?php
/**
 * Survey Respondents (survey-respondent)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\RespondentController
 * @var $model dpadjogja\survey\models\SurveyRespondent
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Respondent'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="survey-respondent-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
