<?php

namespace app\controllers;

use Yii;
use app\models\Comments;
use app\models\Lead;
use app\models\Task;
use app\models\Agreeement;
use app\models\Application;
use app\models\Outcall;
use app\models\Kp;
use app\models\Opk;
use app\models\Agreement;
use app\models\Ork;
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

class CommentsController extends BaseController 
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
    *    tags={"Comments"},
    *    path="/comments",
    *    summary="list commentss",
    *    description="find comments",
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
    *            @OA\Items(ref="#/components/schemas/Comments")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
		$type = Yii::$app->request->post('type');
		$object_id = Yii::$app->request->post('object_id');
	    $query = self::getStringToLike(Yii::$app->request->post('query'));
		
	    $q = Comments::find()->andWhere(['account_id' => self::$user->account_id]);
	  
        if($type !=null && $object_id !=null){
			$q->andWhere(['object_id' => $object_id])->andWhere(['type' => $type]);  
		}
	    if ($query) {
            $q->andWhere('lower(name) like :q', ['q' => $query ]);
        }
		$q->orderBy('id asc');
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
    *    tags={"Comments"},
    *    path="/comments/create",
    *    summary="create  comments ",
    *    description="create new comments",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Comments")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Comments")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {			
		
		$type = Yii::$app->request->post('type');
		$object_id = Yii::$app->request->post('object_id');
		$message = Yii::$app->request->post('message');
		
		if($type == Comments::TYPE_LEAD){
			$lead = Lead::findOne(['id' => $object_id]);
			if($lead != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('lead', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			}
		}
		if($type == Comments::TYPE_APPLICATION){
 			$appl = Application::findOne(['id' => $object_id]);
			if($appl != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('application', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		if($type == Comments::TYPE_OUTCALL){
 			$outcall = Outcall::findOne(['id' => $object_id]);
			if($outcall != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('outcall', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		if($type == Comments::TYPE_KP){
 			$kp = Kp::findOne(['id' => $object_id]);
			if($kp != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('kp', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		if($type == Comments::TYPE_AGREEMENT){
 			$agreement = Agreement::findOne(['id' => $object_id]);
			if($agreement != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('agreement', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		if($type == Comments::TYPE_ORK){
 			$ork = Ork::findOne(['id' => $object_id]);
			if($ork != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('ork', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		if($type == Comments::TYPE_TASK){
 			$task = Task::findOne(['id' => $object_id]);
			if($task != null ){
				$res = Yii::$app->db
						->createCommand()
						->update('task', ['comment' =>  $message], ['id' => $object_id])
						->execute();
			}else{
				return self::error('this object is not exist!');
			} 
		}
		
		$res = Yii::$app->db->createCommand()->insert('comments', [
			'message' => $message,
			'type' => $type,
			'object_id' => $object_id,
			'user_id' => self::$user->id,
			'account_id' => self::$user->account_id,
			'adddate' => date('Y-m-d H:i:s'),
		])->execute();
		
		if($res != 1){
			return self::error('Error save comments!');
		}
		
        return self::ok();
    }

    /**
    * @OA\Delete(
    *     path="/comments/delete",
    *     summary="Delete comments",
    *    description="delete  comments",
    *     tags={"Comments"},
    *     @OA\Parameter(
    *         description="Comments id to delete",
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
			self::error('Comments not found with id: ' . $id);
			return;
		}
				if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('comments', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/comments/restore",
    *     summary="Restore comments",
    *    description="restore  comments",
    *     tags={"Comments"},
    *     @OA\Parameter(
    *         description="Comments id to delete",
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
			self::error('Comments not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('comments', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Comments"},
    *    path="/comments/update",
    *    summary="update  comments ",
    *    description="update comments",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Comments")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Comments")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('object_id');
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
	    $modelNew = new Comments();
        $modelNew->setAttributes(Yii::$app->request->post());
		
	    $model->message = $modelNew->message;
		$model->account_id = self::$user->account_id;
		$model->object_id = $modelNew->object_id;
		$model->type = $modelNew->type;
		$model->user_id = $modelNew->user_id;
		$model->adddate =  date("Y-m-d H:i:s");  
		
		if ($model->save()) {
	        self::updateLog('comments', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Comments::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }

    public function actionDownloadlist()
    {
        $table = Comments::tableName();
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
            'columns' => ['id', 'message', 'account_id', 'object_id', 'type', 'user_id', 'adddate', 'deleted_at', ],
            'headers' => ['id'  => 'id', 'message'  => 'message', 'account_id'  => 'account_id', 'object_id'  => 'object_id', 'type'  => 'type', 'user_id'  => 'user_id', 'adddate'  => 'adddate', 'deleted_at'  => 'deleted_at', ],
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
					Comments::deleteAll();
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
                $model = Comments::findOne($v['id']);
                if ($model == null) {
                    $model = new Comments();
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
		$nameArr = ['id', 'message', 'account_id', 'object_id', 'type', 'user_id', 'adddate', 'deleted_at', ];
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
		$owner = Comments::findOne($id);
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
