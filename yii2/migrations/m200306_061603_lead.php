<?php

use yii\db\Migration;

/**
 * Class m200306_061603_lead
 */
class m200306_061603_lead extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('lead', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'interesttype' => $this->text(),
            'state' => $this->integer()->notNull(),
            'source_id' => $this->integer(),
            'comment' => $this->text(),
            'manager' => $this->integer()->notNull(),
            'startdate' => $this->dateTime()->notNull(),
            'enddate' => $this->dateTime(),
            'contactdate' => $this->dateTime()->notNull(),
            'declinematter_id' => $this->integer(),
            

        ]);

        $this->createIndex('lead_account_id_idx','lead', 'account_id');
        $this->createIndex('lead_clients_id_idx','lead', 'clients_id');
        $this->createIndex('lead_state_idx','lead', 'state');
        $this->createIndex('lead_source_id_idx','lead', 'source_id');
        $this->createIndex('lead_manager_idx','lead', 'manager');
        $this->createIndex('lead_startdate_idx','lead', 'startdate');
        $this->createIndex('lead_enddate_idx','lead', 'enddate');
        $this->createIndex('lead_contactdate_idx','lead', 'contactdate');
        $this->createIndex('lead_declinematter_id_idx','lead', 'declinematter_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('lead');
    }

    
}
