<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\forms;
use yii\bootstrap\ActiveForm;
use app\models\Lead;
use yii\helpers\ArrayHelper;
use app\models\Source;
use app\models\User;
use app\models\Application;
use \DateTime;
use \DateInterval;
/**
 * Description of Funnel
 *
 * @author кот
 */
class Funnel {
    //put your code here
    private $leads=null;
    private $applications=null;
    private $kps=null;
    private $agreements=null;
    private $orks=null;
	private $calls=null;
	private $pays=null;
    private $date=[];
	private $rep;
	private $agrpay;
     
     
    private function __construct() {
       
    }
    
    public static function getInstance($rep,$leads,$application,$kp,$agreement,$ork,$calls,$pays,$agrpay){
        $funnel = new Funnel();
        $funnel->leads=$leads;
        $funnel->applications=$application; 
        $funnel->kps=$kp; 
        $funnel->agreements=$agreement; 
        $funnel->orks=$ork; 
		$funnel->calls=$calls; 
		$funnel->pays=$pays; 
		$funnel->rep=$rep;
		$funnel->agrpay=$agrpay;
        $funnel->intense();
        return $funnel;
    }
    
    public function getAllData(){
        return $this->date; 
    }
    
    
    private function intense(){
        if($this->leads!=null){
            foreach($this->leads as $lead){
                $leaddate=date('Y-m-d',strtotime($lead->startdate));
                $manId=$lead->manager;
                $sourceId=$lead->source_id;
                if(isset($this->date['leads'][$leaddate][$manId][$sourceId]['count'])){
                    $this->date['leads'][$leaddate][$manId][$sourceId]['count']+=1;
                }else{
                    $this->date['leads'][$leaddate][$manId][$sourceId]['count']=1;
                }
            }
            foreach($this->applications as $application){
                $appdate=date('Y-m-d',strtotime($application->startdate));
                $manId=$application->manager_id;
                 $sourceId=$application->source_id;
                if(isset($this->date['applications'][$appdate][$manId][$sourceId]['count'])){
                    $this->date['applications'][$appdate][$manId][$sourceId]['count']+=1;
                }else{
                    $this->date['applications'][$appdate][$manId][$sourceId]['count']=1;
                }
            }
            foreach($this->kps as $kp){
                $kpdate=date('Y-m-d',strtotime($kp->startdate));
                $manId=$kp->manager_id;
                 $sourceId=$kp->source_id;
                if(isset($this->date['kp'][$kpdate][$manId][$sourceId]['count'])){
                    $this->date['kp'][$kpdate][$manId][$sourceId]['count']+=1;
                }else{
                    $this->date['kp'][$kpdate][$manId][$sourceId]['count']=1;
                }
            }
            foreach($this->agreements as $agreement){
                $agrdate=date('Y-m-d',strtotime($agreement->startdate));
                $manId=$agreement->manager_id;
                 $sourceId=$agreement->source_id;
                if(isset($this->date['agreement'][$agrdate][$manId][$sourceId]['count'])){
                    $this->date['agreement'][$agrdate][$manId][$sourceId]['count']+=+1;
                }else{
                    $this->date['agreement'][$agrdate][$manId][$sourceId]['count']=1;
                }
            }
            
            foreach($this->agrpay as $pay){
                
                    $paydate=date('Y-m-d',strtotime($pay['payment_date']));
                    $manId=$pay['user_id'];
                    $sourceId=$pay['source_id'];
                    if(isset($this->date['pay'][$paydate][$manId][$sourceId]['count'])){
                        $this->date['pay'][$paydate][$manId][$sourceId]['count']+=1;
                    }else{
                        $this->date['pay'][$paydate][$manId][$sourceId]['count']=1;
                    }
                    if(isset($this->date['pay'][$paydate][$manId][$sourceId]['total'])){
                        $this->date['pay'][$paydate][$manId][$sourceId]['total']+=$pay['amount'];
                    }else{
                         $this->date['pay'][$paydate][$manId][$sourceId]['total']=$pay['amount'];
                    }
					$marge =round($pay['amount']*(($pay['total']-$pay['our_cost'])/$pay['total']),2);
					if(isset($this->date['margin'][$paydate][$manId][$sourceId]['total'])){
                        $this->date['margin'][$paydate][$manId][$sourceId]['amount']+=$marge;
                    }else{
                         $this->date['margin'][$paydate][$manId][$sourceId]['amount']=$marge;
                    }
					
            }
            
            foreach($this->orks as $ork){
                $orkdate=date('Y-m-d',strtotime($ork->startdate));
                $manId=$ork->manager_id;
                 $sourceId=$ork->source_id;
                if(isset($this->date['ork'][$orkdate][$manId][$sourceId])){
                    $this->date['ork'][$orkdate][$manId][$sourceId]['count']+=1;
                }else{
                    $this->date['ork'][$orkdate][$manId][$sourceId]['count']=1;
                }
            }
			
			foreach($this->calls as $call){
                $calldate=date('Y-m-d',strtotime($call->callstart));
                $manId=$call->user_id;
                if(isset($this->date['call'][$calldate][$manId])){
                    $this->date['call'][$calldate][$manId]['count']+=1;
                }else{
                    $this->date['call'][$calldate][$manId]['count']=1;
                }
            }
			
			foreach($this->pays as $pay){
                $paydate=date('Y-m-d',strtotime($pay->payment_date));
                $manId=$pay->user_id;
				$sourceId=$pay->source_id;
                if(isset($this->date['pays'][$paydate][$manId][$sourceId])){
                    $this->date['pays'][$paydate][$manId][$sourceId]['amount']+=$pay->amount;
                }else{
                    $this->date['pays'][$paydate][$manId][$sourceId]['amount']=$pay->amount;
                }
            }
        }
    }
    
