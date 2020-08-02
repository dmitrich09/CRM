<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Ork model",
*     type="object",
*     title="Ork ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="agreement_id",
*         description="agreement_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
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
*         property="state",
*         description="state",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="signagree",
*         description="signagree",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="signact",
*         description="signact",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="clients_id",
*         description="clients_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="manager_id",
*         description="manager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="contactdate",
*         description="contactdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="source_id",
*         description="source_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="pay_date",
*         description="pay_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="provider_debt",
*         description="provider_debt",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="clientdoc_date",
*         description="clientdoc_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="close_date",
*         description="close_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="declinematter_id",
*         description="declinematter_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
*     @OA\Property(
*         property="sert_num",
*         description="sert_num",   
*         type="string"
*     ),
*     @OA\Property(
*         property="get_date",
*         description="get_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="license_to",
*         description="license_to",   
*         type="string"
*     ),
*     @OA\Property(
*         property="act_num",
*         description="act_num",   
*         type="string"
*     ),
*     @OA\Property(
*         property="act_organ",
*         description="act_organ",   
*         type="string"
*     ),
* *     required={ "id" ,"agreement_id" ,"account_id" ,"startdate" ,"state" ,"signagree" ,"signact" ,"clients_id" ,"manager_id" ,"contactdate" ,"source_id"},
* )
*/
class Ork extends \yii\db\ActiveRecord  
{
	const STATUS_NEW = 10;
    const STATUS_START = 20;
    const STATUS_PROJECT_CLIENT=30;
    const STATUS_PROJECT_DEVELOPER = 40;
    const STATUS_ANALISYS_DEVELOPER = 50;
    const STATUS_INPRINT = 60;
    const STATUS_ONREGISTER = 70;
    const STATUS_SINGINPI = 80;
    const STATUS_ACCEPT_PI_CLIENT = 90;
    const STATUS_ACCEPT_PI_DEVELOPER = 100;
    const STATUS_PI_INPRINT = 110;
    const STATUS_SHIPMENT_FROM_STRUCT = 120;
    const STATUS_SHIPMENT_TO_CLIENT = 130;
    const STATUS_CALL = 140;
    const STATUS_ACCEPT = 150;
    const STATUS_DECLINE= 160;

    public static function tableName()
    {
        return 'ork';
    }

