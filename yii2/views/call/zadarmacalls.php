<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Source;
use app\models\Application;
use budyaga\users\models\User;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\data\ActiveDataProvider; 
use yii\grid\GridView; 
$this->title = 'Звонки';
?>

<h3>Звонки</h3>
 

<?php
	$userList = User::getManager();
									
									
	$typemap = ArrayHelper::map([
										['id' => 1, 'name' => 'Входящий'],
										['id' => 0, 'name' => 'Исходящий'],
								]
									,'id'
									,'name');
	
	$typecoldmap = ArrayHelper::map([
										['id' => 0, 'name' => 'Холодный'],
										['id' => 1, 'name' => 'Теплый'],
								]
									,'id'
									,'name');

	$form = ActiveForm::begin([ 
						'id' => 'adarmacalls-form',
						'action'=>['/call/zadarmacalls'], 
						'options' => ['class' => 'form-inline'], 
					]);

			echo $form->field($callreport, 'from')	->widget(DateTimePicker::classname(),					
								[
							'name' => 'Callreport[from]',
							'options' => ['placeholder' => 'ОТ','value'=>date('d.m.Y H:i',strtotime($callreport->from))],
							'convertFormat' => true,
							'type' => DateTimePicker::TYPE_INPUT,
							'pluginOptions' => [
								'weekStart'=>1,
								'format' => 'dd.MM.yyyy h:i',
								'startDate' => date('dd.MM.yyyy h:i'),
								'todayHighlight' => true,	
							]
						])->label('От');
			
			echo $form->field($callreport, 'to')	->widget(DateTimePicker::classname(),					
								[
							'name' => 'Callreport[to]',
							'options' => ['placeholder' => 'ОТ','value'=>date('d.m.Y H:i',strtotime($callreport->to))],
							'convertFormat' => true,
							'type' => DateTimePicker::TYPE_INPUT,
							'pluginOptions' => [
								'weekStart'=>1,
								'format' => 'dd.MM.yyyy h:i',
								'startDate' => date('dd.MM.yyyy h:i'),
								'todayHighlight' => true,	
							]
						])->label('До');

			echo $form->field($callreport, 'type')->dropDownList($typemap,['prompt'=>'Тип звонка','class'=>'form-control input-xs'] )->label(false);
			echo $form->field($callreport, 'typecold')->dropDownList($typecoldmap,['prompt'=>'Холодный/теплый','class'=>'form-control input-xs'] )->label(false);
			echo $form->field($callreport, 'user_id')->dropDownList($userList,['prompt'=>'Менеджер','class'=>'form-control input-xs'] )->label(false);
			echo $form->field($callreport, 'number')->textInput()->input('text', ['placeholder' => "Номер телефона"])->label(false);
			echo Html::submitButton('Обновить',['class' => 'btn btn-xs btn-primary', 'value'=>1,'name' => 'add-button','style'=>'margin-left:10px;']);		
			ActiveForm::end();
			
			
			
	
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => ['pageSize' => 20],
			]);
			
			$userList = User::getMapAll();
			echo '<b>Итого звонков: '.$query->count().'</b>';
			echo GridView::widget([ 
				'dataProvider' => $dataProvider, 
				'rowOptions'=>['class' => 'xs'],
				'summary'=>'',
				'showHeader'=>true,
				'tableOptions' => [
					'class' => 'table table-striped table-bordered'
				],
				'rowOptions' => function ($data){
				  if($data['seconds']==0){
					return ['class' => 'danger'];
				  }else if($data['incoming']==1){
					return ['class' => 'warning'];
				  }else{
					return ['class' => 'success'];   
				  }
				},
				'columns' => [ 
					['label'=>'Наш id',
						'format' => 'raw',
						'value'=>function ($data)  {
								return $data['call_id'];
							},
					],
					

					['label'=>'id zadarma',
						'format' => 'raw',
						'value'=>function ($data)  {
								return $data['zad_call_id'];
							},
					],
					
					['label'=>'Дата',
						'format' => 'raw',
						'value'=>function ($data)  {
								return date('d.m.Y H:i',strtotime($data['callstart']));
							},
					],

					['label'=>'Откуда',
						'format' => 'raw',
						'value'=>function ($data)  {
								return $data['sip'];
							},
					],

					['label'=>'Куда',
						'format' => 'raw',
						'value'=>function ($data)  {
								return $data['destination'];
							},
					],
					

					['label'=>'Длительность',
						'format' => 'raw',
						'value'=>function ($data)  {
								if($data['is_recorded']!='true'){
									return $data['seconds'];
								}
								
							},
					],
					
					['label'=>'Тип',
						'format' => 'raw',
						'value'=>function ($data)  {
								if($data['incoming']==1){
									return 'Входящий';
								}
								return 'Исходящий';
							},
					],
					['label'=>'Тип',
						'format' => 'raw',
						'value'=>function ($data)  {
								if($data['is_warm']!=1){
									return 'Холодный';
								}
								return 'Теплый';
							},
					],
					
					['label'=>'Оператор',
						'format' => 'raw',
						'value'=>function ($data) use($userList) {
								if(isset($userList[$data['user_id']])){
									return $userList[$data['user_id']];
								}
								return $data['user_id'];
							},
					],
					['label'=>'Клиент',
						'format' => 'raw',
						'value'=>function ($data){
							if( $data['clients_id']!=null){
								return Html::a($data['name'],['clients/show','clients_id'=>$data['clients_id']],['target'=>'_blank']);
							}
						}	
					],
					['label'=>'Прослушать',
						'format' => 'raw',
						'value'=>function ($data) {
							if($data['is_recorded']=='true'){
								return '<audio src="/calls/'.$data['call_id'].'.mp3" preload="auto" controls></audio>';
							}else{
								return 'Звонок не был записан на АТС';
							}
						}
					],
					
				], 
			]);

			
			
?>



