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

<h3>Воронка</h3>

<?php


	$form = ActiveForm::begin([ 
						'id' => 'funnel',
						'action'=>['/funnel/funnel'], 
						'options' => ['class' => 'form-inline'], 
					]);

			echo $form->field($funnelreport, 'from')->widget(DatePicker::classname(),					
								[
							'name' => 'FunnelReport[from]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>date('d.m.Y',strtotime($funnelreport->from))],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true, 
								]
			])->label('От');
			
			echo $form->field($funnelreport, 'to')->widget(DatePicker::classname(),					
								[
							'name' => 'FunnelReport[to]',
							'options' => ['placeholder' => 'Дата оплаты','value'=>date('d.m.Y',strtotime($funnelreport->to))],
								'convertFormat' => true,
								'type' => DatePicker::TYPE_INPUT,
								'pluginOptions' => [
									'weekStart'=>1,
									'format' => 'dd.MM.yyyy',
									'todayHighlight' => true,
								]
			])->label('До'); 
						
			echo $form->field($funnelreport, 'group')->checkbox(['value' => 1, ])->label('Год');			

			echo Html::submitButton('Получить',['class' => 'btn btn-xs btn-primary', 'name' => 'add-button', 'value'=>'1','style'=>'margin-left:10px;']);		
			ActiveForm::end();


?>


