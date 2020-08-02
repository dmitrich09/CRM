<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Item model",
*     type="object",
*     title="Item ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="document_id",
*         description="document_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="quantity",
*         description="quantity",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="cost",
*         description="cost",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="discount",
*         description="discount",   
*         type="string"
*     ),
*     @OA\Property(
*         property="total",
*         description="total",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="our_cost",
*         description="our_cost",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="clients_id",
*         description="clients_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="nameproduct",
*         description="nameproduct",   
*         type="string"
*     ),
*     @OA\Property(
*         property="typemarkmodel",
*         description="typemarkmodel",   
*         type="string"
*     ),
*     @OA\Property(
*         property="days",
*         description="days",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="timeline",
*         description="timeline",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="application_id",
*         description="application_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="pionhand",
*         description="pionhand",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="control",
*         description="control",   
*         type="string"
*     ),
*     @OA\Property(
*         property="provider_id",
*         description="provider_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="sert_num",
*         description="sert_num",   
*         type="string"
*     ),
*     @OA\Property(
*         property="get_date",
*         description="get_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="license_to",
*         description="license_to",   
*         type="string"
*     ),
*     @OA\Property(
*         property="act_organ",
*         description="act_organ",   
*         type="string"
*     ),
*     @OA\Property(
*         property="is_lead",
*         description="is_lead",   
*         type="integer"
*     ),
* *     required={ "id" ,"document_id" ,"account_id" ,"quantity" ,"total" ,"clients_id" ,"nameproduct" ,"application_id" ,"pionhand"},
* )
*/
class Item extends \yii\db\ActiveRecord
{
	const TIMELINE_PARTY=10;
    const TIMELINE_1_YEAR=20;
    const TIMELINE_2_YEAR=30;
    const TIMELINE_3_YEAR=40;
    const TIMELINE_4_YEAR=50;
    const TIMELINE_5_YEAR=60;
    const TIMELINE_EVER=70;

    public static function tableName()
    {
        return 'item';
    }

    public function rules()
    {
        return [
            [['document_id', 'account_id', 'quantity', 'total', 'clients_id', 'nameproduct', 'application_id', 'pionhand'], 'required'],
            [['document_id', 'account_id', 'quantity', 'clients_id', 'days', 'timeline', 'application_id', 'pionhand', 'provider_id', 'is_lead'], 'default', 'value' => null],
            [['document_id', 'account_id', 'quantity', 'clients_id', 'days', 'timeline', 'application_id', 'pionhand', 'provider_id', 'is_lead'], 'integer'],
            [['cost', 'total', 'our_cost'], 'number'],
            [['discount', 'nameproduct', 'typemarkmodel', 'control', 'sert_num', 'act_organ'], 'string'],
            [['get_date', 'license_to'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_id' => 'Document ID',
            'account_id' => 'Account ID',
            'quantity' => 'Quantity',
            'cost' => 'Cost',
            'discount' => 'Discount',
            'total' => 'Total',
            'our_cost' => 'Our Cost',
            'clients_id' => 'Clients ID',
            'nameproduct' => 'Nameproduct',
            'typemarkmodel' => 'Typemarkmodel',
            'days' => 'Days',
            'timeline' => 'Timeline',
            'application_id' => 'Application ID',
            'pionhand' => 'Pionhand',
            'control' => 'Control',
            'provider_id' => 'Provider ID',
            'sert_num' => 'Sert Num',
            'get_date' => 'Get Date',
            'license_to' => 'License To',
            'act_organ' => 'Act Organ',
            'is_lead' => 'Is Lead',
        ];
    }
	
	  public static function getTimelineArray(){
        return [
            self::TIMELINE_PARTY ,
            self::TIMELINE_1_YEAR ,
            self::TIMELINE_2_YEAR ,
            self::TIMELINE_3_YEAR ,
            self::TIMELINE_4_YEAR ,
            self::TIMELINE_5_YEAR ,
            self::TIMELINE_EVER ,
        ];
    }

    public static function getTimelineMap(){
        $map = [
            ['id' => self::TIMELINE_PARTY, 'name' => 'Партия'],
            ['id' => self::TIMELINE_1_YEAR, 'name' => '1 год'],
            ['id' => self::TIMELINE_2_YEAR, 'name' => '2 года'],
            ['id' => self::TIMELINE_3_YEAR, 'name' => '3 года'],
            ['id' => self::TIMELINE_4_YEAR, 'name' => '4 года'],
            ['id' => self::TIMELINE_5_YEAR, 'name' => '5 лет'],
            ['id' => self::TIMELINE_EVER, 'name' => 'Бессрочно'],
	];
	return $map;
    }

    public static function getTimelinenameById($id){
        $map=ArrayHelper::map(Application::getTimelineMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        }
        return $id;
    }


    public static function getTotal($quant,$cost,$discount){
        $res=0;
        $res=$quant*$cost;
        preg_match("/[0-9]{1,3}\%/",$discount, $result);
        if($result!=null){
            $res=$res*(100-substr($result[0], 0, -1))/100;
        }else{
            $res=$res-$discount;
        }
        return $res;
    }

    public static function updateLinkedCost($applicationId){
        $items=Item::find()->where(['application_id'=>$applicationId])->all();
        $totalCost=0;
        $totalOurCost=0;
        foreach($items as $item){
			$qu=$item->quantity;
			if($qu==null){
				$qu=0;
			}
            $totalCost+=$item->total;
            $totalOurCost+=$qu*($item->our_cost);
        }
        $app=Application::findOne($applicationId);
        if($app!=null){
            if($app->enddate==null){
				if(Yii::$app->user->identity->id != $app->manager_id){
					$app->showed=1;
				}else{
					$app->showed=0;
				}
                $app->total=$totalCost;
                $app->our_cost=$totalOurCost;
                $app->save();
            }
        }
        $kps=Kp::find()->where(['application_id'=>$applicationId])->all();
        foreach($kps as $kp){
            if($kp->enddate==null){
                $kp->total=$totalCost;
                $kp->our_cost=$totalOurCost;
                $kp->save();

            }
			$agreements=Agreement::find()->where(['id'=>$kp->id])->all();
                foreach($agreements as $agreement){
                    $agreement->total=$totalCost;
                    $agreement->our_cost=$totalOurCost;
					$agreement->checkDebt();
					$agreement->save();
			}
        }

		$agreements=Agreement::find()->where(['application_id'=>$applicationId])->all();
        foreach($agreements as $agreement){
                    $agreement->total=$totalCost;
                    $agreement->our_cost=$totalOurCost;
					$agreement->checkDebt();
					$agreement->save();
		}
    }
}








