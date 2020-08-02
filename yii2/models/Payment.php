<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Payment model",
*     type="object",
*     title="Payment ",
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
*         property="payment_date",
*         description="payment_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="amount",
*         description="amount",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="agreement_id",
*         description="agreement_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="pay_number",
*         description="pay_number",   
*         type="string"
*     ),
*     @OA\Property(
*         property="source_id",
*         description="source_id",   
*         type="integer"
*     ),
* *     required={ "id" ,"clients_id" ,"account_id" ,"payment_date" ,"amount"},
* )
*/
class Payment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'payment';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id', 'payment_date', 'amount'], 'required'],
            [['clients_id', 'account_id', 'user_id', 'agreement_id', 'source_id'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'user_id', 'agreement_id', 'source_id'], 'integer'],
            [['payment_date'], 'safe'],
            [['amount'], 'number'],
            [['pay_number'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'payment_date' => 'Payment Date',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'agreement_id' => 'Agreement ID',
            'pay_number' => 'Pay Number',
            'source_id' => 'Source ID',
        ];
    }
    
	public function afterSave($insert, $changedAttributes){  
		self::agreementDebt($this->id);
		parent::afterSave($insert, $changedAttributes);
	}
	
	 public function afterDelete(){
        self::agreementDebt($this->id);
		parent::afterDelete();
		 
	 }
	 
	public static function agreementDebt($agreement_id){
		$agr=Agreement::findOne($agreement_id);
		$sum=0;
		if ($agr != null) {
			foreach(Payment::find()->where(['id'=>$agr->id])->all() as $pay){
				$sum+=$pay->amount;
			}
			$agr->debt=$agr->total-$sum;
			$agr->save();
		}
	}

}
