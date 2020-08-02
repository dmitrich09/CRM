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

$this->title = 'Orks';

?>
<div class="ork-index">
    <h4><?= Html::encode($this->title) ?></h4>
        
    <p>

        <?php echo Html::a('Добавить', ['#'], [
        'class' => 'btn btn-success',
        'data-target' => '#addork',
        'data-toggle' => 'modal',
        'title' => 'Добавить',
        ]);
        ?>
        <?php         echo Html::a('', ['/ork/downloadlist/'], [
        'class' => 'glyphicon glyphicon-download',
        'data-target' => '#downork',
        'data-toggle' => 'tooltip',
        'data-pjax' => '0',
        'title' => 'Скачать',
        ]);
        $form = ActiveForm::begin([
        'action' =>['/ork/uploadlist/'],
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
        'title' => 'Загрузить xsl поля ( id:: agreement_id:: account_id:: startdate:: enddate:: state:: signagree:: signact:: clients_id:: manager_id:: contactdate:: source_id:: pay_date:: provider_debt:: clientdoc_date:: close_date:: declinematter_id:: comment:: sert_num:: get_date:: license_to:: act_num:: act_organ:: deleted_at::)',
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
				'attribute' => 'agreement_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['agreement_id'];},
            ],
        
            [
				'attribute' => 'account_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['account_id'];},
            ],
        
            [
				'attribute' => 'startdate',
                'format' => 'raw',
                'value' => function ($data) {return ($data['startdate']!=null?date("d.m.Y H:i",strtotime($data['startdate'])):null);},
            ],
        
            [
				'attribute' => 'enddate',
                'format' => 'raw',
                'value' => function ($data) {return ($data['enddate']!=null?date("d.m.Y H:i",strtotime($data['enddate'])):null);},
            ],
        
            [
				'attribute' => 'state',
                'format' => 'raw',
                'value' => function ($data) {return $data['state'];},
            ],
        
            [
				'attribute' => 'signagree',
                'format' => 'raw',
                'value' => function ($data) {return $data['signagree'];},
            ],
        
            [
				'attribute' => 'signact',
                'format' => 'raw',
                'value' => function ($data) {return $data['signact'];},
            ],
        
            [
				'attribute' => 'clients_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['clients_id'];},
            ],
        
            [
				'attribute' => 'manager_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['manager_id'];},
            ],
        
            [
				'attribute' => 'contactdate',
                'format' => 'raw',
                'value' => function ($data) {return ($data['contactdate']!=null?date("d.m.Y H:i",strtotime($data['contactdate'])):null);},
            ],
        
            [
				'attribute' => 'source_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['source_id'];},
            ],
        
            [
				'attribute' => 'pay_date',
                'format' => 'raw',
                'value' => function ($data) {return ($data['pay_date']!=null?date("d.m.Y H:i",strtotime($data['pay_date'])):null);},
            ],
        
            [
				'attribute' => 'provider_debt',
                'format' => 'raw',
                'value' => function ($data) {return $data['provider_debt'];},
            ],
        
            [
				'attribute' => 'clientdoc_date',
                'format' => 'raw',
                'value' => function ($data) {return ($data['clientdoc_date']!=null?date("d.m.Y H:i",strtotime($data['clientdoc_date'])):null);},
            ],
        
            [
				'attribute' => 'close_date',
                'format' => 'raw',
                'value' => function ($data) {return ($data['close_date']!=null?date("d.m.Y H:i",strtotime($data['close_date'])):null);},
            ],
        
            [
				'attribute' => 'declinematter_id',
                'format' => 'raw',
                'value' => function ($data) {return $data['declinematter_id'];},
            ],
        
            [
				'attribute' => 'comment',
                'format' => 'raw',
                'value' => function ($data) {return $data['comment'];},
            ],
        
            [
				'attribute' => 'sert_num',
                'format' => 'raw',
                'value' => function ($data) {return $data['sert_num'];},
            ],
        
            [
				'attribute' => 'get_date',
                'format' => 'raw',
                'value' => function ($data) {return ($data['get_date']!=null?date("d.m.Y H:i",strtotime($data['get_date'])):null);},
            ],
        
            [
				'attribute' => 'license_to',
                'format' => 'raw',
                'value' => function ($data) {return ($data['license_to']!=null?date("d.m.Y H:i",strtotime($data['license_to'])):null);},
            ],
        
            [
				'attribute' => 'act_num',
                'format' => 'raw',
                'value' => function ($data) {return $data['act_num'];},
            ],
        
            [
				'attribute' => 'act_organ',
                'format' => 'raw',
                'value' => function ($data) {return $data['act_organ'];},
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
        ['/ork/view', 'id' => $data['id']],
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
        ['/ork/update', 'id' => $data['id']],
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
        ['/ork/delete', 'id' => $data['id']],
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
        


