<?php

use yii\db\Migration;

/**
 * Class m200306_062354_ork
 */
class m200306_062354_ork extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ork', [
            
            'id' => $this->primaryKey(),
            'agreement_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'startdate' => $this->dateTime()->notNull(),
            'enddate' => $this->dateTime(),
            'state' => $this->integer()->notNull(),
            'signagree' => $this->integer()->notNull(),
            'signact' => $this->integer()->notNull(),
            'clients_id' => $this->integer()->notNull(),
            'manager_id' => $this->integer()->notNull(),
            'contactdate' => $this->dateTime()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'pay_date' => $this->dateTime(),
            'provider_debt' => $this->decimal(),
            'clientdoc_date' => $this->dateTime(),
            'close_date' => $this->dateTime(),
            'declinematter_id' => $this->integer(),
            'comment' => $this->text(),
            'sert_num' => $this->text(),
            'get_date' => $this->dateTime(),
            'license_to' => $this->dateTime(),
            'act_num' => $this->text(),
            'act_organ' => $this->text(),

        ]);

        $this->createIndex('ork_account_id_idx','ork', 'account_id');
        $this->createIndex('ork_agreement_id_idx','ork', 'agreement_id');
        $this->createIndex('ork_startdate_idx','ork', 'startdate');
        $this->createIndex('ork_enddate_idx','ork', 'enddate');
        $this->createIndex('ork_state_idx','ork', 'state');
        $this->createIndex('ork_signagree_idx','ork', 'signagree');
        $this->createIndex('ork_signact_idx','ork', 'signact');
        $this->createIndex('ork_clients_id_idx','ork', 'clients_id');
        $this->createIndex('ork_manager_id_idx','ork', 'manager_id');
        $this->createIndex('ork_contactdate_idx','ork', 'contactdate');
        $this->createIndex('ork_source_id_idx','ork', 'source_id');
        $this->createIndex('ork_pay_date_idx','ork', 'pay_date');
        $this->createIndex('ork_provider_debt_idx','ork', 'provider_debt');
        $this->createIndex('ork_clientdoc_date_idx','ork', 'clientdoc_date');
        $this->createIndex('ork_close_date_idx','ork', 'close_date');
        $this->createIndex('ork_declinematter_id_idx','ork', 'declinematter_id');
        $this->createIndex('ork_get_date_idx','ork', 'get_date');
        $this->createIndex('ork_license_to_idx','ork', 'license_to');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ork');
    }

}
