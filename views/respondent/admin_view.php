<?php
/**
 * Survey Respondents (survey-respondent)
 * @var $this app\components\View
 * @var $this dpadjogja\survey\controllers\RespondentController
 * @var $model dpadjogja\survey\models\SurveyRespondent
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 10:39 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Respondent'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = isset($model->user) ? $model->user->displayname : Yii::t('app', 'Number #{id}', ['id' => $model->id]);

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="survey-respondent-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'gender',
		'value' => $model::getGender($model->gender),
		'visible' => !$small,
	],
	[
		'attribute' => 'educationLevel',
		'value' => function ($model) {
			$educationLevel = isset($model->education) ? $model->education->education_level : '-';
            if ($educationLevel != '-') {
				return Html::a($educationLevel, ['setting/education/view', 'id' => $model->education_id], ['title' => $educationLevel, 'class' => 'modal-btn']);
            }
			return $educationLevel;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'workName',
		'value' => function ($model) {
			$workName = isset($model->work) ? $model->work->work_name : '-';
            if ($workName != '-') {
				return Html::a($workName, ['setting/work/view', 'id' => $model->work_id], ['title' => $workName, 'class' => 'modal-btn']);
            }
			return $workName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm modal-btn']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>