<?php

namespace app\controllers;

use Yii;
use app\models\Agreement;
use app\models\Kp;
use app\models\Application;
use app\models\Clients;
use app\models\Item;
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

class AgreementController extends BaseController
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
    *    tags={"Agreement"},
    *    path="/agreement",
    *    summary="list agreements",
    *    description="find agreement",
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
    *            @OA\Items(ref="#/components/schemas/Agreement")
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
		
		$dateFrom = Yii::$app->request->post('dateFrom');
		$dateTo = Yii::$app->request->post('dateTo');
		$active = Yii::$app->request->post('active');
		$userId = Yii::$app->request->post('userId');
		
		$q = new Query;
		$q->select('agr.*, c.name, c.city_id');
		$q->from('agreement agr');
		$q->leftJoin('clients c', 'agr.clients_id = c.id');
		$q->andWhere(['agr.account_id' => self::$user->account_id]);
		$q->orderBy('agr.id asc');
		
        if ($id) {
		    $q->where(['agr.id' => $id]);
	    }
		if ($userId) {
		    $q->andWhere(['agr.manager_id' => $userId]);
	    }
		if ($clients_id) {
		   $q->andWhere(['clients_id' => $clients_id]);
	    }
		if ($dateFrom) {
		    $q->andWhere('agr.startdate >= :dateFrom' , [ 'dateFrom' => date("Y-m-d H:i:s" , strtotime ($dateFrom) ) ]);
	    }
		if ($dateTo) {
		    $q->andWhere('agr.startdate <= :dateTo' , ['dateTo' => date("Y-m-d H:i:s" , strtotime ($dateTo) ) ]);
	    }
		if ($query) {
			$q->andWhere('lower(agr.comment) like :q or lower(agr.description) like :q ' , ['q' => $query ]); 
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
    *    tags={"Agreement"},
    *    path="/agreement/create",
    *    summary="create  agreement ",
    *    description="create new agreement",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Agreement")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Agreement")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
		$res = Agreement::getCurrentMaxNumber();
		
		$agreement = new Agreement();
        $appModel = new Application();
		$agreement->setAttributes(Yii::$app->request->post());
		
		$kpModel = Kp::findOne($agreement->kp_id);
		if($kpModel == null){
		    self::error('kp is not exsist!');
		}

		$appModel = Application::findOne($kpModel->application_id);
		if($appModel == null){
		    self::error('application is not exsist!');
		}
		
		$kpModel->enddate=date('Y-m-d H:i:s');
		$kpModel->state= Kp::STATUS_ACCEPT;
		$agreement->contactdate=date('Y-m-d H:i:s',strtotime($agreement->contactdate));
		$agreement->numberagree= Agreement::getCurrentMaxNumber()+1;
		$agreement->startdate=date('Y-m-d H:i:s');
		$agreement->agreedate=date('Y-m-d H:i:s');
		$agreement->manager_id=$kpModel->manager_id;
		$agreement->source_id=$kpModel->source_id;
		$agreement->state= Agreement::STATUS_NEW;
		$agreement->clients_id=$appModel->clients_id;
		$agreement->application_id=$kpModel->application_id;
		$agreement->doc_on_hand=$kpModel->doc_on_hand;
		$agreement->is_lpr=$kpModel->is_lpr;
		$agreement->another_offer=$kpModel->another_offer;
		$agreement->doc_for_what=$kpModel->doc_for_what;
		$agreement->account_id = self::$user->account_id;
		
		if(!$kpModel->save()) {
			return self::error($kpModel->getErrors());
		}
		Item::updateLinkedCost($kpModel->application_id);
		if (!$agreement->save()) {
			return self::error($agreement->getErrors());
		}
		
	    self::createLog('agreement', serialize($agreement));
        return self::ok($agreement);
    }

    /**
    * @OA\Delete(
    *     path="/agreement/delete",
    *     summary="Delete agreement",
    *    description="delete  agreement",
    *     tags={"Agreement"},
    *     @OA\Parameter(
    *         description="Agreement id to delete",
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
			self::error('Agreement not found with id: ' . $id);
			return;
		}
				if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('agreement', $id);
		return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/agreement/restore",
    *     summary="Restore agreement",
    *    description="restore  agreement",
    *     tags={"Agreement"},
    *     @OA\Parameter(
    *         description="Agreement id to delete",
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
			self::error('Agreement not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('agreement', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Agreement"},
    *    path="/agreement/update",
    *    summary="update  agreement ",
    *    description="update agreement",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Agreement")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Agreement")
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
	        $modelNew = new Agreement();
        $modelNew->setAttributes(Yii::$app->request->post());
		$model->account_id = self::$user->account_id;
		
	    $model->numberagree = $modelNew->numberagree;
		$model->agreedate = ($modelNew->agreedate ? date("Y-m-d H:i:s", strtotime($modelNew->agreedate)) : null);
		$model->startdate = ($modelNew->startdate ? date("Y-m-d H:i:s", strtotime($modelNew->startdate)) : null);
		$model->enddate = ($modelNew->enddate ? date("Y-m-d H:i:s", strtotime($modelNew->enddate)) : null);  
		$model->clients_id = $modelNew->clients_id;
		$model->person = $modelNew->person;
		$model->manager_id = $modelNew->manager_id;
		$model->kp_id = $modelNew->kp_id; 
		$model->state = $modelNew->state;
		$model->tax = $modelNew->tax;
		$model->total = $modelNew->total;
		$model->our_cost = $modelNew->our_cost;
		$model->source_id = $modelNew->source_id;
		$model->debt = $modelNew->debt;
		$model->contactdate = ($modelNew->contactdate ? date("Y-m-d H:i:s", strtotime($modelNew->contactdate)) : null);
		$model->short_person = $modelNew->short_person;
		$model->basement = $modelNew->basement;
		$model->declinematter_id = $modelNew->declinematter_id;
		$model->doc_on_hand = $modelNew->doc_on_hand;
		$model->is_lpr = $modelNew->is_lpr;
		$model->another_offer = $modelNew->another_offer; 
		$model->doc_for_what = $modelNew->doc_for_what;
		$model->options = $modelNew->options;
		$model->application_id = $modelNew->application_id;
		$model->comment = $modelNew->comment;   
		if ($model->save()) {
	    self::updateLog('agreement', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }

     
    private function getOne($id) {
        $md = Agreement::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
	
	public function actionLearn(){
        $this->layout = 'docs';
		 return $this->render('learn');
    }
    
	public function actionPrint(){
        $this->layout = 'docs';
		$model= Agreement::findOne(Yii::$app->request->get('id'));
		$stamp=Yii::$app->request->get('stamp');
		return $this->printDoc($model,$stamp);
    }

	public function actionBill(){
        $this->layout = 'docs';
		return $this->printBill(Yii::$app->request->get('id'),Yii::$app->request->get('stamp'));
    }

	public function printBill($agrId,$stamp){
		$agreement= Agreement::findOne($agrId);
	    $clients=Clients::findOne($agreement->clients_id);
		if($agreement->tax== Agreement::TAX_NDS){
            return $this->render('billNds',['agreement'=>$agreement,'client'=>$clients,'stamp'=>$stamp]);
        }
		return $this->render('billNoNds',['agreement'=>$agreement,'client'=>$clients,'stamp'=>$stamp]);
	}

	public function printDoc($agreement,$stamp){
		$clients=Clients::findOne($agreement->clients_id);
		$phone="";
			foreach(Contacts::find()->where(['clients_id'=>$agreement->clients_id])->all() as $cn){
				if($cn->phone!=null){
					$phone=Contacts::getNumFormat($cn->phone);
					break;
				}
			}
        if($agreement->tax== Agreement::TAX_NDS){
            return $this->render('printNds',['agreement'=>$agreement,'client'=>$clients,'phone'=>$phone,'stamp'=>$stamp]);
        }
		return $this->render('printNoNds',['agreement'=>$agreement,'client'=>$clients,'phone'=>$phone,'stamp'=>$stamp]);
	}
	
	
    public function actionDownloadlist()
    {
        $table = Agreement::tableName();
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
            'columns' => ['id', 'numberagree', 'account_id', 'agreedate', 'startdate', 'enddate', 'clients_id', 'person', 'manager_id', 'kp_id', 'state', 'tax', 'total', 'our_cost', 'source_id', 'debt', 'contactdate', 'short_person', 'basement', 'declinematter_id', 'doc_on_hand', 'is_lpr', 'another_offer', 'doc_for_what', 'options', 'application_id', 'comment', 'deleted_at', ],
            'headers' => ['id'  => 'id', 'numberagree'  => 'numberagree', 'account_id'  => 'account_id', 'agreedate'  => 'agreedate', 'startdate'  => 'startdate', 'enddate'  => 'enddate', 'clients_id'  => 'clients_id', 'person'  => 'person', 'manager_id'  => 'manager_id', 'kp_id'  => 'kp_id', 'state'  => 'state', 'tax'  => 'tax', 'total'  => 'total', 'our_cost'  => 'our_cost', 'source_id'  => 'source_id', 'debt'  => 'debt', 'contactdate'  => 'contactdate', 'short_person'  => 'short_person', 'basement'  => 'basement', 'declinematter_id'  => 'declinematter_id', 'doc_on_hand'  => 'doc_on_hand', 'is_lpr'  => 'is_lpr', 'another_offer'  => 'another_offer', 'doc_for_what'  => 'doc_for_what', 'options'  => 'options', 'application_id'  => 'application_id', 'comment'  => 'comment', 'deleted_at'  => 'deleted_at', ],
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
					Agreement::deleteAll();
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
                $model = Agreement::findOne($v['id']);
                if ($model == null) {
                    $model = new Agreement();
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
		$nameArr = ['id', 'numberagree', 'account_id', 'agreedate', 'startdate', 'enddate', 'clients_id', 'person', 'manager_id', 'kp_id', 'state', 'tax', 'total', 'our_cost', 'source_id', 'debt', 'contactdate', 'short_person', 'basement', 'declinematter_id', 'doc_on_hand', 'is_lpr', 'another_offer', 'doc_for_what', 'options', 'application_id', 'comment', 'deleted_at', ];
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
		$owner = Agreement::findOne($id);
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
