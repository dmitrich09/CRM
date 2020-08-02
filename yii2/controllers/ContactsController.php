<?php

namespace app\controllers;

use Yii;
use app\models\Contacts;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cadyrov\gii\Upload;
use cadyrov\gii\File;
use yii\filters\AccessControl;
use moonland\phpexcel\Excel;
use yii\db\Query;
use yii\web\UploadedFile;
use app\controllers\Dictionary;

class ContactsController extends BaseController
{
    const RES_TRUE = 10;
    const RES_FALSE = 20; 
    const RES_NOONE = 30;

    private $error=[];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['index','view','create','delete','update', 'restore', 'downloadlist','uploadlist','download','upload'],
                'rules' => [
                    [
                        'actions' => ['index','view','create','delete','update', 'restore', 'downloadlist','uploadlist','download','upload'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
    * @OA\Get(
    *    tags={"Contacts"},
    *    path="/contacts",
    *    summary="list contactss",
    *    description="find contacts",
    *     @OA\Parameter(
    *         description="id to find",
    *         in="query",
    *         name="id",
    *         required=false,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Parameter(
    *         description="query to find",
    *         in="query",
    *         name="query",
    *         required=false,
    *         @OA\Schema(
    *             type="string",
    *         )
    *     ),
    *     @OA\Parameter(
    *         description="page number",
    *         in="query",
    *         name="page",
    *         required=false,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/Contacts")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
		$id = Yii::$app->request->post('id');
		$clients_id = Yii::$app->request->post('clients_id');
		$query = self::getStringToLike(Yii::$app->request->get('query'));
		
	    $q = Contacts::find()->andWhere(['account_id' => self::$user->account_id]);
	    $q->orderBy('id asc');
       
		if ($id) {
		   $q->where(['id' => $id]);
	    }
		if ($clients_id) {
		   $q->andWhere(['clients_id' => $clients_id]);
	    }
	    if ($query) {
            $q->andWhere('lower(name) like :q', ['q' => $query ]);
        }
        $cnt = $q->count();
        $maxpage = ceil($cnt/Dictionary::QUERY_LIMIT);
        $page = Yii::$app->request->get('page') && Yii::$app->request->get('page') > 0 ? Yii::$app->request->get('page') : 1;
	    $page = $page > $maxpage ? $maxpage : $page ;
        $q->limit(Dictionary::QUERY_LIMIT);
        $q->offset(($page-1)*Dictionary::QUERY_LIMIT);
        self::ok($q->all(), 'success', $cnt, $page, Dictionary::QUERY_LIMIT);
    }

    /**
    * @OA\Post(
    *    tags={"Contacts"},
    *    path="/contacts/create",
    *    summary="create  contacts ",
    *    description="create new contacts",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Contacts")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Contacts")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Contacts();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
	    if (!$model->save()) {
            return self::error($model->getErrors());
        }
	    self::createLog('contacts', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/contacts/delete",
    *     summary="Delete contacts",
    *    description="delete  contacts",
    *     tags={"Contacts"},
    *     @OA\Parameter(
    *         description="Contacts id to delete",
    *         in="query",
    *         name="id",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *     ),
    *     security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionDelete()
	{
		$id = Yii::$app->request->get('id');
		if (!$id) {
			self::error('Send id');
			return;
		}
		$model = $this->getOne($id);
		if (!$model) {
			self::error('Contacts not found with id: ' . $id);
			return;
		}
				if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('contacts', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/contacts/restore",
    *     summary="Restore contacts",
    *    description="restore  contacts",
    *     tags={"Contacts"},
    *     @OA\Parameter(
    *         description="Contacts id to delete",
    *         in="query",
    *         name="id",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *     ),
    *     security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionRestore()
	{
		$id = Yii::$app->request->get('id');
		if (!$id) {
			self::error('Send id');
			return;
		}
		$model = $this->getOne($id);
		if (!$model) {
			self::error('Contacts not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('contacts', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Contacts"},
    *    path="/contacts/update",
    *    summary="update  contacts ",
    *    description="update contacts",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Contacts")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Contacts")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('id');
		if (!$id) {
            return self::error('Send id');
		}
        $model = $this->getOne($id);
        if (!$model) {
            return self::error('Model not found');
		}
        if ($model->account_id !== self::$user->account_id) {
		    return self::error('You can`t update this document');
		}
			if ($model->deleted_at) {
			return self::error('Model deleted ');
		}
		
	    $modelNew = new Contacts();
        $modelNew->setAttributes(Yii::$app->request->post());
		$model->clients_id = $modelNew->clients_id;
		$model->account_id = self::$user->account_id;
		$model->contacttype_id = $modelNew->contacttype_id;
		$model->name = $modelNew->name;
		$model->phone = $modelNew->phone;
		$model->email = $modelNew->email;
		$model->skype = $modelNew->skype;
		$model->comment = $modelNew->comment;
		if ($model->save()) {
	        self::updateLog('contacts', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Contacts::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }

    public function actionDownloadlist()
    {
        $table = Contacts::tableName();
		ob_end_clean();
		$query = new Query;
		$query->select('*')
		->from($table );
		$resarr = $query->all();

        
		return Excel::export([
            'format' => 'Xlsx',
			'asAttachment' => true,
            'fileName' => $table,
            'models' => $resarr,
            'columns' => ['id', 'clients_id', 'account_id', 'contacttype_id', 'name', 'phone', 'email', 'skype', 'comment', 'deleted_at', ],
            'headers' => ['id'  => 'id', 'clients_id'  => 'clients_id', 'account_id'  => 'account_id', 'contacttype_id'  => 'contacttype_id', 'name'  => 'name', 'phone'  => 'phone', 'email'  => 'email', 'skype'  => 'skype', 'comment'  => 'comment', 'deleted_at'  => 'deleted_at', ],
        ]);
    }

    public function actionUploadlist()
    {
        set_time_limit(5000);
        $model = new Upload();
		$res="";
        if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');

            $path = dirname(__DIR__).'/runtime/temp/';
            if (!file_exists($path) && !mkdir($path)) {
                return 'не удалось создать директорию';
            }
            if ($model->file && $model->validate()) {

                $fileName = 'upload_price_temp.xls';

                if (file_exists($path.$fileName)) {
                    unlink($path.$fileName);
                }
                $model->file->saveAs($path.$fileName);
                if (!file_exists($path.$fileName)) {
                    self::error('не удалось сохранить файл');
                    return;
                }
				ob_end_clean();
                $data =Excel::import($path.$fileName,
                    ['setFirstRecordAsKeys' => true,
                    'setIndexSheetByName' => true,]);
                if (!is_array($data)) {
                    self::error('не удалось разобрать файл');
                    return;
                }

                if (is_array($data) && count($data) > 0) {
					Contacts::deleteAll();
                    foreach ($data as $n => $m) {
						if ($m != null && $this->issetParams($m) == self::RES_TRUE) {
							$res .= $this->updateRecord($m);
							if ($res != null) {
								break;
							}
						} else {
							foreach($m as $k=>$v){
								$res .= $this->updateRecord($v);
								if ($res != null) {
									break;
								}
							}
						}
					}
                } else {
					return self::error(serialize($data));
				}
            } else {
				return self::error(serialize($model->getErrors()));
			}
        } else {
			return 'is no post';
		}
        self::ok($res);
        return;
    }

    private function updateRecord($v){
		$res = "";
        if ($v != null && is_array($v)) {
            $isset = $this->issetParams($v);
            if ($isset == self::RES_TRUE) {
                $model = Contacts::findOne($v['id']);
                if ($model == null) {
                    $model = new Contacts();
                }
				if($v['id']){
					$model->id = $v['id'];
				} else {
					unset ($v['id']);
				}
                $model->setAttributes($v);
                if ($model->validate()) {
					                    $model->save();
                } else {
                    return (serialize($model->getErrors()));
                }

            } elseif ($isset == self::RES_FALSE) {
                return ('Не все параметры переданы');
            }
        }
		return $res;
	}

	private function issetParams(array $array){
		$this->error = [];
		$nameArr = ['id', 'clients_id', 'account_id', 'contacttype_id', 'name', 'phone', 'email', 'skype', 'comment', 'deleted_at', ];
		$result = self::RES_FALSE;
		$all = self::RES_FALSE;
		$one = self::RES_TRUE;
		foreach ($nameArr as $name) {
			if (!isset($array[$name])) {
				$this->error[] = $name;
				$one = self::RES_FALSE;
			} else {
				$all = self::RES_TRUE;
			}
		}

		if ($all == self::RES_FALSE) {
			$result = self::RES_NOONE;
		} elseif ($one == self::RES_FALSE) {
			$result = self::RES_FALSE;
		} else {
			$result = self::RES_TRUE;
		}

		return $result;
	}


	public function actionUpload()
	{
        $model = new Upload();
		$id = Yii::$app->request->post('id');
		$owner = Contacts::findOne($id);
        if (Yii::$app->request->isPost && $owner != null) {
			$model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
			if($model->validate()){
				$fl = new File();
				$fl->user_id = Yii::$app->user->identity->id;
				$fl->add_date = date ("Y-m-d H:i:s");
				$fl->owner_id = $owner->id;
				$fl->name = $model->file->name;
				$fl->ext = $model->file->extension;
				if ($fl->validate()) {
					$fl->save();
					if(!$fll->saveAs('path'.$fl->file_id)){
						$fl->delete();
					}
				}
			} else {
				//error code
            }
            return self::ok($owner);
        }
    }

	public function actionDownload()
	{
		if(Yii::$app->request->post('file_id')){
			$fl=File::findOne(Yii::$app->request->post('file_id'));
			if($fl!=null){
				$path='path'.$fl->file_id;
				if (file_exists($path)) {
					if (ob_get_level()) {
					  ob_end_clean();
					}
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename=' .$fl->name);
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($path));
					readfile($path);
					exit;
				}else{
					return self::error('file don`t exist in store');
				}
			}else{
				return self::error('file not found ');
			}
		}else{
			return self::error('Sent id');
		}
	}
}
