<?php

use yii\db\Migration;

/**
 * Class m200306_053944_application
 */
class m200306_053944_application extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('application', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'source_id' => $this->integer(),
            'nameproduct' => $this->integer()->notNull(),
            'okp' => $this->integer(),
            'okpd2' => $this->integer(),
            'tnved' => $this->integer(),
            'startdate' => $this->dateTime()->notNull(),
            'enddate' => $this->dateTime(),
            'lead_id' =>  $this->integer()->notNull(),
            'manager_id' =>  $this->integer()->notNull(),
            'contactdate' =>  $this->dateTime()->notNull(),
            'state' => $this->integer(),
            'field' => $this->text(),
            'countrymade' => $this->text(),
            'countryask' => $this->text(),
            'comment' => $this->text(),
            'exittoclient' => $this->integer()->notNull(),
            'test' => $this->integer()->notNull(),
            'total' => $this->decimal()->notNull(),
            'our_cost' => $this->decimal()->notNull(),
            'documentprod' => $this->text()->notNull(),
            'showed' => $this->integer(),
            'countmanager_id' => $this->integer(),
            'declinematter_id' => $this->integer(),
            'doc_on_hand' => $this->text(),
            'is_lpr' => $this->integer(),
            'another_offer' => $this->text(),
            'doc_for_what' => $this->text(),

        ]);

        $this->createIndex('application_account_id_idx','application', 'account_id');
        $this->createIndex('application_clients_id_idx','application', 'clients_id');
        $this->createIndex('application_source_id_idx','application', 'source_id');
        $this->createIndex('application_nameproduct_idx','application', 'nameproduct');
        $this->createIndex('application_okp_idx','application', 'okp');
        $this->createIndex('application_okpd2_idx','application', 'okpd2');
        $this->createIndex('application_tnved_idx','application', 'tnved');
        $this->createIndex('application_startdate_idx','application', 'startdate');
        $this->createIndex('application_enddate_idx','application', 'enddate');
        $this->createIndex('application_lead_id_idx','application', 'lead_id');
        $this->createIndex('application_manager_id_idx','application', 'manager_id');
        $this->createIndex('application_contactdate_idx','application', 'contactdate');
        $this->createIndex('application_state_idx','application', 'state');
        $this->createIndex('application_exittoclient_idx','application', 'exittoclient');
        $this->createIndex('application_test_idx','application', 'test');
        $this->createIndex('application_total_idx','application', 'total');
        $this->createIndex('application_our_cost_idx','application', 'our_cost');
        $this->createIndex('application_showed_idx','application', 'showed');
        $this->createIndex('application_countmanager_id_idx','application', 'countmanager_id');
        $this->createIndex('application_declinematter_id_idx','application', 'declinematter_id');
        $this->createIndex('application_is_lpr_idx','application', 'is_lpr');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('application');
    }

}
