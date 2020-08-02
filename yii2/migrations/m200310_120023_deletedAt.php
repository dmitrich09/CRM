<?php

use yii\db\Migration;

/**
 * Class m200310_120023_deletedAt
 */
class m200310_120023_deletedAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agreement', 'deleted_at', $this->datetime());
        $this->addColumn('application', 'deleted_at', $this->datetime());
        $this->addColumn('calls', 'deleted_at', $this->datetime());
        $this->addColumn('city', 'deleted_at', $this->datetime());
        $this->addColumn('clients', 'deleted_at', $this->datetime());
        $this->addColumn('comments', 'deleted_at', $this->datetime());
        $this->addColumn('contacts', 'deleted_at', $this->datetime());
        $this->addColumn('contacttype', 'deleted_at', $this->datetime());
        $this->addColumn('declinematter', 'deleted_at', $this->datetime());
        $this->addColumn('document', 'deleted_at', $this->datetime());
        $this->addColumn('files', 'deleted_at', $this->datetime());
        $this->addColumn('item', 'deleted_at', $this->datetime());
        $this->addColumn('kp', 'deleted_at', $this->datetime());
        $this->addColumn('lead', 'deleted_at', $this->datetime());
        $this->addColumn('ork', 'deleted_at', $this->datetime());
        $this->addColumn('outcall', 'deleted_at', $this->datetime());
        $this->addColumn('payment', 'deleted_at', $this->datetime());
        $this->addColumn('planned', 'deleted_at', $this->datetime());
        $this->addColumn('provider', 'deleted_at', $this->datetime());
        $this->addColumn('settings', 'deleted_at', $this->datetime());
        $this->addColumn('source', 'deleted_at', $this->datetime());
        $this->addColumn('task', 'deleted_at', $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      
        $this->dropColumn('agreement', 'deleted_at');
        $this->dropColumn('application', 'deleted_at');
        $this->dropColumn('calls', 'deleted_at');
        $this->dropColumn('city', 'deleted_at');
        $this->dropColumn('clients', 'deleted_at');
        $this->dropColumn('comments', 'deleted_at');
        $this->dropColumn('contacts', 'deleted_at');
        $this->dropColumn('contacttype', 'deleted_at');
        $this->dropColumn('declinematter', 'deleted_at');
        $this->dropColumn('document', 'deleted_at');
        $this->dropColumn('files', 'deleted_at');
        $this->dropColumn('item', 'deleted_at');
        $this->dropColumn('kp', 'deleted_at');
        $this->dropColumn('lead', 'deleted_at');
        $this->dropColumn('ork', 'deleted_at');
        $this->dropColumn('outcall', 'deleted_at');
        $this->dropColumn('payment', 'deleted_at');
        $this->dropColumn('planned', 'deleted_at');
        $this->dropColumn('provider', 'deleted_at');
        $this->dropColumn('settings', 'deleted_at');
        $this->dropColumn('source', 'deleted_at');
        $this->dropColumn('task', 'deleted_at');
    }


}
