<?php 
use app\models\Clients;
use app\models\Application;
use app\models\Item;
use app\models\Document;
use app\models\support\Money;
use app\models\User;


?>

<div class='col-xs-12' style="height:100%;">
    <div class='col-xs-12'>
        <div class='col-xs-2'></div>    
        <div class="col-xs-8"><img src="/img/kphead.jpg" style="width:100%;"></div>
        <div class='col-xs-2'></div> 
    </div> 
  
     <div class='col-xs-12' style="margin-top:30px;">
        <div class="col-xs-12"><img src="/img/kpimg.jpg"  style="width:100%;"></div>
    </div> 
    
    <h3 class='text-center'>Коммерческое предложение</h3>
    </br>
    <h4 class='text-center'>для <?php echo Clients::findOne($kp->clients_id)->name ?></h4>
    
	<div class='col-xs-12' style="margin-top:100px;">
		<h4 class='text-center' >Пермь <?=date('Y') ?>г.</h4>
	</div> 
</div>    
 
 <div class='col-xs-12'>
    <h4 class='text-center'><?=$kp->client_name ?></h4>
    </br> 
    <h4 class='text-center'>На основании предоставленной технической документации
	ООО «Современные решения» предлагает рассмотреть следующие 
	коммерческие условия, действующие до 
	<?php $date = new \DateTime();
			$date->add(new \DateInterval('P1M'));
			echo $date->format('d.m.Y'); ?> года
    </h4>
    <h4 class='text-center'>Стоимость и сроки оформления документов:
    </h4>

    



    <table class="table table-bordered">
        <tr><th>№</th><th>Наименование продукции/Документ</th><th>Тип</th><th>Срок действия</th><th>Кол-во</th><th>Стоимость</th><th>Сроки оформления в рабочих днях</th></tr>
        
        <?php
        $items = Item::find()->where(['application_id'=>$kp->application_id])->all();
        $cnt=0;
        $maxdays=0;
        $docdefArray;
		$kp->total=0;
            foreach($items as $item ){
              $cnt++;  
              $doc=Document::findOne($item->document_id);
              echo "<tr><td>$cnt</td>";
              echo "<td>Оформление 
                  ".
                    $doc->fullname   
                   ." на продукцию: ".
                $item->nameproduct ." ". $item->typemarkmodel ." Срок действия: ". Item::getTimelinenameById($item->timeline).".";
				if($item->control==Application::YES){
					echo " Инспекционный контроль включен."; 
				}
				if($doc->is_include==Application::YES){
					if($item->pionhand==Application::YES){
						echo " Протокол испытаний включен"; 
					}
				}
			  echo " </td>";
              echo "<td>".$item->typemarkmodel."</td>";
			  echo "<td>".Item::getTimelinenameById($item->timeline)."</td>";
			  echo "<td>".$item->quantity."</td>";
              echo "<td>".$item->total."</td>";
              echo "<td>".$item->days."</td></tr>";
              $docdefArray[$item->document_id]=$doc->for_doc;
              if($maxdays<$item->days){
                  $maxdays=$item->days;
              }
			  $kp->total+=$item->total;
			  $kp->save(); 
            }
            
        ?>
        <tr>
            <td colspan="3"><b>Итого: <?=Money::getText($kp->total)?></b></td>
			<td ></td><td ></td>
            <td ><b><?=$kp->total?></b></td>
            <td ><b><?=$maxdays?></b></td>
        </tr>
    </table>    
    

	<div class="col-lg-12" style="margin-top:50px;">
		<?php
			$us=User::findOne($kp->manager_id);
		?>
	
		<p>С наилучшими пожеланиями,</p>
		<p><?php echo $us->position ?></p>
		<p><?php
			$managerList = User::getMapAll();
			echo (isset($managerList[$kp->manager_id])?$managerList[$kp->manager_id]:$kp->manager_id);							

		?></p>
		<p>_____________________________________________________</p>
		<p></p>
		<p>Орган по Сертификации "Современные решения"</p>
		<p>614000, г.Пермь, Комсомольский проспект 34, оф. 310</p>
		<p>mail: <?php echo $us->email ?></p>
		<p>8 (342) 250-92-50</p>
		<p>сот. <?php echo $us->mobile ?></p>
		<p>сайт: http://sr-sert.ru, http://vk.com/srsert</p>
	
	
	</div>
	



     
</div>