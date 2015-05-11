
<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align=right>
                {if $NG_BLOCK_NAME eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts') }
                        {if $MODULE eq 'Leads'}
                                <input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="searchMapLocation( 'Main' )" title="{$APP.LBL_LOCATE_MAP}">
                        {/if}
                {/if}
        </td>
</tr>
<tr>{strip}
    <td colspan=4 class="dvInnerHeader">

            <div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$NG_BLOCK_NAME|replace:' ':''}','aid{$NG_BLOCK_NAME|replace:' ':''}','{$IMAGE_PATH}');">
                                    <img id="aid{$NG_BLOCK_NAME|replace:' ':''}" src="{'inactivate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/>
                            </a></div><b>&nbsp;
                            {$NG_BLOCK_NAME}
                    </b></div>
    </td>{/strip}
</tr>
</table>

<div style="width:auto;display:none;" id="tbl{$NG_BLOCK_NAME|replace:' ':''}" >
    <div ng-controller="block_{$NG_BLOCK_ID}">
            <nvd3 options="options" 
              data="data">
            </nvd3>
    </div>
</div>

<style>
{literal}
.app-modal-window .modal-dialog {
    width: 500px;
    margin-left:-190px;
    }
{/literal}
</style>

<script>
{literal}
angular.module('demoApp')
.controller('block_{/literal}{$NG_BLOCK_ID}{literal}',function($scope, $http, $modal) {
           
          $http.post('index.php?{/literal}{$blockURL}{literal}&kaction=retrieve_graph'
                )
                .success(function(data) {
                     $scope.data=data;
                     $scope.options = {
                        chart: {
                            type: 'multiBarChart',
                            height: 450,
                            "width": 685,
                            margin : {
                                top: 20,
                                right: 20,
                                bottom: 60,
                                left: 65
                            },
                            clipEdge: false,
                            staggerLabels: false,
                            transitionDuration: 500,
                            stacked: false,
                            tooltips:false,
                            xAxis: {
                                axisLabel: '{/literal}{$NG_BLOCK_NAME}{literal}',
                                showMaxMin: false,
                                tickFormat: function(d){
                                    return d;
                                }
                            },
                            yAxis: {
                                axisLabel: '',
                                axisLabelDistance: 20,
                                tickFormat: function(d){
                                    return d;
                                }
                            }
                        }
                        
                    };
                    
                 });       
        
});
{/literal}
</script>
