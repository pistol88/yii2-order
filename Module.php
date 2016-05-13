<?php
namespace pistol88\order;

use yii;

class Module extends \yii\base\Module
{
    public $orderStatuses = ['new' => 'Новый', 'approve' => 'Подтвержден', 'cancel' => 'Отменен', 'process' => 'В обработке', 'done' => 'Выполнен'];
    public $defaultStatus = 'new';
    public $successUrl = '/order/info/thanks/';
    public $robotEmail = "no-reply@localhost";
    public $dateFormat = 'd.m.Y H:i:s';
    public $robotName = 'Robot';
    public $cartService = 'cart';
    public $ordersEmail = false;
    public $currency = 'руб.';
    public $currencyPosition = 'after';
    public $priceFormat = [2, '.', ''];
    public $adminRoles = ['admin', 'superadmin'];
    
    private $mail;
    
    public function getCart()
    {
        $cart = yii::$app->{$this->cartService};

        return $cart;
    }
    
    public function getMail()
    {
        if ($this->mail === null) {
            $this->mail = yii::$app->getMailer();
            $this->mail->viewPath = __DIR__ . '/mails';
            if ($this->robotEmail !== null) {
                $this->mail->messageConfig['from'] = $this->robotName === null ? $this->robotEmail : [$this->robotEmail => $this->robotName];
            }
        }
        return $this->mail;
    }
}
