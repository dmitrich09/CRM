<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;


class Callreport extends Model{
    public $from;
	public $to;
	public $user_id;
	public $type=null;
	public $isactive=null;
	public $okp=null;
	public $okpd=null;
	public $tnvd=null;
	public $number=null;
	public $id;
	public $typecold;
	
    public function rules(){
        return [
			[['id','okp','okpd','tnvd','isactive','from','to','user_id','type','number','typecold'], 'default','value'=>''], 
        ]; 
    }
	 
	
	public function setDefault(){

		if($this->from==null){
			$this->from=date('01.m.Y 00:00:00');
		}
		if($this->to==null){
			$this->to=date('d.m.Y 23:59:59');
		}
		return $this;
	}
	
	public function getFromInMysql(){
		if($this->from!=null){ 
			return date('Y-m-d H:i:s',strtotime($this->from));
		}
	}
	public function getToInMysql(){
		if($this->to!=null){ 
			return date('Y-m-d 23:59:59',strtotime($this->to));
		}
	}
	
}	
?>