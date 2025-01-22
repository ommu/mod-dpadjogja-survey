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

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['setting/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="survey-work-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
