<?php

namespace app\models\forms;
use yii\base\Model;
use yii\web\UploadedFile;

class ClientUploadForm extends Model
{
    public $file;
	public $city_id;
	public $clienttype_id;
	public $abc_analize;

    public function rules()
    {
        return [
            [['file'], 'file'],
			[['city_id','clienttype_id','abc_analize'],'filter','filter'=>'trim'],

        ];
    }

	public function isEmpty(){
		if ($this->abc_analize == null
			&& $this->city_id == null
			&& $this->clienttype_id == null) {
			return true;
		}
	}
}
