<?php

use yii\db\Migration;

/**
 * Class m200305_064036_payment
 */
class m200305_064036_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'payment_date' => $this->dateTime()->notNull(),
            'user_id' => $this->integer(),
            'amount' => $this->decimal()->notNull(),
            'agreement_id' => $this->integer(),
            'pay_number' => $this->text(),
            'source_id' => $this->integer(),
        ]);

        $this->createIndex('payment_account_id_idx','payment', 'account_id');
        $this->createIndex('payment_clients_id_idx','payment', 'clients_id');
        $this->createIndex('payment_payment_date_idx','payment', 'payment_date');
        $this->createIndex('payment_user_id_idx','payment', 'user_id');
        $this->createIndex('payment_amount_idx','payment', 'amount');
        $this->createIndex('payment_agreement_id_idx','payment', 'agreement_id');
        $this->createIndex('payment_source_id_idx','payment', 'source_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment');
    }

    
}
