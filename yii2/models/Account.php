<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Account model",
*     type="object",
*     title="Account model",
*     required={"email", "username"},
*     @OA\Property(
*         property="created_at",
*         type="string"
*     ),
*     @OA\Property(
*         property="name",
*         type="string"
*     ),
*     @OA\Property(
*         property="id",
*         type="integer"
*     ),
* )
*/
class Account extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'account';
    }

    public function rules()
    {
        return [
            [['name', 'create_at'], 'required'],
            [['create_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'create_at' => 'Create At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
