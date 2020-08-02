<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;


class FunnelReport extends Model{
    public $from;
	public $to;
	public $group; 
	
    public function rules(){
        return [
			[['from','to','group'], 'default','value'=>''], 
        ]; 
    }
	 
	
	public function setDefault(){

		if($this->from==null){
			$this->from=date('d.m.Y 00:00:00');
		}
		if($this->to==null){
			$this->to=date('d.m.Y 23:59:59');
		}
		return $this;
	}
	
	public function getFromInMysql(){
		if($this->from!=null){ 
			return date('Y-m-d 00:00:00',strtotime($this->from));
		}
	}
	public function getToInMysql(){
		if($this->to!=null){ 
			return date('Y-m-d 23:59:59',strtotime($this->to));
		}
	}
	
}	
?>