<table class="table table-bordered  table-hover">
    <?php 
		$headColor='#c5e2ff'; 
		$sumColor='#cfe8f5'; 
		$detailColor='#eaf6ff'; 
	
        echo '<tr style="background-color:'.$headColor.';">';
        $rep=$funnel->getReport();
	
        foreach($rep['days'] as $val){
            echo '<th>'.$val.'</th>';
        }
		echo '<th>ИТОГО</th>';
        echo '</tr>'; 
		
		echo '<tr style="background-color:'.$sumColor.';" data-toggle="collapse" data-target=".alllead">'; 
		$ld=0;
		$cntsn=0;
		
        foreach($rep['lead'] as $val){
			if($cntsn>0){
				echo '<td>'.Html::a($val,['lead/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
			}else{
				echo '<th>'.$val.'</th>';
			}
			$cntsn++;		
			$ld+=$val;
        }
		echo '<td>'.Html::a($ld,['lead/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</td>';
        echo '</tr>';   
        

        foreach($rep['sourcelead'] as $key=>$val){
            echo '<tr  class="alllead collapse" style="background-color:'.$detailColor.';">';
			$sld=0;
				 $cnts=0;
                 foreach($val as $value){
					if($cnts>0){
						echo '<td>'.Html::a($value,['lead/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					 $sld+=$value;
					 
                 }
			echo '<td>'.Html::a($sld,['lead/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>'; 	 
            echo '</tr>'; 
        }
        
        echo '<tr data-toggle="collapse" data-target=".allapp" style="background-color:'.$sumColor.';">'; 
		$ld=0;
		$cntsn=0;
        foreach($rep['app'] as $val){
            if($cntsn>0){
				echo '<td>'.Html::a($val,['application/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
			}else{
				echo '<th>'.$val.'</th>';
			}
			$cntsn++;
			$ld+=$val;
        }
		echo '<td>'.Html::a($ld,['application/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</td>';
        echo '</tr>';   
        
        foreach($rep['sourceapp'] as $key=>$val){
			$sld=0;
			$cnts=0;
            echo '<tr class="allapp collapse" style="background-color:'.$detailColor.';">';
                 foreach($val as $value){  
                    if($cnts>0){
						echo '<td>'.Html::a($value,['application/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					$sld+=$value;					 
                 }
			echo '<td>'.Html::a($sld,['application/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>';	 
            echo '</tr>'; 
        }
        
		echo '<tr data-toggle="collapse" data-target=".allkp" style="background-color:'.$sumColor.';">'; 
		$ld=0;
		$cntsn=0;
        foreach($rep['kp'] as $val){
           if($cntsn>0){
				echo '<td>'.Html::a($val,['kp/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
			}else{
				echo '<th>'.$val.'</th>';
			}
			$cntsn++;
			$ld+=$val;
        }
		echo '<th>'.Html::a($ld,['kp/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</th>';
        echo '</tr>';   
        
        foreach($rep['sourcekp'] as $key=>$val){
			$sld=0;
			$cnts=0;
            echo '<tr class="allkp collapse" style="background-color:'.$detailColor.';">';
                 foreach($val as $value){  
                    if($cnts>0){
						echo '<td>'.Html::a($value,['kp/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					$sld+=$value;					 
                 }
			echo '<td>'.Html::a($sld,['kp/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>';	 	 
            echo '</tr>'; 
        }
        
		echo '<tr data-toggle="collapse" data-target=".allagr" style="background-color:'.$sumColor.';">'; 
		$ld=0;
		
		$cntsn=0;
        foreach($rep['agreement'] as $val){
            if($cntsn>0){
				echo '<td>'.Html::a($val,['agreement/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
			}else{
				echo '<th>'.$val.'</th>';
			}
			$cntsn++;
			$ld+=$val;
        }
		echo '<th>'.Html::a($ld,['agreement/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</th>';
        echo '</tr>';   
        
        foreach($rep['sourceagreement'] as $val){
			$sld=0;
			$cnts=0;
            echo '<tr class="allagr collapse" style="background-color:'.$detailColor.';">';
                 foreach($val as $value){  
                    if($cnts>0){
						echo '<td>'.Html::a($value,['agreement/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					$sld+=$value;							 
                 }
			echo '<td>'.Html::a($sld,['agreement/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>';		 
            echo '</tr>'; 
        }
		
		
        
		echo '<tr data-toggle="collapse" data-target=".alltotal" style="background-color:'.$sumColor.';">'; 
			$ld=0;
			$cntsn=0;
        foreach($rep['total'] as $val){
            if($cntsn>0){
				echo '<td>'.Html::a($val,['payment/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
			}else{
				echo '<th>'.$val.'</th>';
			}
			$cntsn++;
			$ld+=$val;
        }
		echo '<th>'.Html::a($ld,['payment/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</th>';
        echo '</tr>';   
        
		foreach($rep['sourcetotal'] as $val){
			$sld=0;
			$cnts=0;
            echo '<tr class="alltotal collapse" style="background-color:'.$detailColor.';">';
                 foreach($val as $value){  
                    if($cnts>0){
						echo '<td>'.Html::a($value,['payment/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					$sld+=$value;							 
                 }
			echo '<td>'.Html::a($sld,['payment/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>';		 
            echo '</tr>'; 
        }
		
		
		//маржа
		$margeLine="";
		$margeMiddleLine="";
		$percentMagginLine = "";
		$margeLine.= '<tr data-toggle="collapse" data-target=".allmargin" style="background-color:'.$sumColor.';">';
		$margeMiddleLine.= '<tr data-toggle="collapse" data-target=".mdmargin" style="background-color:'.$sumColor.';">'; 
		$percentMagginLine.= '<tr data-toggle="collapse" data-target=".pcmargin" style="background-color:'.$sumColor.';">'; 
			$ld=0;
			$allmrgmd=0;
			$allprcmg=0;
			$agrcount=0;
			$totalcount=0;
			$cntsn=0;
        foreach($rep['margin'] as $key => $val){
			$mrgmd=$val;
			$mrgpc=0;
            if($cntsn>0){
				$margeLine.= '<td>'.Html::a($val,['payment/details','from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'type'=>'all'],['target'=>'_blank']).'</td>';  
				if(isset($rep['agreement'][$key])&&$rep['agreement'][$key]!=null ){
					$mrgmd= $val/$rep['agreement'][$key];
					$agrcount+=$rep['agreement'][$key];
				}
				$margeMiddleLine.= '<td>'.round($mrgmd,2).'</td>';
				if(isset($rep['total'][$key])&&$rep['total'][$key]!=null ){
					$mrgpc= $val/$rep['total'][$key];
					$totalcount+=$rep['total'][$key];
				}
				$percentMagginLine.= '<td>'.round($mrgpc*100,2).'</td>';
			}else{
				$margeLine.=  '<th>'.$val.'</th>';
				$margeMiddleLine.= '<th>Средняя маржа</th>';
				$percentMagginLine.= '<th>Процент маржи</th>';
			}
			$cntsn++;
			$ld+=$val;
        }
		if($agrcount>0){
			$allmrgmd = $ld/$agrcount;
		}else{
			$allmrgmd=$ld;
		}
		if($totalcount>0){
			$allprcmg = $ld/$totalcount;
		}
		$margeLine.=  '<th>'.Html::a($ld,['payment/details','from'=>$funnelreport->from,'to'=>$funnelreport->to,'type'=>'all'],['target'=>'_blank']).'</th>';
        $margeMiddleLine.= '<th>'.round($allmrgmd,2).'</th>';
		$percentMagginLine.= '<th>'.round($allprcmg*100,2).'</th>';
		$percentMagginLine.=  '</tr>';
		$margeMiddleLine.=  '</tr>'; 
		$margeLine.=  '</tr>';   
        
		echo $margeLine;
		
		
		foreach($rep['sourcemargin'] as $val){
			$sld=0;
			$cnts=0;
            echo '<tr class="allmargin collapse" style="background-color:'.$detailColor.';">';
                 foreach($val as $value){  
                    if($cnts>0){
						echo '<td>'.Html::a($value,['payment/details','source_id'=>$key,'from'=>$rep['days'][$cnts],'to'=>$rep['days'][$cnts]],['target'=>'_blank']).'</td>';  
					}else{
						echo '<td>'.$value.'</td>';  
					}
                    $cnts++;  
					$sld+=$value;							 
                 }
			echo '<td>'.Html::a($sld,['payment/details','source_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to],['target'=>'_blank']).'</td>';		 
            echo '</tr>'; 
        }
		
		echo $margeMiddleLine;
		echo $percentMagginLine;
    ?>    
</table>
<?php

		$managerList = User::getManager();
									
		foreach($managerList as $key=>$value){
			echo '<h3>'.$value.'</h3>';
			
			echo '<table class="table table-bordered  table-hover">';
				echo '<tr style="background-color:'.$headColor.';">';
				foreach($rep['days'] as $val){
					echo '<th>'.$val.'</th>';
				}
				echo '<th>ИТОГО</th>';
				echo '</tr>'; 
			//leads	
			$cnt=0;
			foreach($rep['managerlead'][$key] as $skey=>$val){
				
				if($cnt==0){
					echo '<tr  data-toggle="collapse" data-target=".manlead'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
				}else{
					echo '<tr  class="manlead'.$key.' collapse" style="background-color:'.$detailColor.';">';
					$t='td';
				}
				
				$ld=0;
				$cntsn=0;
				foreach($val as $value){  
							if($cntsn>0){
								echo '<'.$t.'>'.Html::a($value,['lead/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
							}else{
								echo '<'.$t.'>'.$value.'</'.$t.'>';
							}
						
						$cntsn++; 
						$ld+=		$value;
				}
				echo '<'.$t.'>'.Html::a($ld,['lead/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				echo '</tr>';
				$cnt=1;
			}			 
			//apps
			$cnt=0;
			foreach($rep['managerapp'][$key] as $skey=>$val){
				if($cnt==0){
					echo '<tr  data-toggle="collapse" data-target=".manapp'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
				}else{
					echo '<tr  class="manapp'.$key.' collapse" style="background-color:'.$detailColor.';">';
					$t='td';
				}
								$ld=0;
				$cntsn=0;
				foreach($val as $value){  
							if($cntsn>0){
								echo '<'.$t.'>'.Html::a($value,['application/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
							}else{
								echo '<'.$t.'>'.$value.'</'.$t.'>';
							}
						
						$cntsn++; 
						$ld+=		$value;
				}
				echo '<'.$t.'>'.Html::a($ld,['application/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				echo '</tr>';
				$cnt=1;
			}
			
			//kp
			$cnt=0;
			foreach($rep['managerkp'][$key] as $skey=>$val){

				if($cnt==0){ 
					echo '<tr  data-toggle="collapse" data-target=".mankp'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
				}else{
					echo '<tr  class="mankp'.$key.' collapse" style="background-color:'.$detailColor.';">'; 
					$t='td';
				}
								$ld=0;
				$cntsn=0;
				foreach($val as $value){  
							if($cntsn>0){
								echo '<'.$t.'>'.Html::a($value,['kp/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
							}else{
								echo '<'.$t.'>'.$value.'</'.$t.'>';
							}
						
						$cntsn++; 
						$ld+=		$value;
				}
				echo '<'.$t.'>'.Html::a($ld,['kp/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				echo '</tr>';
				$cnt=1;
			}
			//agreement
			$cnt=0;
			foreach($rep['manageragreement'][$key] as $skey=>$val){

				if($cnt==0){ 
					echo '<tr  data-toggle="collapse" data-target=".managr'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
				}else{
					echo '<tr  class="managr'.$key.' collapse" style="background-color:'.$detailColor.';">'; 
					$t='td';
				}
								$ld=0;
				$cntsn=0;
				foreach($val as $value){  
							if($cntsn>0){
								echo '<'.$t.'>'.Html::a($value,['agreement/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
							}else{
								echo '<'.$t.'>'.$value.'</'.$t.'>';
							}
						
						$cntsn++; 
						$ld+=		$value;
				}
				echo '<'.$t.'>'.Html::a($ld,['agreement/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				echo '</tr>';
				$cnt=1;
			}
			
			//total
			$cnt=0;
	
			foreach($rep['managertotal'][$key] as $skey=>$val){

				if($cnt==0){ 
					echo '<tr  data-toggle="collapse" data-target=".mantotal'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
				}else{
					echo '<tr  class="mantotal'.$key.' collapse" style="background-color:'.$detailColor.';">'; 
					$t='td';
				}
								$ld=0;
				$cntsn=0;
				foreach($val as $value){  
							if($cntsn>0){
								echo '<'.$t.'>'.Html::a($value,['payment/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
							}else{
								echo '<'.$t.'>'.$value.'</'.$t.'>';
							}
						
						$cntsn++; 
						$ld+=		$value;
				}
				echo '<'.$t.'>'.Html::a($ld,['payment/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				echo '</tr>';
				$cnt=1; 
			}
			
			//margin
			$cnt=0;
			foreach($rep['managermargin'][$key] as $skey=>$val){
				$manmargeLine="";
				$manmargeMilldleLine="";
				$manmargepercentLine="";
				$countagr=0;
				$alltotal=0;
				if($cnt==0){ 
					$manmargeLine.='<tr  data-toggle="collapse" data-target=".manmargin'.$key.'" style="background-color:'.$sumColor.';">';
					$t='th';
					$manmargeMilldleLine.='<tr  data-toggle="collapse" data-target="'.$key.'" style="background-color:'.$sumColor.';">';
					$manmargepercentLine.='<tr  data-toggle="collapse" data-target="'.$key.'" style="background-color:'.$sumColor.';">';
				}else{
					$manmargeLine.= '<tr  class="manmargin'.$key.' collapse" style="background-color:'.$detailColor.';">'; 
					$t='td';
				}
				$ld=0;
				$cntsn=0;
				foreach($val as $nkey => $value){  
							if($cntsn>0){
								$manmargeLine.= '<'.$t.'>'.Html::a($value,['payment/details','user_id'=>$key,'from'=>$rep['days'][$cntsn],'to'=>$rep['days'][$cntsn],'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';  
								if($cnt==0){
									if(isset($rep['manageragreement'][$key][$skey][$nkey])&&
									   $rep['manageragreement'][$key][$skey][$nkey]!=0){
										$manmargeMilldleLine.= '<'.$t.'>'.round($value/$rep['manageragreement'][$key][$skey][$nkey],2).'</'.$t.'>';
										$countagr+=$rep['manageragreement'][$key][$skey][$nkey];
									}else{
										$manmargeMilldleLine.= '<'.$t.'>'.($value).'</'.$t.'>';
									}
									//%
									if(isset($rep['managertotal'][$key][$skey][$nkey])&&
									   $rep['managertotal'][$key][$skey][$nkey]!=0){
										$manmargepercentLine.= '<'.$t.'>'.round(($value/$rep['managertotal'][$key][$skey][$nkey])*100,2).'</'.$t.'>';
										$alltotal+=$rep['managertotal'][$key][$skey][$nkey];
									}else{
										$manmargepercentLine.= '<'.$t.'>0</'.$t.'>';
									}
									
								}
							}else{
								$manmargeLine.= '<'.$t.'>'.$value.'</'.$t.'>';
								if($cnt==0){
									$manmargeMilldleLine.= '<'.$t.'>Cредняя маржа</'.$t.'>';
									$manmargepercentLine.= '<'.$t.'>Процент маржи</'.$t.'>';
								}
							}
						
						$cntsn++; 
						$ld+=$value;
				}
				$manmargeLine.='<'.$t.'>'.Html::a($ld,['payment/details','user_id'=>$key,'from'=>$funnelreport->from,'to'=>$funnelreport->to,'source_id'=>$skey],['target'=>'_blank']).'</'.$t.'>';
				if($cnt==0){
					$almrg=$ld;
					$alprc=0;
					if($countagr!=0){
						$almrg=round($almrg/$countagr,2);
					}
					if($alltotal!=0){
						$alprc=round(($ld/$alltotal)*100,2);
					}
					$manmargeMilldleLine.='<'.$t.'>'.$almrg.'</'.$t.'>';
					$manmargeMilldleLine.='</tr>';
					$manmargepercentLine.='<'.$t.'>'.$alprc.'</'.$t.'>';
					$manmargepercentLine.='</tr>';
				}
				$manmargeLine.='</tr>';
				
				$cnt=1;
				echo $manmargeLine;
				echo $manmargeMilldleLine;
				echo $manmargepercentLine;
			}
			
			
			
			
			
			echo '</table>';
		}

 ?> 



