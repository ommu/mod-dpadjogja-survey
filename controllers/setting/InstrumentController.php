<?php
/**
 * InstrumentController
 * @var $this dpadjogja\survey\controllers\setting\InstrumentController
 * @var $model dpadjogja\survey\models\SurveyInstrument
 *
 * InstrumentController implements the CRUD actions for SurveyInstrument model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 3 December 2019, 17:23 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\controllers\setting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use dpadjogja\survey\models\SurveyInstrument;
use dpadjogja\survey\models\search\SurveyInstrument as SurveyInstrumentSearch;

class InstrumentController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();
		if(Yii::$app->request->get('id') || Yii::$app->request->get('category'))
			$this->subMenu = $this->module->params['setting_submenu'];
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
					'publish' => ['POST'],
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
	 * Lists all SurveyInstrument models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new SurveyInstrumentSearch();
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

		if(($category = Yii::$app->request->get('category')) != null)
			$category = \dpadjogja\survey\models\SurveyCategory::findOne($category);

		$this->view->title = Yii::t('app', 'Instruments');
		if($category)
			$this->view->title = Yii::t('app', 'Instruments: Category {category-name}', ['category-name'=>$category->category_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'category' => $category,
		]);
	}

	/**
	 * Creates a new SurveyInstrument model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		if(($category = Yii::$app->request->get('category')) == null)
			throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'The requested page does not exist.'));

		$model = new SurveyInstrument();
		if($category)
			$model->cat_id = $category;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Survey instrument success created.'));
				if($category)
					return $this->redirect(['manage', 'category'=>$model->cat_id]);
				return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category'=>$model->cat_id]);
				//return $this->redirect(['view', 'id'=>$model->id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Instrument');
		if($category)
			$this->view->title = Yii::t('app', 'Create Instrument: Category {cat-id}', ['cat-id'=>$model->category->category_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing SurveyInstrument model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Survey instrument success updated.'));
				if(!Yii::$app->request->isAjax)
					return $this->redirect(['update', 'id'=>$model->id]);
				return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category'=>$model->cat_id]);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Instrument: Category {cat-id}', ['cat-id' => $model->category->category_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single SurveyInstrument model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Instrument: Category {cat-id}', ['cat-id' => $model->category->category_name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SurveyInstrument model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Survey instrument success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category'=>$model->cat_id]);
		}
	}

	/**
	 * actionPublish an existing SurveyInstrument model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Survey instrument success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage', 'category'=>$model->cat_id]);
		}
	}

	/**
	 * Finds the SurveyInstrument model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SurveyInstrument the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = SurveyInstrument::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}