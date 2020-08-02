<?php

use yii\db\Migration;

/**
 * Class m200310_113112_alter_sphere
 */
class m200310_113112_alter_sphere extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sphere', 'deleted_at', $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sphere', 'deleted_at');
    }

   
}
