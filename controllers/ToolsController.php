<?php
namespace pistol88\order\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use pistol88\cart\widgets\ElementsList;
use pistol88\cart\widgets\CartInformer;
use pistol88\order\models\ShippingType;

class ToolsController  extends Controller
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
        ];
    }

    public function actionFindUsersWindow()
    {
        $this->layout = 'mini';
        
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
        $this->layout = 'mini';
        
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
            if(isset($userModel->userProfile)) {
                $profile = $userModel->userProfile;
                $fullName = $profile->getFullName();
            }
            else {
                $fullName = null;
            }

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
    
    public function actionCartInfo()
    {
        die(json_encode([
            'cart' => ElementsList::widget(['type' => ElementsList::TYPE_FULL, 'showOffer' => false, 'otherFields' => [yii::t('order', 'Amount') => 'amount']]),
            'total' => CartInformer::widget(['htmlTag' => 'div', 'text' => '{c} на {p}']),
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
}
