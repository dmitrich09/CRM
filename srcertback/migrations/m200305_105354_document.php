<?php

use yii\db\Migration;

/**
 * Class m200305_105354_document
 */
class m200305_105354_document extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('document', [
            
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'fullname' => $this->text(),
            'for_doc' => $this->text(),
            'is_include' => $this->integer()->notNull(),
            'is_control' => $this->integer()->notNull(),
        ]);

        $this->createIndex('document_account_id_idx','document', 'account_id');
        $this->createIndex('document_is_include_idx','document', 'is_include');
        $this->createIndex('document_is_control_idx','document', 'is_control');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('document');
    }

    
}
