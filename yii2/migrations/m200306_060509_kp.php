<?php

use yii\db\Migration;

/**
 * Class m200306_060509_kp
 */
class m200306_060509_kp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('kp', [
            
            'id' => $this->primaryKey(),
            'startdate' => $this->dateTime()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'enddate' => $this->dateTime(),
            'clients_id' => $this->integer()->notNull(),
            'state' => $this->integer()->notNull(),
            'manager_id' => $this->integer(),
            'application_id' => $this->integer()->notNull(),
            'client_name' => $this->text(),
            'total' => $this->decimal(),
            'our_cost' => $this->decimal(),
            'source_id' => $this->integer()->notNull(),
            'contactdate' => $this->dateTime(),
            'declinematter_id' => $this->integer(),
            'doc_on_hand' => $this->text(),
            'is_lpr' => $this->integer(),
            'another_offer' => $this->text(),
            'doc_for_what' => $this->text(),
            'comment' => $this->text(),

        ]);

        $this->createIndex('kp_account_id_idx','kp', 'account_id');
        $this->createIndex('kp_startdate_idx','kp', 'startdate');
        $this->createIndex('kp_enddate_idx','kp', 'enddate');
        $this->createIndex('kp_clients_id_idx','kp', 'clients_id');
        $this->createIndex('kp_state_idx','kp', 'state');
        $this->createIndex('kp_manager_id_idx','kp', 'manager_id');
        $this->createIndex('kp_application_id_idx','kp', 'application_id');
        $this->createIndex('kp_total_idx','kp', 'total');
        $this->createIndex('kp_our_cost_idx','kp', 'our_cost');
        $this->createIndex('kp_source_id_idx','kp', 'source_id');
        $this->createIndex('kp_contactdate_idx','kp', 'contactdate');
        $this->createIndex('kp_declinematter_id_idx','kp', 'declinematter_id');
        $this->createIndex('kp_is_lpr_idx','kp', 'is_lpr');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('kp');
    }

    
}
