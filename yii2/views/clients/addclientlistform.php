<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\forms\ClientUploadForm;
use yii\helpers\ArrayHelper;
use app\models\City;
use app\models\Clients;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'id'
									,'name');
$clienttypes = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');
$abcList = ArrayHelper::map(Clients::getAbcMap()
									,'id'
									,'name');
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['action' =>['getclients'],'options' => ['enctype' => 'multipart/form-data']]) ?>
		<?php $model=new ClientUploadForm();
		echo $form->field($model, 'city_id')->dropDownList($cityList,['prompt'=>'Город','class'=>'form-control input-xs'] )->label(false);
        echo $form->field($model, 'clienttype_id')->dropDownList($clienttypes,['prompt'=>'Тип','class'=>'form-control input-xs'] )->label(false);
		echo $form->field($model, 'abc_analize')->dropDownList($abcList,['prompt'=>'ABC','class'=>'form-control input-xs'] )->label(false);
		?>
        <button class="btn btn-primary">Скачать .xls</button>
	</p>
	<p>
        <?php ActiveForm::end() ?>

        <?php $form = ActiveForm::begin(['action' =>['uploadclients'],'options' => ['enctype' => 'multipart/form-data']]) ?>
		<?php $model=new ClientUploadForm();
		echo $form->field($model, 'city_id')->dropDownList($cityList,['prompt'=>'Город','class'=>'form-control input-xs'] )->label(false);
        echo $form->field($model, 'clienttype_id')->dropDownList($clienttypes,['prompt'=>'Тип','class'=>'form-control input-xs'] )->label(false);
		echo $form->field($model, 'file')->fileInput() ?>

        <button>Загрузить</button>

        <?php ActiveForm::end() ?>
    </p>


</br>Параметры excel
</br> при загрузке клиентов, 1 срока используется в качестве заголовка
</br><b>Unumber</b> - уникальный цифробуквенный код клиента,код клиента
в нашей системе начинается с префикса английской K. Если хотите добавить нового клиента, оставьте пустым,
если хотите изменить данные, заполните поле
</br><b>name</b>  - название организации
</br><b>inn</b> - инн, уникален, при совпадении иин и не совпадении Unumber, добавлятся не будет
</br><b>kpp</b> -крр
</br><b>ogrn</b> огрн
</br><b>city_id</b> -ид города
</br><b>sphere_id</b> -ид сферы деятельности
</br><b>ogrn</b> огрн
</br><b>uraddress</b> юридический адрес
</br><b>factaddress</b> фактический адрес
</br><b>postaddress</b> почтовый адрес
</br><b>contacts</b> контактные данные в формате ИМЯ::телефон::email::комментарий|| (|| - разделитель между контактами. Если к примеру телефон пуст, то тогда ::::)
