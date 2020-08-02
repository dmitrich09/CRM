<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Application;
use yii\bootstrap\ActiveForm;
use app\models\Clients;
use yii\helpers\ArrayHelper;
use app\models\City;
use kartik\widgets\Typeahead; 

AppAsset::register($this);

$model= new Clients();
$clienttypes = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');

	$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'id'
									,'name');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


	<?php
	$chat='';
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/img/logo.png', ['title'=>'Современные решения','style' => 'width:30px;']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
	$notice="";
	if(Yii::$app->user->can('notice')){
		$notice = '<li class="dropdown" id="msgdrop">
					'.Html::a('',['#noticeModal'],['style'=>'float:left','id'=>'chtmsg','data-toggle'=>"dropdown",'class'=>'dropdown-toggle glyphicon glyphicon-bell']);
		$notice .= $this->render('//chat/count');
		$notice .= '<ul class="dropdown-menu keep_open" id="drdchat">
					<li><div style="height:400px;width:270px;overflow-y: auto;">';
		$notice .= $this->render('//chat/list');
		$notice .= '</div></li>
					</ul>
				</li>';
	}

	//+ компаний
	$addclient="";
	if(Yii::$app->user->can('clientlist')){
		$addclient='<li>'.Html::a('+Компания',['#clientModal'],['data-toggle'=>"modal"]).'</li>';
	}
	$clientlist="";
	if(Yii::$app->user->can('clientlist')){
		$clientlist='<li>'.Html::a('Компании',['/clients/clientlist']).'</li>';
	}
	//Пользователи
	$users="";
	if(Yii::$app->user->can('rbacManage')){
		$users='<li>'.Html::a('Пользователи',['/user/admin']).'</li>';
	}
	//права
	$rights="";
	if(Yii::$app->user->can('rbacManage')){
		$rights='<li>'.Html::a('Права',['/user/rbac']).'</li>';
	}
	//дела
	$business="";
	if(Yii::$app->user->can('business')){
		$qr=Application::getNoShowedQuery(Yii::$app->user->identity->id);
		$business='<li>'.Html::a('Мои дела',['/business/my']);
		if($qr->count()>0){
			$business='<li>'.Html::a('Мои дела ',['/business/my']);
		}
		$business.='</li>';
	}

	//Почта
	$mail="";
	if(Yii::$app->user->can('mail')){
		$mail='<li>'.Html::a('Почта',['/mail/mail']).'</li>';
	}
	//Общий диск
	$shareddisk="";
	if(Yii::$app->user->can('shareddisk')){
		$shareddisk='<li>'.Html::a('Общий диск',['/shareddisk/shareddisk']).'</li>';
	}


	//Настройки
	$settings="";
	if(Yii::$app->user->can('settings')){
		$settings='<li>'.Html::a('Настройки',['/settings/settings']).'</li>';
	}

	//Воронка
	$funnel="";
	if(Yii::$app->user->can('funnel')){
			$funnel='<li>'.Html::a('Воронка',['/funnel/funnel']).'</li>';
	}
	//Звонки

	if(Yii::$app->user->can('callrep')){
			$funnel.='<li>'.Html::a('Звонки общий',['/call/callreport']).'</li>';
	}
	$calls="";
	if(Yii::$app->user->can('callrep')){
			$funnel.='<li>'.Html::a('Звонки',['/call/zadarmacalls']).'</li>';
	}
	//Конверсия
	if(Yii::$app->user->can('funnel')){
			$funnel.='<li>'.Html::a('Конверсия',['/funnel/converce']).'</li>';
	}

	if(Yii::$app->user->can('funnel')){
			$funnel.='<li>'.Html::a('Отчет об оплатах',['/funnel/payrep']).'</li>';
	}

	if(Yii::$app->user->can('funnel')){
			$funnel.='<li>'.Html::a('Реестр выданных документов',['/funnel/getdoc']).'</li>';
	}

	if(Yii::$app->user->can('funnel')){
			$funnel.='<li>'.Html::a('План-факт',['/funnel/plan']).'</li>';
			$funnel.='<li>'.Html::a('Отчет по действующим',['/funnel/doingclients']).'</li>';
	}
	if(Yii::$app->user->can('plan')){
			$funnel.='<li>'.Html::a('План',['/plan/list']).'</li>';
	}

	//Саправочник: Сфера деятельности
	$sphere="";
	if(Yii::$app->user->can('catalog')){
			$sphere='<li>'.Html::a('Сфера деятельности',['/sphere/sphere']).'</li>';
	}
	//Саправочник: Источник
	$source="";
	if(Yii::$app->user->can('catalog')){
			$source='<li>'.Html::a('Источник',['/source/source']).'</li>';
	}
	//Справочник: Документ
	$document="";
	if(Yii::$app->user->can('catalog')){
			$document='<li>'.Html::a('Документ',['/document/document']).'</li>';
	}
	//Справочник: Типы контактов
	$contacttype="";
	if(Yii::$app->user->can('catalog')){
			$contacttype='<li>'.Html::a('Тип контакта',['/contacttype/contacttype']).'</li>';
	}
	//Справочник: Город
	$city="";
	if(Yii::$app->user->can('catalog')){
			$city='<li>'.Html::a('Область',['/city/city']).'</li>';
	}
	//Саправочник поставщики
	$provider="";
	if(Yii::$app->user->can('catalog')){
		$provider='<li>'.Html::a('Поставщики',['/provider/list']).'</li>';
	}
	//Справочник: Причины отказа
	$declinematter="";
	if(Yii::$app->user->can('catalog')){
			$declinematter='<li>'.Html::a('Причины отказа',['/declinematter/list']).'</li>';
	}
	// распределение клиентов
	$assignclients="";
	if(Yii::$app->user->can('assignclients')){
			$assignclients='<li>'.Html::a('Распределение клиентов',['/clients/assignclients']).'</li>';
	}
	// Перенос дел
	$movebusiness="";
	if(Yii::$app->user->can('settings')){
			$movebusiness='<li>'.Html::a('Перенос дел',['/business/move']).'</li>';
	}


    $addclientlist="";
	if(Yii::$app->user->can('addclientlist')){
			$addclientlist='<li>'.Html::a('Заливка клиентов',['/clients/addclientlistform']).'</li>';
	}
	//Выпадающий список

	$reports='';
	$search='';
	$login="";
	$changePass='<li>'.Html::a('Сменить пароль',['settings/changepass']).'</li>';
	if(Yii::$app->user->isGuest){
		$login='<li>'.Html::a('Вход',['/login']).'</li>';
	}else{
		$search='<li>'.Html::beginForm(['search/search'],'post',['class'=>'form-inline','style'=>'margin-top:10px;']
						).Html::input('text','search','',['class'=>'form-control input-xs','placeholder'=>'Поиск']
						).Html::submitButton('',['class' => 'btn glyphicon glyphicon-search', 'name' => 'add-button','style'=>'margin-left:5px;']
						).Html::endForm().'<li>';
		$reports='<li class="dropdown" id="menudrop">
					<a href="#" class="dropdown-toggle keep_open"  data-toggle="dropdown">Отчеты</a>
					<ul class="dropdown-menu keep_open" id="menu">
					'.$funnel.'
					'.$calls.'
					</ul>
				</li>';
		$login='<li class="dropdown" id="settingsdrop">
					<a href="#" class="dropdown-toggle keep_open"  data-toggle="dropdown" >'.Yii::$app->user->identity->username.'</a>
					<ul class="dropdown-menu keep_open" id="settings">
					<li>'.Html::a('Выход',['/logout']).'</li>
					'.$changePass.'
					<li class="divider"></li>
					'.$settings.'
					'.$movebusiness.'
					'.$users.'
					'.$rights.'
					'.$assignclients.'
                    '.$addclientlist.'
					<li class="divider"></li>
					<li class="disabled"><a href="#" >Справочники</a></li>
					'.$sphere.'
					'.$source.'
					'.$city.'
					'.$contacttype.'
					'.$document.'
					'.$provider.'
					'.$declinematter.'
					</ul>
				</li>';

	}


	echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
			$notice,$addclient,$clientlist,$business,$reports,$mail,$shareddisk
			,$search,$login,
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>

		<div id = "toTop" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-up"/> Наверх </div>

