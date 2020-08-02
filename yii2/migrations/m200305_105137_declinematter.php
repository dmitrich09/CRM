<?php

use yii\db\Migration;

/**
 * Class m200305_105137_declinematter
 */
class m200305_105137_declinematter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('declinematter', [
            
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('declinematter_account_id_idx','declinematter', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('declinematter');
    }

   
}
