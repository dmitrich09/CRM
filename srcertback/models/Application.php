<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Application model",
*     type="object",
*     title="Application ",
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
*         property="source_id",
*         description="source_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="nameproduct",
*         description="nameproduct",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="okp",
*         description="okp",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="okpd2",
*         description="okpd2",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="tnved",
*         description="tnved",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="startdate",
*         description="startdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="enddate",
*         description="enddate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="lead_id",
*         description="lead_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="manager_id",
*         description="manager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="contactdate",
*         description="contactdate",   
*         type="string"
*     ),
*     @OA\Property(
*         property="state",
*         description="state",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="field",
*         description="field",   
*         type="string"
*     ),
*     @OA\Property(
*         property="countrymade",
*         description="countrymade",   
*         type="string"
*     ),
*     @OA\Property(
*         property="countryask",
*         description="countryask",   
*         type="string"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
*     @OA\Property(
*         property="exittoclient",
*         description="exittoclient",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="test",
*         description="test",   
*         type="integer"
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
*         property="documentprod",
*         description="documentprod",   
*         type="string"
*     ),
*     @OA\Property(
*         property="showed",
*         description="showed",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="countmanager_id",
*         description="countmanager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="declinematter_id",
*         description="declinematter_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="doc_on_hand",
*         description="doc_on_hand",   
*         type="string"
*     ),
*     @OA\Property(
*         property="is_lpr",
*         description="is_lpr",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="another_offer",
*         description="another_offer",   
*         type="string"
*     ),
*     @OA\Property(
*         property="doc_for_what",
*         description="doc_for_what",   
*         type="string"
*     ),
* *     required={ "id" ,"clients_id" ,"account_id" ,"nameproduct" ,"startdate" ,"lead_id" ,"manager_id" ,"contactdate" ,"exittoclient" ,"test" ,"total" ,"our_cost" ,"documentprod"},
* )
*/
class Application extends \yii\db\ActiveRecord
{
	const DOC_PROD_GOST=10;
    const DOC_PROD_TU=20;
    const DOC_PROD_OTHER=30;

    const STATUS_ACCEPT = 10;
    const STATUS_DECLINE = 20;

    const TIMELINE_PARTY=10;
    const TIMELINE_1_YEAR=20;
    const TIMELINE_2_YEAR=30;
    const TIMELINE_3_YEAR=40;
    const TIMELINE_4_YEAR=50;
    const TIMELINE_5_YEAR=60;
    const TIMELINE_EVER=70;

    const YES = 10;
    const NO = 20;

    public static function tableName()
    {
        return 'application';
    }

    public function rules()
    {
        return [
            [['clients_id', 'account_id', 'startdate', 'lead_id', 'manager_id', 'contactdate', 'exittoclient', 'test', 'total', 'our_cost', 'documentprod'], 'required'],
            [['clients_id', 'account_id', 'source_id', 'nameproduct', 'okp', 'okpd2', 'tnved', 'lead_id', 'manager_id', 'state', 'exittoclient', 'test', 'showed', 'countmanager_id', 'declinematter_id', 'is_lpr','total', 'our_cost', 'documentprod'], 'default', 'value' => null],
            [['clients_id', 'account_id', 'source_id', 'nameproduct', 'okp', 'okpd2',  'lead_id', 'manager_id', 'state', 'exittoclient', 'test', 'showed', 'countmanager_id', 'declinematter_id', 'is_lpr'], 'integer'],
            [['startdate', 'enddate', 'contactdate'], 'safe'],
            [['field', 'countrymade', 'countryask', 'comment', 'documentprod', 'doc_on_hand', 'another_offer','tnved', 'doc_for_what'], 'string'],
            [['total', 'our_cost'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clients_id' => 'Clients ID',
            'account_id' => 'Account ID',
            'source_id' => 'Source ID',
            'nameproduct' => 'Nameproduct',
            'okp' => 'Okp',
            'okpd2' => 'Okpd2',
            'tnved' => 'Tnved',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'lead_id' => 'Lead ID',
            'manager_id' => 'Manager ID',
            'contactdate' => 'Contactdate',
            'state' => 'State',
            'field' => 'Field',
            'countrymade' => 'Countrymade',
            'countryask' => 'Countryask',
            'comment' => 'Comment',
            'exittoclient' => 'Exittoclient',
            'test' => 'Test',
            'total' => 'Total',
            'our_cost' => 'Our Cost',
            'documentprod' => 'Documentprod',
            'showed' => 'Showed',
            'countmanager_id' => 'Countmanager ID',
            'declinematter_id' => 'Declinematter ID',
            'doc_on_hand' => 'Doc On Hand',
            'is_lpr' => 'Is Lpr',
            'another_offer' => 'Another Offer',
            'doc_for_what' => 'Doc For What',
        ];
    }
	
	   public static function getDocProdArray(){
        return [
            self::DOC_PROD_GOST ,
            self::DOC_PROD_TU ,
            self::DOC_PROD_OTHER,
        ];
    }

    public static function getDocProdMap(){
        $map = [
            ['id' => self::DOC_PROD_GOST, 'name' => 'ГОСТ'],
            ['id' => self::DOC_PROD_TU, 'name' => 'ТУ'],
            ['id' => self::DOC_PROD_OTHER, 'name' => 'Другое'],
	];
	return $map;
    }

    public static function getYesNoArray(){
        return [
            self::YES ,
            self::NO ,
        ];
    }

    public static function getYesNoMap(){
        $map = [
            ['id' => self::YES, 'name' => 'ДА'],
            ['id' => self::NO, 'name' => 'НЕТ'],
	];
	return $map;
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

    public static function getYesNonameById($id){
        $map=ArrayHelper::map(Application::getYesNoMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        }
        return $id;
    }

    public static function getDocProdNameById($id){
        $map=ArrayHelper::map(Application::getDocProdMap(),'id','name');
        if(isset($map[$id])){
            return $map[$id];
        }
        return $id;
    }

	public static function getNoShowedQuery($userId){
		$userQr=Application::find()->where(['enddate'=>null]);
		$userQr->andWhere('(manager_id=:usid and showed=1) or countmanager_id=:usid',['usid'=>$userId,]);
		return $userQr;
	}
}





























