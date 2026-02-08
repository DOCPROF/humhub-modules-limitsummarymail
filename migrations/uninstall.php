<?php

use yii\db\Migration;

class uninstall extends Migration
{
    public function up()
    {
        $this->delete('setting', ['module_id' => 'limitsummarymail']);
    }

    public function down()
    {
        echo "Uninstall migration cannot be reverted.\n";
        return false;
    }
}
