<?php

use yii\db\Migration;

/**
 * Class m200305_104826_contacttype
 */
class m200305_104826_contacttype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contacttype', [
            
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            
        ]);

        $this->createIndex('contacttype_account_id_idx','contacttype', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('contacttype');
    }

    
}
