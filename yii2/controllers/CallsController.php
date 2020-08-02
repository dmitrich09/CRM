<?php

namespace app\controllers;

use Yii;
use app\models\Calls;
use app\models\User;
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
use app\models\forms\Callreport;

class CallsController extends BaseController
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
    *    tags={"Calls"},
    *    path="/calls",
    *    summary="list callss",
    *    description="find calls",
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
    *            @OA\Items(ref="#/components/schemas/Calls")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
		$id = Yii::$app->request->get('id');
		$clients_id = Yii::$app->request->post('clients_id');
	    $query = self::getStringToLike(Yii::$app->request->get('query'));
		
	    $q = Calls::find()->andWhere(['account_id' => self::$user->account_id]);
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
    *    tags={"Calls"},
    *    path="/calls/create",
    *    summary="create  calls ",
    *    description="create new calls",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Calls")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Calls")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Calls();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
		if ($model->callstart) {
			$model->callstart = ($model->callstart ? date("Y-m-d H:i:s", strtotime($model->callstart)) : null);
			}        
		if (!$model->save()) {
			return self::error($model->getErrors());
		}
	    self::createLog('calls', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/calls/delete",
    *     summary="Delete calls",
    *    description="delete  calls",
    *     tags={"Calls"},
    *     @OA\Parameter(
    *         description="Calls id to delete",
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
			self::error('Calls not found with id: ' . $id);
			return;
		}
				if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
					self::deleteLog('calls', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/calls/restore",
    *     summary="Restore calls",
    *    description="restore  calls",
    *     tags={"Calls"},
    *     @OA\Parameter(
    *         description="Calls id to delete",
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
			self::error('Calls not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('calls', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}
    /**
    * @OA\Post(
    *    tags={"Calls"},
    *    path="/calls/update",
    *    summary="update  calls ",
    *    description="update calls",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Calls")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Calls")
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
		
	    $modelNew = new Calls();
        $modelNew->setAttributes(Yii::$app->request->post());
        $model->zad_call_id = $modelNew->zad_call_id;
		$model->account_id = $modelNew->account_id;
		$model->sip = $modelNew->sip;
		$model->callstart = ($modelNew->callstart ? date("Y-m-d H:i:s", strtotime($modelNew->callstart)) : null);
		$model->clid = $modelNew->clid;
		$model->destination = $modelNew->destination;
		$model->disposition = $modelNew->disposition;
		$model->seconds = $modelNew->seconds;
		$model->is_recorded = $modelNew->is_recorded;
		$model->clients_id = $modelNew->clients_id;
		$model->user_id = $modelNew->user_id;
		$model->incoming = $modelNew->incoming;
		$model->is_file = $modelNew->is_file;
		$model->is_warm = $modelNew->is_warm; 
        if ($model->save()) {
	        self::updateLog('calls', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Calls::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
	
	public function actionZadarmacalls(){
		
		$callrep = new Callreport();
		$callrep->load(Yii::$app->request->post());
		$callrep->setDefault();
		if(Yii::$app->request->get('allrep')==1){
			$callrep->from=Yii::$app->request->get('from');
			$callrep->to=Yii::$app->request->get('to');
			$callrep->user_id=Yii::$app->request->get('user_id');
			$callrep->type=Yii::$app->request->get('type');
		}
		if (!Yii::$app->request->post('add-button')
			&&Yii::$app->request->get('allrep')!=1
			&&Yii::$app->session->has('callrep')){
				$callrep=Yii::$app->session->get('callrep');
		}
		Yii::$app->session->set('callrep',$callrep);

        $qr = (new Query());
		$qr->select('cll.*, cl.name');
		$qr->from('calls cll');
		$qr->where('callstart>=:from and callstart<=:to',['from'=>$callrep->getFromInMysql(),'to'=>$callrep->getToInMysql()]);
		if($callrep->type!=null){
			$qr->andWhere(['incoming'=>$callrep->type]);
		}
		if($callrep->typecold!=null){
			if($callrep->typecold==1){
				$qr->andWhere(['is_warm'=>1]);
			}else{
				$qr->andWhere('is_warm <>1 or is_warm is null');
			}

		}
		if($callrep->user_id!=null){
			$qr->andWhere(['user_id'=>$callrep->user_id]);
		}
		if($callrep->number!=null){
			$qr->andWhere('destination like :num or disposition like :num',['num'=>'%'.Contacts::onlyNum($callrep->number).'%']);
		}

			$qr->andWhere('seconds>0');


		$qr->leftJoin('clients cl', 'cl.clients_id = cll.clients_id');
		$qr->orderBy(['callstart'=>SORT_DESC]);

		//return print_r($qr->createCommand()->getRawSql());
		//return $this->render('zadarmacalls',['callreport'=>$callrep,'query'=>$qr]);
		       return self::ok(['callreport' => $callrep, 'query'=>$qr]);
	}

	public function actionCallreport(){
		
		$callrep= new Callreport();
		$callrep->load(Yii::$app->request->post());
		$callrep->setDefault();
		$qr = (new Query());
		$qr->select('cll.*');
		$qr->from('calls cll');
		$qr->where('callstart>=:from and callstart<=:to',['from'=>$callrep->getFromInMysql(),'to'=>$callrep->getToInMysql()]);
		$rows=$qr->all();
		$reparr=[];
		$users=User::getManagerOrk();
		foreach($users as $id => $name){
			$reparr[$id]['in'] = 0;
			$reparr[$id]['intime']=0;
			$reparr[$id]['out']=0;
			$reparr[$id]['outtime']=0;
			$reparr[$id]['fail']=0;
			$reparr[$id]['name']=$name;
		}

		foreach($rows as $cl){
			if(isset($reparr[$cl['user_id']])){
				if($cl['incoming']==1){
					if($cl['seconds']!=0){
						$reparr[$cl['user_id']]['in']+=1;
						$reparr[$cl['user_id']]['intime']+=$cl['seconds'];
					}
				}else{
					if($cl['seconds']!=0){
						$reparr[$cl['user_id']]['out']+=1;
						$reparr[$cl['user_id']]['outtime']+=$cl['seconds'];
					}else{
						$reparr[$cl['user_id']]['fail']+=1;
					}
				}
			}
		}
		//return $this->render('callreport',['callreport'=>$callrep,'report'=>$reparr]);
		return self::ok(['callreport' => $callrep, 'report'=>$reparr]);  
	}

  

 
}
