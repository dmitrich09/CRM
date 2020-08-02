<?php

use yii\db\Migration;

/**
 * Class m200305_111012_comments
 */
class m200305_111012_comments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'object_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'adddate' => $this->dateTime()->notNull(),
            
        ]);

        $this->createIndex('comments_account_id_idx','comments', 'account_id');
        $this->createIndex('comments_object_id_idx','comments', 'object_id');
        $this->createIndex('comments_type_idx','comments', 'type');
        $this->createIndex('comments_user_id_idx','comments', 'user_id');
        $this->createIndex('comments_adddate_idx','comments', 'adddate');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comments');
    }

  
}
