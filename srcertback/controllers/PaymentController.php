<?php

namespace app\controllers;

use Yii;
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
//
class PaymentController extends BaseController
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
    *    tags={"Payment"},
    *    path="/payment",
    *    summary="list payments",
    *    description="find payment",
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(
    *            type="array",
    *            @OA\Items(ref="#/components/schemas/Payment")
    *         )
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    *
    */
    public function actionIndex()
    {
	$dataQuery = Payment::find()->andWhere(['account_id' => self::$user->account_id]);
        $id = Yii::$app->request->get('id');
        if ($id) {
		$dataQuery->where(['id' => $id]);
	}
        self::ok($dataQuery->all());
    }

    /**
    * @OA\Post(
    *    tags={"Payment"},
    *    path="/payment/create",
    *    summary="create  payment ",
    *    description="create new payment",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Payment")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Payment")
    *    ),
    *    security={{"api_key":{"PHPSESSID"}}}
    * )
    */
    public function actionCreate()
    {
        $model = new Payment();
        $model->setAttributes(Yii::$app->request->post());
	    $model->account_id = self::$user->account_id;
	    if ($model->payment_date) {
            $model->payment_date = date("Y-m-d H:i:s", strtotime($model->payment_date));
        }        
        if (!$model->save()) {
            return self::error($model->getErrors());
        }
	    self::createLog('payment', serialize($model));
        return self::ok($model);
    }

    /**
    * @OA\Delete(
    *     path="/payment/delete",
    *     summary="Delete payment",
    *    description="delete  payment",
    *     tags={"Payment"},
    *     @OA\Parameter(
    *         description="Payment id to delete",
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
			self::error('Payment not found with id: ' . $id);
			return;
		}
		if ($model->deleted_at) {
			return self::error('Model deleted');
		}
		$model->deleted_at = date('Y-m-d H:i:s');
		if ($model->update()) {
			self::deleteLog('payment', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

/**
    * @OA\Get(
    *     path="/payment/restore",
    *     summary="Restore payment",
    *    description="restore  payment",
    *     tags={"Payment"},
    *     @OA\Parameter(
    *         description="Payment id to delete",
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
			self::error('Payment not found with id: ' . $id);
			return;
		}
		if (!$model->deleted_at) {
			return self::error('Model not deleted');
		}
		$model->deleted_at = null;
		if ($model->update()()) {
			self::restoreLog('payment', $id);
			return self::ok();
		} else {
		    self::error($model->getErrors());
		}
	}

    /**
    * @OA\Post(
    *    tags={"Payment"},
    *    path="/payment/update",
    *    summary="update  payment ",
    *    description="update payment",
    *    @OA\RequestBody(
    *        @OA\MediaType(
    *            mediaType="application/json",
    *            @OA\Schema(ref="#/components/schemas/Payment")
    *          )
    *     ),
    *    @OA\Response(
    *        response=200,
    *        description="OK",
    *        @OA\JsonContent(ref="#/components/schemas/Payment")
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
        $modelNew = new Payment();
        $modelNew->setAttributes(Yii::$app->request->post());
        $model->id = $modelNew->id; 
        $model->clients_id = $modelNew->clients_id; 
        $model->account_id = $modelNew->account_id; 
        $model->payment_date = ($modelNew->payment_date date("Y-m-d H:i:s", strtotime($model->payment_date)) ? : null); 
        $model->user_id = $modelNew->user_id; 
        $model->amount = $modelNew->amount; 
        $model->agreement_id = $modelNew->agreement_id; 
        $model->pay_number = $modelNew->pay_number; 
        $model->source_id = $modelNew->source_id;         
        if ($model->save()) {
	    self::updateLog('payment', $id);
            return self::ok($model);
        }
        return self::error($model->getErrors());
    }


    private function getOne($id) {
        $md = Payment::findOne($id);
        if (!$md || $md->account_id != self::$user->account_id) {
            return self::error("Модель с ид " . $id . " не найдена");
        }
        return $md;
    }
}
