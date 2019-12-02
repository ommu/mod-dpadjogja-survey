<?php
/**
 * Survey Settings (survey-setting)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\setting\AdminController
 * @var $model dpadjogja\survey\models\SurveySetting
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 02 December 2019, 22:25 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use dpadjogja\survey\models\SurveySetting;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['book/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
?>

<?php echo $this->render(!$model->isNewRecord ? 'admin_view' : 'admin_update', [
	'contentMenu' => true,
	'model' => $model,
	'breadcrumb' => false,
]); ?>