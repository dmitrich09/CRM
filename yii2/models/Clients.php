<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Clients model",
*     type="object",
*     title="Clients ",
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
*         property="clienttype_id",
*         description="clienttype_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="sphere_id",
*         description="sphere_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="manager",
*         description="manager",   
*         type="string"
*     ),
*     @OA\Property(
*         property="city_id",
*         description="city_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="inn",
*         description="inn",   
*         type="string"
*     ),
*     @OA\Property(
*         property="ogrn",
*         description="ogrn",   
*         type="string"
*     ),
*     @OA\Property(
*         property="kpp",
*         description="kpp",   
*         type="string"
*     ),
*     @OA\Property(
*         property="okpo",
*         description="okpo",   
*         type="string"
*     ),
*     @OA\Property(
*         property="uraddress",
*         description="uraddress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="factaddress",
*         description="factaddress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="postaddress",
*         description="postaddress",   
*         type="string"
*     ),
*     @OA\Property(
*         property="bank",
*         description="bank",   
*         type="string"
*     ),
*     @OA\Property(
*         property="bik",
*         description="bik",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="rs",
*         description="rs",   
*         type="string"
*     ),
*     @OA\Property(
*         property="ks",
*         description="ks",   
*         type="string"
*     ),
*     @OA\Property(
*         property="unique_id",
*         description="unique_id",   
*         type="string"
*     ),
*     @OA\Property(
*         property="full_name",
*         description="full_name",   
*         type="string"
*     ),
*     @OA\Property(
*         property="site",
*         description="site",   
*         type="string"
*     ),
*     @OA\Property(
*         property="abc_analize",
*         description="abc_analize",   
*         type="integer"
*     ),
* *     required={ "id" ,"name" ,"account_id" ,"unique_id"},
* )
*/
class Clients extends \yii\db\ActiveRecord
{
		const TYPE_COLD = 10;
		const TYPE_WARM = 20;
		const TYPE_ACTIVE = 30;
		const TYPE_TODEL = 40;

		const TYPE_ABC_A = 10;
		const TYPE_ABC_B = 20;
		const TYPE_ABC_C = 30;

    public static function tableName()
    {
        return 'clients';
    }

    public function rules()
    {
	
	
        return [
            [['name', 'account_id'], 'required'],
            [['name',  'inn', 'ogrn', 'kpp', 'okpo', 'uraddress', 'factaddress', 'postaddress', 'bank', 'rs', 'ks', 'unique_id', 'full_name', 'site'], 'string'],
            [['account_id', 'clienttype_id', 'sphere_id', 'city_id', 'bik', 'abc_analize'], 'default', 'value' => null],
            [['account_id', 'clienttype_id', 'sphere_id', 'city_id', 'bik', 'abc_analize','manager'], 'integer'],
            [['unique_id'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'account_id' => 'Account ID',
            'clienttype_id' => 'Clienttype ID',
            'sphere_id' => 'Sphere ID',
            'manager' => 'Manager',
            'city_id' => 'City ID',
            'inn' => 'Inn',
            'ogrn' => 'Ogrn',
            'kpp' => 'Kpp',
            'okpo' => 'Okpo',
            'uraddress' => 'Uraddress',
            'factaddress' => 'Factaddress',
            'postaddress' => 'Postaddress',
            'bank' => 'Bank',
            'bik' => 'Bik',
            'rs' => 'Rs',
            'ks' => 'Ks',
            'unique_id' => 'Unique ID',
            'full_name' => 'Full Name',
            'site' => 'Site',
            'abc_analize' => 'Abc Analize',
        ];
    }
	
public function beforeSave($insert){
		if (parent::beforeSave($insert)) {
			if($this->unique_id == null){
				$this->unique_id = 'K';
			}
			return true;
		}
		return false;
	}

	public function afterSave($insert, $changedAttributes){
		if($this->unique_id == 'K'){
			$this->unique_id = $this->unique_id . $this->id;
		}
		$this->updateAttributes(['unique_id','id']);
		parent::afterSave($insert, $changedAttributes);
	} 

	 public static function getTypeArray(){
        return [
            self::TYPE_COLD ,
            self::TYPE_WARM ,
            self::TYPE_ACTIVE,
			self::TYPE_TODEL,
        ];
    }

	public static function getTypeMap(){
        $map = [
            ['id' => self::TYPE_COLD, 'name' => 'Холодный'],
            ['id' => self::TYPE_WARM, 'name' => 'Теплый'],
            ['id' => self::TYPE_ACTIVE, 'name' => 'Действующий'],
			['id' => self::TYPE_TODEL, 'name' => 'На удаление'],
		];
		return $map;
	}

	public static function getAbcMap(){
        $map = [
            ['id' => self::TYPE_ABC_A, 'name' => 'A'],
            ['id' => self::TYPE_ABC_B, 'name' => 'B'],
            ['id' => self::TYPE_ABC_C, 'name' => 'C'],
		];
		return $map;
	}
}
