<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Settings model",
*     type="object",
*     title="Settings ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="ip",
*         description="ip",   
*         type="string"
*     ),
* *     required={ "id"},
* )
*/
class Settings extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'settings';
    }

    public function rules()
    {
        return [
            [['ip'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
        ];
    }

/**
    const TYPE_A;
    const TYPE_B;

    public static function getTypeArray()
    {
        return [
            self::TYPE_A,
            self::TYPE_B,
        ];
    }

    public static function getTypeMap()
    {
        $map = [
            self::TYPE_A => 'A',
            self::TYPE_B => 'B',
        ];
        return $map;
    }

    public static function getTypeById($id)
    {
        return ArrayHelper::getValue(self::getTypeMap(), $id);
    }

    public static function map()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }

    //rule custom
    ['type', 'in', 'range' => self::getTypeArray()],

    [['a', 'b'], 'validateNumber', 'when' => function ($data) {
        if ($data->a == null && $data->b == null && $data->tnved == null) {
            return true;
        }
        return false;
    }, 'whenClient' => "function (attribute, value) {
        return $('#a').val() == '' && $('#b').val() == '';
    }", 'message' => 'Необходимо заполнить хотя бы одно из полей a, b],



    [['a', 'b'], 'validateNumber']

    public function validateNumber($attribute, $params)
	{
		$dd = Deal::find()->where('deal_id <> :dl and number = :num',[
			'dl' => $this->deal_id,
			'num' => $this->number
		])->one();
		if ($dd) {
			$this->addError($attribute, 'Такой номер уже зарезервирован');
		}
	}


*/





}
