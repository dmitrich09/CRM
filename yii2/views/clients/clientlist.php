<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Clients;
use yii\helpers\ArrayHelper;
use app\models\Sphere;
use app\models\City;
use budyaga\users\models\User;

$this->title = 'Компании';



if(isset($error)){
		echo '<div class="alert alert-danger">'.$error.'</div>';
	}
	$clienttypes = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');
	$sphereList = ArrayHelper::map(Sphere::find()->orderBy('name')->all()
									,'sphere_id'
									,'name');
	$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'city_id'
									,'name');
	$managerList = User::getManager();

	$abcList = ArrayHelper::map(Clients::getAbcMap()
									,'id'
									,'name');
	$abcdList = $abcList;
	$abcdList[0] = "Без категории";
?>




<div class="tab-content">
<!--НАЗВАНИЕ-->
	<div class="tab-pane active" id="name" role="tab">
				<?php
				$form = ActiveForm::begin([
						'id' => 'reportform',
						'action'=>['/clients/clientlist'],
						'options' => ['class' => 'form-inline','style'=>'margin-top:10px;'],
					]);
				echo $form->field($clients, 'clienttype_id')->dropDownList($clienttypes,['prompt'=>'Тип','class'=>'form-control input-xs'] )->label(false);
				echo $form->field($clients, 'abc_analize')->dropDownList($abcdList,['prompt'=>'АБС','class'=>'form-control input-xs'] )->label(false);
				echo $form->field($clients, 'sphere_id')->dropDownList($sphereList,['prompt'=>'Сфера деятельности','class'=>'form-control input-xs'] )->label(false);
				echo $form->field($clients, 'city_id')->dropDownList($cityList,['prompt'=>'Город','class'=>'form-control input-xs'] )->label(false);
				echo $form->field($clients, 'manager')->dropDownList($managerList,['prompt'=>'Менеджер','class'=>'form-control input-xs'] )->label(false);

				echo Html::submitButton('Получить',['class' => 'btn btn-primary', 'value'=>1,'name' => 'reportbusiness','style'=>'margin-top:-10px;']);
				ActiveForm::end();

			?>

		<?php

		if($report!=null){
			$dataProvider = new ActiveDataProvider([
				'query' => $report,
				'pagination' => ['pagesize'=>50],
			]);

			echo GridView::widget([
				'dataProvider' => $dataProvider,
				'rowOptions'=>function($data){
					if($data->clienttype_id==Clients::TYPE_TODEL){
					    return ['class' => 'danger'];
					}
				},
				'columns' => [

					['label'=>'Название',
						'format' => 'raw',
						'value'=>function ($data) {
							return Html::a($data->name,['clients/show','clients_id'=>$data->clients_id]);
						}
					],
					['label'=>'Тип клиента',
						'format' => 'raw',
						'value'=>function ($data) use($clienttypes,$abcList) {
							$ty = ArrayHelper::getValue($abcList,$data->abc_analize);
							$tyw = ArrayHelper::getValue($clienttypes,$data->clienttype_id);
							return $tyw.' '.$ty;
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
				],
			]);
		}?>
	</div>

  </div>
</div>
