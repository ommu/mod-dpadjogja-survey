<?php
/**
 * Surveys (surveys)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\AdminController
 * @var $model dpadjogja\survey\models\Surveys
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 December 2019, 01:58 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="surveys-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'respondent' => $respondent,
	'assessments' => $assessments,
]); ?>

</div>
