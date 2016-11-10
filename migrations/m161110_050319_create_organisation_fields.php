<?php

use yii\db\Migration;

class m161110_050319_create_organisation_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%order}}', 'organisation_id', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%order}}', 'organisation_id');
        
        return true;
    }
}
