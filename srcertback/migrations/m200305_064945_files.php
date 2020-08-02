<?php

use yii\db\Migration;

/**
 * Class m200305_064945_files
 */
class m200305_064945_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('files', [
            
            'id' => $this->primaryKey(),
            'clients_id' => $this->integer(),
            'account_id' => $this->integer()->notNull(),
            'rusname' => $this->text()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'insert_date' => $this->dateTime()->notNull(),
            'application_id' => $this->integer(),
            'type' => $this->integer(),
            'parent_id' => $this->integer(),
        ]);

        $this->createIndex('files_account_id_idx','files', 'account_id');
        $this->createIndex('files_clients_id_idx','files', 'clients_id');
        $this->createIndex('files_user_id_idx','files', 'user_id');
        $this->createIndex('files_insert_date_idx','files', 'insert_date');
        $this->createIndex('files_application_id_idx','files', 'application_id');
        $this->createIndex('files_type_idx','files', 'type');
        $this->createIndex('files_parent_id_idx','files', 'parent_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('files');
    }

   
}
