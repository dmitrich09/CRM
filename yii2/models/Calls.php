<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Calls model",
*     type="object",
*     title="Calls ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="zad_call_id",
*         description="zad_call_id",   
*         type="string"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="sip",
*         description="sip",   
*         type="string"
*     ),
*     @OA\Property(
*         property="callstart",
*         description="callstart",   
*         type="string"
*     ),
*     @OA\Property(
*         property="clid",
*         description="clid",   
*         type="string"
*     ),
*     @OA\Property(
*         property="destination",
*         description="destination",   
*         type="string"
*     ),
*     @OA\Property(
*         property="disposition",
*         description="disposition",   
*         type="string"
*     ),
*     @OA\Property(
*         property="seconds",
*         description="seconds",   
*         type="string"
*     ),
*     @OA\Property(
*         property="is_recorded",
*         description="is_recorded",   
*         type="string"
*     ),
*     @OA\Property(
*         property="clients_id",
*         description="clients_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="incoming",
*         description="incoming",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="is_file",
*         description="is_file",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="is_warm",
*         description="is_warm",   
*         type="integer"
*     ),
* *     required={ "id" ,"zad_call_id" ,"account_id" ,"sip" ,"callstart" ,"clid" ,"destination" ,"disposition" ,"seconds" ,"is_recorded" ,"incoming"},
* )
*/
class Calls extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'calls';
    }

    public function rules()
    {
        return [
            [['zad_call_id', 'account_id', 'sip', 'callstart', 'clid', 'destination', 'disposition', 'seconds', 'is_recorded', 'incoming'], 'required'],
            [['zad_call_id', 'sip', 'clid', 'destination', 'disposition', 'seconds', 'is_recorded'], 'string'],
            [['account_id', 'clients_id', 'user_id', 'incoming', 'is_file', 'is_warm'], 'default', 'value' => null],
            [['account_id', 'clients_id', 'user_id', 'incoming', 'is_file', 'is_warm'], 'integer'],
            [['callstart'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zad_call_id' => 'Zad Call ID',
            'account_id' => 'Account ID',
            'sip' => 'Sip',
            'callstart' => 'Callstart',
            'clid' => 'Clid',
            'destination' => 'Destination',
            'disposition' => 'Disposition',
            'seconds' => 'Seconds',
            'is_recorded' => 'Is Recorded',
            'clients_id' => 'Clients ID',
            'user_id' => 'User ID',
            'incoming' => 'Incoming',
            'is_file' => 'Is File',
            'is_warm' => 'Is Warm',
        ];
    }
}
