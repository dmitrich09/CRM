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
use app\models\Document;
use app\models\Lead;
use app\models\Kp;
use app\models\Agreement;
use app\models\Ork;
use budyaga\users\models\User;
use yii\widgets\Pjax;
use app\models\Calls;
use app\models\Outcall;

$this->title = 'Клиенты';
?>
<?php
	if(isset($error)){
		echo '<div class="alert alert-danger">'.$error.'</div>';
	}

        if(isset($answer)){
		echo '<div class="alert alert-success">'.$answer.'</div>';
	}
	$clienttypeList = ArrayHelper::map(Clients::getTypeMap()
									,'id'
									,'name');
	$sphereList = ArrayHelper::map(Sphere::find()->orderBy('name')->all()
									,'sphere_id'
									,'name');
	$cityList = ArrayHelper::map(City::find()->orderBy('name')->all()
									,'city_id'
									,'name');
	$managerList = User::getManager();

	$sourceList = ArrayHelper::map(Source::find()->orderBy('name')->all()
									,'source_id'
									,'name');
	$documentList = ArrayHelper::map(Document::find()->orderBy('name')->all()
									,'document_id'
									,'name');
	$abcList = ArrayHelper::map(Clients::getAbcMap()
									,'id'
									,'name');
	$allUser=User::getAll();



?>


<div class='col-xs-12'>
<div class="panel panel-default">
  <div class="panel-heading"><?php echo $model->name?></div>
  <div class="panel-body">
      <?php Pjax::begin(['id'=>'clientsPjax','enablePushState' => false,'timeout' => 3000]);?>
  <div class="col-xs-12">
      <div class="col-xs-4">
			<b>Полное название: </b><?php echo $model->full_name; ?>
          </br><b>Тип клиента: </b><?php echo (isset($clienttypeList[$model->clienttype_id])?$clienttypeList[$model->clienttype_id]:$model->clienttype_id) ?>
				<?php echo (isset($abcList[$model->abc_analize])?$abcList[$model->abc_analize]:$model->abc_analize) ?>
          </br><b>Сфера деятельности: </b><?php echo(isset($sphereList[$model->sphere_id])?$sphereList[$model->sphere_id]:$model->sphere_id)?>
          </br><b>Менеджер: </b><?php echo (isset($allUser[$model->manager])?$allUser[$model->manager]:$model->manager)?>
          </br><b>Область: </b><?php echo (isset($cityList[$model->city_id])?$cityList[$model->city_id]:$model->city_id)?>
          </br><b>ИНН: </b><?php echo $model->inn; ?>
      </div>
      <div class="col-xs-4">
		  <b>Сайт: </b><?php echo Html::a($model->site,$model->site,['target'=>'_blank']); ?>
          </br><b>ОГРН: </b><?php echo $model->ogrn; ?>
          </br><b>КПП: </b><?php echo $model->kpp; ?>
          </br><b>ОКПО: </b><?php echo $model->okpo; ?>
          </br><b>Юр. адрес: </b><?php echo $model->uraddress; ?>
          </br><b>Фактический адрес: </b><?php echo $model->factaddress; ?>
      </div>
      <div class="col-xs-4">
          <b>Почтовый адрес: </b><?php echo $model->postaddress; ?>
          </br><b>Банк: </b><?php echo $model->bank; ?>
          </br><b>БИК: </b><?php echo $model->bik; ?>
          </br><b>Р/С: </b><?php echo $model->rs; ?>
          </br><b>К/С: </b><?php echo $model->ks; ?>
      </div>
  </div>
      <?php echo Html::a('',['/clients/change','clients_id'=>$model->clients_id],['data-pjax'=>'1','class'=>'glyphicon glyphicon-pencil','data-toggle'=>"tooltip",'title'=>'Изменить']);

      Pjax::end(); ?>

  </div>
</div>
</div>

<div class="panel-body" id="clpanel">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
		  <li class="<?=((!isset($tab)||$tab==null)?'active':"") ?>"><a href="#contacts" data-toggle="tab">Контакты</a></li>
		  <li class="<?=((isset($tab)&&$tab=='outcall')?'active':"")?>"><a href="#outcall" data-toggle="tab">Звонки <span class="badge" style='margin-left:3px;'><?php echo Outcall::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
		  <li class="<?=((isset($tab)&&$tab=='lead')?'active':"")?>"><a href="#lead" data-toggle="tab">ЭК<span class="badge" style='margin-left:3px;'><?php echo Lead::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
          <li class="<?=((isset($tab)&&$tab=='apps')?'active':"")?>"><a href="#apps" data-toggle="tab">Заявки<span class="badge" style='margin-left:3px;'><?php echo Application::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
		  <li class="<?=((isset($tab)&&$tab=='kp')?'active':"")?>"><a href="#kp" data-toggle="tab">Коммерческие<span class="badge" style='margin-left:3px;'><?php echo Kp::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
          <li class="<?=((isset($tab)&&$tab=='agreement')?'active':"")?>"><a href="#agreement" data-toggle="tab">Договоры<span class="badge" style='margin-left:3px;'><?php echo Agreement::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
          <li class="<?=((isset($tab)&&$tab=='ork')?'active':"")?>"><a href="#ork" data-toggle="tab">ОРК<span class="badge" style='margin-left:3px;'><?php echo Ork::find()->where(['clients_id'=>$model->clients_id,'enddate'=>null])->count()?></span></a></li>
          <li class="<?=((isset($tab)&&$tab=='calls')?'active':"")?>"><a href="#calls" data-toggle="tab">История звонков<span class="badge" style='margin-left:3px;'><?php echo Calls::find()->where(['clients_id'=>$model->clients_id])->count()?></span></a></li>
		  <li class="<?=((isset($tab)&&$tab=='log')?'active':"")?>"><a href="#log" data-toggle="tab">История</a></li>
		  <li class="<?=((isset($tab)&&$tab=='docs')?'active':"")?>"><a href="#docs" data-toggle="tab">Док-ты</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content" id="client_tabs">
                    <div class="tab-pane <?=((!isset($tab)||$tab==null)?'active':"") ?>" id="contacts">
                         <?= $this->render('/contacts/contacts',['model'=>new Contacts(),'client'=>$model]); ?>
                    </div>
					<div class="tab-pane <?=((isset($tab)&&$tab=='outcall')?'active':"") ?>" id="outcall">
                        <?= $this->render('/outcall/outcall',['model'=>new Outcall(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='lead')?'active':"") ?>" id="lead">
                        <?= $this->render('/lead/lead',['model'=>new Lead(),'application'=>$application,'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='apps')?'active':"") ?>" id="apps">
                        <?= $this->render('/application/application',['model'=>new Application(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='kp')?'active':"") ?>" id="kp">
                        <?= $this->render('/kp/kp',['model'=>new Kp(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='agreement')?'active':"") ?>" id="agreement">
                        <?= $this->render('/agreement/agreement',['model'=>new Agreement(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='ork')?'active':"") ?>" id="ork">
                        <?= $this->render('/ork/list',['model'=>new Ork(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='calls')?'active':"") ?>" id="calls">
                       <?= $this->render('/call/list',['model'=>new Calls(),'client'=>$model]); ?>
                    </div>
                    <div class="tab-pane <?=((isset($tab)&&$tab=='log')?'active':"") ?>" id="log">
                       <?= $this->render('/clientlog/list',['client'=>$model]); ?>
                    </div>
					 <div class="tab-pane <?=((isset($tab)&&$tab=='docs')?'active':"") ?>" id="docs">
                       <?= $this->render('/document/documents',['client'=>$model]); ?>
                    </div>

                </div>
</div>
