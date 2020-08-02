<?php

use yii\db\Migration;

/**
 * Class m200305_105715_clients
 */
class m200305_105715_clients extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('clients', [
            
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'clienttype_id' => $this->integer(),
            'sphere_id' => $this->integer(),
            'manager' => $this->text(),
            'city_id' => $this->integer(),
            'inn' => $this->text(),
            'ogrn' => $this->text(),
            'kpp' => $this->text(),
            'okpo' => $this->text(),
            'uraddress' => $this->text(),
            'factaddress' => $this->text(),
            'postaddress' => $this->text(),
            'bank' => $this->text(),
            'bik' => $this->integer(),
            'rs' => $this->text(),
            'ks' => $this->text(),
            'unique_id' => $this->text()->notNull(),
            'full_name' => $this->text(),
            'site' => $this->text(),
            'abc_analize' => $this->integer(),

        ]);

        $this->createIndex('clients_unique_id_uidx','clients', 'unique_id', true);
        $this->createIndex('clients_account_id_idx','clients', 'account_id');
        $this->createIndex('clients_clienttype_id_idx','clients', 'clienttype_id');
        $this->createIndex('clients_sphere_id_idx','clients', 'sphere_id');
        $this->createIndex('clients_city_id','clients', 'city_id');
        $this->createIndex('clients_bik','clients', 'bik');
        $this->createIndex('clients_abc_analize','clients', 'abc_analize');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('clients');
    }

}
