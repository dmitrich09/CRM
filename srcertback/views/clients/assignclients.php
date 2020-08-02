<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\forms\ClientUploadForm;
use yii\helpers\ArrayHelper;
use app\models\City;
use app\models\Clients;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\data\ActiveDataProvider; 
use budyaga\users\models\User;
use app\models\Sphere;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'city_id'
									,'name');
$clienttypes = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');
$spheres= ArrayHelper::map(Sphere::find()->orderBy('name')->all()
									,'sphere_id'
									,'name');									
									
									
$userList = User::getManager();
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        
        <?php $form = ActiveForm::begin(['action' =>['assignclients'],'options' => ['class' => 'form-inline'], ]) ?>
		<?php
		echo $form->field($model, 'city_id')->dropDownList($cityList,['prompt'=>'Город','class'=>'form-control input-xs'] )->label(false);
        echo $form->field($model, 'clienttype_id')->dropDownList($clienttypes,['prompt'=>'Тип','class'=>'form-control input-xs'] )->label(false);
		echo $form->field($model, 'sphere_id')->dropDownList($spheres,['prompt'=>'Сфера','class'=>'form-control input-xs'] )->label(false);
		echo $form->field($model, 'from')	->widget(DateTimePicker::classname(),					
								[
							'name' => 'Callreport[to]',
							'options' => ['placeholder' => 'ОТ','value'=>date('d.m.Y H:i',strtotime($model->from))],
							'convertFormat' => true,
							'type' => DateTimePicker::TYPE_INPUT,
							'pluginOptions' => [
								'weekStart'=>1,
								'format' => 'dd.MM.yyyy h:i',
								'startDate' => date('dd.MM.yyyy h:i'),
								'todayHighlight' => true,	
							]
						])->label('Контактов не было от');
						echo $form->field($model, 'user_id')->dropDownList($userList,['prompt'=>'Менеджер','class'=>'form-control input-xs'] )->label(false);
		echo $form->field($model, 'amount')->textInput(['placeholder'=> 'Количество для назначения','class' => 'form-control'])->label(false);
		if($query!=null){
			echo $form->field($model, 'isassign')->checkBox(['value'=> '1','class' => 'form-control','label'=>false])->label('назначить');
		}
		echo Html::submitButton('Просмотр',['class' => 'btn  btn-primary','value'=>1, 'name' => 'add-button','style'=>'margin-left:10px;margin-top:-10px']);		
		?>
        <?php ActiveForm::end() ?>
    </p>
    
 
		<?php
		if($query!=null){
		$dataProvider = new ActiveDataProvider([
				'query' => $query,	'pagination'=>	false,		
			]);
		echo '<b>'.$query->count().'</b> клиентов для распределения';
		echo GridView::widget([ 
				'dataProvider' => $dataProvider,
				'rowOptions' => function ($data){
				  
				},
				'summary'=>'',
				'showHeader'=>true,
				'tableOptions' => [
					'class' => 'table table-striped table-bordered'
				],				
				'columns' => [ 
					['label'=>'Название',
						'format' => 'raw',
						'value'=>'name',						
					],
					['label'=>'Название',
						'format' => 'raw',
						'value'=>'inn',						
					],
				],  
			]);
		
		}
		
		?>
 