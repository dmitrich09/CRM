<?php

use yii\db\Migration;

/**
 * Class m200306_052815_agreement
 */
class m200306_052815_agreement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('agreement', [
            
            'id' => $this->primaryKey(),
            'numberagree' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'agreedate' => $this->dateTime()->notNull(),
            'startdate' => $this->dateTime()->notNull(),
            'enddate' => $this->dateTime(),
            'clients_id' => $this->integer()->notNull(),
            'person' => $this->text(),
            'manager_id' => $this->integer()->notNull(),
            'kp_id' => $this->integer()->notNull(),
            'state' => $this->integer()->notNull(),
            'tax' => $this->integer()->notNull(),
            'total' => $this->decimal(),
            'our_cost' => $this->decimal(),
            'source_id' => $this->integer()->notNull(),
            'debt' => $this->decimal(),
            'contactdate' => $this->dateTime(),
            'short_person' => $this->text(),
            'basement' => $this->text(),
            'declinematter_id' => $this->integer(),
            'doc_on_hand' => $this->text(),
            'is_lpr' => $this->integer(),
            'another_offer' => $this->text(),
            'doc_for_what' => $this->text(),
            'options' => $this->text(),
            'application_id' => $this->integer(),
            'comment' => $this->text(),

        ]);

        $this->createIndex('agreement_account_id_idx','agreement', 'account_id');
        $this->createIndex('agreement_numberagree_idx','agreement', 'numberagree');
        $this->createIndex('agreement_agreedate_idx','agreement', 'agreedate');
        $this->createIndex('agreement_startdate_idx','agreement', 'startdate');
        $this->createIndex('agreement_enddate_idx','agreement', 'enddate');
        $this->createIndex('agreement_clients_id_idx','agreement', 'clients_id');
        $this->createIndex('agreement_manager_id_idx','agreement', 'manager_id');
        $this->createIndex('agreement_kp_id_idx','agreement', 'kp_id');
        $this->createIndex('agreement_state_idx','agreement', 'state');
        $this->createIndex('agreement_tax_idx','agreement', 'tax');
        $this->createIndex('agreement_total_idx','agreement', 'total');
        $this->createIndex('agreement_our_cost_idx','agreement', 'our_cost');
        $this->createIndex('agreement_source_id_idx','agreement', 'source_id');
        $this->createIndex('agreement_debt_idx','agreement', 'debt');
        $this->createIndex('agreement_contactdate_idx','agreement', 'contactdate');
        $this->createIndex('agreement_declinematter_id_idx','agreement', 'declinematter_id');
        $this->createIndex('agreement_is_lpr_idx','agreement', 'is_lpr');
        $this->createIndex('agreement_application_id_idx','agreement', 'application_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('agreement');
    }

    
}
