<?php

use yii\db\Migration;

/**
 * Class m200305_054628_sphere
 */
class m200305_054628_sphere extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sphere', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('sphere_account_id_idx','sphere', 'account_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('sphere');
    }

}