    public function getReport(){ 
        $reparr=[];
        $managerList = ArrayHelper::map(User::find()->orderBy('username')->all()
									,'id'
									,'username');
        $sourceList = ArrayHelper::map(Source::find()->orderBy('name')->all()
									,'source_id'
									,'name');
        
        $datearr=$this->getDateArr();
		
        $reparr['days'][0]='Показатель';
		$reparr['call'][0]='Звонки';
        $reparr['lead'][0]='ЭК';
        $reparr['app'][0]='Заявки';
        $reparr['kp'][0]='Коммерческие';
        $reparr['agreement'][0]='Договоры';
        $reparr['total'][0]='Выручка';
		$reparr['margin'][0]='Маржа';

        foreach($datearr as $key=>$i){
          $reparr['days'][$key+1]=$i;
		  $reparr['call'][$i]=0;
          $reparr['lead'][$i]=0;
          $reparr['app'][$i]=0;
          $reparr['kp'][$i]=0;
          $reparr['agreement'][$i]=0;
          $reparr['total'][$i]=0;
		  $reparr['margin'][$i]=0;
		  
		  $day=$i;
          foreach($managerList as $key=>$value){
			  $reparr['managerlead'][$key]['all'][0]='ЭК';
			  $reparr['managerlead'][$key]['all'][$i]=0;
			  $reparr['managerapp'][$key]['all'][0]='Заявки';
			  $reparr['managerapp'][$key]['all'][$i]=0;
			  $reparr['managerkp'][$key]['all'][0]='Коммерческие';
			  $reparr['managerkp'][$key]['all'][$i]=0;
			  $reparr['manageragreement'][$key]['all'][0]='Договоры';
			  $reparr['manageragreement'][$key]['all'][$i]=0;
			  $reparr['managertotal'][$key]['all'][0]='Выручка';
			  $reparr['managertotal'][$key]['all'][$i]=0;
			  $reparr['managermargin'][$key]['all'][0]='Маржа';
			  $reparr['managermargin'][$key]['all'][$i]=0;
			  
              foreach ($sourceList as $sourcekey=>$val2){
				  //leads
                  $reparr['sourcelead'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['managerlead'][$key][$sourcekey][0]=$sourceList[$sourcekey];
                  $reparr['managerlead'][$key][$sourcekey][$i]=0;
                  $valuelead=$this->getValFromData('leads',$day,$key,$sourcekey,'count');
                  $reparr['lead'][$i]+=$valuelead;
                  if(isset($reparr['sourcelead'][$sourcekey][$i])){
					$reparr['sourcelead'][$sourcekey][$i]+=$valuelead;
				  }else{
					$reparr['sourcelead'][$sourcekey][$i]=$valuelead;  
				  }
				  $reparr['managerlead'][$key]['all'][$i]+=$valuelead;
                  $reparr['managerlead'][$key][$sourcekey][$i]+=$valuelead;
				  
                  //applications
                  $reparr['sourceapp'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['managerapp'][$key][$sourcekey][0]=$sourceList[$sourcekey];
                  $reparr['managerapp'][$key][$sourcekey][$i]=0;
                  $valueapp=$this->getValFromData('applications',$day,$key,$sourcekey,'count');
                  $reparr['app'][$i]+=$valueapp;
                  if(isset($reparr['sourceapp'][$sourcekey][$i])){
					$reparr['sourceapp'][$sourcekey][$i]+=$valueapp;
				  }else{
					$reparr['sourceapp'][$sourcekey][$i]=$valueapp;  
				  }
				  $reparr['managerapp'][$key]['all'][$i]+=$valueapp;
                  $reparr['managerapp'][$key][$sourcekey][$i]+=$valueapp;
                  
				  //kp
                  $reparr['sourcekp'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['managerkp'][$key][$sourcekey][0]=$sourceList[$sourcekey];
                  $reparr['managerkp'][$key][$sourcekey][$i]=0;
                  $valuekp=$this->getValFromData('kp',$day,$key,$sourcekey,'count');
                  $reparr['kp'][$i]+=$valuekp;
				  if(isset($reparr['sourcekp'][$sourcekey][$i])){
					$reparr['sourcekp'][$sourcekey][$i]+=$valuekp;
				  }else{
					$reparr['sourcekp'][$sourcekey][$i]=$valuekp;  
				  }
				  $reparr['managerkp'][$key]['all'][$i]+=$valuekp;
                  $reparr['managerkp'][$key][$sourcekey][$i]+=$valuekp;
                   
                  //agreements
                  $reparr['sourceagreement'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['manageragreement'][$key][$sourcekey][0]=$sourceList[$sourcekey]; 
                  $reparr['manageragreement'][$key][$sourcekey][$i]=0;
                  $valueagr=$this->getValFromData('agreement',$day,$key,$sourcekey,'count');
                  $reparr['agreement'][$i]+=$valueagr;
				  if(isset($reparr['sourceagreement'][$sourcekey][$i])){
					$reparr['sourceagreement'][$sourcekey][$i]+=$valueagr;
				  }else{
					$reparr['sourceagreement'][$sourcekey][$i]=$valueagr;  
				  }
				  $reparr['manageragreement'][$key]['all'][$i]+=$valueagr;
                  $reparr['manageragreement'][$key][$sourcekey][$i]+=$valueagr;	


					//money
                  $reparr['sourcetotal'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['managertotal'][$key][$sourcekey][0]=$sourceList[$sourcekey]; 
                  $reparr['managertotal'][$key][$sourcekey][$i]=0;
				  
                  $valuepay=$this->getPay($day,$key,$sourcekey);
                  $reparr['total'][$i]+=$valuepay;
				  if(isset($reparr['sourcetotal'][$sourcekey][$i])){
					$reparr['sourcetotal'][$sourcekey][$i]+=$valuepay;
				  }else{
					$reparr['sourcetotal'][$sourcekey][$i]=$valuepay;  
				  }
                  
				  $reparr['managertotal'][$key]['all'][$i]+=$valuepay;
                  $reparr['managertotal'][$key][$sourcekey][$i]+=$valuepay;						  
                  
				  
				  //margin
                  $reparr['sourcemargin'][$sourcekey][0]=$sourceList[$sourcekey];
				  $reparr['managermargin'][$key][$sourcekey][0]=$sourceList[$sourcekey]; 
                  $reparr['managermargin'][$key][$sourcekey][$i]=0;
                  $valueagr=$this->getValFromData('margin',$day,$key,$sourcekey,'amount');
                  $reparr['margin'][$i]+=$valueagr;
				  if(isset($reparr['sourcemargin'][$sourcekey][$i])){
					$reparr['sourcemargin'][$sourcekey][$i]+=$valueagr;
				  }else{
					$reparr['sourcemargin'][$sourcekey][$i]=$valueagr;  
				  }
				  $reparr['managermargin'][$key]['all'][$i]+=$valueagr;
                  $reparr['managermargin'][$key][$sourcekey][$i]+=$valueagr;	
				  
              }
			  
          } 
          
        }
        
        
        return $reparr;
    }
    

	private function getDateArr(){
		$arr;
		$date = new DateTime($this->rep->from);
		if($this->rep->group==1){
			$arr[]=$date->format('m.Y');
			while($date->format('m.Y')!=date('m.Y',strtotime($this->rep->to))){
				$date->add(new DateInterval('P1M'));
				$arr[]=$date->format('m.Y');
			}
		}else{
			$arr[]=$date->format('d.m.Y');
			while($date->format('d.m.Y')!=date('d.m.Y',strtotime($this->rep->to))){
				$date->add(new DateInterval('P1D'));
				$arr[]=$date->format('d.m.Y');
			}
		}
		return $arr;
	}
	
    
    private function getValFromData($type,$date,$userId,$sourceId,$name){
		if($this->rep->group==1){
			$res=0;
			$dat = new DateTime('01.'.$date);
			while($dat->format('m.Y')==$date){
				
				$res+=(isset($this->date[$type][$dat->format('Y-m-d')][$userId][$sourceId][$name])?
                        $this->date[$type][$dat->format('Y-m-d')][$userId][$sourceId][$name]:0);
				$dat->add(new DateInterval('P1D'));
			}
			return $res;
		}else{
			 return (isset($this->date[$type][date('Y-m-d',strtotime($date))][$userId][$sourceId][$name])?
                        $this->date[$type][date('Y-m-d',strtotime($date))][$userId][$sourceId][$name]:0);
		}

    }
	
	private function getPay($date,$userId,$sourceId){
		if($this->rep->group==1){
			$res=0;
			$dat = new DateTime('01.'.$date);
			while($dat->format('m.Y')==$date){
				$res+=(isset($this->date['pays'][$dat->format('Y-m-d')][$userId][$sourceId]['amount'])?
                       $this->date['pays'][$dat->format('Y-m-d')][$userId][$sourceId]['amount']:0);
				$dat->add(new DateInterval('P1D'));
			}
			return $res;
		}else{
			return (isset($this->date['pays'][date('Y-m-d',strtotime($date))][$userId][$sourceId]['amount'])?
                    $this->date['pays'][date('Y-m-d',strtotime($date))][$userId][$sourceId]['amount']:0);
		}
    }
    
}
