<?php

use yii\db\Migration;


class m200430_093353_alter_sphere_col extends Migration
{
        /**
     * {@inheritdoc}
     */
    public function safeUp()
    {		
        $this->dropColumn('sphere', 'name'); 
		$this->addColumn('sphere', 'spherename', $this->string() ); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sphere', 'spherename' ); 
		 $this->addColumn('sphere', 'name', $this->string() );         
    }
 
}
