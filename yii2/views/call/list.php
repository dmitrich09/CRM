	<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Application;
use yii\helpers\ArrayHelper;
use app\models\Clients;
use app\models\Calls;
use budyaga\users\models\User;
/* @var $this yii\web\View */ 


?>

<h4>Звонки</h4>
<?php
			$query=Calls::find()->where(['clients_id'=>$client->clients_id])->orderBy(['callstart'=>SORT_DESC])->asArray();
			$dataProvider = new ActiveDataProvider([
				'query' =>$query,
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
								return $data['seconds'];
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
					

					['label'=>'Оператор',
						'format' => 'raw',
						'value'=>function ($data) use($userList) {
								if(isset($userList[$data['user_id']])){
									return $userList[$data['user_id']];
								}
								return $data['user_id'];
							},
					],
					['label'=>'Прослушать',
						'format' => 'raw',
						'value'=>function ($data) {
								return '<audio src="/calls/'.$data['call_id'].'.mp3" preload="auto" controls></audio>';
							},
					],
					
				], 
			]);
?>

