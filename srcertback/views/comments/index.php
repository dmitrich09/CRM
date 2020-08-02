<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use budyaga\users\models\User;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use kartik\widgets\FileInput;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';

?>
<div class="comments-index">
    <h4><?= Html::encode($this->title) ?></h4>
        
    <p>

        <?php echo Html::a('Добавить', ['#'], [
        'class' => 'btn btn-success',
        'data-target' => '#addcomments',
        'data-toggle' => 'modal',
        'title' => 'Добавить',
        ]);
        ?>
        <?php         echo Html::a('', ['/comments/downloadlist/'], [
        'class' => 'glyphicon glyphicon-download',
        'data-target' => '#downcomments',
        'data-toggle' => 'tooltip',
        'data-pjax' => '0',
        'title' => 'Скачать',
        ]);
        $form = ActiveForm::begin([
        'action' =>['/comments/uploadlist/'],
        'options' => ['enctype' => 'multipart/form-data','class' => 'form-inline']
        ]);
        echo FileInput::widget([
        'model' => $upload,
        'attribute' => 'file',
        'options' => ['multiple' => false],
        'pluginOptions' => [
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => false
        ]
        ]);
        echo Html::submitButton(
        'Загрузить',
        [
        'class' => 'btn btn-success glyphicon glyphicon-upload',
        'name' => 'add-button',
        'data-toggle' => 'tooltip',
        'title' => 'Загрузить xsl поля ( id:: message:: account_id:: object_id:: type:: user_id:: adddate:: deleted_at::)',
        ]
        );
        ActiveForm::end(); ?>
    </p>



            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        
            [
				'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($data) {return $data['id'];},
            ],
        
            [
				'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($data) {return $data['message'];},
            ],
        
            [
				'attribute' => 'account_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['account_id'];},
            ],
        
            [
				'attribute' => 'object_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['object_id'];},
            ],
        
            [
				'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($data) {return $data['type'];},
            ],
        
            [
				'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['user_id'];},
            ],
        
            [
				'attribute' => 'adddate',
                'format' => 'raw',
                'value' => function ($data) {return ($data['adddate']!=null?date("d.m.Y H:i",strtotime($data['adddate'])):null);},
            ],
        
            [
				'attribute' => 'deleted_at',
                'format' => 'raw',
                'value' => function ($data) {return ($data['deleted_at']!=null?date("d.m.Y H:i",strtotime($data['deleted_at'])):null);},
            ],
        
        [
        'format' => 'raw',
        'value' => function ($data){
        $res = Html::a(
        '',
        ['/comments/view', 'id' => $data['id']],
        [
        'class' => 'glyphicon glyphicon-eye-open',
        'data-toggle' => 'tooltip',
        'title' => 'Просмотр',
        ]
        );
        return (Yii::$app->user->can('') ? $res : '');
        },
        ],

        [
        'format' => 'raw',
        'value' => function ($data){
        $res = Html::a(
        '',
        ['/comments/update', 'id' => $data['id']],
        [
        'class' => 'glyphicon glyphicon-pencil',
        'data-toggle' => 'tooltip',
        'title' => 'Изменить',
        ]
        );
        return (Yii::$app->user->can('') ? $res : '');
        },
        ],

        [
        'format' => 'raw',
        'value' => function ($data){
        $res = Html::a(
        '',
        ['/comments/delete', 'id' => $data['id']],
        [
        'class' => 'glyphicon glyphicon-trash',
        'onclick'=>'return confirm("Вы уверены?");',
        'data-toggle' => 'tooltip',
        'title' => 'Удалить',
        'style' => 'color:brown',
        ]
        );
        return (Yii::$app->user->can('') ? $res : '');
        },
        ],
        ],
        ]); ?>
        


