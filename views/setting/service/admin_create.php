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

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="survey-service-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
