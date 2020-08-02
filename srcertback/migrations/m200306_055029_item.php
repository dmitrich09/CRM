<?php

use yii\db\Migration;

/**
 * Class m200306_055029_item
 */
class m200306_055029_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('item', [
            
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'account_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'cost' => $this->decimal(),
            'discount' => $this->text(),
            'total' =>  $this->decimal()->notNull(),
            'our_cost' =>  $this->decimal(),
            'clients_id' => $this->integer()->notNull(),
            'nameproduct' => $this->text()->notNull(),
            'typemarkmodel' => $this->text(),
            'days' => $this->integer(),
            'timeline' => $this->integer(),
            'application_id' => $this->integer()->notNull(),
            'pionhand' => $this->integer()->notNull(),
            'control' => $this->text(),
            'provider_id' => $this->integer(),
            'sert_num' => $this->text(),
            'get_date' => $this->dateTime(),
            'license_to' => $this->dateTime(),
            'act_organ' => $this->text(),
            'is_lead' => $this->integer(),


        ]);

        $this->createIndex('item_account_id_idx','item', 'account_id');
        $this->createIndex('item_document_id_idx','item', 'document_id');
        $this->createIndex('item_quantity_idx','item', 'quantity');
        $this->createIndex('item_cost_idx','item', 'cost');
        $this->createIndex('item_total_idx','item', 'total');
        $this->createIndex('item_our_cost_idx','item', 'our_cost');
        $this->createIndex('item_clients_id_idx','item', 'clients_id');
        $this->createIndex('item_days_idx','item', 'days');
        $this->createIndex('item_timeline_idx','item', 'timeline');
        $this->createIndex('item_application_id_idx','item', 'application_id');
        $this->createIndex('item_pionhand_idx','item', 'pionhand');
        $this->createIndex('item_provider_id_idx','item', 'provider_id');
        $this->createIndex('item_get_date_idx','item', 'get_date');
        $this->createIndex('item_license_to_idx','item', 'license_to');
        $this->createIndex('item_is_lead_idx','item', 'is_lead');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('item');
    }

}
