<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Planned model",
*     type="object",
*     title="Planned ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="planned_date",
*         description="planned_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="calls",
*         description="calls",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="leads",
*         description="leads",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="applications",
*         description="applications",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="kps",
*         description="kps",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="agreements",
*         description="agreements",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="pays",
*         description="pays",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="marges",
*         description="marges",   
*         type="integer"
*     ),
* *     required={ "id" ,"planned_date" ,"account_id" ,"calls" ,"leads" ,"applications" ,"kps" ,"agreements" ,"pays" ,"marges"},
* )
*/
class Planned extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'planned';
    }

    public function rules()
    {
        return [
            [['planned_date', 'account_id', 'calls', 'leads', 'applications', 'kps', 'agreements', 'pays', 'marges'], 'required'],
            [['planned_date'], 'safe'],
            [['account_id', 'calls', 'leads', 'applications', 'kps', 'agreements'], 'default', 'value' => null],
            [['account_id', 'calls', 'leads', 'applications', 'kps', 'agreements'], 'integer'],
            [['pays', 'marges'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planned_date' => 'Planned Date',
            'account_id' => 'Account ID',
            'calls' => 'Calls',
            'leads' => 'Leads',
            'applications' => 'Applications',
            'kps' => 'Kps',
            'agreements' => 'Agreements',
            'pays' => 'Pays',
            'marges' => 'Marges',
        ];
    }


}

















