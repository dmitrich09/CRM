<?php

namespace app\controllers;

use Yii;
use app\models\Clients;
use app\models\City;
use app\models\User;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cadyrov\gii\Upload;
use app\models\forms\ClientUploadForm;
use cadyrov\gii\File;
use yii\filters\AccessControl;
use moonland\phpexcel\Excel;
use yii\db\Query;
use yii\web\UploadedFile;
use app\controllers\Dictionary;

class ClientsController extends BaseController
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
    *    tags={"Clients"},
    *    path="/clients",
    *    summary="list clientss",
    *    description="find clients",
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
    *            @OA\Items(ref="#/components/schemas/Clients")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
		
		$id = Yii::$app->request->get('id');
        $query = self::getStringToLike(Yii::$app->request->get('query'));
		
        $q = new Query;		
		$q->select('cli.* , sph.spherename');
		$q->from('clients cli');
		$q->leftJoin('sphere sph', ' cli.sphere_id = sph.id'); 
        $q->andWhere(['cli.account_id' => self::$user->account_id]);		
		$q->orderBy('cli.id asc');

		if ($id) {
			$q->andWhere(['cli.id' => $id]);
		}
		if ($query) {
			$q->andWhere('lower(cli.name) like :q or lower(cli.bank) like :q or lower(cli.inn) like :q  or lower(sph.spherename) like :q ', ['q' => $query ]);
		}
		
		$cnt = $q->count();
		$maxpage = ceil($cnt/Dictionary::QUERY_LIMIT);
		$page = Yii::$app->request->post('page') && Yii::$app->request->post('page') > 0 ? Yii::$app->request->post('page') : 1;
		$page = $page > $maxpage ? $maxpage : $page ;
		$q->limit(Dictionary::QUERY_LIMIT);
		$q->offset(($page-1)*Dictionary::QUERY_LIMIT);
		self::ok($q->all(), 'success', $cnt, $page, Dictionary::QUERY_LIMIT);
    }
	
	  /**
    * @OA\Get(
    *     path="/city/getall",
    *     summary="Restore city",
    *    description="restore  city",
    *     tags={"Clients"},
    *  
    *     @OA\Response(
    *         response=200,
    *         description="OK",
    *     ),
    *     security={{"api_key":{"PHPSESSID"}}}
    * )
    */
	public function actionGetall(){
		
	    $q = Clients::find()->andWhere(['account_id' => self::$user->account_id]);
		return self::ok($q->all() );
	}

    /**
    * @OA\Post(
    *    tags={"Clients"},
    *    path="/clients/create",
    *    summary="create  clients ",
    *    description="create new clients",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Clients")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Clients")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Clients();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
	     if (!$model->save()) {
            return self::error($model->getErrors());
        }
	    self::createLog('clients', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/clients/delete",
    *     summary="Delete clients",
    *    description="delete  clients",
    *     tags={"Clients"},
    *     @OA\Parameter(
    *         description="Clients id to delete",
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
			self::error('Clients not found with id: ' . $id);
			return;
		}
		if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('clients', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/clients/restore",
    *     summary="Restore clients",
    *    description="restore  clients",
    *     tags={"Clients"},
    *     @OA\Parameter(
    *         description="Clients id to delete",
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
			self::error('Clients not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('clients', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Clients"},
    *    path="/clients/update",
    *    summary="update  clients ",
    *    description="update clients",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Clients")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Clients")
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
	
	    $modelNew = new Clients();
        $modelNew->setAttributes(Yii::$app->request->post());
		$model->name = $modelNew->name;
		$model->clienttype_id = $modelNew->clienttype_id; 
		$model->sphere_id = $modelNew->sphere_id; 
		$model->manager = $modelNew->manager; 
		$model->city_id = $modelNew->city_id; 
		$model->inn = $modelNew->inn; 
		$model->ogrn = $modelNew->ogrn; 
		$model->kpp = $modelNew->kpp; 
		$model->okpo = $modelNew->okpo; 
		$model->uraddress = $modelNew->uraddress; 
		$model->factaddress = $modelNew->factaddress; 
		$model->postaddress = $modelNew->postaddress; 
		$model->bank = $modelNew->bank; 
		$model->bik = $modelNew->bik; 
		$model->rs = $modelNew->rs; 
		$model->ks = $modelNew->ks; 
		$model->unique_id = $modelNew->unique_id; 
		$model->full_name = $modelNew->full_name; 
		$model->site = $modelNew->site; 
		$model->abc_analize = $modelNew->abc_analize; 
        
		if($model->save()) {
	        self::updateLog('clients', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }
	
	private function getOne($id) {
        $md = Clients::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
    
public function actionDownloadlist()
    {
		$type = Yii::$app->request->get('type');
		$city = Yii::$app->request->get('city');  
		$abc = Yii::$app->request->get('abc');
		$user = Yii::$app->request->get('user');
		
		$table = Clients::tableName();
		ob_end_clean();
		
		$q = new Query;
		$q->select('c.*, city.name as city_name, u.username');
		$q->from('clients c');
		$q->leftJoin('city', 'city.id = c.city_id');
		$q->leftJoin('user u', 'u.id = c.manager');
		$q->andWhere(['c.account_id' => self::$user->account_id]);
		$q->orderBy('c.id asc');
		
        if ($user) {
		    $q->andWhere(['c.manager' => $user]);
	    }
		if ($city) {
		    $q->andWhere(['c.city_id' => $city]);
	    }
		if ($type) {
		    $q->andWhere(['c.clienttype_id' => $type]);
	    }
		$resarr = $q->all();
//		return  '<pre>'. print_r($resarr, true) . '</pre>';
		
		return Excel::export([
            'format' => 'Xlsx',
			'asAttachment' => true,
            'fileName' => $table,
            'models' => $resarr,
            'columns' => ['id', 'name', 'account_id', 'clienttype_id', 'sphere_id', 'manager',  'inn', 'ogrn', 'kpp', 'okpo', 'uraddress', 'factaddress', 'postaddress', 'bank', 'bik', 'rs', 'ks', 'unique_id', 'full_name', 'site', 'abc_analize', 'deleted_at', 'city_name','city_id', 'username'],
            'headers' => ['id'  => 'id', 'name'  => 'name', 'account_id'  => 'account_id', 'clienttype_id'  => 'clienttype_id', 'sphere_id'  => 'sphere_id', 'manager'  => 'manager', 'inn'  => 'inn', 'ogrn'  => 'ogrn', 'kpp'  => 'kpp', 'okpo'  => 'okpo', 'uraddress'  => 'uraddress', 'factaddress'  => 'factaddress', 'postaddress'  => 'postaddress', 'bank'  => 'bank', 'bik'  => 'bik', 'rs'  => 'rs', 'ks'  => 'ks', 'unique_id'  => 'unique_id', 'full_name'  => 'full_name', 'site'  => 'site', 'abc_analize'  => 'abc_analize', 'deleted_at'  => 'deleted_at','city_name'=> 'city_name','city_id'=> 'city_id', 'username'=>'username' ],
        ]);
    } 


    public function actionUploadlist()
    {
		$user = Yii::$app->request->post('user');
		$type = Yii::$app->request->post('type');
		$city = Yii::$app->request->post('city');   
		
        set_time_limit(5000);
		$res="";
		if (Yii::$app->request->isPost) {
			$upload = UploadedFile::getInstanceByName('file'); 
           
            $path = dirname(__DIR__).'/runtime/temp/';
            if (!file_exists($path) && !mkdir($path)) {
                return 'не удалось создать директорию';
            }
            if ($upload){
				$fileName = 'upload_price_temp.xls';

                if (file_exists($path.$fileName)) {
                    unlink($path.$fileName);
                }
                $upload->saveAs($path.$fileName);
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
                    foreach ($data as $n => $m) { 
						if ($m != null && $this->issetParams($m) == self::RES_TRUE) {
							
							$res .= $this->updateRecord($m, $type, $city, $user);
							if ($res != null) {
								break;
							}
						} else {
							foreach($m as $k=>$v){
								$res .= $this->updateRecord($v, $type, $city, $user);
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
				return self::error(serialize('File in not a upload!'));
			}
        } else {
			return 'is no post';
		}
        self::ok($res);
        return;
    }

    private function updateRecord($v, $type, $city, $user){
		$res = "";
	    if ($v != null && is_array($v)) {
            $isset = $this->issetParams($v);
            if ($isset == self::RES_TRUE) {
				if($user != null && $user != $v['manager']){
				   return;
				}
				if($type != null && $type != $v['clienttype_id']){
				   return;
				}
				if($city != null && $city != $v['city_id']){
				   return;
				}
				
				$model = Clients::findOne(['unique_id' => $v['unique_id']]);
				if ($model == null) {
                    $model = new Clients();
                }
				if($v['id']){
					$model->id = $v['id'];
				} else {
					unset ($v['id']);
				}
				
				$model->setAttributes($v);
				if($v['city_name'] != null){
                    $city = City::findOne(['name' => $v['city_name']]);
					$model->city_id = $city['id'];
				}
				if(isset($v['username']) != null){
                    $user = User::findOne(['username' => $v['username']]);
					$model->manager = $user['id'];
				}
			    if (!($model->validate() && $model->save())) {
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
		$nameArr = [ 'name', 'clienttype_id'];
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
//       return ['array' => $array, 'errors' => $this->error];
//       return $this->error;
		return $result;
	}


	public function actionUpload()
	{
        $model = new Upload();
		$id = Yii::$app->request->post('id');
		$owner = Clients::findOne($id);
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
