<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;


class MoveBusinessForm extends Model{
    public $fromUser;
	public $toUser;
    public $clientCount;
    public $callsCount;
    public $leadCount;
    public $kpCount;
    public $applicationCount;
    public $agreementCount;
    public $orkCount;
  
	
    public function rules(){
        return [
			[['fromUser','toUser','clientCount','callsCount','leadCount',
              'kpCount','applicationCount','agreementCount','orkCount','orkOrkCount'], 'trim'], 
        ]; 
    }
}