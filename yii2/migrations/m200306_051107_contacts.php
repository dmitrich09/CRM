<?php

use yii\db\Migration;

/**
 * Class m200306_051107_contacts
 */
class m200306_051107_contacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contacts', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'contacttype_id' => $this->integer(),
            'name' => $this->text(),
            'phone' => $this->text(),
            'email' => $this->text(),
            'skype' => $this->text(),
            'comment' => $this->text(),

        ]);

        $this->createIndex('contacts_account_id_idx','contacts', 'account_id');
        $this->createIndex('contacts_clients_id_idx','contacts', 'clients_id');
        $this->createIndex('contacts_contacttype_id_idx','contacts', 'contacttype_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('contacts');
    }

    
}
