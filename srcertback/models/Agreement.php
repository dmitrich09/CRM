<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Agreement model",
*     type="object",
*     title="Agreement ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="numberagree",
*         description="numberagree",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="agreedate",
*         description="agreedate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="startdate",
*         description="startdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="enddate",
*         description="enddate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="clients_id",
*         description="clients_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="person",
*         description="person",   
*         type="string"
*     ),
*     @OA\Property(
*         property="manager_id",
*         description="manager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="kp_id",
*         description="kp_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="state",
*         description="state",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="tax",
*         description="tax",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="total",
*         description="total",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="our_cost",
*         description="our_cost",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="source_id",
*         description="source_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="debt",
*         description="debt",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="contactdate",
*         description="contactdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="short_person",
*         description="short_person",   
*         type="string"
*     ),
*     @OA\Property(
*         property="basement",
*         description="basement",   
*         type="string"
*     ),
*     @OA\Property(
*         property="declinematter_id",
*         description="declinematter_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="doc_on_hand",
*         description="doc_on_hand",   
*         type="string"
*     ),
*     @OA\Property(
*         property="is_lpr",
*         description="is_lpr",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="another_offer",
*         description="another_offer",   
*         type="string"
*     ),
*     @OA\Property(
*         property="doc_for_what",
*         description="doc_for_what",   
*         type="string"
*     ),
*     @OA\Property(
*         property="options",
*         description="options",   
*         type="string"
*     ),
*     @OA\Property(
*         property="application_id",
*         description="application_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
* *     required={ "id" ,"numberagree" ,"account_id" ,"agreedate" ,"startdate" ,"clients_id" ,"manager_id" ,"kp_id" ,"state" ,"tax" ,"source_id"},
* )
*/
class Agreement extends \yii\db\ActiveRecord
{
	const STATUS_ACCEPT = 10;
    const STATUS_DECLINE = 20;
    const STATUS_NEW = 30;
     
    const TAX_NDS = 10;
    const TAX_NONDS = 20;

    public static function tableName()
    {
        return 'agreement';
    }

    public function rules()
    {
        return [
            [['numberagree', 'account_id', 'agreedate', 'startdate', 'clients_id', 'manager_id', 'kp_id', 'state', 'tax', 'source_id'], 'required'],
            [['numberagree', 'account_id', 'clients_id', 'manager_id', 'kp_id', 'state', 'tax', 'source_id', 'declinematter_id', 'is_lpr', 'application_id'], 'default', 'value' => null],
            [['numberagree', 'account_id', 'clients_id', 'manager_id', 'kp_id', 'state', 'tax', 'source_id', 'declinematter_id', 'is_lpr', 'application_id'], 'integer'],
            [['agreedate', 'startdate', 'enddate', 'contactdate'], 'safe'],
            [['person', 'short_person', 'basement', 'doc_on_hand', 'another_offer', 'doc_for_what', 'options', 'comment'], 'string'],
            [['total', 'our_cost', 'debt'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numberagree' => 'Numberagree',
            'account_id' => 'Account ID',
            'agreedate' => 'Agreedate',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'clients_id' => 'Clients ID',
            'person' => 'Person',
            'manager_id' => 'Manager ID',
            'kp_id' => 'Kp ID',
            'state' => 'State',
            'tax' => 'Tax',
            'total' => 'Total',
            'our_cost' => 'Our Cost',
            'source_id' => 'Source ID',
            'debt' => 'Debt',
            'contactdate' => 'Contactdate',
            'short_person' => 'Short Person',
            'basement' => 'Basement',
            'declinematter_id' => 'Declinematter ID',
            'doc_on_hand' => 'Doc On Hand',
            'is_lpr' => 'Is Lpr',
            'another_offer' => 'Another Offer',
            'doc_for_what' => 'Doc For What',
            'options' => 'Options',
            'application_id' => 'Application ID',
            'comment' => 'Comment',
        ];
    }
	
	   public static function getTaxArray(){
        return [
            self::TAX_NDS , 
            self::TAX_NONDS ,
        ];
    }
    
    public static function getTaxMap(){
        $map = [
            ['id' => self::TAX_NONDS, 'name' => 'ИП Филимонов'],
            ['id' => self::TAX_NDS, 'name' => 'ООО Современные решения'],
	];	
	return $map;
    }
    
     public static function getStateArray(){
        return [
            self::STATUS_ACCEPT ,
            self::STATUS_DECLINE ,
            self::STATUS_NEW ,
        ];
    }
	
    public static function getStateMap(){
        $map = [
            ['id' => self::STATUS_ACCEPT, 'name' => 'Согласован'],
            ['id' => self::STATUS_DECLINE, 'name' => 'Отказ'],
            ['id' => self::STATUS_NEW, 'name' => 'Новый'],
	];	
	return $map;
    }
	
    public static function getStateNameById($id){
        $map=ArrayHelper::map(self::getStateMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        } 
        return $id;
    }
    
    
    public static function getTaxNameById($id){
        $map=ArrayHelper::map(self::getTaxMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        } 
        return $id;
    }
	
	public function getCountPi(){
		$cnt=0;
		if($this->application_id!=null){
			foreach(Item::find()->where(['application_id'=>$this->application_id])->all() as $item){
				if($item->pionhand==Application::YES){
					$cnt++;
				}
			}
		}
		return $cnt; 
	}
	
	
	public function checkDebt(){
		if($this->id!=null){
			$count = 0;
			foreach(Payment::find()->where(['id'=>$this->id])->all() as $py){
				$count+=$py->amount;
			}
			$this->debt= $this->total- $count;
			$this->save();
		}
	}
	
	public static function getCurrentMaxNumber(){
		
		$post = Yii::$app->db->createCommand('SELECT MAX(numberagree) FROM agreement')
           ->queryOne();
		
		return $post['max'];
		
	}
}












