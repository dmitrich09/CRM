<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Lead;
use app\models\Application;
use app\models\Kp;
use app\models\Agreement;
use app\models\Ork;
use app\models\Calls;
use app\models\Contacts;
use app\models\Payment;
use app\models\Outcall;
use app\models\forms\FunnelReport;
use app\models\forms\Report;
use app\models\forms\Getdocreport;
use budyaga\users\models\User;
use yii\db\Query;
use app\models\forms\Funnel;
use app\models\Planned;
use app\models\support\Planreport;

class FunnelController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['funnel','converce','payrep','plan',"doingclients",'getdoc'],
                'rules' => [
                    [
                        'actions' => ['funnel','converce','payrep','plan',"doingclients",'getdoc'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

   public function actionFunnel(){
	   
	$error=Yii::$app->request->get('error');
		$funRep=new FunnelReport();
		$funRep->setDefault();
		if(Yii::$app->request->post('add-button')!=null){
			$funRep->load(Yii::$app->request->post());
		}

        $leads=Lead::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();
        $application= Application::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();

        $kps=Kp::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();
				
        $agreements= Agreement::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();
				
        $orks= Ork::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();
				
		$calls=	Calls::find()->where('callstart>=:activeFrom and callstart<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->
				andWhere('seconds::int > 0')->all();
				
		$pays=	Payment::find()->where('payment_date>=:activeFrom and payment_date<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()])->all();

		$agrpay= new Query();
		$agrpay->select('p.*, a.our_cost, a.total')
		->from('payment p, agreement a')
		->where('a.id = p.agreement_id')
		->andwhere('p.payment_date>=:activeFrom and p.payment_date<=:activeTo'
                ,['activeFrom'=>$funRep->getFromInMysql(),'activeTo'=>$funRep->getToInMysql()]);

		$fun= Funnel::getInstance($funRep,$leads, $application,$kps, $agreements, $orks,$calls,$pays,$agrpay->all());
		//return print_r($leads);
		return $this->render('funnel',['funnel'=>$fun,'error'=>$error,'funnelreport'=>$funRep]);
    }

	 public function actionConverce(){
		$report= Report::getInstance(Report::INSTANCE_CURRENT);
		if(Yii::$app->request->post('add-button')!=null){
			$report->load(Yii::$app->request->post());
		}

		$calls=	Calls::find()->where('callstart>=:activeFrom and callstart<=:activeTo and incoming=0'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()])
		->andWhere('seconds > 0')->asArray()->all();
		$application= Application::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()])->asArray()->all();
		$kps= Kp::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()])->asArray()->all();
		$agreements= Agreement::find()->where('startdate>=:activeFrom and startdate<=:activeTo'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()])->asArray()->all();
		$pays=	Payment::find()->where('payment_date>=:activeFrom and payment_date<=:activeTo'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()])->asArray()->all();


		$callsr=[];
		foreach($calls as $call){
			$date=date('m.Y',strtotime($call['callstart']));
			$manager=$call['user_id'];
			if(isset($callsr[$date]['count'])){
				$callsr[$date]['count']=$callsr[$date]['count']+1;
			}else{
				$callsr[$date]['count']=1;
			}
			if(isset($callsr[$manager][$date])){
				$callsr[$manager][$date]=$callsr[$manager][$date]+1;
			}else{
				$callsr[$manager][$date]=1;
			}

		}

		$kpsr=[];
		foreach($kps as $kp){
			$date=date('m.Y',strtotime($kp['startdate']));
			$manager=$kp['manager_id'];
			if(isset($kpsr[$date]['count'])){
				$kpsr[$date]['count']=$kpsr[$date]['count']+1;
			}else{
				$kpsr[$date]['count']=1;
			}
			if(isset($kpsr[$manager][$date])){
				$kpsr[$manager][$date]=$kpsr[$manager][$date]+1;
			}else{
				$kpsr[$manager][$date]=1;
			}
		}

		$appsr=[];
		foreach($application as $app){
			$date=date('m.Y',strtotime($app['startdate']));
			$manager=$app['manager_id'];
			if(isset($appsr[$date]['count'])){
				$appsr[$date]['count']=$appsr[$date]['count']+1;
			}else{
				$appsr[$date]['count']=1;
			}
			if(isset($appsr[$manager][$date])){
				$appsr[$manager][$date]=$appsr[$manager][$date]+1;
			}else{
				$appsr[$manager][$date]=1;
			}
		}

		$aggrsr=[];
		foreach($agreements as $ags){
			$date=date('m.Y',strtotime($ags['startdate']));
			$manager=$ags['manager_id'];
			if(isset($aggrsr[$date]['count'])){
				$aggrsr[$date]['count']=$aggrsr[$date]['count']+1;
			}else{
				$aggrsr[$date]['count']=1;
			}
			if(isset($aggrsr[$manager][$date])){
				$aggrsr[$manager][$date]=$aggrsr[$manager][$date]+1;
			}else{
				$aggrsr[$manager][$date]=1;
			}
		}

		$paysr=[];
		foreach($pays as $pay){
			$date=date('m.Y',strtotime($pay['payment_date']));
			$manager=$pay['user_id'];
			if(isset($paysr[$date]['count'])){
				$paysr[$date]['count']=$paysr[$date]['count']+1;
			}else{
				$paysr[$date]['count']=1;
			}
			if(isset($paysr[$manager][$date])){
				$paysr[$manager][$date]=$paysr[$manager][$date]+1;
			}else{
				$paysr[$manager][$date]=1;
			}
		}

		$start=date('m.Y',strtotime($report->getFromInMysql()));
		$end=date('m.Y',strtotime($report->getToInMysql()));
		$converceRep['dates'][$start]='';
		while ($start!=$end){
			$arr=preg_split ('(\.)',$start);
			$m=$arr[0];
			$y=$arr[1];
			if($m==12){
				$m='01';
				$y=$y+1;
			}else{
				$m=$m+1;
				if($m<10&&mb_strlen($m)<2){
					$m='0'.$m;
				}
			}
			$start=$m.'.'.$y;
			$converceRep['dates'][$start]='';
		}
		$converceRep['dates'][$end]='';

		$converceRep['calls']=$callsr;
		$converceRep['kps']=$kpsr;
		$converceRep['apps']=$appsr;
		$converceRep['agreements']=$aggrsr;
		$converceRep['pays']=$paysr;
