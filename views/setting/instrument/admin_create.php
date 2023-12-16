<?php
/**
 * Survey Instruments (survey-instrument)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\InstrumentController
 * @var $model dpadjogja\survey\models\SurveyInstrument
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 17:23 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['setting/admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category'), 'url' => ['setting/category/index']];
$this->params['breadcrumbs'][] = ['label' => $model->category->category_name, 'url' => ['setting/category/view', 'id' => $model->category->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Instrument'), 'url' => ['manage', 'category' => $model->category->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="survey-instrument-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
