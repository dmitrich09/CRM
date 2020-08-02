<?php

use yii\db\Migration;

/**
 * Class m200305_063119_planned
 */
class m200305_063119_planned extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('planned', [
            'id' => $this->primaryKey(),
            'planned_date' => $this->dateTime()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'calls' => $this->integer()->notNull(),
            'leads' => $this->integer()->notNull(),
            'applications' => $this->integer()->notNull(),
            'kps' => $this->integer()->notNull(),
            'agreements' => $this->integer()->notNull(),
            'pays' => $this->decimal()->notNull(),
            'marges' => $this->decimal()->notNull(),
        ]);

        $this->createIndex('planned_account_id_idx','planned', 'account_id');
        $this->createIndex('planned_planned_date_idx','planned', 'planned_date');
        $this->createIndex('planned_calls_idx','planned', 'calls');
        $this->createIndex('planned_leads_idx','planned', 'leads');
        $this->createIndex('planned_applications_idx','planned', 'applications');
        $this->createIndex('planned_kps_idx','planned', 'kps');
        $this->createIndex('planned_agreements_idx','planned', 'agreements');
        $this->createIndex('planned_pays_idx','planned', 'pays');
        $this->createIndex('planned_marges_idx','planned', 'marges');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('planned');
    }

    
}
