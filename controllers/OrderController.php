<?php
namespace pistol88\order\controllers;

use yii;
use pistol88\order\models\tools\OrderSearch;
use pistol88\order\models\OrderElement;
use pistol88\order\models\Order;
use pistol88\order\models\Element;
use pistol88\order\models\tools\ElementSearch;
use pistol88\order\models\Field;
use pistol88\order\models\FieldValue;
use pistol88\order\models\PaymentType;
use pistol88\order\models\ShippingType;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use pistol88\order\events\OrderEvent;

class OrderController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				'only' => ['create', 'update', 'index', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);

        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        $shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'shippingTypes' => $shippingTypes,
            'paymentTypes' => $paymentTypes,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new ElementSearch;
        $params = yii::$app->request->queryParams;
        if(empty($params['ElementSearch'])) {
            $params = ['ElementSearch' => ['order_id' => $model->id]];
        }

        $dataProvider = $searchModel->search($params);

        $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        $shippingTypes = ArrayHelper::map(ShippingType::find()->all(), 'id', 'name');

        $fieldFind = Field::find();

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'shippingTypes' => $shippingTypes,
            'fieldFind' => $fieldFind,
            'paymentTypes' => $paymentTypes,
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $orderModel = yii::$app->orderModel;

        $model = new $orderModel;

        if ($model->load(yii::$app->request->post()) && $model->save()) {
            $module = $this->module;
            $orderEvent = new OrderEvent(['model' => $model]);
            $this->module->trigger($module::EVENT_ORDER_CREATE, $orderEvent);
            
            return $this->redirect(['order/view', 'id' => $model->id]);
        } else {
            //yii::$app->cart->truncate();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCustomerCreate()
    {
        $model = new Order(['scenario' => 'customer']);

        if ($model->load(yii::$app->request->post())) {
            $model->date = date('Y-m-d');
            $model->time = date('H:i:s');
            $model->timestamp = time();
            $model->status = $this->module->defaultStatus;
            $model->payment = 'no';
            $model->user_id = yii::$app->user->id;

            if($model->save()) {
                return $this->redirect([yii::$app->getModule('order')->successUrl, 'id' => $model->id, 'payment' => $model->payment_type_id]);
            } else {
                yii::$app->session->setFlash('orderError', yii::t('order', 'Error (check required fields)'));
                return $this->redirect(yii::$app->request->referrer);
            }
        } else {
            yii::$app->session->setFlash('orderError', yii::t('order', 'Error (check required fields)'));
            return $this->redirect(yii::$app->request->referrer);
        }
    }

    public function actionUpdateStatus()
    {
        if($id = yii::$app->request->post('id')) {
            $model = Order::findOne($id);
            $model->status = yii::$app->request->post('status');
            if($model->save(false)) {
                die(json_encode(['result' => 'success']));
            } else {
                die(json_encode(['result' => 'fail', 'error' => 'enable to save']));
            }
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $module = $this->module;
        $orderEvent = new OrderEvent(['model' => $model]);
        $this->module->trigger($module::EVENT_ORDER_DELETE, $orderEvent);
        
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionEditable() {
        $name = yii::$app->request->post('name');
        $value = yii::$app->request->post('value');
        $pk = unserialize(base64_decode(yii::$app->request->post('pk')));
        OrderElement::editField($pk, $name, $value);
    }

    protected function findModel($id)
    {
        $orderModel = yii::$app->orderModel;
        
        if (($model = $orderModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
