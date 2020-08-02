<?php

namespace app\controllers;

use Yii;
use app\models\Source;
use app\models\City;
use app\models\Sphere;
use app\models\Declinematter;
use app\models\Contacttype;
use app\models\Document;
use app\models\Provider;
use app\models\Task;
use app\models\Clients;
use app\models\Outcall;
use app\models\Calls;
use app\models\Lead;
use app\models\Application;
use app\models\Kp;
use app\models\Agreement;
use app\models\Ork;
use app\models\Comments;
use app\models\Item;
use app\models\User;
use app\models\Contacts;
use app\models\Planned;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use cadyrov\gii\Upload;
use cadyrov\gii\File;
use yii\filters\AccessControl;
use moonland\phpexcel\Excel;
use yii\db\Query;
use yii\web\UploadedFile;
use app\controllers\Dictionary;

class GetdataController extends BaseController
{
   
	
	public function actionCity(){
		
//	    City::deleteAll();
//		return self::ok(City::find()->andWhere(['account_id' => self::$user->account_id])->all());   
	    return self::ok('заглушка City');   //заглушка
		  
		$path = "http://95.183.8.45/index.php/getdata/city?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
	    foreach($rez as $val){
			$query = City::findOne($val->city_id);	
			if(!$query){
				Yii::$app->db->createCommand()->insert('city', 
					[
						'id' => $val->city_id,
						'name' => $val->name,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('city', 
				[
				   'name' => $val->name,
				   'account_id' => self::$user->account_id,
				   'deleted_at' => null,
				], 
				"id = $val->city_id "
				)->execute();
			}
		}     
       	return self::ok(City::find()->andWhere(['account_id' => self::$user->account_id])->orderBy('name asc')->all());   
		
	} 
	
	public function actionSource(){
		
//		Source::deleteAll();
//		return self::ok(Source::find()->andWhere(['account_id' => self::$user->account_id])->all());   
		return self::ok('заглушка Source');   //заглушка
		
	    $path = "http://95.183.8.45/index.php/getdata/source?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
        foreach($rez as $val){
			$query = Source::findOne($val->source_id);
				if(!$query){
				Yii::$app->db->createCommand()->insert('source', 
					[
						'id' => $val->source_id,
						'name' => $val->name,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('source', 
					[
					   'name' => $val->name,
					   'account_id' => self::$user->account_id,
					   'deleted_at' => null,
					], 
					"id = $val->source_id "
				)->execute();
			}
		}   
		return self::ok(Source::find()->andWhere(['account_id' => self::$user->account_id])->all());    
	} 
	
	public function actionSphere(){
		
//		Sphere::deleteAll();
//		return self::ok(Sphere::find()->andWhere(['account_id' => self::$user->account_id])->all());   
		return self::ok('заглушка Sphere');   //заглушка
			
	    $path = "http://95.183.8.45/index.php/getdata/sphere?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
	    foreach($rez as $val){
			$query = Sphere::findOne($val->sphere_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('sphere', 
					[
						'id' => $val->sphere_id,
						'spherename' => $val->name,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('sphere', 
					[
					   'spherename' => $val->name,
					   'account_id' => self::$user->account_id,
					   'deleted_at' => null,
					], 
					"id = $val->sphere_id "
				)->execute();
			}
		}   
		return self::ok(Sphere::find()->andWhere(['account_id' => self::$user->account_id])->all());    
		
	} 
	
	public function actionDeclinematter(){
		
//		Declinematter::deleteAll();
//		return self::ok(Declinematter::find()->andWhere(['account_id' => self::$user->account_id])->all());   
		return self::ok('заглушка Declinematter');   //заглушка
		
	    $path = "http://95.183.8.45/index.php/getdata/declinematter?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Declinematter::findOne($val->declinematter_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('declinematter', 
					[
						'id' => $val->declinematter_id,
						'name' => $val->name,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('declinematter', 
					[
					   'name' => $val->name,
					   'account_id' => self::$user->account_id,
					   'deleted_at' => null,
					], 
					"id = $val->declinematter_id "
				)->execute();
			}
		}   
		return self::ok(Declinematter::find()->andWhere(['account_id' => self::$user->account_id])->all());    
		
	} 
	
	public function actionContacttype(){
		
//		Contacttype::deleteAll();
//		return self::ok(Contacttype::find()->andWhere(['account_id' => self::$user->account_id])->all());   
		return self::ok('заглушка Contacttype');   //заглушка
		
	    $path = "http://95.183.8.45/index.php/getdata/contacttype?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Contacttype::findOne($val->contacttype_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('contacttype', 
					[
						'id' => $val->contacttype_id,
						'name' => $val->name,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('contacttype', 
					[
					   'name' => $val->name,
					   'account_id' => self::$user->account_id,
					   'deleted_at' => null,
					], 
					"id = $val->contacttype_id "
				)->execute();
			}
		}   
		return self::ok(Contacttype::find()->andWhere(['account_id' => self::$user->account_id])->all());    
	} 
	
	public function actionDocument(){
		
//		Document::deleteAll();
//		return self::ok(Document::find()->andWhere(['account_id' => self::$user->account_id])->all());   
		return self::ok('заглушка Document');   //заглушка
		
	    $path = "http://95.183.8.45/index.php/getdata/document?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
		    $query = Document::findOne($val->document_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('document', 
					[
						'id' => $val->document_id,
						'name' => $val->name,
						'fullname' => $val->fullname,
						'for_doc' => $val->for_doc,
						'is_include' => $val->is_include,
						'is_control' => $val->is_control,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('document', 
					[
					    'name' => $val->name,
					    'fullname' => $val->fullname,
				        'for_doc' => $val->for_doc,
					    'is_include' => $val->is_include,
					    'is_control' => $val->is_control,
					    'account_id' => self::$user->account_id,
					    'account_id' => self::$user->account_id,
					    'deleted_at' => null,
					], 
					"id = $val->document_id "
				)->execute();
			}
		}   
		return self::ok(Document::find()->andWhere(['account_id' => self::$user->account_id])->all());    
	} 
	
	public function actionProvider(){
		
//		Provider::deleteAll();
//		return self::ok(Provider::find()->andWhere(['account_id' => self::$user->account_id])->all());  
        return self::ok('заглушка Provider');   //заглушка   
		  
	    $path = "http://95.183.8.45/index.php/getdata/provider?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Provider::findOne($val->provider_id);
				if(!$query){
				Yii::$app->db->createCommand()->insert('provider', 
					[
						'id' => $val->provider_id,
						'name' => $val->name,
						'email' => $val->email,
						'lawadress' => $val->lawadress,
						'postadress' => $val->postadress,
						'adress' => $val->adress,
						'contact' => $val->contact,
						'phone' => $val->phone,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('provider', 
					[
					    'name' => $val->name,
					   	'email' => $val->email,
						'lawadress' => $val->lawadress,
						'postadress' => $val->postadress,
						'adress' => $val->adress,
						'contact' => $val->contact,
						'phone' => $val->phone,
					    'account_id' => self::$user->account_id,
					    'deleted_at' => null,
					], 
					"id = $val->provider_id "
				)->execute();
			}
		}   
		return self::ok(Provider::find()->andWhere(['account_id' => self::$user->account_id])->all());    
	} 
	
	public function actionClients(){

//		Clients::deleteAll();
//		return self::ok(Clients::find()->andWhere(['account_id' => self::$user->account_id])->all());     
//		return self::ok('заглушка Clients');   //заглушка	
		
	    $path = "http://95.183.8.45/index.php/getdata/clients?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Clients::findOne($val->clients_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('clients', 
					[
						'id' => $val->clients_id,
						'name' => $val->name,
						'clienttype_id' => $val->clienttype_id,
						'sphere_id' => $val->sphere_id,
						'manager' => $val->manager,
						'city_id' => $val->city_id,
						'inn' => $val->inn,
						'ogrn' => $val->ogrn,
						'kpp' => $val->kpp,
						'okpo' => $val->okpo,
						'uraddress' => $val->uraddress,
						'factaddress' => $val->factaddress,
						'postaddress' => $val->postaddress,
						'bank' => $val->bank,
						'bik' => $val->bik,
						'rs' => $val->rs,
						'ks' => $val->ks,
						'unique_id' => $val->unique_id,
						'full_name' => $val->full_name,
						'site' => $val->site,
						'abc_analize' => $val->abc_analize,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('clients', 
					[
						'name' => $val->name,
						'clienttype_id' => $val->clienttype_id,
						'sphere_id' => $val->sphere_id,
						'manager' => $val->manager,
						'city_id' => $val->city_id,
						'inn' => $val->inn,
						'ogrn' => $val->ogrn,
						'kpp' => $val->kpp,
						'okpo' => $val->okpo,
						'uraddress' => $val->uraddress,
						'factaddress' => $val->factaddress,
						'postaddress' => $val->postaddress,
						'bank' => $val->bank,
						'bik' => $val->bik,
						'rs' => $val->rs,
						'ks' => $val->ks,
						'unique_id' => $val->unique_id,
						'full_name' => $val->full_name,
						'site' => $val->site,
						'abc_analize' => $val->abc_analize,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->clients_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	public function actionTask(){
		
//		Task::deleteAll();
//		return self::ok(Task::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка Task');   //заглушка
		 
	    $path = "http://95.183.8.45/index.php/getdata/task?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Task::findOne($val->task_id);
				
			if(!$query){
				Yii::$app->db->createCommand()->insert('task', 
					[
						'id' => $val->task_id,
						'user_id' => $val->user_id,
						'manager_id' => $val->manager_id,
						'task_date' => $val->task_date,
						'comment' => $val->comment,
						'description' => $val->description,
						'status_id' => $val->status_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('task', 
					[
					    'user_id' => $val->user_id,
						'manager_id' => $val->manager_id,
						'task_date' => $val->task_date,
						'comment' => $val->comment,
						'description' => $val->description,
						'status_id' => $val->status_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->task_id "
					
				)->execute();
			}
		}   
		return self::ok(Task::find()->andWhere(['account_id' => self::$user->account_id])->all());    
	} 
	
	public function actionCalls(){
		
//		Calls::deleteAll();
//		return self::ok(Calls::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка Calls');   //заглушка 
        
		
	    $path = "http://95.183.8.45/index.php/getdata/calls?token=liyglijgljhksabce&limit=50000&offset=100000";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Calls::findOne($val->call_id);
				if(!$query){
				Yii::$app->db->createCommand()->insert('calls', 
					[
						'id' => $val->call_id,
						'zad_call_id' => $val->zad_call_id,
						'sip' => $val->sip,
						'callstart' => $val->callstart,
						'clid' => $val->clid,
						'destination' => $val->destination,
						'disposition' => $val->disposition,
						'seconds' => $val->seconds,
						'is_recorded' => $val->is_recorded,
						'clients_id' => $val->clients_id,
						'user_id' => $val->user_id,
						'incoming' => $val->incoming,
						'is_file' => $val->is_file,
						'is_warm' => $val->is_warm,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('calls', 
					[
					    'zad_call_id' => $val->zad_call_id,
						'sip' => $val->sip,
						'callstart' => $val->callstart,
						'clid' => $val->clid,
						'destination' => $val->destination,
						'disposition' => $val->disposition,
						'seconds' => $val->seconds,
						'is_recorded' => $val->is_recorded,
						'clients_id' => $val->clients_id,
						'user_id' => $val->user_id,
						'incoming' => $val->incoming,
						'is_file' => $val->is_file,
						'is_warm' => $val->is_warm,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->call_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	} 
	
	public function actionOutcall(){
		
//		Outcall::deleteAll();
//		return self::ok(Outcall::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка');   //заглушка
		
	    $path = "http://95.183.8.45/index.php/getdata/outcall?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Outcall::findOne($val->outcall_id);
				
			if(!$query){
				Yii::$app->db->createCommand()->insert('outcall', 
					[
						'id' => $val->outcall_id,
						'clients_id' => $val->clients_id,
						'user_id' => $val->user_id,
						'contactdate' => $val->contactdate,
						'comment' => $val->comment,
						'status' => $val->status,
						'showed' => $val->showed,
						'enddate' => $val->enddate,
						'startdate' => $val->startdate,
						'declinematter_id' => $val->declinematter_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('outcall', 
					[
						'clients_id' => $val->clients_id,
						'user_id' => $val->user_id,
						'contactdate' => $val->contactdate,
						'comment' => $val->comment,
						'status' => $val->status,
						'showed' => $val->showed,
						'enddate' => $val->enddate,
						'startdate' => $val->startdate,
						'declinematter_id' => $val->declinematter_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->outcall_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	public function actionLead(){
		
//		Lead::deleteAll();
//		return self::ok(Lead::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		  
	    $path = "http://95.183.8.45/index.php/getdata/lead?token=liyglijgljhksabce";
		
        $ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		return self::ok('заглушка');   //заглушка
		
		foreach($rez as $val){
			
			$query = Lead::findOne($val->lead_id);
				
			if(!$query){
				Yii::$app->db->createCommand()->insert('lead', 
					[
						'id' => $val->lead_id,
						'clients_id' => $val->clients_id,
						'interesttype' => $val->interesttype,
						'state' => $val->state,
						'source_id' => $val->source_id,
						'comment' => $val->comment,
						'manager' => $val->manager,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'contactdate' => $val->contactdate,
						'declinematter_id' => $val->declinematter_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('lead', 
					[
						'clients_id' => $val->clients_id,
						'interesttype' => $val->interesttype,
						'state' => $val->state,
						'source_id' => $val->source_id,
						'comment' => $val->comment,
						'manager' => $val->manager,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'contactdate' => $val->contactdate,
						'declinematter_id' => $val->declinematter_id,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->lead_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	public function actionApplication(){
		
//		Application::deleteAll();
//		return self::ok(Application::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		  
	    $path = "http://95.183.8.45/index.php/getdata/application?token=liyglijgljhksabce";
		
        $ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		return self::ok('заглушка');   //заглушка
		
		foreach($rez as $val){
			
			$query = Application::findOne($val->application_id);
				
			if(!$query){
				Yii::$app->db->createCommand()->insert('application', 
					[
						'id' => $val->application_id,
						'clients_id' => $val->clients_id,
						'source_id' => $val->source_id,
						'nameproduct' => $val->nameproduct ,
						'okp' => $val->okp,
						'okpd2' => $val->okpd2,
						'tnved' => $val->tnved,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'lead_id' => $val->lead_id,
						'manager_id' => $val->manager_id,
						'contactdate' => $val->contactdate,
						'state' => $val->state,
						'field' => $val->field,
						'countrymade' => $val->countrymade,
						'countryask' => $val->countryask,
						'comment' => $val->comment,
						'exittoclient' => $val->exittoclient,
						'test' => $val->test,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'documentprod' => $val->documentprod,
						'showed' => $val->showed,
						'countmanager_id' => $val->countmanager_id,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('application', 
					[
						'clients_id' => $val->clients_id,
						'source_id' => $val->source_id,
						'nameproduct' => $val->nameproduct,
						'okp' => $val->okp,
						'okpd2' => $val->okpd2,
						'tnved' => $val->tnved,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'lead_id' => $val->lead_id,
						'manager_id' => $val->manager_id,
						'contactdate' => $val->contactdate,
						'state' => $val->state,
						'field' => $val->field,
						'countrymade' => $val->countrymade,
						'countryask' => $val->countryask,
						'comment' => $val->comment,
						'exittoclient' => $val->exittoclient,
						'test' => $val->test,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'documentprod' => $val->documentprod,
						'showed' => $val->showed,
						'countmanager_id' => $val->countmanager_id,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->application_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
		public function actionKp(){
		
//		Kp::deleteAll();
//		return self::ok(Kp::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		  
	    $path = "http://95.183.8.45/index.php/getdata/kp?token=liyglijgljhksabce";
		
        $ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		return self::ok('заглушка');   //заглушка
		
		foreach($rez as $val){
			
			$query = Kp::findOne($val->kp_id);
				
			if(!$query){
				Yii::$app->db->createCommand()->insert('kp', 
					[
						'id' => $val->kp_id,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'clients_id' => $val->clients_id ,
						'state' => $val->state,
						'manager_id' => $val->manager_id,
						'application_id' => $val->application_id,
						'client_name' => $val->client_name,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'source_id' => $val->source_id,
						'contactdate' => $val->contactdate,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('kp', 
					[
						'startdate' => $val->startdate,
						'enddate' => $val->enddate,
						'clients_id' => $val->clients_id ,
						'state' => $val->state,
						'manager_id' => $val->manager_id,
						'application_id' => $val->application_id,
						'client_name' => $val->client_name,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'source_id' => $val->source_id,
						'contactdate' => $val->contactdate,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->kp_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	public function actionAgreement(){
		
//		Agreement::deleteAll();
//		return self::ok(Agreement::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка Agreement');   //заглушка
			
	    $path = "http://95.183.8.45/index.php/getdata/agreement?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
        foreach($rez as $val){
			$query = Agreement::findOne($val->agreement_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('agreement', 
					[
						'id' => $val->agreement_id,
						'numberagree' => $val->numberagree,
						'agreedate' => $val->agreedate,
						'startdate' => $val->startdate ,
						'enddate' => $val->enddate,
						'clients_id' => $val->clients_id,
						'person' => $val->person,
						'manager_id' => $val->manager_id,
						'kp_id' => $val->kp_id,
						'state' => $val->state,
						'tax' => $val->tax,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'source_id' => $val->source_id,
						'debt' => $val->debt,
						'contactdate' => $val->contactdate,
						'short_person' => $val->short_person,
						'basement' => $val->basement,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'options' => $val->options,
						'application_id' => $val->application_id,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('agreement', 
					[
						'numberagree' => $val->numberagree,
						'agreedate' => $val->agreedate,
						'startdate' => $val->startdate ,
						'enddate' => $val->enddate,
						'clients_id' => $val->clients_id,
						'person' => $val->person,
						'manager_id' => $val->manager_id,
						'kp_id' => $val->kp_id,
						'state' => $val->state,
						'tax' => $val->tax,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'source_id' => $val->source_id,
						'debt' => $val->debt,
						'contactdate' => $val->contactdate,
						'short_person' => $val->short_person,
						'basement' => $val->basement,
						'declinematter_id' => $val->declinematter_id,
						'doc_on_hand' => $val->doc_on_hand,
						'is_lpr' => $val->is_lpr,
						'another_offer' => $val->another_offer,
						'doc_for_what' => $val->doc_for_what,
						'options' => $val->options,
						'application_id' => $val->application_id,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->agreement_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
    public function actionOrk(){
		
//		Ork::deleteAll();
//		return self::ok(Ork::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка Ork');   //заглушка 
		
	    $path = "http://95.183.8.45/index.php/getdata/ork?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Ork::findOne($val->ork_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('ork', 
					[
						'id' => $val->ork_id,
						'agreement_id' => $val->agreement_id,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate ,
						'state' => $val->state,
						'signagree' => $val->signagree,
						'signact' => $val->signact,
						'clients_id' => $val->clients_id,
						'manager_id' => $val->manager_id,
						'contactdate' => $val->contactdate,
						'source_id' => $val->source_id,
						'pay_date' => $val->pay_date,
						'provider_debt' => $val->provider_debt,
						'clientdoc_date' => $val->clientdoc_date,
						'close_date' => $val->close_date,
						'declinematter_id' => $val->declinematter_id,
						'comment' => $val->comment,
						'sert_num' => $val->sert_num,
						'get_date' => $val->get_date,
						'license_to' => $val->license_to,
						'act_num' => $val->act_num,
						'act_organ' => $val->act_organ,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('ork', 
					[
						'agreement_id' => $val->agreement_id,
						'startdate' => $val->startdate,
						'enddate' => $val->enddate ,
						'state' => $val->state,
						'signagree' => $val->signagree,
						'signact' => $val->signact,
						'clients_id' => $val->clients_id,
						'manager_id' => $val->manager_id,
						'contactdate' => $val->contactdate,
						'source_id' => $val->source_id,
						'pay_date' => $val->pay_date,
						'provider_debt' => $val->provider_debt,
						'clientdoc_date' => $val->clientdoc_date,
						'close_date' => $val->close_date,
						'declinematter_id' => $val->declinematter_id,
						'comment' => $val->comment,
						'sert_num' => $val->sert_num,
						'get_date' => $val->get_date,
						'license_to' => $val->license_to,
						'act_num' => $val->act_num,
						'act_organ' => $val->act_organ,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->ork_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	public function actionComments(){
		
//		Comments::deleteAll();
//		return self::ok(Comments::find()->andWhere(['account_id' => self::$user->account_id])->all());  
        return self::ok('заглушка Comments');   //заглушка   
		  
	    $path = "http://95.183.8.45/index.php/getdata/comments?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		
		
		foreach($rez as $val){
			$query = Comments::findOne($val->comment_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('comments', 
					[
						'id' => $val->comment_id,
						'message' => $val->message,
						'object_id' => $val->object_id,
						'type' => $val->type ,
						'user_id' => $val->user_id,
						'adddate' => $val->adddate,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('comments', 
					[
						'message' => $val->message,
						'object_id' => $val->object_id,
						'type' => $val->type ,
						'user_id' => $val->user_id,
						'adddate' => $val->adddate,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->comment_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	    public function actionItem(){
		
//		Item::deleteAll();
//		return self::ok(Item::find()->andWhere(['account_id' => self::$user->account_id])->all());     
		return self::ok('заглушка Item');   //заглушка
			
	    $path = "http://95.183.8.45/index.php/getdata/item?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE);
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
		foreach($rez as $val){
			$query = Item::findOne($val->item_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('item', 
					[
						'id' => $val->item_id,
						'document_id' => $val->document_id,
						'quantity' => $val->quantity,
						'cost' => $val->cost ,
						'discount' => $val->discount,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'clients_id' => $val->clients_id,
						'nameproduct' => $val->nameproduct,
						'typemarkmodel' => $val->typemarkmodel,
						'days' => $val->days,
						'timeline' => $val->timeline,
						'application_id' => $val->application_id,
						'pionhand' => $val->pionhand,
						'control' => $val->control,
						'provider_id' => $val->provider_id,
						'sert_num' => $val->sert_num,
						'get_date' => $val->get_date,
						'license_to' => $val->license_to,
						'act_organ' => $val->act_organ,
						'is_lead' => $val->is_lead,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				
				Yii::$app->db->createCommand()->update('item', 
					[
						'document_id' => $val->document_id,
						'quantity' => $val->quantity,
						'cost' => $val->cost ,
						'discount' => $val->discount,
						'total' => $val->total,
						'our_cost' => $val->our_cost,
						'clients_id' => $val->clients_id,
						'nameproduct' => $val->nameproduct,
						'typemarkmodel' => $val->typemarkmodel,
						'days' => $val->days,
						'timeline' => $val->timeline,
						'application_id' => $val->application_id,
						'pionhand' => $val->pionhand,
						'control' => $val->control,
						'provider_id' => $val->provider_id,
						'sert_num' => $val->sert_num,
						'get_date' => $val->get_date,
						'license_to' => $val->license_to,
						'act_organ' => $val->act_organ,
						'is_lead' => $val->is_lead,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					], 
					"id = $val->item_id "
					
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
    public function actionUser(){
		
//		User::deleteAll();
//		return self::ok(User::find()->andWhere(['account_id' => self::$user->account_id])->all());
     
		return self::ok('заглушка User');   //заглушка 
	    $path = "http://95.183.8.45/index.php/getdata/user?token=liyglijgljhksabce";
		
        $ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE); 
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
        foreach($rez as $val){
			$query = User::findOne($val->id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('user', 
					[
						'id' => $val->id,
						'username' => $val->username,
						'email' => $val->email,
						'status' => $val->status ,
						'password_hash' => $val->password_hash,
						'auth_key' => $val->auth_key,
						'updated_at' => $val->updated_at,
						'created_at' => $val->created_at,
						'account_id' => self::$user->account_id,
				    ]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('user', 
					[
						'username' => $val->username,
						'email' => $val->email,
						'status' => $val->status ,
						'password_hash' => $val->password_hash,
						'auth_key' => $val->auth_key,
						'updated_at' => $val->updated_at,
						'created_at' => $val->created_at,
						'account_id' => self::$user->account_id,
		            ], 
					"id = $val->id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	public function actionContacts(){
		
//		User::deleteAll();
//		return self::ok(Contacts::find()->andWhere(['account_id' => self::$user->account_id])->all());   
        return self::ok('заглушка Contacts');   //заглушка
  
		$path = "http://95.183.8.45/index.php/getdata/contacts?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE); 
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
	    foreach($rez as $val){
			$query = Contacts::findOne($val->contacts_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('contacts', 
					[
						'id' => $val->contacts_id,
						'clients_id' => $val->clients_id,
						'contacttype_id' => $val->contacttype_id,
						'name' => $val->name ,
						'phone' => $val->phone,
						'email' => $val->email,
						'skype' => $val->skype,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('contacts', 
					[
						'clients_id' => $val->clients_id,
						'contacttype_id' => $val->contacttype_id,
						'name' => $val->name ,
						'phone' => $val->phone,
						'email' => $val->email,
						'skype' => $val->skype,
						'comment' => $val->comment,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
		            ], 
					"id = $val->contacts_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
		public function actionPlanned(){
		
//		User::deleteAll();
//		return self::ok(Planned::find()->andWhere(['account_id' => self::$user->account_id])->all());   
//        return self::ok('заглушка Planned');   //заглушка
  
		$path = "http://95.183.8.45/index.php/getdata/planned?token=liyglijgljhksabce";
		$ku = curl_init();
	    curl_setopt($ku,CURLOPT_URL, $path);
	    curl_setopt($ku,CURLOPT_RETURNTRANSFER,TRUE); 
        $result = curl_exec($ku);
        curl_close($ku);
        $rez = json_decode($result);
		
	    foreach($rez as $val){
			$query = Planned::findOne($val->planned_id);
			if(!$query){
				Yii::$app->db->createCommand()->insert('planned', 
					[
						'id' => $val->planned_id,
						'planned_date' => $val->planned_date,
						'calls' => $val->calls,
						'leads' => $val->leads ,
						'applications' => $val->applications,
						'kps' => $val->kps,
						'agreements' => $val->agreements,
						'pays' => $val->pays,
						'marges' => $val->marges,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
					]
				)->execute();
			}
			if($query){
				Yii::$app->db->createCommand()->update('planned', 
					[
						'planned_date' => $val->planned_date,
						'calls' => $val->calls,
						'leads' => $val->leads ,
						'applications' => $val->applications,
						'kps' => $val->kps,
						'agreements' => $val->agreements,
						'pays' => $val->pays,
						'marges' => $val->marges,
						'account_id' => self::$user->account_id,
						'deleted_at' => null,
		            ], 
					"id = $val->planned_id "
				)->execute();
			}
		}   
		return self::ok('ok');    
	}
	
	private static function clearTb(){
		
		Provider::deleteAll();
		Document::deleteAll();
		Contacttype::deleteAll();
		Declinematter::deleteAll();
		Sphere::deleteAll();
		Source::deleteAll();
		City::deleteAll();
		
	}
	
	
	
	
}
