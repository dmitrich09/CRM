<?php

namespace app\controllers;

use Yii;
use app\models\Planned;
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

class PlannedController extends BaseController
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
                'only' => ['index','create','delete','update', 'restore'],
                'rules' => [
                    [
                        'actions' => ['index','create','delete','update', 'restore'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
    * @OA\Get(
    *    tags={"Planned"},
    *    path="/planned",
    *    summary="list planneds",
    *    description="find planned",
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/Planned")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
	$dataQuery = Planned::find()->andWhere(['account_id' => self::$user->account_id]);
        $id = Yii::$app->request->get('id');
        if ($id) {
		$dataQuery->where(['id' => $id]);
	}
        self::ok($dataQuery->all());
    }

    /**
    * @OA\Post(
    *    tags={"Planned"},
    *    path="/planned/create",
    *    summary="create  planned ",
    *    description="create new planned",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Planned")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Planned")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Planned();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
	    if ($model->planned_date) {
            $model->planned_date = date("Y-m-d H:i:s", strtotime($model->planned_date));
        }   
        
//        $pl=Planned::find()->where(['planned_date'=>$model->planned_date])->one();
//			if($pl!=null){
//				$pl->delete();
//			}		
        if (!$model->save()) {
            return self::error($model->getErrors());
        }
	    self::createLog('planned', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/planned/delete",
    *     summary="Delete planned",
    *    description="delete  planned",
    *     tags={"Planned"},
    *     @OA\Parameter(
    *         description="Planned id to delete",
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
			self::error('Planned not found with id: ' . $id);
			return;
		}
		if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
			self::deleteLog('planned', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/planned/restore",
    *     summary="Restore planned",
    *    description="restore  planned",
    *     tags={"Planned"},
    *     @OA\Parameter(
    *         description="Planned id to delete",
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
			self::error('Planned not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('planned', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

    /**
    * @OA\Post(
    *    tags={"Planned"},
    *    path="/planned/update",
    *    summary="update  planned ",
    *    description="update planned",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Planned")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Planned")
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
        $modelNew = new Planned();
        $modelNew->setAttributes(Yii::$app->request->post());
        $model->id = $modelNew->id; 
        $model->planned_date = ($modelNew->planned_date = date("Y-m-d H:i:s", strtotime($model->planned_date)) ? : null); 
        $model->account_id = $modelNew->account_id; 
        $model->calls = $modelNew->calls; 
        $model->leads = $modelNew->leads; 
        $model->applications = $modelNew->applications; 
        $model->kps = $modelNew->kps; 
        $model->agreements = $modelNew->agreements; 
        $model->pays = $modelNew->pays; 
        $model->marges = $modelNew->marges;		
        if ($model->save()) {
	    self::updateLog('planned', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Planned::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
}
