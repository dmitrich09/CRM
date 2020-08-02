<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use app\models\Source;
use budyaga\users\models\User;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use app\models\Planned;
use app\models\Outcall;
use app\models\Lead;
use app\models\Application;
use app\models\Kp;
use app\models\Agreement;
use app\models\Payment;
use app\models\support\Planreport;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

$this->title = 'Отчет по действующим';
?>
 
<h3>Отчет по действующим</h3>

<?php
    $userList = User::getManagerOrk();
    $typeList=$report::getTypes();
			
	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/doingclients'], 
						'options' => ['class' => 'form-inline'], 
					]);

            echo $form->field($report, 'user_id')->dropDownList($userList,['prompt'=>'Менеджер'])->label(false);		        
			echo $form->field($report, 'type_id')->dropDownList($typeList,['prompt'=>'Все','multiple'=>true])->label(false);
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
			
			
						

			echo Html::submitButton('Получить',['class' => 'btn btn-primary', 'name' => 'add-button', 'value'=>'1','style'=>'margin-top:-10px;margin-left:10px;']);		
			ActiveForm::end();
            
        $dataProvider = new ArrayDataProvider([
				'allModels' => $query,
				'pagination' => ['pagesize'=>50],
			]);
			
			echo GridView::widget([ 
				'dataProvider' => $dataProvider, 
				'columns' => [ 

					['label'=>'Название',
						'format' => 'raw',
						'value'=>function ($data) {
							return Html::a($data['name'],['clients/show','clients_id'=>$data['clients_id']]);
						}
					],
                    ['label'=>'Звонок',
						'format' => 'raw',
						'value'=>function ($data) {
							$dt = $data['call_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
							
						}
					],
                    ['label'=>'ЭК',
						'format' => 'raw',
						'value'=>function ($data) {
							$dt = $data['lead_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
						}
					],
                    ['label'=>'Заявка',
						'format' => 'raw',
						'value'=>function ($data) {
                            $dt = $data['application_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
						}
					],
                    ['label'=>'КП',
						'format' => 'raw',
						'value'=>function ($data) {
                            $dt = $data['kp_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
						}
					],
                    ['label'=>'ДОГОВОР',
						'format' => 'raw',
						'value'=>function ($data) {
                            $dt = $data['agreement_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
						}
					],
                    ['label'=>'ОРК',
						'format' => 'raw',
						'value'=>function ($data) {
                            $dt = $data['ork_date'];
							if($dt!=null){
								$style="";
								if(strtotime($dt)<strtotime(date('d.m.Y H:i'))){
									$style="color:red;";
								}
								return '<span style="'.$style.'">'.date('d.m.Y H:i',strtotime($dt)).'</span>';
							}
							return '';
						}
					],
                ],
            ]);
            