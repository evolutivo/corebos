{include_php file="modules/Vtiger/DetailViewDep.php"}
<script>
var blocks ={$BlocksJson};
var mapFieldDep ={$MapFieldDep};
var CurrRole ='{$CurrRole}';
var CurrProfiles ={$CurrProfiles};
</script>
<script type="text/javascript" src="include/js/ngAppFieldDepEdit.js"></script>
<script>

{foreach key=header item=data from=$BLOCKS}
{foreach key=label item=subdata from=$data}
{foreach key=mainlabel item=maindata from=$subdata}
                {assign var="uitype" value="$maindata[0][0]"}
		{assign var="fldname" value="$maindata[2][0]"}
		{assign var="fldvalue" value="$maindata[3][0]"}
		
        {if $uitype eq '15' || $uitype eq '16' || $uitype eq '31' || $uitype eq '32' || $uitype eq '33' || $uitype eq '1613'} 
                             
            angular.module('demoApp').filter('{$fldname}_filter', function() {ldelim}
              return function({$fldname}_values ,scope) {ldelim}
                var filterEvent = [];
                var filterEventTemp = [];
                var count_false_condition=0;
                  for (var i = 0;i < {$fldname}_values.length; i++){ldelim}   
                  {if isset($MAP_PCKLIST_TARGET)} {* control to avoid errors for  modules not having BR*}
                  {if $fldname|in_array:$MAP_PCKLIST_TARGET}
                      {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
                          {ldelim}
                          {if $fldname|in_array:$map.target_picklist}
                              var condition='';
                               {foreach key=map_key item=map_item from=$map.respfield}
                                {ldelim}
                                    {if $map_item neq $fldname}
                                        var resp_values=new Array({$map.respvalue[$map_key]}); 
                                        if({$map_key} !=0) condition +=' && ';
                                        condition += resp_values.indexOf(scope.{$map_item})!=-1   ;
                                    {/if}
                                {rdelim}
                               {/foreach}
                                if( eval(condition))
                                    {ldelim}
                                      {foreach key=map_key item=map_item from=$map.target_picklist_values.$fldname}
                                         {ldelim}
                                             if ({$fldname}_values[i]['value']=='{$map_item}' && filterEventTemp.indexOf('{$map_item}') === -1)
                                                {ldelim}
                                                  filterEventTemp.push('{$map_item}');
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
                            {/if}
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

</script>