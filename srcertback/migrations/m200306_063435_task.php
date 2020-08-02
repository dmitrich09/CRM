<?php

use yii\db\Migration;

/**
 * Class m200306_063435_task
 */
class m200306_063435_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'manager_id' => $this->integer()->notNull(),
            'task_date' => $this->dateTime()->notNull(),
            'comment' => $this->text(),
            'description' => $this->text()->notNull(),
            'status_id' => $this->integer()->notNull(),
            

        ]);

        $this->createIndex('task_account_id_idx','task', 'account_id');
        $this->createIndex('task_user_id_idx','task', 'user_id');
        $this->createIndex('task_manager_id_idx','task', 'manager_id');
        $this->createIndex('task_task_date_idx','task', 'task_date');
        $this->createIndex('task_status_id_idx','task', 'status_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }

    
}
