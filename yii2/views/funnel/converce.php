<?php

/* @var $this yii\web\View */
use yii\helpers\ArrayHelper;
use app\models\Source;
use budyaga\users\models\User;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
$this->title = 'Воронка';
?>

<h3>Конверсия</h3>

<?php


	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/converce'], 
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

			

			$firstColor='#C6E2FF';
			$secondColor='#E0FFFF';
?>
			
<?php 
			echo '<h3>Общая</h3>
			<table class="table table-bordered table-hover">
				<tr><th style="background-color:'.$firstColor.'">Месяц</th>
				<th style="background-color:'.$secondColor.'">Конверсия заявка - оплата</th>
				<th style="background-color:'.$firstColor.'">Звонки</th>
				<th style="background-color:'.$secondColor.'">Конверсия</th>
				<th style="background-color:'.$firstColor.'">Заявки</th>
				<th style="background-color:'.$secondColor.'">Конверсия</th>
				<th style="background-color:'.$firstColor.'">КП</th>
				<th style="background-color:'.$secondColor.'">Конверсия</th>
				<th style="background-color:'.$firstColor.'">Договоры</th>
				<th style="background-color:'.$secondColor.'">Конверсия</th>
				<th style="background-color:'.$firstColor.'">Оплаты</th>
				</tr>';
				
					$calls=0;
					$apps=0;
					$agrs=0;
					$pays=0;
					$kps=0;
					foreach($conrep['dates'] as $key=>$val){
						$calls+=(isset($conrep['calls'][$key]['count'])?$conrep['calls'][$key]['count']:0);
						$apps+=(isset($conrep['apps'][$key]['count'])?$conrep['apps'][$key]['count']:0);
						$agrs+=(isset($conrep['agreements'][$key]['count'])?$conrep['agreements'][$key]['count']:0);
						$pays+=(isset($conrep['pays'][$key]['count'])?$conrep['pays'][$key]['count']:0);
						$kps+=(isset($conrep['kps'][$key]['count'])?$conrep['kps'][$key]['count']:0);
						echo '<tr>';
							echo '<td style="background-color:'.$firstColor.'">'.$key.'</td>';
							//конверсия заявок к оплатам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['pays'][$key]['count'])&&isset($conrep['apps'][$key]['count'])){
									if($conrep['pays'][$key]['count']!=null){
										echo ($conrep['apps'][$key]['count']/$conrep['pays'][$key]['count']);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//звонки
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['calls'][$key]['count'])?$conrep['calls'][$key]['count']:0).'</td>';
							//конверсия звонков к заявкам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['apps'][$key]['count'])&&isset($conrep['calls'][$key]['count'])){
									if($conrep['apps'][$key]['count']!=null){
										echo ($conrep['calls'][$key]['count']/$conrep['apps'][$key]['count']);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//заявки
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['apps'][$key]['count'])?Html::a($conrep['apps'][$key]['count'],['application/details','from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//конверсия заявок к кп
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['kps'][$key]['count'])&&isset($conrep['apps'][$key]['count'])){
									if($conrep['kps'][$key]['count']!=null){
										echo ($conrep['apps'][$key]['count']/$conrep['kps'][$key]['count']);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//кп
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['kps'][$key]['count'])?Html::a($conrep['kps'][$key]['count'],['kp/details','from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//конверсия кп к договорам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['agreements'][$key]['count'])&&isset($conrep['kps'][$key]['count'])){
									if($conrep['agreements'][$key]['count']!=null){
										echo ($conrep['kps'][$key]['count']/$conrep['agreements'][$key]['count']);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//конверсия договора
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['agreements'][$key]['count'])?Html::a($conrep['agreements'][$key]['count'],['agreement/details','from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//конверсия договоров к платежам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['pays'][$key]['count'])&&isset($conrep['agreements'][$key]['count'])){
									if($conrep['pays'][$key]['count']!=null){
										echo ($conrep['agreements'][$key]['count']/$conrep['pays'][$key]['count']);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//платежей
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['pays'][$key]['count'])?Html::a($conrep['pays'][$key]['count'],['payment/details','from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							
						echo '</tr>';
					}	
					echo '<tr><th style="background-color:'.$firstColor.'">ИТОГО</th>';
					if($pays!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$apps/$pays.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					echo '<th style="background-color:'.$firstColor.'">'.$calls.'</th>';
					
					if($apps!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$calls/$apps.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($apps,['application/details','from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					if($kps!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$apps/$kps.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($kps,['kp/details','from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					if($agrs!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$kps/$agrs.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($agrs,['agreement/details','from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					if($pays!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$agrs/$pays.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($pays,['payment/details','from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					echo '</tr>';
					
				?>
			
			</table>

			
			
			<?php 
			$managerList = User::getManager();
			foreach($managerList as $keysm=>$val){
				echo '<h4 href="#man'.$keysm.'" data-toggle="collapse">'.$val.'</h4>
				<div class="collapse" id="man'.$keysm.'"">
				<table class="table table-bordered table-hover " >
					<tr><th style="background-color:'.$firstColor.'">Месяц</th>
					<th style="background-color:'.$secondColor.'">Конверсия заявка - оплата</th>
					<th style="background-color:'.$firstColor.'">Звонки</th>
					<th style="background-color:'.$secondColor.'">Конверсия</th>
					<th style="background-color:'.$firstColor.'">Заявки</th>
					<th style="background-color:'.$secondColor.'">Конверсия</th>
					<th style="background-color:'.$firstColor.'">КП</th>
					<th style="background-color:'.$secondColor.'">Конверсия</th>
					<th style="background-color:'.$firstColor.'">Договоры</th>
					<th style="background-color:'.$secondColor.'">Конверсия</th>
					<th style="background-color:'.$firstColor.'">Оплаты</th>
					</tr>';
					$calls=0;
					$apps=0;
					$agrs=0;
					$pays=0;
					$kps=0;
					$manager=$keysm;
					foreach($conrep['dates'] as $key=>$val){
						$calls+=(isset($conrep['calls'][$manager][$key])?$conrep['calls'][$manager][$key]:0);
						$apps+=(isset($conrep['apps'][$manager][$key])?$conrep['apps'][$manager][$key]:0);
						$agrs+=(isset($conrep['agreements'][$manager][$key])?$conrep['agreements'][$manager][$key]:0);
						$pays+=(isset($conrep['pays'][$manager][$key])?$conrep['pays'][$manager][$key]:0);
						$kps+=(isset($conrep['kps'][$manager][$key])?$conrep['kps'][$manager][$key]:0);
						echo '<tr>';
							//месяц
							echo '<td style="background-color:'.$firstColor.'">'.$key.'</td>';
							//заявки к оплатам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['pays'][$manager][$key])&&isset($conrep['apps'][$manager][$key])){
									if($conrep['pays'][$manager][$key]!=null){
										echo ($conrep['apps'][$manager][$key]/$conrep['pays'][$manager][$key]);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//звонки
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['calls'][$manager][$key])?$conrep['calls'][$manager][$key]:0).'</td>';
							//звонки к заявкам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['apps'][$manager][$key])&&isset($conrep['calls'][$manager][$key])){
									if($conrep['apps'][$manager][$key]!=null){
										echo ($conrep['calls'][$manager][$key]/$conrep['apps'][$manager][$key]);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//заявки
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['apps'][$manager][$key])?Html::a($conrep['apps'][$manager][$key],['application/details','user_id'=>$manager,'from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//заявки к кп
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['kps'][$manager][$key])&&isset($conrep['apps'][$manager][$key])){
									if($conrep['kps'][$manager][$key]!=null){
										echo ($conrep['apps'][$manager][$key]/$conrep['kps'][$manager][$key]);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//кп
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['kps'][$manager][$key])?Html::a($conrep['kps'][$manager][$key],['kp/details','user_id'=>$manager,'from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//кп к договорам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['kps'][$manager][$key])&&isset($conrep['agreements'][$manager][$key])){
									if($conrep['agreements'][$manager][$key]!=null){
										echo ($conrep['kps'][$manager][$key]/$conrep['agreements'][$manager][$key]);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							
							//договора
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['agreements'][$manager][$key])?Html::a($conrep['agreements'][$manager][$key],['agreement/details','user_id'=>$manager,'from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							//договора к платежам
							echo '<td style="background-color:'.$secondColor.'">';
								if(isset($conrep['pays'][$manager][$key])&&isset($conrep['agreements'][$manager][$key])){
									if($conrep['pays'][$manager][$key]!=null){
										echo ($conrep['agreements'][$manager][$key]/$conrep['pays'][$manager][$key]);
									}else{
										echo 0;
									}
								}else{
									echo 0;
								}
							
							echo '</td>';
							//платежи
							echo '<td style="background-color:'.$firstColor.'">'.(isset($conrep['pays'][$manager][$key])?Html::a($conrep['pays'][$manager][$key],['payment/details','user_id'=>$manager,'from'=>$key,'to'=>$key],['target'=>'_blank']):0).'</td>';
							
						echo '</tr>';
					}	
									echo '<tr><th style="background-color:'.$firstColor.'">ИТОГО</th>';
					if($pays!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$apps/$pays.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					echo '<th style="background-color:'.$firstColor.'">'.$calls.'</th>';
					
					if($apps!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$calls/$apps.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($apps,['application/details','user_id'=>$manager,'from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					
					if($kps!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$apps/$kps.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($kps,['kp/details','user_id'=>$manager,'from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					
					if($agrs!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$kps/$agrs.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($agrs,['agreement/details','user_id'=>$manager,'from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					
					if($pays!=0){
						echo '<th style="background-color:'.$secondColor.'">'.$agrs/$pays.'</th>';
					}else{
						echo '<th style="background-color:'.$secondColor.'">0</th>';
					}
					
					echo '<th style="background-color:'.$firstColor.'">'.Html::a($pays,['payment/details','user_id'=>$manager,'from'=>$report->from,'to'=>$report->to],['target'=>'_blank']).'</th>';
					echo '</tr>';
			
			echo '</table></div>';
			}
			?>

