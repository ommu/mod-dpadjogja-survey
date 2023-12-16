<?php
/**
 * Survey Settings (survey-setting)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\AdminController
 * @var $model dpadjogja\survey\models\SurveySetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 02 December 2019, 22:25 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Url;

if ($breadcrumb) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
}

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="survey-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>