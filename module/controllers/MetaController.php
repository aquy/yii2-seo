<?php

namespace aquy\seo\module\controllers;

use Yii;
use aquy\seo\module\models\SeoMeta;
use aquy\seo\module\models\SeoPage;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetaController implements the CRUD actions for SeoMeta model.
 */
class MetaController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'update-item',
                            'delete',
                            'delete-item',
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all SeoMeta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SeoPage::find()->with(array_keys(SeoMeta::nameList())),
            'pagination' => [
                'pageSize' => 100
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing SeoMeta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SeoMeta::find()->where(['page_id' => $id])->orderBy('name'),
        ]);

        $model = new SeoMeta();
        $model->page_id = $id;
        $page = SeoPage::find()->where(['id' => $id])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $id]);
        } else {
            return $this->render('update', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'page' => $page
            ]);
        }
    }

    /**
     * Updates an existing SeoMeta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateItem($id)
    {
        $model = SeoMeta::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => SeoMeta::find()->where(['page_id' => $model->page_id]),
        ]);

        $page = SeoPage::find()->where(['id' => $model->page_id])->asArray()->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $page['id']]);
        } else {
            return $this->render('update', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'page' => $page
            ]);
        }
    }

    /**
     * Deletes an existing SeoMeta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = SeoPage::find()->where(['id' => $id])->with('meta')->one();
        if (is_null($model)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            foreach($model['meta'] as $m) {
                $m->delete();
            }
            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing SeoMeta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteItem($id)
    {
        $model = $this->findModel($id);
        $id = $model->page_id;
        $model->delete();

        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Finds the SeoMeta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SeoMeta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SeoMeta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
