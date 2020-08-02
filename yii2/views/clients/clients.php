	<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Clients;
use yii\helpers\ArrayHelper;
use app\models\Clienttypes;
use app\models\Sphere;
use app\models\City;
use budyaga\users\models\User;
/* @var $this yii\web\View */

$this->title = 'Клиенты';
?>

<h4>Клиенты</h4>

<?php
	if($error){
		echo '<div class="alert alert-danger">'.$error.'</div>';
	}
	$clienttypeList = ArrayHelper::map(Clienttypes::find()->orderBy('name')->all()
									,'clienttype_id'
									,'name');
	$sphereList = ArrayHelper::map(Sphere::find()->orderBy('name')->all()
									,'sphere_id'
									,'name');
	$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'city_id'
									,'name');
	$managerList = ArrayHelper::map(User::find()->orderBy('username')->all()
									,'id'
									,'username');

			$dataProvider = new ActiveDataProvider([
				'query' => Clients::find()->orderBy(['name'=>SORT_ASC]),
				'pagination' => [
					'pageSize' => 10,
				], 
			]);
			
			echo GridView::widget([ 
				'dataProvider' => $dataProvider, 
				'columns' => [ 

					['label'=>'Название',
						'format' => 'raw',
						'value'=>function ($data) {
							return Html::a($data->name,['clients/show','clients_id'=>$data->clients_id],['class'=>'glyphicon glyphicon-eye-open']);
						}
					],						
					['label'=>'Тип клиента',
						'format' => 'raw',
						'value'=>function ($data) use($clienttypeList) {
						if(isset($clienttypeList[$data->clienttype_id])){
								return  $clienttypeList[$data->clienttype_id];
							}
							return $data->clienttype_id;
						}
						
					],
					['label'=>'Сфера деятельности',
						'format' => 'raw',
						'value'=>function ($data) use ($sphereList) {
							if(isset($sphereList[$data->sphere_id])){
								return $sphereList[$data->sphere_id];
							}
							return  $data->sphere_id;
						}
					],
					['label'=>'Менеджер',
						'format' => 'raw',
						'value'=>function ($data) use ($managerList) {
							if(isset($managerList[$data->manager])){
								return $managerList[$data->manager];
							}	
							return  $data->manager;
						}
					],
					['label'=>'Город',
						'format' => 'raw',
						'value'=>function ($data) use ($cityList) {
							if(isset($cityList[$data->city_id])){
								return $cityList[$data->city_id];
							}
							return  $data->city_id;
						}
					],	
					['label'=>'Инн',
						'format' => 'raw',
						'value'=>function ($data) {
							return  $data->inn;
						}
					],
					['label'=>'Банк',
						'format' => 'raw',
						'value'=>function ($data) {
							return  $data->bank;
						}
					],
					['label'=>'БИК',
						'format' => 'raw',
						'value'=>function ($data) {
							return  $data->bik;
						}
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'header'=>'', 
						'template' => '{delete}'
					],
				], 
			]);
?>

 