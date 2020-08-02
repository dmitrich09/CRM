<?php

use yii\db\Migration;

/**
 * Class m200305_104436_city
 */
class m200305_104436_city extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('city', [
            
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('city_account_id_idx','city', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('city');
    }

    
}
