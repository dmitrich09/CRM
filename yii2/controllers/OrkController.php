<?php

namespace app\controllers;

use Yii;
use app\models\Ork;
use app\models\Agreement;
use app\models\Payment;
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

class OrkController extends BaseController
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
    *    tags={"Ork"},
    *    path="/ork",
    *    summary="list orks",
    *    description="find ork",
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
    *            @OA\Items(ref="#/components/schemas/Ork")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
		  
		$id = Yii::$app->request->post('id');
		$query = self::getStringToLike(Yii::$app->request->get('query'));
		$clients_id = Yii::$app->request->post('clients_id');
		
		$dateFrom = Yii::$app->request->post('dateFrom');
		$dateTo = Yii::$app->request->post('dateTo');
		$active = Yii::$app->request->post('active');
		$userId = Yii::$app->request->post('userId');
		
		$q = new Query;
		$q->select('o.*, c.name, c.city_id, c.id as client_id, agr.debt,agr.total,agr.our_cost');
		$q->from('ork o');
		$q->leftJoin('clients c', 'o.clients_id = c.id');
		$q->leftJoin('agreement agr', 'o.agreement_id = agr.id');
		$q->andWhere(['o.account_id' => self::$user->account_id]);
		$q->orderBy('o.id asc');
      
        if ($id) {
		    $q->where(['o.id' => $id]);
	    }
		if ($userId) {
		    $q->andWhere(['o.manager_id' => $userId]);
	    }
		if ($clients_id) {
		   $q->andWhere(['o.clients_id' => $clients_id]);
	    }
		if ($dateFrom) {
		    $q->andWhere('o.startdate >= :dateFrom' , [ 'dateFrom' => date("Y-m-d H:i:s" , strtotime ($dateFrom) ) ]);
	    }
		if ($dateFrom) {
		    $q->andWhere('o.startdate <= :dateTo' , ['dateTo' => date("Y-m-d H:i:s" , strtotime ($dateTo) ) ]);
	    }
		if ($query) {
			$q->andWhere('lower(o.comment) like :q ' , ['q' => $query ]); 
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
    * @OA\Post(
    *    tags={"Ork"},
    *    path="/ork/create",
    *    summary="create  ork ",
    *    description="create new ork",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Ork")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Ork")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $ork = new Ork();
        $ork->setAttributes(Yii::$app->request->post());
		
		$agreement = Agreement::findOne($ork->agreement_id);

		$agreement->enddate = date('Y-m-d H:i:s');
        $agreement->state = Agreement::STATUS_ACCEPT;
		
		$ork->startdate = date('Y-m-d H:i:s');
        $ork->state = Ork::STATUS_NEW;
        $ork->source_id = $agreement->source_id;
        $ork->clients_id = $agreement->clients_id;
		$ork->signagree = 20;
		$ork->signact = 20;
		$ork->contactdate = date('Y-m-d H:i:s',strtotime($ork->contactdate));
		$ork->pay_date = date('Y-m-d H:i:s',strtotime($ork->pay_date));
		$ork->clientdoc_date = date('Y-m-d H:i:s',strtotime($ork->clientdoc_date));
		$ork->close_date = date('Y-m-d H:i:s',strtotime($ork->close_date));
		$ork->account_id = self::$user->account_id;  
		
		Payment::agreementDebt($agreement->id);
		if(!$ork->save()) {
			return self::error($ork->getErrors());
		}

		if (!$agreement->save()) {
			return self::error( $agreement->getErrors());
		}
		self::createLog('ork', serialize($ork));
        return self::ok($ork);
    }

    /**
    * @OA\Delete(
    *     path="/ork/delete",
    *     summary="Delete ork",
    *    description="delete  ork",
    *     tags={"Ork"},
    *     @OA\Parameter(
    *         description="Ork id to delete",
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
			self::error('Ork not found with id: ' . $id);
			return;
		}
				if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('ork', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/ork/restore",
    *     summary="Restore ork",
    *    description="restore  ork",
    *     tags={"Ork"},
    *     @OA\Parameter(
    *         description="Ork id to delete",
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
			self::error('Ork not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('ork', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Ork"},
    *    path="/ork/update",
    *    summary="update  ork ",
    *    description="update ork",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Ork")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Ork")
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
	    $modelNew = new Ork();
        $modelNew->setAttributes(Yii::$app->request->post());
		$model->agreement_id =  self::$user->account_id;
		$model->account_id = $modelNew->account_id;
		$model->startdate = ($modelNew->startdate ? date("Y-m-d H:i:s", strtotime($modelNew->startdate)) : null);
		$model->enddate = ($modelNew->enddate ? date("Y-m-d H:i:s", strtotime($modelNew->enddate)) : null);
		$model->state = $modelNew->state;
		$model->signagree = $modelNew->signagree;
		$model->signact = $modelNew->signact; 
		$model->clients_id = $modelNew->clients_id; 
		$model->manager_id = $modelNew->manager_id; 
		$model->contactdate = ($modelNew->contactdate ? date("Y-m-d H:i:s", strtotime($modelNew->contactdate)) : null);
		$model->source_id = $modelNew->source_id;
		$model->pay_date = ($modelNew->pay_date ? date("Y-m-d H:i:s", strtotime($modelNew->pay_date)) : null);
		$model->provider_debt = $modelNew->provider_debt; $model->clientdoc_date = ($modelNew->clientdoc_date ? date("Y-m-d H:i:s", strtotime($modelNew->clientdoc_date)) : null);
		$model->close_date = ($modelNew->close_date ? date("Y-m-d H:i:s", strtotime($modelNew->close_date)) : null);
		$model->declinematter_id = $modelNew->declinematter_id;
		$model->comment = $modelNew->comment;
		$model->sert_num = $modelNew->sert_num;
		$model->get_date = ($modelNew->get_date ? date("Y-m-d H:i:s", strtotime($modelNew->get_date)) : null);
		$model->license_to = ($modelNew->license_to ? date("Y-m-d H:i:s", strtotime($modelNew->license_to)) : null); 
		$model->act_num = $modelNew->act_num; $model->act_organ = $modelNew->act_organ; 
		
        if ($model->save()) {
	    self::updateLog('ork', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }
	



    private function getOne($id) {
        $md = Ork::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }

    public function actionDownloadlist()
    {
        $table = Ork::tableName();
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
            'columns' => ['id', 'agreement_id', 'account_id', 'startdate', 'enddate', 'state', 'signagree', 'signact', 'clients_id', 'manager_id', 'contactdate', 'source_id', 'pay_date', 'provider_debt', 'clientdoc_date', 'close_date', 'declinematter_id', 'comment', 'sert_num', 'get_date', 'license_to', 'act_num', 'act_organ', 'deleted_at', ],
            'headers' => ['id'  => 'id', 'agreement_id'  => 'agreement_id', 'account_id'  => 'account_id', 'startdate'  => 'startdate', 'enddate'  => 'enddate', 'state'  => 'state', 'signagree'  => 'signagree', 'signact'  => 'signact', 'clients_id'  => 'clients_id', 'manager_id'  => 'manager_id', 'contactdate'  => 'contactdate', 'source_id'  => 'source_id', 'pay_date'  => 'pay_date', 'provider_debt'  => 'provider_debt', 'clientdoc_date'  => 'clientdoc_date', 'close_date'  => 'close_date', 'declinematter_id'  => 'declinematter_id', 'comment'  => 'comment', 'sert_num'  => 'sert_num', 'get_date'  => 'get_date', 'license_to'  => 'license_to', 'act_num'  => 'act_num', 'act_organ'  => 'act_organ', 'deleted_at'  => 'deleted_at', ],
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
					Ork::deleteAll();
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
                $model = Ork::findOne($v['id']);
                if ($model == null) {
                    $model = new Ork();
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
		$nameArr = ['id', 'agreement_id', 'account_id', 'startdate', 'enddate', 'state', 'signagree', 'signact', 'clients_id', 'manager_id', 'contactdate', 'source_id', 'pay_date', 'provider_debt', 'clientdoc_date', 'close_date', 'declinematter_id', 'comment', 'sert_num', 'get_date', 'license_to', 'act_num', 'act_organ', 'deleted_at', ];
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
		$owner = Ork::findOne($id);
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
