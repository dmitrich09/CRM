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
use app\models\Contacts;
use app\models\Application;
use app\models\Source;
use app\models\Pionhand;
use app\models\Document;
use app\models\Lead;
use app\models\Kp;
use app\models\Agreement;
use budyaga\users\models\User;


	$sphereList = ArrayHelper::map(Sphere::find()->orderBy('name')->all()
									,'sphere_id'
									,'name');
	$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'city_id'
									,'name');
	$managerList = ArrayHelper::map(User::find()->orderBy('username')->all()
									,'id'
									,'username');
	$clientsList = ArrayHelper::map(Clients::find()->orderBy('name')->all()
									,'clients_id'
									,'name');
	$sourceList = ArrayHelper::map(Source::find()->orderBy('name')->all()
									,'source_id'
									,'name');
	$documentList = ArrayHelper::map(Document::find()->orderBy('name')->all()
									,'document_id'
									,'name');
	$clienttypeList = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');
    $abcList = ArrayHelper::map(Clients::getAbcMap()
									,'id'
									,'name');

		echo Html::a( 'x',['clients/show','clients_id'=>$model->clients_id],['class'=>'close']);
			$form = ActiveForm::begin([
						'id' => 'clientschange-form',
						'action'=>['/clients/change'],
						'options' => ['class' => 'form-inline'],
					]);
			echo $form->field($model, 'name')->textInput(['placeholder' => 'Название','class' => 'form-control'])->label("Название");
			echo $form->field($model, 'full_name')->textInput(['placeholder' => 'Полное название','class' => 'form-control'])->label("Полное название");
			echo $form->field($model, 'sphere_id')->dropDownList($sphereList,['prompt'=>'Сфера деятельности','class'=>'form-control input-xs'] )->label("Сфера деятельности");
			echo $form->field($model, 'clienttype_id')->dropDownList($clienttypeList,['prompt'=>'Тип','class'=>'form-control input-xs'] )->label("Тип");
            echo $form->field($model, 'abc_analize')->dropDownList($abcList,['prompt'=>'ABC','class'=>'form-control input-xs'] )->label("ABC");
			echo $form->field($model, 'manager')->dropDownList($managerList,['prompt'=>'Менеджер','class'=>'form-control input-xs'] )->label("Менеджер");
			echo $form->field($model, 'city_id')->dropDownList($cityList,['prompt'=>'Город','class'=>'form-control input-xs'] )->label("Город");
			echo $form->field($model, 'site')->textInput(['placeholder' => 'Сайт','class' => 'form-control'])->label("Сайт");
			echo $form->field($model, 'inn')->textInput(['placeholder' => 'ИНН','class' => 'form-control'])->label("ИНН");
			echo $form->field($model, 'ogrn')->textInput(['placeholder' => 'ОГРН','class' => 'form-control'])->label("ОГРН");
			echo $form->field($model, 'kpp')->textInput(['placeholder' => 'КПП','class' => 'form-control'])->label("КПП");
			echo $form->field($model, 'okpo')->textInput(['placeholder' => 'ОКПО','class' => 'form-control'])->label("ОКПО");
			echo $form->field($model, 'uraddress')->textInput(['placeholder' => 'Юр. адрес','class' => 'form-control'])->label('Юр. адрес');
			echo $form->field($model, 'factaddress')->textInput(['placeholder' => 'Фактический адрес','class' => 'form-control'])->label('Фактический адрес');
			echo $form->field($model, 'postaddress')->textInput(['placeholder' => 'Почтовый адрес','class' => 'form-control'])->label('Почтовый адрес');
			echo $form->field($model, 'bank')->textInput(['placeholder'=> 'Банк','class' => 'form-control'])->label('Банк');
			echo $form->field($model, 'bik')->textInput(['placeholder'=> 'БИК','class' => 'form-control'])->label('БИК');
			echo $form->field($model, 'rs')->textInput(['placeholder' => 'Р/С','class' => 'form-control'])->label('Р/С');
			echo $form->field($model, 'ks')->textInput(['placeholder' => 'К/С','class' => 'form-control'])->label('К/С');
			echo Html::hiddenInput('clients_id',$model->clients_id);
			echo Html::submitButton('Обновить',['class' => 'btn btn-xs btn-primary', 'name' => 'add-button','style'=>'margin-left:10px;']);
			ActiveForm::end();


?>
