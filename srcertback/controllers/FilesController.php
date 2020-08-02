<?php

namespace app\controllers;

use Yii;
use app\models\Files;
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

class FilesController extends BaseController
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
    *    tags={"Files"},
    *    path="/files",
    *    summary="list filess",
    *    description="find files",
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/Files")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
	$dataQuery = Files::find()->andWhere(['account_id' => self::$user->account_id]);
        $id = Yii::$app->request->get('id');
        if ($id) {
		$dataQuery->where(['id' => $id]);
	}
        self::ok($dataQuery->all());
    }

    /**
    * @OA\Post(
    *    tags={"Files"},
    *    path="/files/create",
    *    summary="create  files ",
    *    description="create new files",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Files")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Files")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Files();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
	    if ($model->insert_date) {
            $model->insert_date = date("Y-m-d H:i:s", strtotime($model->insert_date));
        }        
        if (!$model->save()) {
            return self::error($model->getErrors());
        }
	    self::createLog('files', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/files/delete",
    *     summary="Delete files",
    *    description="delete  files",
    *     tags={"Files"},
    *     @OA\Parameter(
    *         description="Files id to delete",
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
			self::error('Files not found with id: ' . $id);
			return;
		}
		if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
			self::deleteLog('files', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/files/restore",
    *     summary="Restore files",
    *    description="restore  files",
    *     tags={"Files"},
    *     @OA\Parameter(
    *         description="Files id to delete",
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
			self::error('Files not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('files', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

    /**
    * @OA\Post(
    *    tags={"Files"},
    *    path="/files/update",
    *    summary="update  files ",
    *    description="update files",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Files")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Files")
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
        $modelNew = new Files();
        $modelNew->setAttributes(Yii::$app->request->post());
        $model->id = $modelNew->id; 
        $model->clients_id = $modelNew->clients_id; 
        $model->account_id = $modelNew->account_id; 
        $model->rusname = $modelNew->rusname; 
        $model->user_id = $modelNew->user_id; 
        $model->insert_date = ($modelNew->insert_date date("Y-m-d H:i:s", strtotime($model->insert_date)) ? : null); 
        $model->application_id = $modelNew->application_id; 
        $model->type = $modelNew->type; 
        $model->parent_id = $modelNew->parent_id;         
        if ($model->save()) {
	    self::updateLog('files', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Files::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
}
