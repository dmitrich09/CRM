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

$this->title = 'Воронка';
?>
 
<h3>План - факт</h3>

<?php

			
	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/plan'], 
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
			
			
						

			echo Html::submitButton('Получить',['class' => 'btn btn-primary', 'name' => 'add-button', 'value'=>'1','style'=>'margin-top:-10px;margin-left:10px;']);		
			ActiveForm::end();

			$firstColor='#b0d6ff';
			$firstHalfColor='#93c6fb';
			$secondColor='#d7ffde';
			$secondHalfColor='#a8ffb7';
					
			$pl=Planreport::getInstance($planned);
			
			echo '<table class="table table-hover table-bordered">';
			echo '<tr><th>Показатель</th><th>Неделя</th>';
			
			// МЕСЯЦ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<th>'.$pl->weeks[$value]->from." ".$pl->weeks[$value]->to.'</th>');
			};
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$firstColor.'">Исходящие</th>
			<td style="background-color:'.$firstColor.'">План</td>';
			///звонки
			// ПЛАН //////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				print_r (round($pl->weeks[$value]->planCalls).'</td>');
			};
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r (round($pl->weeks[$value]->factCalls).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				// кол-во дней в месяце: cal_days_in_month(CAL_GREGORIAN, date('m',strtotime($value->planned_date)), date('Y',strtotime($value->planned_date)))
				print_r ('<td style="background-color:'.$firstColor.'">');
				$res=(round($pl->weeks[$value]->planCalls)-round($pl->weeks[$value]->factCalls));
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r($pl->weeks[$value]->getStepCall());
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><th rowspan="4" style="background-color:'.$secondColor.'">Эффективные компании</th>
			<td style="background-color:'.$secondColor.'">План</td>';
			
			// ПЛАН ЛИДЫ//////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				print_r (round($pl->weeks[$value]->planLeads).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r (round($pl->weeks[$value]->factLeads).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				$res=(round($pl->weeks[$value]->planLeads)-round($pl->weeks[$value]->factLeads));
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r($pl->weeks[$value]->getStepLeads());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$firstColor.'">Заявки</th>
			<td style="background-color:'.$firstColor.'">План</td>';
			
			// ПЛАН ЗАЯВКИ//////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				print_r (round($pl->weeks[$value]->planApps).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r (round($pl->weeks[$value]->factApps).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				$res= (round($pl->weeks[$value]->planApps)-round($pl->weeks[$value]->factApps));
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r($pl->weeks[$value]->getStepApps());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$secondColor.'">Коммерческие</th>
			<td style="background-color:'.$secondColor.'">План</td>';
			
			// ПЛАН КОммерческие//////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				print_r (round($pl->weeks[$value]->planKps).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r (round($pl->weeks[$value]->factKps).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				$res=round($pl->weeks[$value]->planKps)-round($pl->weeks[$value]->factKps);
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r($pl->weeks[$value]->getStepKps());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$firstColor.'">Договоры</th>
			<td style="background-color:'.$firstColor.'">План</td>';
			
			// ПЛАН договоры//////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				print_r (round($pl->weeks[$value]->planAgreements).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r (round($pl->weeks[$value]->factAgrs).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				$res=round($pl->weeks[$value]->planAgreements)-round($pl->weeks[$value]->factAgrs);
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r($pl->weeks[$value]->getStepAgrs());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$secondColor.'">Оплаты</th>
			<td style="background-color:'.$secondColor.'">План</td>';
			
			// ПЛАН  платежи//////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				print_r (round($pl->weeks[$value]->planPays).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Факт</td>';
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys  as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r (round($pl->weeks[$value]->factPays).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys  as $key=>$value){
				print_r ('<td style="background-color:'.$secondColor.'">');
				$res=(round($pl->weeks[$value]->planPays)-round($pl->weeks[$value]->factPays));
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$secondHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys  as $key=>$value){
				print_r ('<td style="background-color:'.$secondHalfColor.'">');
				print_r($pl->weeks[$value]->getStepPays());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '<tr><th rowspan="4" style="background-color:'.$firstColor.'">Маржа</th>
			<td style="background-color:'.$firstColor.'">План</td>';
			
			// ПЛАН маржа //////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				print_r (round($pl->weeks[$value]->planMarges).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Факт</td>';
			
			// ФАКТ /////////////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r (round($pl->weeks[$value]->factMarges).'</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstColor.'">Отставание</td>';
			
			// ОТСТАВАНИЕ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstColor.'">');
				$res=round($pl->weeks[$value]->planMarges)-round($pl->weeks[$value]->factMarges);
				if($res>0){
					print_r ($res);
				}else{
					print_r (0);
				}
				print_r ('</td>');
			};
			
			echo '</tr>';
			echo '<tr><td style="background-color:'.$firstHalfColor.'">Нужный шаг</td>';
			
			// НУЖНЫЙ ШАГ //////////////////////////////////////////////////////////////////
			foreach ($pl->keys as $key=>$value){
				print_r ('<td style="background-color:'.$firstHalfColor.'">');
				print_r($pl->weeks[$value]->getStepMarges());
				print_r ('</td>');
			};
			
			echo '</tr>';
			
			echo '</table>';

?>


