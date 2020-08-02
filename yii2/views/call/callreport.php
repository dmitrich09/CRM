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
$this->title = 'Отчет по звонкам';
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

	$form = ActiveForm::begin([ 
						'id' => 'adarmacalls-form',
						'action'=>['/call/callreport'], 
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
						
			echo Html::submitButton('Обновить',['class' => 'btn btn-xs btn-primary', 'name' => 'add-button','style'=>'margin-left:10px;']);		
			ActiveForm::end();
			echo '<table class="table table-hover table-bordered">';
			echo '<tr><th>Менеджер</th><th>Входящие</th><th>Время входящих(мин\час)</th>
			<th>Среднее время входящих(сек)</th>
			<th>Исходящие</th><th>Время исходящих(мин\час)</th>
			<th>Среднее время исходящих(сек)</th>
			<th>Недозвонов</th></tr>';
			$in=0;
			$intime=0;
			$out=0;
			$outtime=0;
			$fail=0;
			foreach($report as $key=>$value){
				$in+=$value['in'];
				$intime+=$value['intime'];
				$out+=$value['out'];
				$outtime+=$value['outtime'];
				$fail+=$value['fail'];
				echo '<tr> 
				<td>'.Html::a($value['name'],['call/zadarmacalls'
					,'from'=>$callreport['from'],'to'=>$callreport['to']
					,'user_id'=>$key,'allrep'=>1],['target'=>'_blank']).'</td>
				<td>'.Html::a($value['in'],['call/zadarmacalls'
					,'from'=>$callreport['from'],'to'=>$callreport['to']
					,'user_id'=>$key,'type'=>1,'allrep'=>1],['target'=>'_blank']).'</td>
				<td>'.round($value['intime']/60,2).' / '.round($value['intime']/3600,2).'</td>
				<td>'.($value['in']!=null&&$value['in']!=0?round($value['intime']/$value['in'],2):0).'</td>
				<td>'.Html::a($value['out'],['call/zadarmacalls'
					,'from'=>$callreport['from'],'to'=>$callreport['to']
					,'user_id'=>$key,'type'=>0,'allrep'=>1],['target'=>'_blank']).'</td>
				<td>'.round($value['outtime']/60,2).' / '.round($value['outtime']/3600,2).'</td>
				<td>'.($value['out']!=null&&$value['out']!=0?round($value['outtime']/$value['out'],2):0).'</td>
				<td>'.$value['fail'].'</td>
				</tr>';
				
				
			}
			echo '<tr><th>ИТОГО</th>
			<th>'.Html::a($in,['call/zadarmacalls','from'=>$callreport['from'],'to'=>$callreport['to'],
			'type'=>1,'allrep'=>1],['target'=>'_blank']).'</th>
			
			<th>'.round($intime/60,2).' / '.round($intime/3600,2).'</th>
			<th>'.($in!=null&&$in!=0?round($intime/$in,2):0).'</th>
			<th>'.Html::a($out,['call/zadarmacalls','type'=>0,
				'from'=>$callreport['from'],'to'=>$callreport['to'],'allrep'=>1],['target'=>'_blank']).'</th>
			<th>'.round($outtime/60,2).' / '.round($outtime/3600,2).'</th>
			<th>'.($out!=null&&$out!=0?round($outtime/$out,2):0).'</th>
			
			<th>'.$fail.'</th></tr>';
			echo '</table">';	
	
		

			
			
?>



