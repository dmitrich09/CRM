<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;


class Report extends Model{
	
	const INSTANCE_CURRENT=10;
	
	const TYPE_CALL=10;
	const TYPE_LEAD=20;
	const TYPE_APPLICATION=30;
	const TYPE_KP=40;
	const TYPE_AGREEMENT=50;
	const TYPE_ORK=60;
	
	
    public $from;
	public $to;
	public $source_id;
	public $user_id=null;
	public $type_id;
	
    public function rules(){
        return [
			[['from','to','user_id','type_id'], 'default','value'=>''], 
        ]; 
    }
	 
	public static function getInstance($instance){
		if($instance=self::INSTANCE_CURRENT){
				return self::getInstanceCurrent();
		}
		return new Report();
		
	}	
	
	public function getUsers(){
		if(is_array($this->user_id)){
			if(count($this->user_id)==1){
				return $this->user_id[0];
			}
		}
		return $this->user_id;
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
	
	private static function getInstanceCurrent(){
		$rp= new Report();
		if($rp->from==null){
			$rp->from=date('01.m.Y 00:00:00');
		}
		if($rp->to==null){
			$rp->to=date('d.m.Y 23:59:59');
		}
		return $rp;
	}
	
	public function setFromGet($arr){
		(isset($arr['user_id'])?$this->user_id=$arr['user_id']:null);
		((isset($arr['source_id'])&&$arr['source_id']!='all')?$this->source_id=$arr['source_id']:null);
		(isset($arr['from'])?$this->from=$arr['from']:null);
		(isset($arr['to'])?$this->to=$arr['to']:null);
		
		if($this->from!=null&&mb_strlen($this->from)==7){
			$this->from='01.'.$this->from;
		}
		if($this->to!=null&&mb_strlen($this->to)==7){
			$dt=new \DateTime($this->from);
			$dt->add(new \DateInterval('P1M'));
			$dt->sub(new \DateInterval('P1D'));
			$this->to=$dt->format('d.m.Y');
		}
		return $this;
	}
	
	
	public static function getTypes(){
		return [self::TYPE_CALL=>'Звонок',
				self::TYPE_LEAD =>'ЭК',
				self::TYPE_APPLICATION =>'Заявка',
				self::TYPE_KP=>'КП',
				self::TYPE_AGREEMENT=>'Договор',
				self::TYPE_ORK=>'ОРК',
				];
	}
	
}	
?>