    public function rules()
    {
        return [
            [['agreement_id', 'account_id', 'startdate', 'state', 'signagree', 'signact', 'manager_id', 'contactdate', 'source_id'], 'required'],
            [['agreement_id', 'account_id', 'state', 'signagree', 'signact', 'clients_id', 'manager_id', 'source_id', 'declinematter_id'], 'default', 'value' => null],
            [['agreement_id', 'account_id', 'state', 'signagree', 'signact', 'clients_id', 'manager_id', 'source_id', 'declinematter_id'], 'integer'],
            [['startdate', 'enddate', 'contactdate', 'pay_date', 'clientdoc_date', 'close_date', 'get_date', 'license_to'], 'safe'],
            [['provider_debt'], 'number'],
            [['comment', 'sert_num', 'act_num', 'act_organ'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agreement_id' => 'Agreement ID',
            'account_id' => 'Account ID',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'state' => 'State',
            'signagree' => 'Signagree',
            'signact' => 'Signact',
            'clients_id' => 'Clients ID',
            'manager_id' => 'Manager ID',
            'contactdate' => 'Contactdate',
            'source_id' => 'Source ID',
            'pay_date' => 'Pay Date',
            'provider_debt' => 'Provider Debt',
            'clientdoc_date' => 'Clientdoc Date',
            'close_date' => 'Close Date',
            'declinematter_id' => 'Declinematter ID',
            'comment' => 'Comment',
            'sert_num' => 'Sert Num',
            'get_date' => 'Get Date',
            'license_to' => 'License To',
            'act_num' => 'Act Num',
            'act_organ' => 'Act Organ',
        ];
    }
	
	   public static function getStatusArray(){
        return [
            self::  STATUS_NEW,
            self::  STATUS_START ,
            self::  STATUS_PROJECT_CLIENT,
            self::  STATUS_PROJECT_DEVELOPER ,
            self::  STATUS_ANALISYS_DEVELOPER,
            self::  STATUS_INPRINT,
            self::  STATUS_ONREGISTER,
            self::  STATUS_SINGINPI,
            self::  STATUS_ACCEPT_PI_CLIENT ,
            self::  STATUS_ACCEPT_PI_DEVELOPER ,
            self::  STATUS_PI_INPRINT ,
            self::  STATUS_SHIPMENT_FROM_STRUCT,
            self::  STATUS_SHIPMENT_TO_CLIENT,
            self::  STATUS_CALL,
            self::  STATUS_DECLINE ,
            self::  STATUS_ACCEPT ,
        ];
    }
	
	public static function getActiveStatuses(){
		return [
            self::  STATUS_NEW,
            self::  STATUS_START ,
            self::  STATUS_PROJECT_CLIENT,
            self::  STATUS_PROJECT_DEVELOPER ,
            self::  STATUS_ANALISYS_DEVELOPER,
            self::  STATUS_INPRINT,
            self::  STATUS_ONREGISTER,
            self::  STATUS_SINGINPI,
            self::  STATUS_ACCEPT_PI_CLIENT ,
            self::  STATUS_ACCEPT_PI_DEVELOPER ,
            self::  STATUS_PI_INPRINT ,
            self::  STATUS_SHIPMENT_FROM_STRUCT,
            self::  STATUS_SHIPMENT_TO_CLIENT,
            self::  STATUS_CALL,
        ];
	}
	
    public static function getStatusMap(){
            $map = [    
                        ['id' =>self::  STATUS_NEW, 'name' => 'Сбор документов для запуска'],
                        ['id' =>self::  STATUS_START , 'name' => 'В запуске'],
                        ['id' =>self::  STATUS_PROJECT_CLIENT, 'name' => 'Согласование макета клиентом'],
                        ['id' =>self::  STATUS_PROJECT_DEVELOPER , 'name' => 'Соглсование макета поставщиком'],
                        ['id' =>self::  STATUS_ANALISYS_DEVELOPER, 'name' => 'Анализ документов поставщиком'],
                        ['id' =>self::  STATUS_INPRINT, 'name' => 'В печати'],
                        ['id' =>self::  STATUS_ONREGISTER, 'name' => 'На регистрации'],
                        ['id' =>self::  STATUS_SINGINPI, 'name' => 'Оформление ПИ'],
                        ['id' =>self::  STATUS_ACCEPT_PI_CLIENT , 'name' => 'Согласование ПИ клиентом'],
                        ['id' =>self::  STATUS_ACCEPT_PI_DEVELOPER , 'name' => 'Согласование ПИ поставщиком'],
                        ['id' =>self::  STATUS_PI_INPRINT , 'name' => 'В печати ПИ'],
                        ['id' =>self::  STATUS_SHIPMENT_FROM_STRUCT, 'name' => 'Доставка оригинала из органа'],
                        ['id' =>self::  STATUS_SHIPMENT_TO_CLIENT, 'name' => 'Доставка оригинала клиенту'],
                        ['id' =>self::  STATUS_CALL, 'name' => 'Обзвон по качеству'],
                        ['id' =>self::  STATUS_ACCEPT , 'name' => 'Завершен'],
                        ['id' =>self::  STATUS_DECLINE , 'name' => 'Отказ'],
                    ];	
            return $map;
    }
	
    public static function getNameById($id){
        $map=ArrayHelper::map(Ork::getStatusMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        } 
        return $id;
    }
	
	public static function updateProviderDebtByAgreement($agreement_id){
		$orks=Ork::find()->where(['agreement_id'=>$agreement_id])->all();
		foreach($orks as $ork){
			Ork::updateProviderDebt($ork->ork_id);
		}
	}
	public static function updateProviderDebt($orkId){
		$orArr;
		if(!is_array($orkId)){
			$orArr[]=$orkId;
		}else{
			$orArr=$orkId;
		}
		
		foreach($orArr as $id){
			$ork=Ork::findOne($id);
			if($ork!=null){
				$agreement=Agreement::findOne($ork->agreement_id);
				$pays=Payment::find()->where(['agreement_id'=>$ork->agreement_id])->all();
				if($agreement!=null&&$pays!= null){
					$debt=$agreement->our_cost;
					foreach($pays as $pay){
						$debt=$debt-$pay->amount;
					}
					$ork->provider_debt=$debt;
					$ork->update();
				}
			}
		}
	}

}












