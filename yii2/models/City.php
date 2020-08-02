<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="City model",
*     type="object",
*     title="City ",
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
* *     required={ "id" ,"name" ,"account_id"},
* )
*/
class City extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'city';
    }

    public function rules()
    {
        return [
            [['name', 'account_id'], 'required'],
            [['name'], 'string'],
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
        ];
    }
}
