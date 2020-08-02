<?php

use yii\db\Migration;

/**
 * Class m200305_060538_source
 */
class m200305_060538_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('source', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('source_account_id_idx','source', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('source');
    }

    
}