</div>

<div id="clientModal" class="modal fade " role="dialog">
  <div class="modal-dialog ">
    <div class="modal-content">
		<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Добавить клиента</h4>
      </div>
	   <div class="modal-body">
	  <?php

			$form = ActiveForm::begin([
						'id' => 'clientsadd-form',
						'action'=>['/clients/add'],

					]);


/*			echo Typeahead::widget([
				'name' => 'Clients[name]',
				'options' => ['placeholder' => 'Название','class'=>'form-control',"style"=>'margin-top:5px;margin-bottom:5px;'],
				'pluginOptions' => ['highlight'=>true],
				'dataset' => [
					[
						'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
						'display' => 'value',
						'remote' => [
							'url' => Url::to(['clients/namelist']) . '?q=%QUERY',
							'wildcard' => '%QUERY'
						]
					]
				]
			]);  */


			echo $form->field($model, 'clienttype_id')->dropDownList($clienttypes,['class'=>'form-control input-xs'] )->label(false);
			echo $form->field($model, 'city_id')->dropDownList($cityList,['prompt'=>'Область','class'=>'form-control input-xs'] )->label(false);
			//echo $form->field($model, 'site')->input("text","site",'',['placeholder'=>'Сайт','class'=>'form-control input-xs'] )->label(false);
			echo Html::input('text','Clients[site]',"",['placeholder' => 'Сайт','class'=>'form-control',"style"=>'margin-top:5px;margin-bottom:5px;']);
			echo Html::input('text','contact',"",['placeholder' => 'Контактное лицо','class'=>'form-control',"style"=>'margin-top:5px;margin-bottom:5px;']);
			echo Html::input('text','phone',"",['placeholder' => 'Телефон','class'=>'form-control',"style"=>'margin-top:5px;margin-bottom:5px;']);
			echo Html::submitButton('Добавить', ['class' => 'btn btn-primary form-control', 'name' => 'add-button', 'value'=>1 ,'style'=>'margin-top:8px;']);
			ActiveForm::end();

		?>
			</div>
    </div>
  </div>
