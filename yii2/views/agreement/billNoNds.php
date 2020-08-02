<?php 
use app\models\Item;
use app\models\Kp;
use app\models\Document;
use app\models\Application;
use app\models\support\Money;

$kp = Kp::findOne($agreement->kp_id)

?>

<div style="">
<div class="col-xs-12">
	<div class="col-xs-12" style="margin-top:20px;font-size: 12px;">
		<table class="table table-bordered">
			<tr>
				<td colspan="4">ВОЛГО-ВЯТСКИЙ БАНК ПАО СБЕРБАНК г. Нижний Новгород</td>
				<td>БИК</td>
				<td>042202603</td>
			</tr>
			<tr>
				<td colspan="4">Банк получателя</td>
				<td>Сч. №</td>
				<td>30101810900000000603</td>
			</tr>
			<tr>
				<td>ИНН</td>
				<td>434589582760</td>
				<td>КПП</td>
				<td></td>
				<td rowspan="3">Сч. №</td>
				<td rowspan="3">40802810749770052025</td>
			</tr>
			<tr>
				<td colspan="4">ИП Филимонов Алексей Евгеньевич</td>
			</tr>
			<tr>
				<td colspan="4">Получатель</td>
			</tr>
		</table>
		
		<h3><b>Счет на оплату № <?=$kp->application_id?> от <?php echo date("d.m.Y",strtotime($agreement->agreedate));?> г.</b></h3>  
		<hr  >
		<div class="col-xs-12">
			<div class="col-xs-4">Поставщик<br>(Исполнитель):</div>
			<div class="col-xs-8"><b>Индивидуальный предприниматель Филимонов Алексей Евгеньевич, 
				ИНН 434589582760,  614000, Пермский край, г. Пермь, ул. 25 Октября, д.17 кв.20</b>
			</div>
		</div>
		<div class="col-xs-12" style="margin-top:20px;">
			<div class="col-xs-4">Покупатель<br>(Заказчик):</div>
			<div class="col-xs-8"><b><?=$client->full_name?>, ИНН <?=$client->inn?>, 
			КПП <?=$client->kpp?>, <?=$client->uraddress?></b></div>
		</div>
		
		<div class="col-xs-12" style="margin-top:20px;">
			<div class="col-xs-4">Основание:</div>
			<div class="col-xs-8"><b></b></div> 
		</div>
		
		<div class="col-xs-12" style="margin-top:20px;">
			<table class="table table-bordered"  style=" border: 1px solid black" >
				<tr>
					<th>№</th>
					<th>Товары (работы, услуги)</th>
					<th>Кол-во</th>
					<th>Ед.</th>
					<th>Цена</th>
					<th>Сумма</th>
				</tr>
			
		
		<?php
        $items = Item::find()->where(['application_id' => $kp->application_id])->all();
        $cnt=0;
		$amount=0;
            foreach($items as $item ){
              $cnt++;  
              $doc=Document::findOne($item->document_id);
              echo "<tr><td>$cnt</td>";
              echo "<td>Оформление 
                  ".
                    $doc->fullname   
                   ." на продукцию: ".
                $item->nameproduct ." ". $item->typemarkmodel ." Срок действия: ". Item::getTimelinenameById($item->timeline);
				if($item->pionhand == 10){
					echo ".   Протокол испытаний на бумажном носителе (1 шт. на 1 тип)";
				}
				if($item->pionhand == 20 ){
					echo ".  Без предоставления протокола испытаний";
				}
				if($item->pionhand == 0 || $item->pionhand == null){
					echo ".  Без предоставления протокола испытаний";
				}
				
			  echo " </td>";
              echo "<td>".$item->quantity."</td>";
			  echo "<td> усл.</td>";
              echo "<td>".$item->cost."</td>";
			  echo "<td>".$item->total."</td>";
			  $amount+=$item->total;
            }
            
        ?>
		
			</table>
		</div>
		
		<div class="col-xs-12" style="margin-top:20px;">
			<div class="col-xs-4"></div>
			<div class="col-xs-4"></div>
			<div class="col-xs-4">
				</br><b>Итого: <?=$amount?></b>
				</br><b>Без налога (НДС) </b>
				</br><b>Всего к оплате: <?=$amount?></b>
			</div>
		</div>
		<div class="col-xs-12" style="margin-top:20px;">
			Всего наименований <?=$cnt?>, на сумму <?=$amount?> руб.
		</div>
		<div class="col-xs-12" style="margin-top:20px;">
			<?php echo Money::getText($amount);?> 
		</div>
        <div  class="col-xs-12" style="margin-top: 20px">Оплатить не позднее <?= date("d.m.Y", time()+60*60*24*5);?></br>
			Оплата данного счета означает согласие с условиями поставки товара.
			Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.
			Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и
			паспорта.
		</div>

		<div class="col-xs-12" style="margin-top:20px;">
		<hr >
		<br>
		<br>
		
			<div class="col-xs-10" style="font-size:13px; ">Предприниматель __________________________________Филимонов А.Е.</div>
		
		</div>
		<div class="col-xs-12" style="margin-top:20px;">
		
			<div class="col-xs-7">
			<?php 
			if(isset($stamp)&&$stamp==1){
				echo '<img class="img-responsive center-block" 
						   src="/img/singStamp/singDir.png" 
						   style="position:relative;
								  z-index: 1; 
								  top: -80px;
								  height: 60px;
								  margin-left: 30%">';	
			}
			?> 
		</div>
		
		<div class="col-xs-12" >
		
			<div class="col-xs-5">
			<?php 
			if(isset($stamp)&&$stamp==1){
				echo '<img class="img-responsive center-block" 
						   src="/img/singStamp/StampIP.png" style="position:relative;
						          height: 250px;
								  z-index: 1; 
								  top: -170px;">
						  ';	
			}
			?> 
		</div>
		
			<div class="col-xs-7">
			</div>

		</div>
		
	</div>
</div>	
</div>
