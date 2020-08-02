<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Files model",
*     type="object",
*     title="Files ",
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
*         property="rusname",
*         description="rusname",   
*         type="string"
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="insert_date",
*         description="insert_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="application_id",
*         description="application_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="type",
*         description="type",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="parent_id",
*         description="parent_id",   
*         type="integer"
*     ),
* *     required={ "id" ,"account_id" ,"rusname" ,"user_id" ,"insert_date"},
* )
*/
class Files extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'files';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id', 'user_id', 'application_id', 'type', 'parent_id'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'user_id', 'application_id', 'type', 'parent_id'], 'integer'],
            [['account_id', 'rusname', 'user_id', 'insert_date'], 'required'],
            [['rusname'], 'string'],
            [['insert_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'rusname' => 'Rusname',
            'user_id' => 'User ID',
            'insert_date' => 'Insert Date',
            'application_id' => 'Application ID',
            'type' => 'Type',
            'parent_id' => 'Parent ID',
        ];
    }
}
