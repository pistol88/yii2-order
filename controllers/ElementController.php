<?php
namespace pistol88\order\controllers;

use yii;
use pistol88\order\models\Element;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ElementController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => yii::$app->getModule('order')->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'edittable' => ['post'],
                ],
            ],
        ];
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(yii::$app->request->referrer);
    }

    public function actionEditable()
    {
        $name = yii::$app->request->post('name');
        $value = yii::$app->request->post('value');
        $pk = unserialize(base64_decode(yii::$app->request->post('pk')));
        Element::editField($pk, $name, $value);
    }

    protected function findModel($id)
    {
        if (($model = Element::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
