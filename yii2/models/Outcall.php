<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Outcall model",
*     type="object",
*     title="Outcall ",
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
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="contactdate",
*         description="contactdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
*     @OA\Property(
*         property="status",
*         description="status",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="showed",
*         description="showed",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="enddate",
*         description="enddate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="startdate",
*         description="startdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="declinematter_id",
*         description="declinematter_id",   
*         type="integer"
*     ),
* *     required={ "id" ,"clients_id" ,"account_id" ,"user_id" ,"contactdate" ,"status" ,"startdate"},
* )
*/
class Outcall extends \yii\db\ActiveRecord
{
	const STATUS_NEW = 10;
	const STATUS_DECLINE = 20;
	const STATUS_IN_LEAD = 30;
	 
	const SHOWED_NO = 10;
	const SHOWED_YES = 20;

    public static function tableName()
    {
        return 'outcall';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id', 'user_id', 'contactdate', 'status', 'startdate'], 'required'],
            [['clients_id', 'account_id', 'user_id', 'status', 'showed', 'declinematter_id'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'user_id', 'status', 'showed', 'declinematter_id'], 'integer'],
            [['contactdate', 'enddate', 'startdate'], 'safe'],
            [['comment'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'user_id' => 'User ID',
            'contactdate' => 'Contactdate',
            'comment' => 'Comment',
            'status' => 'Status',
            'showed' => 'Showed',
            'enddate' => 'Enddate',
            'startdate' => 'Startdate',
            'declinematter_id' => 'Declinematter ID',
        ];
    }
	
	public static function getStatusArray(){
        return [
            self::STATUS_NEW ,
            self::STATUS_DECLINE ,
            self::STATUS_IN_LEAD ,
        ];
    }
	
    public static function getStatusMap(){
        $map = [
            ['id' => self::STATUS_NEW, 'name' => 'В работе'],
            ['id' => self::STATUS_DECLINE, 'name' => 'Отказ'],
            ['id' => self::STATUS_IN_LEAD, 'name' => 'Переведен в лид'],

		];	
	return $map;
    }
	
    public static function getShowArray(){
        return [
            self::SHOWED_NO ,
            self::SHOWED_YES ,
        ];
    }
	
    public static function getShowMap(){
        $map = [
            ['id' => self::SHOWED_NO, 'name' => 'Просмотрено'],
            ['id' => self::SHOWED_YES, 'name' => 'Не просмотрено'],

		];	
		return $map;
    }

}
