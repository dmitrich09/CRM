<?php

use yii\db\Migration;

/**
 * Class m200306_063044_outcall
 */
class m200306_063044_outcall extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('outcall', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'contactdate' => $this->dateTime()->notNull(),
            'comment' => $this->text(),
            'status' => $this->integer()->notNull(),
            'showed' => $this->integer(),
            'enddate' => $this->dateTime(),
            'startdate' => $this->dateTime()->notNull(),
            'declinematter_id' => $this->integer(),

        ]);

        $this->createIndex('outcall_account_id_idx','outcall', 'account_id');
        $this->createIndex('outcall_clients_id_idx','outcall', 'clients_id');
        $this->createIndex('outcall_user_id_idx','outcall', 'user_id');
        $this->createIndex('outcall_contactdate_idx','outcall', 'contactdate');
        $this->createIndex('outcall_status_idx','outcall', 'status');
        $this->createIndex('outcall_showed_idx','outcall', 'showed');
        $this->createIndex('outcall_enddate_idx','outcall', 'enddate');
        $this->createIndex('outcall_startdate_idx','outcall', 'startdate');
        $this->createIndex('outcall_declinematter_id_idx','outcall', 'declinematter_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('outcall');
    }

    
}
