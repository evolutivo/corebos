{php}
global $current_user;
require_once('include/utils/UserInfoUtil.php');
$current_profiles=getUserProfile($current_user->id);
$roleid=$current_user->roleid;
$this->assign('CurrRole',$roleid);
$this->assign('CurrProfiles',$current_profiles);
{/php}
<script>

{foreach key=header item=data from=$BLOCKS}
{foreach key=label item=subdata from=$data}
{foreach key=mainlabel item=maindata from=$subdata}
                {assign var="uitype" value="$maindata[0][0]"}
		{assign var="fldname" value="$maindata[2][0]"}
		{assign var="fldvalue" value="$maindata[3][0]"}
		
        {if $uitype eq '15' || $uitype eq '16' || $uitype eq '31' || $uitype eq '32'} 
                             
            angular.module('demoApp').filter('{$fldname}_filter', function() {ldelim}
              return function({$fldname}_values {$MAP_RESPONSIBILE_FIELDS2}) {ldelim}
                var filterEvent = [];
                var count_false_condition=0;
                  for (var i = 0;i < {$fldname}_values.length; i++){ldelim}   
                  {if isset($MAP_PCKLIST_TARGET)} {* control to avoid errors for  modules not having BR*}
                  {if $fldname|in_array:$MAP_PCKLIST_TARGET}
                      {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
                          {ldelim}
                          var condition='';
                          
                           {foreach key=map_key item=map_item from=$map.respfield}
                            {ldelim}
                                
                                if({$map_key} !=0) condition +=' && ';
                                condition +=' {$map_item} == "{$map.respvalue[$map_key]}"  ';
                                {rdelim}
                           {/foreach}
                                if( eval(condition))
                                    {ldelim}
                                      {foreach key=map_key item=map_item from=$map.target_picklist_values}
                                         {ldelim}
                                             if ({$fldname}_values[i]['text']=='{$map_item}' )
                                                {ldelim}
                                                  filterEvent.push({$fldname}_values[i]);
                                                {rdelim}
                                         {rdelim}
                                      {/foreach}
                                    {rdelim}
                                  else
                                    {ldelim}
                                            //count_false_condition++;
                                            //if(count_false_condition=={$MAP_FIELD_DEPENDENCY|@count})
                                            //{ldelim}
                                            //    filterEvent.push({$fldname}_values[i]);  
                                            //{rdelim}
                                    {rdelim}
                          {rdelim}  
                        {/foreach}
                      
                  {else}
                  filterEvent.push({$fldname}_values[i]);
                  {/if}
              {else}
                  filterEvent.push({$fldname}_values[i]);
              {/if}
              {rdelim}
                  //filterEvent.$stateful = true;
                  return filterEvent;
              {rdelim};
            {rdelim}); 
      
             {/if}
     {/foreach}
{/foreach} 
{/foreach} 
angular.module('demoApp').controller('editViewng', function($scope,$http,$filter,$sce) {ldelim}

{foreach key=header item=data from=$BLOCKS}
{foreach key=label item=subdata from=$data}
{foreach key=mainlabel item=maindata from=$subdata}
                {assign var="uitype" value="$maindata[0][0]"}
		{assign var="fldlabel" value="$maindata[1][0]"}
		{assign var="fldname" value="$maindata[2][0]"}
		{assign var="fldvalue" value="$maindata[3][0]"}
             {if $uitype eq '56' && $fldvalue eq 'si'}
                 $scope.{$fldname}=true;
             {elseif $uitype eq '56' && $fldvalue eq 'no'}
                 $scope.{$fldname}=false;
             {elseif $uitype eq '15' || $uitype eq '16' || $uitype eq '31' || $uitype eq '32'} 
                 
                 var t= '{$fldname}'+'_values';
                 $scope[t] = [];
                 {foreach item=arr from=$fldvalue}
                            {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                                $scope[t].push({ldelim}"value":"{$arr[1]}", "text":"{$arr[0]}", "v":"{$arr[2]}"{rdelim});
                            {elseif $arr[2] eq 'selected'}
                                $scope.{$fldname}="{$arr[1]}";
                                $scope[t].push({ldelim}"value":"{$arr[1]}", "text":"{$arr[0]}", "v":"{$arr[2]}"{rdelim});
                            {else}
                                $scope[t].push({ldelim}"value":"{$arr[1]}", "text":"{$arr[0]}", "v":"{$arr[2]}"{rdelim});
                            {/if}
                {/foreach}
            {elseif $uitype neq '22' &&  $uitype neq '19'  &&  $uitype neq '20' &&  $uitype neq '21' && $fldname neq "" && $fldname neq 'msgdescription' && $fldname neq 'content' && $fldname neq 'description' && $fldname neq 'bodymessage_msg' && $fldname neq 'budymessage'}
              $scope.{$fldname}= '{$fldvalue|html_entity_decode:1:"UTF-8"|replace:"'":'"'|replace:'&quot;':"'"|replace:'&amp;':"&"|replace:'\\':""}'; 
             
            {/if}
        {/foreach}
{/foreach} 
{/foreach}
 {literal} 
    $scope.showPicklist = function(fld) {
        
        //$scope.fld=sel;
  };
  $scope.choosePicklistValue = function(fld) {
        //var index=document.getElementById(fld).selectedIndex;
        //var sel=document.getElementById(fld).options[index].text;
        //alert(sel+' '+fld+' '+$scope[fld]);
        //document.getElementById(fld).value=sel;
  };
  $scope.showValue = function(fld) {
        var ret='Empty';
        if($scope[fld] != '')
            ret = $scope[fld];
        return ret;
  };
    $scope.show_logic = function(fld) {
        var ret=true;
        {/literal} 
        {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
            var target_roles=new Array({$map.target_roles});
            var target_profiles=new Array({$map.target_profiles});
            var curr_prof_in_excluded=false;
            {foreach key=prof_key item=prof_item from=$CurrProfiles}
                if(target_profiles.indexOf('{$prof_item}')!=-1)
                    curr_prof_in_excluded=true;
            {/foreach}
               // if(target_profiles.length==0)  // if there is a BR for create/edit view but no profile specified then remove to all profiles
                 //   curr_prof_in_excluded=true;
           {foreach key=map_key item=map_item from=$map.targetfield}
           if(fld=='{$map_item}'  && '{$map.action[$map_key]}'=='hide' && '{$OP_MODE}' == '{$map.target_mode}' && curr_prof_in_excluded ) 
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='hide' && target_roles.indexOf('{$CurrRole}')!=-1)
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='hide' && $scope.{$map.respfield[0]}=='{$map.respvalue[0]}')
            {ldelim}
                ret=false;
            {rdelim}
           {/foreach}
        {/foreach}
            
        {literal}
        return ret;
      };
      
    $scope.editable_logic = function(fld,fldlabel) {
        var ret=true;
        {/literal} 
        {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
            var target_roles=new Array({$map.target_roles});
            var target_profiles=new Array({$map.target_profiles});
            var curr_prof_in_excluded=false;
            {foreach key=prof_key item=prof_item from=$CurrProfiles}
                if(target_profiles.indexOf('{$prof_item}')!=-1)
                    curr_prof_in_excluded=true;
            {/foreach}
            {foreach key=map_key item=map_item from=$map.targetfield}
               if (fld =='{$map_item}')
               {ldelim}
                   var f_length= fld+'_length';
                   var actual_length=parseInt($scope[fld].length);
                   var length=parseInt({$map.fieldlength[$map_key]});

                   if(actual_length > length)
                   {ldelim}
                      $scope.disabled_save=true;
                      $scope[f_length]=fldlabel+" should be "+{$map.fieldlength[$map_key]}+" characters";
                   {rdelim}
                   else
                   {ldelim}
                      $scope.disabled_save=false;
                      $scope[f_length]="";
                   {rdelim}
               {rdelim}
            {/foreach}    
           {foreach key=map_key item=map_item from=$map.targetfield}
           if(fld=='{$map_item}'  && '{$map.action[$map_key]}'=='readonly' && '{$OP_MODE}' == '{$map.target_mode}' && curr_prof_in_excluded ) 
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='readonly' && target_roles.indexOf('{$CurrRole}')!=-1 )
            {ldelim}
                ret=false;
            {rdelim}
           else if(fld=='{$map_item}' && '{$map.action[$map_key]}'=='readonly' && $scope.{$map.respfield[0]}=='{$map.respvalue[0]}')
            {ldelim}
                ret=false;
            {rdelim}
           {/foreach}
        {/foreach}
            
        {literal}
    return ret;
   }
{/literal}
{rdelim} );
    </script>