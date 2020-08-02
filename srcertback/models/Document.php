<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Document model",
*     type="object",
*     title="Document ",
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
*         property="fullname",
*         description="fullname",   
*         type="string"
*     ),
*     @OA\Property(
*         property="for_doc",
*         description="for_doc",   
*         type="string"
*     ),
*     @OA\Property(
*         property="is_include",
*         description="is_include",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="is_control",
*         description="is_control",   
*         type="integer"
*     ),
* *     required={ "id" ,"name" ,"account_id" ,"is_include" ,"is_control"},
* )
*/
class Document extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'document';
    }

    public function rules()
    {
        return [
            [['name', 'account_id', 'is_include', 'is_control'], 'required'],
            [['name', 'fullname', 'for_doc'], 'string'],
            [['account_id', 'is_include', 'is_control'], 'default', 'value' => null],
            [['account_id', 'is_include', 'is_control'], 'integer'],
			[['deleted_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account_id' => 'Account ID',
            'fullname' => 'Fullname',
            'for_doc' => 'For Doc',
            'is_include' => 'Is Include',
            'is_control' => 'Is Control',
			'deleted_at' => 'Deleted_at'
        ];
    }

}
