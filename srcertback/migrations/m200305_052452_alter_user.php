<?php

use yii\db\Migration;

/**
 * Class m200305_052452_alter_user
 */
class m200305_052452_alter_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'caller_id', $this->text());
        $this->addColumn('user', 'tocall', $this->integer());
        $this->addColumn('user', 'mobile', $this->text());
        $this->addColumn('user', 'position', $this->text());
        $this->addColumn('user', 'postpass', $this->text());
        $this->addColumn('user', 'is_dir', $this->integer());
        $this->addColumn('user', 'is_ork', $this->integer());
        $this->addColumn('user', 'is_man', $this->integer());
        $this->addColumn('user', 'by_ip', $this->integer());
        $this->addColumn('user', 'is_logout', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'caller_id');
        $this->dropColumn('user', 'tocall');
        $this->dropColumn('user', 'mobile');
        $this->dropColumn('user', 'position');
        $this->dropColumn('user', 'postpass');
        $this->dropColumn('user', 'is_dir');
        $this->dropColumn('user', 'is_ork');
        $this->dropColumn('user', 'is_man');
        $this->dropColumn('user', 'by_ip');
        $this->dropColumn('user', 'is_logout');
    }

}
