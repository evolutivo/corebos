{include_php file="modules/Vtiger/DetailViewDep.php"}
<script>
var blocks ={$BlocksJson};
var mapFieldDep ={$MapFieldDep};
var CurrRole ='{$CurrRole}';
var CurrProfiles ={$CurrProfiles};
</script>
<script type="text/javascript" src="include/js/ngAppFieldDep.js"></script>
<script>
    {literal}
angular.module('demoApp').run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});
{/literal}
{foreach key=header item=detail from=$BLOCKS}
{foreach item=detail from=$detail}
{foreach key=label item=data from=$detail}
            {assign var=keyid value=$data.ui}
            {assign var=keyfldname value=$data.fldname}
        {if $keyid eq '15' || $keyid eq '16' || $keyid eq '31' || $keyid eq '32' || $keyid eq '33' || $keyid eq '1613'} 
                             
            angular.module('demoApp').filter('{$keyfldname}_filter', function() {ldelim}
              return function({$keyfldname}_values ,scope) {ldelim}
                var filterEvent = [];
                var filterEventTemp = [];
                var count_false_condition=0;
                  for (var i = 0;i < {$keyfldname}_values.length; i++){ldelim}   
                  {if isset($MAP_PCKLIST_TARGET)} {* control to avoid errors for  modules not having BR*}
                  {if $keyfldname|in_array:$MAP_PCKLIST_TARGET}
                      {foreach key=mapid item=map from=$MAP_FIELD_DEPENDENCY}
                          {ldelim}
                          {if $keyfldname|in_array:$map.target_picklist}
                              var condition='';
                               {foreach key=map_key item=map_item from=$map.respfield}
                                {ldelim}
                                    {if $map_item neq $keyfldname}
                                        var resp_values=new Array({$map.respvalue[$map_key]});
                                        if({$map_key} !=0) condition +=' && ';
                                        condition +=resp_values.indexOf(scope.{$map_item})!=-1   ;
                                    {/if}
                                {rdelim}
                               {/foreach}
                                if( eval(condition))
                                    {ldelim}
                                      {foreach key=map_key item=map_item from=$map.target_picklist_values.$keyfldname}
                                         {ldelim}
                                             if ({$keyfldname}_values[i]['value']=='{$map_item}' && filterEventTemp.indexOf('{$map_item}') === -1 )
                                                {ldelim}
                                                    filterEventTemp.push('{$map_item}');
                                                  filterEvent.push({$keyfldname}_values[i]);
                                                {rdelim}
                                         {rdelim}
                                      {/foreach}
                                    {rdelim}
                                  else
                                    {ldelim}
                                            //count_false_condition++;
                                            //if(count_false_condition=={$MAP_FIELD_DEPENDENCY|@count})
                                            //{ldelim}
                                            //    filterEvent.push({$keyfldname}_values[i]);  
                                            //{rdelim}
                                    {rdelim}
                            {/if}
                          {rdelim}  
                        {/foreach}
                      
                  {else}
                  filterEvent.push({$keyfldname}_values[i]);
                  {/if}
              {else}
                  filterEvent.push({$keyfldname}_values[i]);
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