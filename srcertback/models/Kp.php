<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Kp model",
*     type="object",
*     title="Kp ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="startdate",
*         description="startdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
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
*         property="state",
*         description="state",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="manager_id",
*         description="manager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="application_id",
*         description="application_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="client_name",
*         description="client_name",   
*         type="string"
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
*         property="contactdate",
*         description="contactdate",   
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
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
* *     required={ "id" ,"startdate" ,"account_id" ,"clients_id" ,"state" ,"application_id" ,"source_id"},
* )
*/
class Kp extends \yii\db\ActiveRecord
{
    
	const STATUS_NEW = 10;
    const STATUS_SEND = 20;
    const STATUS_PRICE = 30;
    const STATUS_ACCEPT = 40;
    const STATUS_DECLINE = 50;
	
    public static function tableName()
    {
        return 'kp';
    }

    public function rules()
    {
        return [
            [['startdate', 'account_id', 'clients_id', 'state', 'application_id', 'source_id'], 'required'],
            [['startdate', 'enddate', 'contactdate'], 'safe'],
            [['account_id', 'clients_id', 'state', 'manager_id', 'application_id', 'source_id', 'declinematter_id', 'is_lpr'], 'default', 'value' => null],
            [['account_id', 'clients_id', 'state', 'manager_id', 'application_id', 'source_id', 'declinematter_id', 'is_lpr'], 'integer'],
            [['client_name', 'doc_on_hand', 'another_offer', 'doc_for_what', 'comment'], 'string'],
            [['total', 'our_cost'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'startdate' => 'Startdate',
            'account_id' => 'Account ID',
            'enddate' => 'Enddate',
            'clients_id' => 'Clients ID',
            'state' => 'State',
            'manager_id' => 'Manager ID',
            'application_id' => 'Application ID',
            'client_name' => 'Client Name',
            'total' => 'Total',
            'our_cost' => 'Our Cost',
            'source_id' => 'Source ID',
            'contactdate' => 'Contactdate',
            'declinematter_id' => 'Declinematter ID',
            'doc_on_hand' => 'Doc On Hand',
            'is_lpr' => 'Is Lpr',
            'another_offer' => 'Another Offer',
            'doc_for_what' => 'Doc For What',
            'comment' => 'Comment',
        ];
    }
	
	public static function getStatusArray(){
        return [
            self::STATUS_NEW ,
            self::STATUS_SEND ,
            self::STATUS_PRICE ,
            self::STATUS_ACCEPT ,
            self::STATUS_DECLINE ,
        ];
    }
	
    public static function getStatusMap(){
            $map = [
			['id' => self::STATUS_NEW, 'name' => 'Составлено'],
			['id' => self::STATUS_SEND, 'name' => 'Отправлено'],
			['id' => self::STATUS_PRICE, 'name' => 'Ценовые переговоры по КП'],
			['id' => self::STATUS_ACCEPT, 'name' => 'Договор'],
			['id' => self::STATUS_DECLINE, 'name' => 'Отмена'],

                    ];	
            return $map;
    }
	
    public static function getNameById($id){
        $map=ArrayHelper::map(Kp::getStatusMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        } 
        return $id;
    }
}


























