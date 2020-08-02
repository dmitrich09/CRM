<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;


class Getdocreport extends Model{
    public $name;
	public $agreenum;
	public $product;
	public $manager_id=null;
	public $actnum=null;
	public $from=null;
	public $to=null;
	public $organ=null;
	public $sertnum=null;
	
	const INSTANCE_CURRENT=10;
	
    public function rules(){
        return [
			[['name','agreenum','product','manager_id','actnum'
			,'from','to','organ','sertnum'], 'default','value'=>''], 
        ]; 
    }
	 
	public static function getInstance($instance){
		if($instance=self::INSTANCE_CURRENT){
				return self::getInstanceCurrent();
		}
		return new Getdocreport();
		
	}	
	
	private static function getInstanceCurrent(){
		$rp= new Getdocreport();
		if($rp->from==null){
			$rp->from=date('01.m.Y 00:00:00');
		}
		if($rp->to==null){
			$rp->to=date('d.m.Y 23:59:59');
		}
		return $rp;
	}
		
	
	public function getFromInMysql(){
		if($this->from!=null){ 
			return date('Y-m-d H:i:s',strtotime($this->from));
		}
	}
	public function getToInMysql(){
		if($this->to!=null){ 
			return date('Y-m-d H:i:s',strtotime($this->to));
		}
	}
	
}	
?>