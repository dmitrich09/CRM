<?php

use yii\db\Migration;

/**
 * Class m200306_055836_calls
 */
class m200306_055836_calls extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('calls', [
            
            'id' => $this->primaryKey(),
            'zad_call_id' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'sip' => $this->text()->notNull(),
            'callstart' => $this->dateTime()->notNull(),
            'clid' => $this->text()->notNull(),
            'destination' => $this->text()->notNull(),
            'disposition' => $this->text()->notNull(),
            'seconds' => $this->text()->notNull(),
            'is_recorded' => $this->text()->notNull(),
            'clients_id' => $this->integer(),
            'user_id' => $this->integer(),
            'incoming' => $this->integer()->notNull(),
            'is_file' => $this->integer(),
            'is_warm' => $this->integer(),

        ]);

        $this->createIndex('calls_account_id_idx','calls', 'account_id');
        $this->createIndex('calls_callstart_idx','calls', 'callstart');
        $this->createIndex('calls_clients_id_idx','calls', 'clients_id');
        $this->createIndex('calls_user_id_idx','calls', 'user_id');
        $this->createIndex('calls_incoming_idx','calls', 'incoming');
        $this->createIndex('calls_is_file_idx','calls', 'is_file');
        $this->createIndex('calls_is_warm_idx','calls', 'is_warm');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('calls');
    }

   
}
