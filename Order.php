<?php
namespace pistol88\order;

use yii\base\Component;
use yii\db\Query;
use yii;

class Order extends Component
{
    public $order = 'pistol88\order\models\Order';
    public $element = 'pistol88\order\models\Element';
    
    public function init()
    {
        parent::init();
    }

    public function get($id)
    {
        $order = $this->order;
        
        return $order::findOne($id);
    }
    
	public function getOrdersByDatePeriod($dateStart, $dateStop, $where = null)
	{
		$order = $this->order;
		
        if($dateStop == '0000-00-00 00:00:00' | empty($dateStop)) {
            $dateStop = date('Y-m-d H:i:s');
        }
		
		$query = $order::find()->where('date >= :dateStart', [':dateStart' => $dateStart])->andWhere('date <= :dateStop', [':dateStop' => $dateStop]);
		
        if($where) {
            $query->andWhere($where);
        }

		return $query->all();
	}
	
    public function getStatInMoth($month = null, $where = null, $where = null)
    {
        if(!$month) {
            $month = date('Y-m');
        }
        
        $order = $this->order;
        
        $query = new Query();
        $query->addSelect(['sum(cost) as total, sum(count) as count_elements, COUNT(DISTINCT id) as count_orders'])
                ->from([$order::tableName()])
                ->where('DATE_FORMAT(date, "%Y-%m") = :date', [':date' => $month]);

        if($where) {
            $query->andWhere($where);
        }
        
        $result = $query->one();
        
        return array_map('intval', $result);
    }

    public function getStatByDate($date, $where = null, $where = null)
    {
        $order = $this->order;
        
        $query = new Query();
        $query->addSelect(['sum(cost) as total, sum(count) as count_elements, COUNT(DISTINCT id) as count_orders'])
                ->from([$order::tableName()])
                ->where('DATE_FORMAT(date, "%Y-%m-%d") = :date', [':date' => $date]);

        if($where) {
            $query->andWhere($where);
        }
        
        $result = $query->one();
        
        return array_map('intval', $result);
    }
    
    public function getStatByDatePeriod($dateStart, $dateStop = null, $where = null)
    {
        if($dateStop == '0000-00-00 00:00:00' | empty($dateStop)) {
            $dateStop = date('Y-m-d H:i:s');
        }

        $order = $this->order;
        
        $query = new Query();
        $query->addSelect(['sum(cost) as total, sum(count) as count_elements, COUNT(DISTINCT id) as count_orders'])
                ->from([$order::tableName()])
                ->where('date >= :dateStart', [':dateStart' => $dateStart])
                ->andWhere('date <= :dateStop', [':dateStop' => $dateStop]);

        if($where) {
            $query->andWhere($where);
        }
        
        $result = $query->one();
        
        return array_map('intval', $result);
    }

    public function getStatByModelAndDatePeriod($model, $dateStart, $dateStop, $where = null)
    {
        if($dateStop == '0000-00-00 00:00:00' | empty($dateStop)) {
            $dateStop = date('Y-m-d H:i:s');
        }
        
        $order = $this->order;
        $element = $this->element;
        
        $query = new Query();
        $query->addSelect(['sum(e.count*e.price) as total, sum(e.count) as count_elements, COUNT(DISTINCT order_id) as count_order'])
                ->from ([$element::tableName().' e'])
                ->leftJoin($order::tableName().' o','o.id = e.order_id')
                ->where('o.date >= :dateStart', [':dateStart' => $dateStart])
                ->andWhere('o.date <= :dateStop', [':dateStop' => $dateStop])
                ->andWhere(['e.model' => $model]);

        if($where) {
            $query->andWhere($where);
        }
        
        $result = $query->one();
        
        
        
        return array_map('intval', $result);
    }

    public function setStatus($orderId, $status)
    {
        if ($orderId && $status) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand()->update('order', ['status' => $status], "id = $orderId");
            return $result = $command->execute();
        }
    }
}
