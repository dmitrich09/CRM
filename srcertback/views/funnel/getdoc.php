<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use app\models\Source;
use app\models\Application;
use app\models\Agreement;
use app\models\Kp;
use app\models\Document;
use budyaga\users\models\User;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
$this->title = 'Реестр выданных документов';
?>

<h3>Реестр выданных документов</h3>

<?php

	$userList = User::getAll();
	$allUserList=User::getAll()	;
	$documentlist = ArrayHelper::map(Document::find()->orderBy('name')->all()
									,'document_id'
									,'name');

	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/getdoc'], 
						'options' => ['class' => 'form-inline'], 
					]);

			echo $form->field($report, 'from')->widget(DatePicker::classname(),					
						   ['name' => 'Getdocreport[from]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>($report->from!=null?date('d.m.Y',strtotime($report->from)):'')],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true,
								]
			])->label('выдан от');		
			echo $form->field($report, 'to')->widget(DatePicker::classname(),					
						   ['name' => 'Getdocreport[to]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>($report->to!=null?date('d.m.Y',strtotime($report->to)):'')],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true,
								]
			])->label('выдан до');
			
			
			echo $form->field($report, 'manager_id')->dropDownList($userList,['prompt'=>'Менеджер','class'=>'form-control input-xs'] )->label('Менеджер');
			echo $form->field($report, 'name')->textInput()->input('text', ['placeholder' => "компания"])->label('компания');
			echo $form->field($report, 'agreenum')->textInput()->input('text', ['placeholder' => "Номер договора"])->label('Номер договора');
			echo $form->field($report, 'product')->textInput()->input('text', ['placeholder' => "Название продукции"])->label('Название продукции');
			echo $form->field($report, 'actnum')->textInput()->input('text', ['placeholder' => "Номер акта"])->label('Номер акта');
			echo $form->field($report, 'organ')->textInput()->input('text', ['placeholder' => "Орган"])->label('Орган');
			echo $form->field($report, 'sertnum')->textInput()->input('text', ['placeholder' => "Номер сертификата"])->label('Номер сертификата');

						
	
			echo Html::submitButton('Получить',['class' => 'btn btn-primary', 'name' => 'add-button', 'value'=>'1','style'=>'margin-top:-10px;margin-left:10px;']);		
			ActiveForm::end();

						$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => false, 
			]);
			
			$firstColor='#d9d9fd';
			$secondColor='#bfbebe';
			$thirdColor="#e8e8f7";

			echo GridView::widget([ 
				'dataProvider' => $dataProvider, 
				'columns' => [  
					['label'=>'Компания',
						'format' => 'raw',
						'value'=>function ($data){
							return Html::a($data['name'],['/clients/show','clients_id'=>$data['clients_id']],['data-pjax'=>'0','target'=>'_blank','data-toggle'=>"tooltip",'title'=>'Перейти']);
						},
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]						
					],
					['label'=>'Номер договора',
						'format' => 'raw',
						'value'=>'numberagree',	
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]	
					], 
					['label'=>'Выданный документ',
						'format' => 'raw',
						'value'=>function ($data) use ($documentlist){
							return (isset($documentlist[$data['document_id']])?
									$documentlist[$data['document_id']]:$data['document_id']);
						},
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]		
					],
					['label'=>'Название продукции',
						'format' => 'raw',
						'value'=>'prod_name',	
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]		
					],
					['label'=>'Менеджер орк',
						'format' => 'raw',
						'value'=>function ($data) use ($allUserList){
							return (isset($allUserList[$data['manager_id']])?
									$allUserList[$data['manager_id']]:$data['manager_id']);
						},
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]		
					],
					
					
					
					['label'=>'Номер акта',
						'format' => 'raw',
						'value'=>'act_num',
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]	
					],
					['label'=>'Акт подписан клиентом',
						'format' => 'raw',
						'value'=>function ($data){
							return Application::getYesNonameById($data['signact']);
						},	
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]	
					],
					['label'=>'Договор подписан',
						'format' => 'raw',
						'value'=>function ($data){
							return Application::getYesNonameById($data['signagree']);
						},		
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]		
					],
					['label'=>'Выдан',
						'format' => 'raw',
						'value'=>function ($data){
							return (isset($data['get_date'])?date('d.m.Y',strtotime($data['get_date'])):"");
						},
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]		
					],
					['label'=>'Действует до',
						'format' => 'raw',
						'value'=>function ($data){
							return (isset($data['license_to'])?date('d.m.Y',strtotime($data['license_to'])):"");
						},		
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]		
					],
					['label'=>'Месяц/год оконания',
						'format' => 'raw',
						'value'=>function ($data){
							return (isset($data['license_to'])?date('m/Y',strtotime($data['license_to'])):"");
						},		
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]		
					],
					['label'=>'Орган',
						'format' => 'raw',
						'value'=>'act_organ',
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]		
					],
					['label'=>'№ сертификата',
						'format' => 'raw',
						'value'=>'sert_num',	
						'contentOptions' => ['style' => 'background-color:'.$firstColor],
						'headerOptions' => ['style' => 'background-color:'.$firstColor]		
					],
					['label'=>'Менеджер договора',
						'format' => 'raw',
						'value'=>function ($data) use ($allUserList){
							return (isset($allUserList[$data['a_man']])?
									$allUserList[$data['a_man']]:$data['a_man']);
						},
						'contentOptions' => ['style' => 'background-color:'.$thirdColor],
						'headerOptions' => ['style' => 'background-color:'.$secondColor]		
					],
				], 
			]);
			
			
?>


