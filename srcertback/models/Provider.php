<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Provider model",
*     type="object",
*     title="Provider ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="name",
*         description="name",   
*         type="string"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="email",
*         description="email",   
*         type="string"
*     ),
*     @OA\Property(
*         property="lawadress",
*         description="lawadress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="postadress",
*         description="postadress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="adress",
*         description="adress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="contact",
*         description="contact",   
*         type="string"
*     ),
*     @OA\Property(
*         property="phone",
*         description="phone",   
*         type="string"
*     ),
* *     required={ "id" ,"name" ,"account_id" ,"email"},
* )
*/
class Provider extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'provider';
    }

    public function rules()
    {
        return [
            [['name', 'account_id', 'email'], 'required'],
            [['name', 'email', 'lawadress', 'postadress', 'adress', 'contact', 'phone'], 'string'],
            [['account_id'], 'default', 'value' => null],
            [['account_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account_id' => 'Account ID',
            'email' => 'Email',
            'lawadress' => 'Lawadress',
            'postadress' => 'Postadress',
            'adress' => 'Adress',
            'contact' => 'Contact',
            'phone' => 'Phone',
        ];
    }


}
