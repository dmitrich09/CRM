<?php

use yii\db\Migration;


class m200619_074157_change_clients_manager extends Migration
{
   
    public function safeUp()
    {
        Yii::$app->db->createCommand('ALTER TABLE "clients" ALTER COLUMN "manager" TYPE integer USING (manager::integer), ALTER COLUMN "manager" SET DEFAULT NULL, ALTER COLUMN "manager" DROP NOT NULL ')->execute();		 
    }

  
    public function safeDown()
    {
        Yii::$app->db->createCommand('ALTER TABLE "clients" ALTER COLUMN "manager" TYPE varchar(255) USING (manager::integer), ALTER COLUMN "manager" SET DEFAULT NULL, ALTER COLUMN "manager" DROP NOT NULL ')->execute();		 
    } 
    

  
}
