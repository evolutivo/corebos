{include file="modules/$MODULE/Header.tpl"}

<table width=98% align=center border="0" style="padding:0px;" ng-app="demoApp" ng-cloak>
    <tr><td style="height:2px"><br/><br/></td></tr>
    <tr>  
	<td valign="top" style="padding:0px;" width=100%>
            <div  layout="column" class="demo">
                {if $TYPE eq 'TypeForm'}                
                    {include file="modules/$MODULE/ElasticForm.tpl"}
                {elseif $TYPE eq 'Kibi'}
                    {include file="modules/$MODULE/KibiForm.tpl"}                    
                {else}
                    {include file="modules/$MODULE/ModuleForm.tpl"}                    
                {/if}
            </div>
        </td>
     </tr>
</table>