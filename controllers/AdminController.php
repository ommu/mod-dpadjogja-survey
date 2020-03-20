<?php
/**
 * AdminController
 * @var $this dpadjogja\survey\controllers\AdminController
 * @var $model dpadjogja\survey\models\Surveys
 *
 * AdminController implements the CRUD actions for Surveys model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 4 December 2019, 01:58 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use dpadjogja\survey\models\Surveys;
use dpadjogja\survey\models\search\Surveys as SurveysSearch;
use dpadjogja\survey\models\SurveyRespondent;
use yii\helpers\ArrayHelper;
use app\components\widgets\ActiveForm;
use dpadjogja\survey\models\SurveyInstrument;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id'))
			$this->subMenu = $this->module->params['survey_submenu'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		return $this->redirect(['manage']);
	}

	/**
	 * Lists all Surveys models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new SurveysSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if(($service = Yii::$app->request->get('service')) != null)
			$service = \dpadjogja\survey\models\SurveyService::findOne($service);
		if(($respondent = Yii::$app->request->get('respondent')) != null)
			$respondent = \dpadjogja\survey\models\SurveyRespondent::findOne($respondent);

		$this->view->title = Yii::t('app', 'Surveys');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'service' => $service,
			'respondent' => $respondent,
		]);
	}

	/**
	 * Creates a new Surveys model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Surveys();
		$respondent = new SurveyRespondent();

		$assessments = SurveyInstrument::find()->alias('t')
			->select(['t.id', 't.question', 't.answer'])
			->where(['t.publish' => 1])
			->andWhere(['t.cat_id' => 2])
			->orderBy('t.order ASC')
			->all();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$respondent->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			$isValid = $model->validate();
			$isValid = $respondent->validate() && $isValid;

			if($isValid) {
				$respondent->save();
				$model->respondent_id = $respondent->id;
				if($model->save()) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Survey success created.'));
					return $this->redirect(['manage']);
				}

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(ArrayHelper::merge(ActiveForm::validate($model), ActiveForm::validate($respondent)));
			}
		}

		$this->view->title = Yii::t('app', 'Create Survey');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
			'respondent' => $respondent,
			'assessments' => $assessments,
		]);
	}

	/**
	 * Updates an existing Surveys model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$respondent = SurveyRespondent::findOne($model->respondent_id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$respondent->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			$isValid = $model->validate();
			$isValid = $respondent->validate() && $isValid;

			if($isValid) {
				if($model->save() && $respondent->save()) {
					Yii::$app->session->setFlash('success', Yii::t('app', 'Survey success updated.'));
					return $this->redirect(['update', 'id'=>$model->id]);
	
				}

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Survey: {respondent-id}', ['respondent-id' => $model->respondent->education->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'respondent' => $respondent,
			'assessments' => $assessments,
		]);
	}

	/**
	 * Displays a single Surveys model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Survey: {respondent-id}', ['respondent-id' => $model->respondent->education->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Surveys model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Survey success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * Finds the Surveys model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Surveys the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = Surveys::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}