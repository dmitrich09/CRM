<?php

namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Files;
use Yii;

class Upload extends Model{
    /**
     * @var UploadedFile
     */
    public $imageFiles;
	public $clients_id;
	public $application_id;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false,
                'maxFiles' => 10,
                'maxSize' => 1024 * 1024 * 150
                ],
            [['clients_id'], 'required', 'message' => 'Id клиента'],
			[['application_id'],'filter','filter'=>'trim'],
        ];
    }
    
}












?>