<?php
/*************************************************************************************************
 * Copyright 2012-2014 JPL TSolucio, S.L.  --  This file is a part of coreBOSCP.
* You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
* Vizsage Public License (the "License"). You may not use this file except in compliance with the
* License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
* and share improvements. However, for proper details please read the full License, available at
* http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
* the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
* applicable law or agreed to in writing, any software distributed under the License is distributed
* on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and limitations under the
* License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
*************************************************************************************************/

function vtws_getcalendarvisits($dealer){
	global $log,$adb,$default_language;
	$log->debug("Entering function vtws_getcalendarvisits");
        
        $t=explode('x',$dealer);
        $id=$t[1];
        $getvisit="Select * ,vtiger_crmentity.smownerid as smowner
            from vtiger_task
            join vtiger_taskcf on vtiger_task.taskid=vtiger_taskcf.taskid            
            join vtiger_account on vtiger_account.accountid=vtiger_task.linktoentity
            join vtiger_cases on vtiger_cases.casesid=vtiger_task.casereltask
            left join vtiger_contactdetails on vtiger_contactdetails.contactid=vtiger_task.dealercontactask
            join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_task.taskid 
            join vtiger_crmentity c2 on c2.crmid=vtiger_cases.casesid
            where vtiger_crmentity.deleted=0 and c2.deleted=0 and vtiger_task.linktoentity=? 
            order by vtiger_crmentity.createdtime Desc";
        $result=$adb->pquery($getvisit,array($id));
        $v=array();
        for($i=0;$i<$adb->num_rows($result);$i++){
            $visitname=$adb->query_result($result,$i,'taskname');
            $firstname=$adb->query_result($result,$i,'firstname');
            $lastname=$adb->query_result($result,$i,'lastname');
            $nome=$adb->query_result($result,$i,'nome');
            $casesid=$adb->query_result($result,$i,'casesid');
            $cases_no=$adb->query_result($result,$i,'cases_no');
            $casesname=$adb->query_result($result,$i,'casesname');
            $description=$adb->query_result($result,$i,'taskdescription');
            $visitid=$adb->query_result($result,$i,'taskid');
            $taskresult=$adb->query_result($result,$i,'taskresult');
            $datedipre=$adb->query_result($result,$i,'date_start');
            $y=date('Y',strtotime($datedipre));
            $temp_month=date('m',strtotime('-1 month ',strtotime($datedipre)));
            $m=($temp_month==12 ? 0 : $temp_month);
            $d=date('d',strtotime($datedipre));
            $time_start=$adb->query_result($result,$i,'time_start');
            $t=explode(':',$time_start);
            $h_start=$t[0];
            $m_start=$t[1];
            $date_end=$adb->query_result($result,$i,'date_end');
            $y_end=date('Y',strtotime($date_end));
            $temp_month=date('m',strtotime('-1 month ',strtotime($date_end)));
            $m_end=($temp_month==12 ? 0 : $temp_month);
            $d_end=date('d',strtotime($date_end));
            $time_end=$adb->query_result($result,$i,'time_end');
            $t2=explode(':',$time_end);
            $h_end=$t2[0];
            $min_end=$t2[1];
            
            $smowner=$adb->query_result($result,$i,'smowner');
            $getusername=$adb->pquery("select first_name,last_name from vtiger_users where id=?",array($smowner));
            $userfirstname=$adb->query_result($getusername,0,0);
            $userlastname=$adb->query_result($getusername,0,1);
            $showthisname=$userfirstname.' '.$userlastname;
            
            $v[]=array('title'=>$visitname,'start'=>array($y,$m,$d,$h_start,$m_start),
                'end'=>array($y_end,$m_end,$d_end,$h_end,$min_end),
                'id'=>$visitid,'allDay'=>false,'url'=>'','casesid'=>$casesid,
                'cases_no'=>$cases_no,'casesname'=>$casesname,'description'=>myfunction($description),
                'nome'=>$showthisname,'taskresult'=>$taskresult,'contact'=>$firstname.' '.$lastname);
        }
	return $v;
}
function myfunction($v)
{
    $t=html_entity_decode($v,ENT_QUOTES);
    if(empty($t))
    $t=utf8_encode($v);
    return $t;
}

function vtws_updatecalendarvisits($id,$newdate,$newdate_end){
	global $log,$adb,$default_language;
	$log->debug("Entering function vtws_vitalparameters");

        $pos=strpos($newdate,'T');
        $newd=  substr($newdate, 0,$pos);
        $pos=strpos($newdate_end,'T');
        $newd_end=  substr($newdate_end, 0,$pos);
        $getvisit="Update vtiger_task"
                . " set date_start=?,"
                . " time_start=?,"
                . " date_end=?,"
                . " time_end=?"
                . " where taskid=?";
        $result=$adb->pquery($getvisit,array($newd,'12:00',$newd_end,'12:00',$id));
        
}

?>