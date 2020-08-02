<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Lead model",
*     type="object",
*     title="Lead ",
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
*         property="interesttype",
*         description="interesttype",   
*         type="string"
*     ),
*     @OA\Property(
*         property="state",
*         description="state",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="source_id",
*         description="source_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
*     @OA\Property(
*         property="manager",
*         description="manager",   
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
*         property="contactdate",
*         description="contactdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="declinematter_id",
*         description="declinematter_id",   
*         type="integer"
*     ),
* *     required={ "id" ,"clients_id" ,"account_id" ,"state" ,"manager" ,"startdate" ,"contactdate"},
* )
*/
class Lead extends \yii\db\ActiveRecord
{
	const STATUS_NEW = 10;
    const STATUS_NO_CALL = 20;
    const STATUS_NO_LPR = 30;
	const STATUS_LPR = 40;
	const STATUS_DECLINE = 50;
    const STATUS_IN_BID = 60;

    public static function tableName()
    {
        return 'lead';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id', 'state', 'manager', 'startdate', 'contactdate'], 'required'],
            [['clients_id', 'account_id', 'state', 'source_id', 'manager', 'declinematter_id'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'state', 'source_id', 'manager', 'declinematter_id'], 'integer'],
            [['interesttype', 'comment'], 'string'],
            [['startdate', 'enddate', 'contactdate'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'interesttype' => 'Interesttype',
            'state' => 'State',
            'source_id' => 'Source ID',
            'comment' => 'Comment',
            'manager' => 'Manager',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'contactdate' => 'Contactdate',
            'declinematter_id' => 'Declinematter ID',  
        ];
    }
	
	
    public static function getStatusArray(){
        return [
            self::STATUS_NEW ,
            self::STATUS_NO_CALL ,
            self::STATUS_NO_LPR ,
            self::STATUS_LPR ,
            self::STATUS_DECLINE ,
            self::STATUS_IN_BID ,
        ];
    }
	
    public static function getFullStatusMap(){
        $map = [
            ['id' => self::STATUS_NEW, 'name' => 'Не обработан'],
            ['id' => self::STATUS_NO_CALL, 'name' => 'Не дозвонился'],
            ['id' => self::STATUS_NO_LPR, 'name' => 'Не вышел на ЛПР'],
            ['id' => self::STATUS_LPR, 'name' => 'ЛПР'],
            ['id' => self::STATUS_DECLINE, 'name' => 'Отказ'],
            ['id' => self::STATUS_IN_BID, 'name' => 'Успех'],
	];	
	return $map;
    }
	
    public static function getStatusMap(){
        $map = [
            ['id' => self::STATUS_NEW, 'name' => 'Не обработан'],
            ['id' => self::STATUS_NO_CALL, 'name' => 'Не дозвонился'],
            ['id' => self::STATUS_NO_LPR, 'name' => 'Не вышел на ЛПР'],
            ['id' => self::STATUS_LPR, 'name' => 'ЛПР'],
		];	
		return $map;
    }


}