//		return $this->render('converce',['report'=>$report,'conrep'=>$converceRep]);
         
		return self::ok(['report'=>$report]);
	 }

	public function actionPayrep(){
		$report= Report::getInstance(Report::INSTANCE_CURRENT);
		if(Yii::$app->request->post('add-button')!=null){
			$report->load(Yii::$app->request->post());
		}
		$qr = (new Query())
					->select('p.* , c.name,a.total,a.numberagree
					,a.our_cost, a.debt ,a.manager_id,ork.provider_debt, maxid');
					$qr->from('payment p, clients c , agreement a')
					->leftJoin('ork', 'ork.agreement_id = a.agreement_id')

					->where('p.agreement_id=a.agreement_id')
					->leftJoin('(SELECT MAX(provider_id) AS maxid, kp_id
                                  FROM item ,kp
								  where item.application_id= kp.application_id
								  GROUP BY kp_id) AS T1','a.kp_id = T1.kp_id')
					->andWhere('p.clients_id=c.clients_id')
					->andWhere('payment_date>=:activeFrom and payment_date<=:activeTo'
                ,['activeFrom'=>$report->getFromInMysql(),'activeTo'=>$report->getToInMysql()]);
		//return $qr ->createCommand()->getRawSql();

//		return $this->render('payrep',['report'=>$report,'query'=>$qr ]);
        return self::ok(['report'=>$report]);
	}

	public function actionGetdoc(){
		$report= Getdocreport::getInstance(Getdocreport::INSTANCE_CURRENT);
		if(Yii::$app->request->post('add-button')!=null){
			$report->load(Yii::$app->request->post());
		}


		$qr = (new Query());
		$qr ->select('i.total,i.our_cost,i.nameproduct as prod_name, i.sert_num,i.document_id,
					 i.get_date,i.license_to,i.act_organ,o.act_num, o.ork_id,o.clients_id,o.manager_id,o.signact,o.signagree , c.name,a.numberagree ,a.manager_id as a_man');
		$qr->from('ork o,clients c, agreement a,item i');

		$qr ->where('o.agreement_id=a.agreement_id')
					->andWhere('o.clients_id=c.clients_id')
					->andWhere('i.application_id=a.application_id')
					->andWhere(['o.state'=>Ork::STATUS_ACCEPT]);

		if($report->from!=null)	{
			$qr ->andWhere('i.get_date>=:activeFrom'
                ,['activeFrom'=>$report->getFromInMysql()]);
		}
		if($report->to!=null)	{
			$qr ->andWhere('i.get_date<=:activeTo'
                ,['activeTo'=>$report->getToInMysql()]);
		}
		if($report->manager_id!=null)	{
			$qr ->andWhere('a.manager_id=:man or o.manager_id=:man'
                ,['man'=>$report->manager_id]);
		}
		if($report->name!=null)	{
			$qr ->andWhere('c.name like :cm or c.full_name like :cm'
                ,['cm'=>'%'.$report->name.'%']);
		}
		if($report->agreenum!=null)	{
			$qr ->andWhere('a.numberagree like :cm '
                ,['cm'=>'%'.$report->agreenum.'%']);
		}
		if($report->product!=null)	{
			$qr ->andWhere('i.nameproduct like :cm '
                ,['cm'=>'%'.$report->product.'%']);
		}
		if($report->actnum!=null)	{
			$qr ->andWhere('o.act_num like :cm '
                ,['cm'=>'%'.$report->actnum.'%']);
		}
		if($report->organ!=null)	{
			$qr ->andWhere('i.act_organ like :cm '
                ,['cm'=>'%'.$report->organ.'%']);
		}
		if($report->sertnum!=null)	{
			$qr ->andWhere('i.sert_num like :cm '
                ,['cm'=>'%'.$report->sertnum.'%']);
		}

		//return $qr->createCommand()->getRawSql();
//		return $this->render('getdoc',['report'=>$report,'query'=>$qr ]);

        return self::ok(['report'=>$report]);
	}

	public function actionPlan(){
		$report= Report::getInstance(Report::INSTANCE_CURRENT);
		if(Yii::$app->request->post('add-button')!=null){
			$report->load(Yii::$app->request->post());
		}

		$planned = Planned::find()->where('planned_date >= :from and planned_date <= :to',
				['from' => date('Y.m.d',strtotime($report->from)), 'to' => date('Y.m.d',strtotime($report->to))])->all();

		$rep=[];

		foreach ($planned as $key){
			$rep[date('m.Y',strtotime($key->planned_date))] = $key;
		};



//		return $this->render('plan',['report'=>$report,'data'=>$rep,'planned'=>$planned]);
         return self::ok(['report'=>$report,'data'=>$rep,'planned'=>$planned]);
	}


	public function actionDoingclients(){
		$report= Report::getInstance(Report::INSTANCE_CURRENT);
		if(Yii::$app->request->post('add-button')!=null){
			$report->load(Yii::$app->request->post());
		}else{
			if(Yii::$app->session->get('doingreport')!=null){
				$report=Yii::$app->session->get('doingreport');
			}
		}
		Yii::$app->session->set('doingreport',$report);


		$connection = Yii::$app->getDb();



		$command ="select cl.*, ";


		$command.=' (select max(o.contactdate) call_date from outcall o
				where o.clients_id=cl.clients_id
				and o.status= :OUC_NEW) call_date
				,(select max(l.contactdate) lead_date from lead l
				where l.clients_id=cl.clients_id
				and l.state= :LEAD) lead_date

				,(select max(a.contactdate) application_date from application a
				where a.clients_id=cl.clients_id
				and a.state= :APP) application_date

				,(select max(kp.contactdate) kp_date from kp
				where kp.clients_id=cl.clients_id
				and kp.state= :KP) kp_date

				,(select max(agr.contactdate) areement_date from agreement agr
				where agr.clients_id=cl.clients_id
				and agr.state= :AGREEMENT) agreement_date

				,(select max(ork.contactdate) ork_date from ork
				where ork.clients_id=cl.clients_id
				and ork.state= :ORK) ork_date ';

		$command.=' from clients cl ';

		if($report->user_id!=null){

			$command .=' where (exists (select * from outcall o
				where o.clients_id = cl.clients_id
				and o.status= :OUC_NEW and o.user_id = :MAN
				and o.contactdate>=:from and o.contactdate<:to) or ';

			$command .=' exists (select 1 from lead l
				where l.clients_id = cl.clients_id
				and l.state= :LEAD and l.manager =:MAN
				and l.contactdate>=:from and l.contactdate<:to) or ';

			$command .=' exists (select 1 from application a
				where a.clients_id=cl.clients_id
				and a.state= :APP and a.manager_id =:MAN
				and a.contactdate>=:from and a.contactdate<:to) or ';

			$command .=' exists (select 1 from  kp
				where kp.clients_id=cl.clients_id
				and kp.state= :KP and kp.manager_id =:MAN
				and kp.contactdate>=:from and kp.contactdate<:to) or ';

			$command .=' exists (select 1 from agreement agr
				where agr.clients_id=cl.clients_id
				and agr.state= :AGREEMENT and agr.manager_id =:MAN
				and agr.contactdate>=:from and agr.contactdate<:to) or ';

			$command .=' exists (select 1 from ork
				where ork.clients_id=cl.clients_id
				and ork.state= :ORK and ork.manager_id =:MAN
				and ork.contactdate>=:from and ork.contactdate<:to)
				) ';


		}else{

			$command .='where (exists (select 1 from outcall o
				where o.clients_id = cl.clients_id
				and o.status= :OUC_NEW
				and o.contactdate>=:from and o.contactdate<:to) or
				exists (select 1 from lead l
				where l.clients_id = cl.clients_id
				and l.state= :LEAD
				and l.contactdate>=:from and l.contactdate<:to) or
				exists (select 1 from application a
				where a.clients_id=cl.clients_id
				and a.state= :APP
				and a.contactdate>=:from and a.contactdate<:to) or
				exists (select 1 from  kp
				where kp.clients_id=cl.clients_id
				and kp.state= :KP
				and kp.contactdate>=:from and kp.contactdate<:to) or
				exists (select 1 from agreement agr
				where agr.clients_id=cl.clients_id
				and agr.state= :AGREEMENT
				and agr.contactdate>=:from and agr.contactdate<:to) or
				exists (select 1 from ork
				where ork.clients_id=cl.clients_id
				and ork.state= :ORK
				and ork.contactdate>=:from and ork.contactdate<:to)
				)';

		}

		if($report->type_id!=null){
			if(in_array(Report::TYPE_CALL,$report->type_id)){
				$command .=' and exists (select * from outcall o
				where o.clients_id = cl.clients_id
				and o.contactdate>=:from and o.contactdate<:to) ';
			}
			if(in_array(Report::TYPE_LEAD,$report->type_id)){
				$command .=' and exists (select 1 from lead l
				where l.clients_id = cl.clients_id
				and l.state= :LEAD
				and l.contactdate>=:from and l.contactdate<:to) ';
			}
			if(in_array(Report::TYPE_APPLICATION,$report->type_id)){
				$command .=' and exists (select 1 from application a
				where a.clients_id=cl.clients_id
				and a.state= :APP
				and a.contactdate>=:from and a.contactdate<:to)';
			}
			if(in_array(Report::TYPE_KP,$report->type_id)){
				$command .=' and exists (select 1 from  kp
				where kp.clients_id=cl.clients_id
				and kp.state= :KP
				and kp.contactdate>=:from and kp.contactdate<:to) ';
			}
			if(in_array(Report::TYPE_AGREEMENT,$report->type_id)){
				$command .=' and exists (select 1 from agreement agr
				where agr.clients_id=cl.clients_id
				and agr.state= :AGREEMENT
				and agr.contactdate>=:from and agr.contactdate<:to)  ';
			}
			if(in_array(Report::TYPE_ORK,$report->type_id)){
				$command .=' and exists (select 1 from ork
				where ork.clients_id=cl.clients_id
				and ork.state= :ORK
				and ork.contactdate>=:from and ork.contactdate<:to)  ';
			}



		}

		$params=['OUC_NEW'=>Outcall::STATUS_NEW,
				 'LEAD'=>[Lead::STATUS_NEW,Lead::STATUS_NO_CALL,Lead::STATUS_NO_LPR,Lead::STATUS_LPR],
				 'APP'=>null,
				 'KP'=>[Kp::STATUS_NEW,Kp::STATUS_SEND,Kp::STATUS_PRICE,],
				 'AGREEMENT'=>Agreement::STATUS_NEW,
				 'ORK'=>Ork::getActiveStatuses(),
				 'MAN'=>$report->getUsers(),
				 'from'=>$report->getFromInMysql(),
				 'to'=>$report->getToInMysql(),
				 ];
		//return print_r($params);

		$command = $connection->createCommand($command,$params);

		$query = $command->queryAll();

       return $this->render('doingclients',['report'=>$report,'query'=>$query]);
	     return self::ok(['report'=>$report,]);
	}


	private static function getDateArray($format,$start,$end){
		$dateArr=[];

		return $dateArr;
	}
}
