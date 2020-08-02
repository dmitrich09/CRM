<?php

use yii\db\Migration;

/**
 * Class m200305_061252_provider
 */
class m200305_061252_provider extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('provider', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'email' => $this->text()->notNull(),
            'lawadress' => $this->text(),
            'postadress' => $this->text(),
            'adress' => $this->text(),
            'contact' => $this->text(),
            'phone' => $this->text(),
        ]);

        $this->createIndex('provider_account_id_idx','provider', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('provider');
    }

} 