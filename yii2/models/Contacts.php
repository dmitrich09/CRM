<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Contacts model",
*     type="object",
*     title="Contacts ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="clients_id",
*         description="clients_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="contacttype_id",
*         description="contacttype_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="name",
*         description="name",   
*         type="string"
*     ),
*     @OA\Property(
*         property="phone",
*         description="phone",   
*         type="string"
*     ),
*     @OA\Property(
*         property="email",
*         description="email",   
*         type="string"
*     ),
*     @OA\Property(
*         property="skype",
*         description="skype",   
*         type="string"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
* *     required={ "id" ,"clients_id" ,"account_id"},
* )
*/
class Contacts extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'contacts';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id'], 'required'],
            [['clients_id', 'account_id', 'contacttype_id'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'contacttype_id'], 'integer'],
            [['name', 'phone', 'email', 'skype', 'comment'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'contacttype_id' => 'Contacttype ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'skype' => 'Skype',
            'comment' => 'Comment',
        ];
    }
	
	public function afterSave($insert, $changedAttributes){
		if($this->phone!=null){
			$this->phone=Contacts::formatNumber($this->phone);
			$this->updateAttributes(['phone']);
		}
		
		$cl=Calls::find()->where(['clients_id'=>null,'sip'=>'+'.$this->phone])->all();
		foreach($cl as $call){
			$call->clients_id=$this->clients_id;
			$call->update();
		}
		$cl=Calls::find()->where(['clients_id'=>null,'destination'=>'+'.$this->phone])->all();
		foreach($cl as $call){
			$call->clients_id=$this->clients_id;
			$call->update();
		}
		
		
		parent::afterSave($insert, $changedAttributes);
	}
	
	public static function onlyNum($number){
		if($number!=null){
			$string= preg_replace('~[^0-9]+~','',$number); 
			return $string;
		}
	}
	
	public static function formatNumber($number){
		if($number!=null){
			$string= preg_replace('~[^0-9]+~','',$number); 
			if(mb_strlen($string)==11){
				if(substr($string,0,1)==8){
					return "7".substr($string,1);
				}
				return $string;
			}else if(mb_strlen($string)==10){
				return "7".$string;
			}else if(mb_strlen($string)==7){
				return "7342".$string;
			}else if(mb_strlen($string)==6){
				return "73422".$string;
			}
			return $string;
		}
	}
	
		public static function getNumFormat($number){ 
		$string="";
		if($number!=null){
			$string1 = preg_replace('~[^0-9]+~','',$number); 
			$lenght=mb_strlen($string1);
			if($lenght==11){
				if(mb_substr($string1,0,4)=='8342'){
					$string.=mb_substr($string1,4,3);
					$string.="-";
					$string.=mb_substr($string1,7,2);
					$string.="-";
					$string.=mb_substr($string1,9,2);
				}else{
					$string=mb_substr($string1,0,1);
					$string.=" (";
					$string.=mb_substr($string1,1,3);
					$string.=") ";
					$string.=mb_substr($string1,4,3);
					$string.="-";
					$string.=mb_substr($string1,7,2);
					$string.="-";
					$string.=mb_substr($string1,9,2);
					
				}
				
			}else{
				$string=$string1;
			}
		}
		
			
		return $string;
	}
}












