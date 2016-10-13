<?php
namespace pistol88\order\controllers;

use yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use pistol88\cart\widgets\ElementsList;
use pistol88\cart\widgets\CartInformer;
use pistol88\order\models\ShippingType;
use pistol88\order\events\OrderEvent;

class ToolsController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				'only' => ['user-info', 'ajax-elements-list', 'outcoming', 'find-users-window', 'user-info'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
        ];
    }

    public function actionFindUsersWindow()
    {
        $this->layout = '@vendor/pistol88/yii2-order/views/layouts/mini';
        
        $searchModel = new $this->module->userSearchModel;
        $model = new $this->module->userModel;
        
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);

        return $this->render('users_list', [
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionFindProductsWindow()
    {
        $this->layout = '@vendor/pistol88/yii2-order/views/layouts/mini';
        
        $searchModel = new $this->module->productSearchModel;
        $model = new $this->module->productModel;
        $categories = $this->module->productCategoriesList;
        
        $dataProvider = $searchModel->search(yii::$app->request->queryParams);

        return $this->render('products_list', [
            'categories' => $categories,
            'searchModel' => $searchModel,
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionBuyProductByCode()
    {
        $code = yii::$app->request->post('code');

        $model = new $this->module->productModel;
        
        if($model = $model::find()->where('code=:code OR id=:code', [':code' => $code])->one()) {
            yii::$app->cart->put($model->sellModel, 1, []);
            
            $json = ['status' => 'success'];
        } else {
            $json = [
                'status' => 'fail',
                'message' => yii::t('order', 'Not found')
            ];
        }
        
        die(json_encode($json));
    }
    
    public function actionUserInfo()
    {
        $userId = (int)yii::$app->request->post('userId');
        
        $model = new $this->module->userModel;
        
        $userModel = $model::findOne($userId);
        
        $promocode = false;
        
        if(!$userModel) {
            foreach($this->module->userModelCustomFields as $field) {
                if($userModel = $model::findOne([$field => $userId])) {
                    break;
                }
            }
        }
        
        if($userModel->promocode) {
            $promocode = $userModel->promocode;
        }
        
        if($userModel) {
            $fullName = $userModel->name;

            $json = [
                'id' => $userModel->id,
                'status' => 'success',
                'promocode' => $promocode,
                'username' => $userModel->username,
                'email' => $userModel->email,
                'phone' => $userModel->phone,
                'client_name' => $fullName,
            ];
        } else {
            $json = [
                'status' => 'fail',
                'message' => yii::t('order', 'Not found')
            ];
        }
        
        die(json_encode($json));
    }
    
	public function actionAjaxElementsList()
	{
		$model = yii::$app->order->get(yii::$app->request->post('orderId'));
		
		$elements = Html::ul($model->elements, ['item' => function($item, $index) {
			return Html::tag(
				'li',
				"{$item->getModel()->getCartName()} - {$item->base_price} {$this->module->currency}x{$item->count}",
				['class' => 'post']
			);
		}]);
		
        die(json_encode([
            'elementsHtml' => $elements,
        ]));
	}
	
    public function actionCartInfo()
    {
        die(json_encode([
            'cart' => ElementsList::widget(['type' => ElementsList::TYPE_FULL, 'showOffer' => false, 'otherFields' => $this->module->cartCustomFields]),
            'total' => CartInformer::widget(['htmlTag' => 'div', 'text' => '{c} на {p}']),
            'count' => yii::$app->cart->count,
        ]));
    }
    
    public function actionUpdateShippingType()
    {
        $shippingTypeId = (int)yii::$app->request->post('shipping_type_id');
        yii::$app->session->set('orderShippingType', $shippingTypeId);
        
        die(json_encode([
            'total' => yii::$app->cart->cost,
        ]));
    }
    
    public function actionOneClick()
    {
        $orderModel = yii::$app->orderModel;

        $model = new $orderModel;

        $elementModel = yii::$app->request->post('model');
        $elementModel = new $elementModel;
        
        $elementId = yii::$app->request->post('id');
        
        if($elementModel = $elementModel::findOne($elementId)) {
            yii::$app->cart->put($elementModel, 1, []);

            if ($model->load(yii::$app->request->post()) && $model->save()) {
                if($ordersEmail = yii::$app->getModule('order')->ordersEmail) {
                    $sender = yii::$app->getModule('order')->mail
                        ->compose('admin_notification', ['model' => $model])
                        ->setTo($ordersEmail)
                        ->setFrom(yii::$app->getModule('order')->robotEmail)
                        ->setSubject(Yii::t('order', 'New order')." #{$model->id} ({$model->client_name})")
                        ->send();
                }

                $module = $this->module;
                $orderEvent = new OrderEvent(['model' => $model]);
                $this->module->trigger($module::EVENT_ORDER_CREATE, $orderEvent);

                $module = $this->module;
                $orderEvent = new OrderEvent(['model' => $model]);
                $this->module->trigger($module::EVENT_ORDER_CREATE, $orderEvent);

                $json = ['id' => $model->id, 'redirect' => Url::toRoute([$this->module->successUrl, 'id' => $model->id, 'payment' => $model->payment_type_id]), 'result' => 'success'];
            } else {
                //yii::$app->cart->truncate();
                $json = ['result' => 'fail'];
                $json['error'] = current($model->getFirstErrors());
            }
            
            die(json_encode($json));
        }
    }
	
	public function actionOutcoming()
	{
		$stockId = yii::$app->request->post('stock_id');
        $productId = yii::$app->request->post('product_id');
        $count = yii::$app->request->post('count');
        $orderId = yii::$app->request->post('order_id');
        
        $json = ['result' => 'fail'];

        if($stockId && $productId && $count && yii::$app->has('stock')) {
            $stock = yii::$app->stock;
            
            $amount = $stock->outcoming($stockId, $productId, $count, $orderId);
            
            $json['amount'] = $amount;
            $json['result'] = 'success';
        }
        
        die(json_encode($json));
	}
}
