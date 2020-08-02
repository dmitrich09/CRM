<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use app\models\Source;
use budyaga\users\models\User;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Provider;
use app\models\support\GridTotal;
$this->title = 'Отчет об оплатах';
?>

<h3>Отчет об оплатах</h3>

<?php


	$provs=ArrayHelper::map(Provider::find()->all(),'id','name');

	
	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/payrep'], 
						'options' => ['class' => 'form-inline'], 
					]);

			echo $form->field($report, 'from')->widget(DatePicker::classname(),					
						   ['name' => 'Report[from]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>date('d.m.Y',strtotime($report->from))],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true,
								]
			])->label('От');		
			echo $form->field($report, 'to')->widget(DatePicker::classname(),					
						   ['name' => 'Report[to]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>date('d.m.Y',strtotime($report->to))],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true,
								]
			])->label('До');
			
			
			$userList = User::getAll();			

			echo Html::submitButton('Получить',['class' => 'btn btn-primary', 'name' => 'add-button', 'value'=>'1','style'=>'margin-top:-10px;margin-left:10px;']);		
			ActiveForm::end();

			
			
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => false, 
			]);

			echo GridView::widget([ 
				'dataProvider' => $dataProvider,
				'summary'=>'',
				'showFooter'=>TRUE,
				'columns' => [  
					['label'=>'Номер договора',
						'format' => 'raw',
						'value'=>'numberagree',						
					],
					['label'=>'Менеджер',
						'format' => 'raw',
						'value'=>function ($data) use ($userList){
							return (isset($userList[$data['manager_id']])?$userList[$data['manager_id']]:$data['manager_id']);
						},						
					],
					['label'=>'Компания',
						'format' => 'raw',
						'value'=>'name',						
					],
					['label'=>'Сумма договора',
						'format' => 'raw',
						'value'=>'total',
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageTotal($dataProvider->models,'total'),
												 2,"."," ").'</span>',
					],
					['label'=>'Себестоимость',
						'format' => 'raw',
						'value'=>'our_cost',
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageTotal($dataProvider->models,'our_cost'),
												 2,"."," ").'</span>',
					],
					['label'=>'Маржа',
						'format' => 'raw',
						'value'=>function ($data){
							return  $data['total']-$data['our_cost'];
						},
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageDef($dataProvider->models,'total','our_cost'),
												 2,"."," ").'</span>',
					],
					['label'=>'Дата оплаты',
						'format' => 'raw',
						'value'=>'payment_date',						
					],
					['label'=>'Сумма оплаты',
						'format' => 'raw',
						'value'=>'amount',
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageTotal($dataProvider->models,'amount'),
												 2,"."," ").'</span>',
					],
					['label'=>'Долг клиента',
						'format' => 'raw',
						'value'=>'debt',
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageTotal($dataProvider->models,'debt'),
												 2,"."," ").'</span>',
					],
					['label'=>'Долг поставщику',
						'format' => 'raw',
						'value'=>function ($data){
							if($data['provider_debt']==null){
								return $data['our_cost'];
							}
							return $data['provider_debt'];
						},
						'footer'=>'<span style="white-space: nowrap;font-weight:bold;">'.number_format ( GridTotal::pageOr($dataProvider->models,'provider_debt','our_cost'),
												 2,"."," ").'</span>',
					],
					['label'=>'Поставщики',
						'format' => 'raw',
						'value'=>function ($data) use ($provs){
							return ArrayHelper::getValue($provs,$data['maxid']);
						},								
					],
					
				], 
			]);

?>



