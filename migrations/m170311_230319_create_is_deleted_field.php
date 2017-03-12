<?php

use yii\db\Migration;

class m170311_230319_create_is_deleted_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'is_deleted', $this->boolean());
        $this->addColumn('{{%order_element}}', 'is_deleted', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'is_deleted');
        $this->dropColumn('{{%order_element}}', 'is_deleted');
        
        return true;
    }
}
