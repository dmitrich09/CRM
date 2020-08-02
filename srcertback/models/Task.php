<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* @OA\Schema(
*     description="Task model",
*     type="object",
*     title="Task ",
*     @OA\Property(
*         property="id",
*         description="id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="user_id",
*         description="user_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="account_id",
*         description="account_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="manager_id",
*         description="manager_id",   
*         type="integer"
*     ),
*     @OA\Property(
*         property="task_date",
*         description="task_date",   
*         type="string"
*     ),
*     @OA\Property(
*         property="comment",
*         description="comment",   
*         type="string"
*     ),
*     @OA\Property(
*         property="description",
*         description="description",   
*         type="string"
*     ),
*     @OA\Property(
*         property="status_id",
*         description="status_id",   
*         type="integer"
*     ),
* *     required={ "id" ,"user_id" ,"account_id" ,"manager_id" ,"task_date" ,"description" ,"status_id"},
* )
*/
class Task extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'task';
    }

    public function rules()
    {
        return [
            [['user_id', 'account_id', 'manager_id', 'task_date', 'description', 'status_id'], 'required'],
            [['user_id', 'account_id', 'manager_id', 'status_id'], 'default', 'value' => null],
            [['user_id', 'account_id', 'manager_id', 'status_id'], 'integer'],
            [['task_date'], 'safe'],
            [['comment', 'description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'account_id' => 'Account ID',
            'manager_id' => 'Manager ID',
            'task_date' => 'Task Date',
            'comment' => 'Comment',
            'description' => 'Description',
            'status_id' => 'Status ID',
        ];
    }

}
