<?php

namespace app\controllers;

use Yii;
use app\models\Settings;
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

class SettingsController extends BaseController
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
                'only' => ['index','create','delete','update','restore'],
                'rules' => [
                    [
                        'actions' => ['index','create','delete','update','restore'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
    * @OA\Get(
    *    tags={"Settings"},
    *    path="/settings",
    *    summary="list settingss",
    *    description="find settings",
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/Settings")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
	    $dataQuery = Settings::find();
        $id = Yii::$app->request->get('id');
        if ($id) {
		    $dataQuery->where(['id' => $id]);
	    }
        self::ok($dataQuery->all());
    }

    /**
    * @OA\Post(
    *    tags={"Settings"},
    *    path="/settings/create",
    *    summary="create  settings ",
    *    description="create new settings",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Settings")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Settings")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Settings();
        $model->setAttributes(Yii::$app->request->post());
	    if (!$model->save()) {
            return self::error($model->getErrors());
        }
        self::createLog('settings', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/settings/delete",
    *     summary="Deletes settings",
    *    description="delete  settings",
    *     tags={"Settings"},
    *     @OA\Parameter(
    *         description="Settings id to delete",
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
			self::error('Settings not found with id: ' . $id);
			return;
        }
        if ($model->deleted_at) {
            return self::error('Запись удалена' . $id );
        }
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
            self::deleteLog('settings', serialize($model));
			return self::ok();
		} else {
            self::error($model->getErrors());
        }
	}

    /**
    * @OA\Get(
    *     path="/settings/restore",
    *     summary="Restore settings",
    *    description="Restore  settings",
    *     tags={"Settings"},
    *     @OA\Parameter(
    *         description="Settings id to restore",
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
			self::error('Sphere not found with id: ' . $id);
			return;
        }
        if (!$model->deleted_at) {
            return self::error('Запись не удалена' . $id );
        }
        $model->deleted_at = null;
		if ($model->update()) {
            self::restoreLog('settings', serialize($model));
			return self::ok();
		} else {
            self::error($model->getErrors());
        }
	}


    /**
    * @OA\Post(
    *    tags={"Settings"},
    *    path="/settings/update",
    *    summary="update  settings ",
    *    description="update settings",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Settings")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Settings")
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
        if ($model->deleted_at) {
            return self::error('Запись удалена' . $id );
        }
        $modelNew = new Settings();
        $modelNew->setAttributes(Yii::$app->request->post());
        $model->ip = $modelNew->ip;         
        if ($model->save()) {
            self::updateLog('settings', serialize($model));
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Settings::findOne($id);
        if (!$md ) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }

}
