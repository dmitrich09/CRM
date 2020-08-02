<?php

use yii\db\Migration;

/**
 * Class m200503_100349_alt_col_appls_nameprod
 */
class m200503_100349_alt_col_appls_nameprod extends Migration
{
   
    public function safeUp()
    {
        $this->alterColumn('application', 'nameproduct', $this->integer() );
		$this->alterColumn('application', 'tnved', $this->string() );
    }

  
    public function safeDown()
    {
        $this->alterColumn('application', 'nameproduct', $this->integer()->notNull() );
        $this->alterColumn('application', 'tnved', $this->integer() );
    }


}
