<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Sphere model",
*     type="object",
*     title="Sphere ",
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
*         property="deleted_at",
*         description="deleted_at",   
*         type="string"
*     ),
* *     required={ "id" ,"name" ,"account_id"},
* )
*/
class Sphere extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'sphere';
    }

    public function rules()
    {
        return [
            [['spherename', 'account_id'], 'required'],
            [['spherename'], 'string'],
            [['account_id'], 'default', 'value' => null],
            [['account_id'], 'integer'],
            [['deleted_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'spherename' => 'Name',
            'account_id' => 'Account ID',
            'deleted_at' => 'Deleted At',
        ];
    }

}
