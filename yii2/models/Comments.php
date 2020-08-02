<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Comments model",
*     type="object",
*     title="Comments ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="message",
*         description="message",   
*         type="string"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="object_id",
*         description="object_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="type",
*         description="type",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="adddate",
*         description="adddate",   
*         type="string"
*     ),
* *     required={ "id" ,"message" ,"account_id" ,"object_id" ,"type" ,"user_id" ,"adddate"},
* )
*/
class Comments extends \yii\db\ActiveRecord
{
	const TYPE_LEAD = 10;
    const TYPE_APPLICATION = 20; 
	const TYPE_OUTCALL = 30;
	const TYPE_KP = 40;
	const TYPE_AGREEMENT = 50;
	const TYPE_ORK = 60;
	const TYPE_TASK = 70;

    public static function tableName()
    {
        return 'comments';
    }

    public function rules()
    {
        return [
            [['message', 'account_id', 'object_id', 'type', 'user_id', 'adddate'], 'required'],
            [['message'], 'string'],
            [['account_id', 'object_id', 'type', 'user_id'], 'default', 'value' => null],
            [['account_id', 'object_id', 'type', 'user_id'], 'integer'],
            [['adddate'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message' => 'Message',
            'account_id' => 'Account ID',
            'object_id' => 'Object ID',
            'type' => 'Type',
            'user_id' => 'User ID',
            'adddate' => 'Adddate',
        ];
    }
	
	
	
	
	
	
	
	
	
	
	

}
