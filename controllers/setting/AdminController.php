<?php
/**
 * AdminController
 * @var $this dpadjogja\survey\controllers\setting\AdminController
 * @var $model dpadjogja\survey\models\SurveySetting
 *
 * AdminController implements the CRUD actions for SurveySetting model.
 * Reference start
 * TOC :
 *	Index
 *	Update
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 02 December 2019, 22:25 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey\controllers\setting;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use dpadjogja\survey\models\SurveySetting;

class AdminController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();

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
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
		$model = SurveySetting::findOne(1);
        if ($model === null) {
            $model = new SurveySetting(['id' => 1]);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Survey setting success updated.'));
                return $this->redirect(['update']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Survey Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_index', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing SurveySetting model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionUpdate()
	{
		$model = SurveySetting::findOne(1);
        if ($model == null) {
            $model = new SurveySetting(['id' => 1]);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $postData = Yii::$app->request->post();
            // $model->load($postData);
            // $model->order = $postData['order'] ? $postData['order'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Survey setting success updated.'));
                return $this->redirect(['update']);
                //return $this->redirect(['view', 'id' => $model->id]);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->subMenu = $this->module->params['setting_submenu'];
		$this->view->title = Yii::t('app', 'Survey Settings');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_update', [
			'model' => $model,
			'breadcrumb' => true,
		]);
	}

	/**
	 * Deletes an existing SurveySetting model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete()
	{
		$model = $this->findModel(1);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Survey setting success reset.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['index']);
	}

	/**
	 * Finds the SurveySetting model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SurveySetting the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = SurveySetting::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
