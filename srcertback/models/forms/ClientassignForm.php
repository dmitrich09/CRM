<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use budyaga\users\models\User;


class ClientassignForm extends Model{
    public $from;
	public $city_id;
	public $sphere_id;
	public $clienttype_id;
	public $user_id;	
	public $amount;
	public $isassign;
	public static $currentUser;
	
    public function rules(){
        return [
			[['from'], 'required','message'=>'Передайте обязательный параметр'], 
			[['from','sphere_id','city_id','clienttype_id','user_id','amount','isassign'], 'filter','filter'=>'trim'], 
        ]; 
    }
	 
	
	public function setDefault(){

		if($this->from==null){
			$this->from=date('01.m.Y 00:00:00');
		}
	
		return $this;
	}
	
	public function getFromInMysql(){
		if($this->from!=null){ 
			return date('Y-m-d H:i:s',strtotime($this->from));
		}
	}

	public function getNextUser(){
		if($this->user_id!=null){
			return $this->user_id;
		}else{
			$next=0;
			foreach(User::getManager() as $key =>$value){
				if(self::$currentUser==null){
					self::$currentUser=$key;
					return $key;
				}else{
					if($next==1){
						return $key;
					}
					if(self::$currentUser==$key){
						$next==1;
					}
				}
			}			
			
		}
	}
	
}	
?>