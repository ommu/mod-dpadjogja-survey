<?php
/**
 * AssessmentController
 * @var $this dpadjogja\survey\controllers\AssessmentController
 * @var $model dpadjogja\survey\models\SurveyAssessment
 *
 * AssessmentController implements the CRUD actions for SurveyAssessment model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 December 2019, 05:17 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\controllers;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use dpadjogja\survey\models\SurveyAssessment;
use dpadjogja\survey\models\search\SurveyAssessment as SurveyAssessmentSearch;

class AssessmentController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id') || Yii::$app->request->get('survey'))
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
	 * Lists all SurveyAssessment models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new SurveyAssessmentSearch();
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

		if(($survey = Yii::$app->request->get('survey')) != null) {
			$this->subMenuParam = $survey;
			$survey = \dpadjogja\survey\models\Surveys::findOne($survey);
		}
		if(($instrument = Yii::$app->request->get('instrument')) != null)
			$instrument = \dpadjogja\survey\models\SurveyInstrument::findOne($instrument);

		$this->view->title = Yii::t('app', 'Assessments');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'survey' => $survey,
			'instrument' => $instrument,
		]);
	}

	/**
	 * Displays a single SurveyAssessment model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		$this->subMenuParam = $model->survey_id;

		$this->view->title = Yii::t('app', 'Detail Assessment: {survey-id}', ['survey-id' => $model->survey->respondent->education->id]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SurveyAssessment model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Survey assessment success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the SurveyAssessment model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SurveyAssessment the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = SurveyAssessment::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}