</div>

<?php echo $chat;?>






<?php $this->endBody() ?>

<?php echo '<script>

$(function() {

$(window).scroll(function() {

if($(this).scrollTop() != 0) {

	$("#toTop").fadeIn();

} else {

$("#toTop").fadeOut();

}

});

$("#toTop").click(function() {

$("body,html").animate({scrollTop:0},800);

});

});

</script>';?>

<?php
/**
if(Yii::$app->user->can('notice')){
	echo '<script>

		$(document).ready(function() {

			setInterval(function(){

				var vis=0;
				var vis1=0;
				var vis2=0;
				if($("#drdchat").is(":visible")){
					vis=1;
				}
				if($("#menu").is(":visible")){
					vis1=1;
				}
				if($("#settings").is(":visible")){
					vis2=1;
				}

				$("#refreshButton").click();

				if(vis==1){
					$("#chtmsg").attr("aria-expanded","true");
					$("#msgdrop").removeClass("dropdown");
					$("#msgdrop").addClass("dropdown open");

				}
				if(vis1==1){
					$("#menudrop").removeClass("dropdown");
					$("#menudrop").addClass("dropdown open");
				}
				if(vis2==1){
					$("#settingsdrop").removeClass("dropdown");
					$("#settingsdrop").addClass("dropdown open");
				}

			}, 4000);

		});
		</script>';
		echo '<script>

		$(document).ready(function() {

			setInterval(function(){

				var vis=0;
				var vis1=0;
				var vis2=0;
				if($("#drdchat").is(":visible")){
					vis=1;
				}
				if($("#menu").is(":visible")){
					vis1=1;
				}
				if($("#settings").is(":visible")){
					vis2=1;
				}
				$("#refreshCountButton").click();

				if(vis==1){
					$("#msgdrop").removeClass("dropdown");
					$("#msgdrop").addClass("dropdown open");
				}
				if(vis1==1){
					$("#menudrop").removeClass("dropdown");
					$("#menudrop").addClass("dropdown open");
				}
				if(vis2==1){
					$("#settingsdrop").removeClass("dropdown");
					$("#settingsdrop").addClass("dropdown open");
				}

			}, 3000);

		});
		</script>';

}*/
?>
<script>
$(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});
</script>
<script>
  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });
</script>
</body>
</html>
<?php $this->endPage() ?>
