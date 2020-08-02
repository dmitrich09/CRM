<?php

use yii\db\Migration;

/**
 * Class m200310_111712_alter_logs
 */
class m200310_111712_alter_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('logs', 'type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('logs', 'type_id');
    }

}
